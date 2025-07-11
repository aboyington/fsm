<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'work_order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'assigned_to' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'User ID of assigned technician',
            ],
            'work_order_type' => [
                'type'       => 'ENUM',
                'constraint' => ['installation', 'repair', 'maintenance', 'canvassing', 'other'],
                'default'    => 'other',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['new', 'assigned', 'in_progress', 'completed', 'cancelled'],
                'default'    => 'new',
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'normal', 'high', 'urgent'],
                'default'    => 'normal',
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'scheduled_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'scheduled_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'completed_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'estimated_duration' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'comment'    => 'Duration in minutes',
            ],
            'actual_duration' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'comment'    => 'Actual duration in minutes',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addKey('work_order_number');
        $this->forge->addKey('customer_id');
        $this->forge->addKey('assigned_to');
        $this->forge->addKey('status');
        $this->forge->addKey('scheduled_date');
        $this->forge->createTable('work_orders');
    }

    public function down()
    {
        $this->forge->dropTable('work_orders');
    }
}
