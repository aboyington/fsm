<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsToAssets extends Migration
{
    public function up()
    {
        $fields = [
            'serial_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'model_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'manufacturer' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'vendor' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'cost' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
            ],
            'book_value' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
            ],
            'depreciation_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'useful_life' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Useful life in months',
            ],
            'condition' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'tags' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'mac_address' => [
                'type' => 'VARCHAR',
                'constraint' => 17,
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'operating_system' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'software_licenses' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'maintenance_schedule' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'last_maintenance' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'next_maintenance' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'retired_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'disposal_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'disposal_method' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'territory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('assets', $fields);
        
        // Add foreign key for territory
        $this->forge->addForeignKey('territory_id', 'territories', 'id', 'SET NULL', 'CASCADE');
        
        // Add indexes for performance
        $this->forge->addKey('serial_number');
        $this->forge->addKey('model_number');
        $this->forge->addKey('manufacturer');
        $this->forge->addKey('location');
        $this->forge->addKey('territory_id');
    }

    public function down()
    {
        $fields = [
            'serial_number',
            'model_number',
            'manufacturer',
            'vendor',
            'location',
            'department',
            'cost',
            'book_value',
            'depreciation_method',
            'useful_life',
            'condition',
            'tags',
            'mac_address',
            'ip_address',
            'operating_system',
            'software_licenses',
            'maintenance_schedule',
            'last_maintenance',
            'next_maintenance',
            'retired_date',
            'disposal_date',
            'disposal_method',
            'territory_id'
        ];
        
        $this->forge->dropColumn('assets', $fields);
    }
}
