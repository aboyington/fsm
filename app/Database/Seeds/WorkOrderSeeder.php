<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WorkOrderSeeder extends Seeder
{
    public function run()
    {
        // First, let's get some customer IDs to use
        $customers = $this->db->table('customers')->select('id')->limit(5)->get()->getResultArray();
        
        if (empty($customers)) {
            echo "No customers found. Please run CustomerSeeder first.\n";
            return;
        }

        // Get user IDs for assignment
        $users = $this->db->table('users')->select('id, role')->get()->getResultArray();
        $techUsers = array_filter($users, function($user) {
            return in_array($user['role'], ['dispatcher', 'field_tech']);
        });
        $techUsers = array_values($techUsers); // Re-index array
        
        // Get admin user for created_by
        $adminUser = array_values(array_filter($users, function($user) {
            return $user['role'] === 'admin';
        }))[0] ?? $users[0];

        $data = [
            [
                'work_order_number' => 'WO-2025-001',
                'customer_id' => $customers[0]['id'],
                'assigned_to' => $techUsers[0]['id'] ?? null,
                'work_order_type' => 'installation',
                'status' => 'completed',
                'priority' => 'normal',
                'title' => 'New System Installation',
                'description' => 'Install complete home security system with 4 cameras and motion sensors',
                'scheduled_date' => date('Y-m-d', strtotime('-2 days')),
                'scheduled_time' => '09:00:00',
                'completed_date' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'notes' => 'Installation completed successfully. Customer satisfied.',
                'created_by' => $adminUser['id'],
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'work_order_number' => 'WO-2025-002',
                'customer_id' => $customers[1]['id'] ?? $customers[0]['id'],
                'assigned_to' => $techUsers[1]['id'] ?? $techUsers[0]['id'] ?? null,
                'work_order_type' => 'repair',
                'status' => 'in_progress',
                'priority' => 'high',
                'title' => 'AC Unit Repair',
                'description' => 'AC unit not cooling. Customer reports strange noise from outdoor unit.',
                'scheduled_date' => date('Y-m-d'),
                'scheduled_time' => '14:00:00',
                'notes' => 'Parts ordered. Waiting for delivery.',
                'created_by' => $adminUser['id'],
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'work_order_number' => 'WO-2025-003',
                'customer_id' => $customers[2]['id'] ?? $customers[0]['id'],
                'assigned_to' => null,
                'work_order_type' => 'maintenance',
                'status' => 'new',
                'priority' => 'normal',
                'title' => 'Annual HVAC Maintenance',
                'description' => 'Scheduled annual maintenance for HVAC system',
                'scheduled_date' => date('Y-m-d', strtotime('+3 days')),
                'scheduled_time' => '10:00:00',
                'created_by' => $adminUser['id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'work_order_number' => 'WO-2025-004',
                'customer_id' => $customers[3]['id'] ?? $customers[0]['id'],
                'assigned_to' => $techUsers[0]['id'] ?? null,
                'work_order_type' => 'canvassing',
                'status' => 'assigned',
                'priority' => 'low',
                'title' => 'Neighborhood Canvassing',
                'description' => 'Door-to-door canvassing for new solar panel installation opportunities',
                'scheduled_date' => date('Y-m-d'),
                'scheduled_time' => '09:00:00',
                'notes' => 'Focus on Oak Street area',
                'created_by' => $adminUser['id'],
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ],
            [
                'work_order_number' => 'WO-2025-005',
                'customer_id' => $customers[4]['id'] ?? $customers[0]['id'],
                'assigned_to' => $techUsers[1]['id'] ?? $techUsers[0]['id'] ?? null,
                'work_order_type' => 'repair',
                'status' => 'cancelled',
                'priority' => 'urgent',
                'title' => 'Emergency Plumbing Repair',
                'description' => 'Water leak in basement - URGENT',
                'scheduled_date' => date('Y-m-d', strtotime('-1 day')),
                'scheduled_time' => '15:00:00',
                'notes' => 'Customer resolved issue themselves - Cancelled',
                'created_by' => $adminUser['id'],
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'work_order_number' => 'WO-2025-006',
                'customer_id' => $customers[0]['id'],
                'assigned_to' => $techUsers[0]['id'] ?? null,
                'work_order_type' => 'installation',
                'status' => 'in_progress',
                'priority' => 'normal',
                'title' => 'Smart Thermostat Installation',
                'description' => 'Install new WiFi-enabled smart thermostat',
                'scheduled_date' => date('Y-m-d'),
                'scheduled_time' => '11:00:00',
                'notes' => 'Customer requested Nest thermostat',
                'created_by' => $adminUser['id'],
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert work orders
        foreach ($data as $workOrder) {
            $this->db->table('work_orders')->insert($workOrder);
        }

        echo "Work orders seeded successfully!\n";
        echo "Created " . count($data) . " sample work orders.\n";
    }
}
