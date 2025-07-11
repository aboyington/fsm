<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBusinessHoursTable extends Migration
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
            'organization_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 1,
            ],
            'business_hours_type' => [
                'type' => 'ENUM',
                'constraint' => ['24x7', '24x5', 'custom'],
                'default' => '24x7',
            ],
            'monday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'monday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'tuesday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'tuesday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'wednesday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'wednesday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'thursday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'thursday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'friday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'friday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'saturday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'saturday_end' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'sunday_start' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'sunday_end' => [
                'type' => 'TIME',
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
        $this->forge->addKey('organization_id');
        $this->forge->createTable('business_hours');
        
        // Insert default business hours record
        $this->db->table('business_hours')->insert([
            'organization_id' => 1,
            'business_hours_type' => '24x7',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('business_hours');
    }
}
