<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWorkOrdersTableStructure extends Migration
{
    public function up()
    {
        // Drop the existing work_orders table
        $this->forge->dropTable('work_orders', true);
        
        // Create new work_orders table with updated structure
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'work_order_number' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
                'unique' => true,
            ],
            'summary' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high'],
                'default' => 'medium',
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['service', 'corrective', 'preventive'],
                'default' => 'service',
            ],
            'due_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'company_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'contact_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'asset_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'service_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'billing_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'preferred_date_1' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'preferred_date_2' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'preferred_time' => [
                'type' => 'ENUM',
                'constraint' => ['-none-', 'any', 'morning', 'afternoon', 'evening'],
                'null' => true,
            ],
            'preference_note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sub_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
            ],
            'tax_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
            ],
            'discount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
            ],
            'adjustment' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
            ],
            'grand_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'completed', 'cancelled'],
                'default' => 'pending',
            ],
            'created_by' => [
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
        $this->forge->addKey('work_order_number');
        $this->forge->addKey('company_id');
        $this->forge->addKey('contact_id');
        $this->forge->addKey('asset_id');
        $this->forge->addKey('status');
        $this->forge->addKey('priority');
        $this->forge->addKey('type');
        $this->forge->addKey('created_by');
        $this->forge->addKey('created_at');
        $this->forge->addKey('deleted_at');
        
        $this->forge->createTable('work_orders');
    }

    public function down()
    {
        $this->forge->dropTable('work_orders');
    }
}