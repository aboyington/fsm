<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'category_type' => [
                'type' => 'ENUM',
                'constraint' => ['parts', 'services', 'both'],
                'default' => 'both',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_by' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('name');
        $this->forge->addKey('category_type');
        $this->forge->addKey('is_active');
        $this->forge->addKey('created_by');
        $this->forge->addKey('deleted_at');
        
        $this->forge->createTable('categories');
    }

    public function down()
    {
        $this->forge->dropTable('categories');
    }
}
