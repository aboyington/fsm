<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRecordTemplatesTable extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'module' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'template_data' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Stores the template field configurations as JSON'
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null' => false,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('module');
        $this->forge->addKey('is_active');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('record_templates');
    }

    public function down()
    {
        $this->forge->dropTable('record_templates');
    }
}
