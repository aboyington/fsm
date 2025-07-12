<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserSkillsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'skill_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'skill_level' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'certificate_status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'expired', 'pending'],
                'default' => 'active',
            ],
            'issue_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'expiry_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'assigned_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'assigned_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('skill_id', 'skills', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assigned_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addUniqueKey(['user_id', 'skill_id']); // Prevent duplicate assignments
        
        $this->forge->createTable('user_skills');
    }

    public function down()
    {
        $this->forge->dropTable('user_skills');
    }
}
