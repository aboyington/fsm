<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsersTableSchema extends Migration
{
    public function up()
    {
        // Add language column
        $fields = [
            'language' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'default' => 'en-US',
                'after' => 'mobile'
            ],
            'enable_rtl' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'default' => 0,
                'after' => 'language'
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'enable_rtl'
            ]
        ];

        $this->forge->addColumn('users', $fields);

        // For SQLite, we need to recreate the table to change the CHECK constraint
        // Since SQLite doesn't support ALTER TABLE to modify constraints
        if ($this->db->DBDriver === 'SQLite3') {
            // Create a temporary table with the new schema
            $this->db->query("
                CREATE TABLE users_temp (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    email VARCHAR NOT NULL UNIQUE,
                    username VARCHAR NOT NULL UNIQUE,
                    password VARCHAR NOT NULL,
                    first_name VARCHAR NOT NULL,
                    last_name VARCHAR NOT NULL,
                    phone VARCHAR NULL,
                    mobile VARCHAR NULL,
                    language VARCHAR NULL DEFAULT 'en-US',
                    enable_rtl TINYINT NULL DEFAULT 0,
                    created_by VARCHAR NULL,
                    role TEXT CHECK(role IN ('admin','call_center_agent','dispatcher','field_agent','limited_field_agent')) NOT NULL DEFAULT 'field_agent',
                    status TEXT CHECK(status IN ('active','inactive','suspended')) NOT NULL DEFAULT 'active',
                    session_token VARCHAR NULL,
                    last_login DATETIME NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL
                )
            ");

            // Copy data from old table to new table
            $this->db->query("
                INSERT INTO users_temp (id, email, username, password, first_name, last_name, phone, mobile, language, enable_rtl, created_by, role, status, session_token, last_login, created_at, updated_at)
                SELECT id, email, username, password, first_name, last_name, phone, mobile, 
                       COALESCE(language, 'en-US'), 
                       COALESCE(enable_rtl, 0), 
                       COALESCE(created_by, 'System'),
                       CASE 
                           WHEN role = 'field_tech' THEN 'field_agent'
                           ELSE role 
                       END as role, 
                       status, session_token, last_login, created_at, updated_at
                FROM users
            ");

            // Drop the old table
            $this->db->query("DROP TABLE users");

            // Rename the temporary table
            $this->db->query("ALTER TABLE users_temp RENAME TO users");

            // Recreate indexes
            $this->db->query("CREATE INDEX users_email ON users (email)");
            $this->db->query("CREATE INDEX users_session_token ON users (session_token)");
        }
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('users', ['language', 'enable_rtl', 'created_by']);

        // Revert role constraint changes (would need similar table recreation for SQLite)
    }
}
