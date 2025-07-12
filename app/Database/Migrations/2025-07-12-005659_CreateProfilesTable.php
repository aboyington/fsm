<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfilesTable extends Migration
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
            'permissions' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
            ],
            'is_default' => [
                'type' => 'BOOLEAN',
                'default' => false,
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
        $this->forge->addKey('status');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('profiles');

        // Insert default profiles
        $profiles = [
            [
                'name' => 'Administrator',
                'description' => 'This profile will have all the permissions. Users with this profile will have access to all modules and features.',
                'permissions' => json_encode([
                    'settings' => ['read', 'write', 'delete'],
                    'users' => ['read', 'write', 'delete'],
                    'customers' => ['read', 'write', 'delete'],
                    'work_orders' => ['read', 'write', 'delete'],
                    'dispatch' => ['read', 'write', 'delete'],
                    'billing' => ['read', 'write', 'delete'],
                    'reports' => ['read', 'write', 'delete'],
                ]),
                'status' => 'active',
                'is_default' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Dispatcher',
                'description' => 'This profile will have permissions aiding to Schedule work orders, Assign field agents and Manage dispatch operations.',
                'permissions' => json_encode([
                    'customers' => ['read', 'write'],
                    'work_orders' => ['read', 'write'],
                    'dispatch' => ['read', 'write'],
                    'users' => ['read'],
                    'reports' => ['read'],
                ]),
                'status' => 'active',
                'is_default' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Call Center Agent',
                'description' => 'Handles customer service requests',
                'permissions' => json_encode([
                    'customers' => ['read', 'write'],
                    'work_orders' => ['read', 'write'],
                    'reports' => ['read'],
                ]),
                'status' => 'active',
                'is_default' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Field Agent',
                'description' => 'Executes customer service appointments',
                'permissions' => json_encode([
                    'work_orders' => ['read', 'write'],
                    'customers' => ['read'],
                    'reports' => ['read'],
                ]),
                'status' => 'active',
                'is_default' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Limited Field Agent',
                'description' => 'Executes customer service appointments similar to field agent but with limited access',
                'permissions' => json_encode([
                    'work_orders' => ['read'],
                    'customers' => ['read'],
                ]),
                'status' => 'active',
                'is_default' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('profiles')->insertBatch($profiles);
    }

    public function down()
    {
        $this->forge->dropTable('profiles');
    }
}
