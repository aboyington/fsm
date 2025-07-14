<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContactsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'job_title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'company_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'default' => 'Canada'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active'
            ],
            'is_primary' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Primary contact for the company'
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ]
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('company_id', 'clients', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('contacts');
        
        // Add indexes separately for SQLite compatibility
        $this->db->query('CREATE INDEX idx_contacts_name ON contacts (first_name, last_name)');
        $this->db->query('CREATE INDEX idx_contacts_email ON contacts (email)');
        $this->db->query('CREATE INDEX idx_contacts_company ON contacts (company_id)');
    }

    public function down()
    {
        $this->forge->dropTable('contacts');
    }
}
