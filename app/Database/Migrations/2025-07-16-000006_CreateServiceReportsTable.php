<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServiceReportsTable extends Migration
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
            'service_appointment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'work_order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'technician_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'report_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'completed', 'submitted', 'approved'],
                'default' => 'draft',
            ],
            'service_type' => [
                'type' => 'ENUM',
                'constraint' => ['installation', 'repair', 'maintenance', 'inspection', 'consultation', 'other'],
                'null' => true,
            ],
            'work_summary' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'parts_used' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'time_spent' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Time spent in hours',
            ],
            'labor_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'default' => 0.00,
            ],
            'material_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'default' => 0.00,
            ],
            'total_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'default' => 0.00,
            ],
            'customer_feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'recommendations' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'additional_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
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
        $this->forge->addKey('service_appointment_id');
        $this->forge->addKey('work_order_id');
        $this->forge->addKey('technician_id');
        $this->forge->addKey('report_date');
        $this->forge->addKey('status');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        $this->forge->addKey('deleted_at');

        $this->forge->createTable('service_reports');

        // Add foreign key constraints
        $this->forge->addForeignKey('service_appointment_id', 'service_appointments', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('work_order_id', 'work_orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('technician_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('updated_by', 'users', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('service_reports');
    }
}
