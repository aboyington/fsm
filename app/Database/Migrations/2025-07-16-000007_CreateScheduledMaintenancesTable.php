<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScheduledMaintenancesTable extends Migration
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
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'schedule_type' => [
                'type' => 'ENUM',
                'constraint' => ['daily', 'weekly', 'monthly', 'yearly', 'custom'],
                'default' => 'monthly',
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'frequency' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
                'comment' => 'How often to repeat (e.g., every 2 weeks)',
            ],
            'schedule_details' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Additional scheduling details (days of week, specific dates, etc.)',
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'asset_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'assigned_to' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'territory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'urgent'],
                'default' => 'medium',
            ],
            'estimated_duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Duration in minutes',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'active', 'inactive', 'paused'],
                'default' => 'draft',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'next_due_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'last_generated_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('client_id');
        $this->forge->addKey('asset_id');
        $this->forge->addKey('assigned_to');
        $this->forge->addKey('territory_id');
        $this->forge->addKey('status');
        $this->forge->addKey('next_due_date');
        $this->forge->addKey('created_by');

        $this->forge->createTable('scheduled_maintenances');
    }

    public function down()
    {
        $this->forge->dropTable('scheduled_maintenances');
    }
}
