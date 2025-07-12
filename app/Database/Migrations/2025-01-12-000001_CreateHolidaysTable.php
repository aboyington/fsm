<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHolidaysTable extends Migration
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
                'constraint' => 100,
                'null' => false,
            ],
            'date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'year' => [
                'type' => 'YEAR',
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_recurring' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '1 = recurring annually, 0 = one-time holiday'
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
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

        $this->forge->addKey('id', true);
        $this->forge->addKey(['year', 'date']);
        $this->forge->addKey('date');
        
        $this->forge->createTable('holidays');

        // Insert some default holidays for 2025
        $data = [
            [
                'name' => 'New Year\'s Day',
                'date' => '2025-01-01',
                'year' => 2025,
                'description' => 'New Year\'s Day',
                'is_recurring' => 1,
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Independence Day',
                'date' => '2025-07-04',
                'year' => 2025,
                'description' => 'Independence Day',
                'is_recurring' => 1,
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Christmas Day',
                'date' => '2025-12-25',
                'year' => 2025,
                'description' => 'Christmas Day',
                'is_recurring' => 1,
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('holidays')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('holidays');
    }
}
