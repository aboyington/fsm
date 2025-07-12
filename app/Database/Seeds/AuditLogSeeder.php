<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run()
    {
        $auditLogModel = new \App\Models\AuditLogModel();
        
        // Sample audit log entries
        $auditLogs = [
            [
                'user_id' => 1, // Admin user
                'target_user_id' => null,
                'event_type' => 'created',
                'module' => 'holidays',
                'title' => 'Holiday Created',
                'description' => 'Created new holiday: Christmas Day',
                'old_value' => null,
                'new_value' => json_encode(['name' => 'Christmas Day', 'date' => '2024-12-25']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'user_id' => 1, // Admin user
                'target_user_id' => null,
                'event_type' => 'deleted',
                'module' => 'holidays',
                'title' => 'Holiday Deleted',
                'description' => 'Deleted holiday: New Year Eve',
                'old_value' => json_encode(['name' => 'New Year Eve', 'date' => '2024-12-31']),
                'new_value' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
            ],
            [
                'user_id' => 1, // Admin user
                'target_user_id' => null,
                'event_type' => 'created',
                'module' => 'holidays',
                'title' => 'Holiday Created',
                'description' => 'Created new holiday: Independence Day',
                'old_value' => null,
                'new_value' => json_encode(['name' => 'Independence Day', 'date' => '2025-07-04']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 hours'))
            ],
            [
                'user_id' => 1, // Admin user
                'target_user_id' => 2,
                'event_type' => 'updated',
                'module' => 'users',
                'title' => 'User Updated',
                'description' => 'Updated user profile information',
                'old_value' => json_encode(['status' => 'active', 'role' => 'user']),
                'new_value' => json_encode(['status' => 'active', 'role' => 'admin']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 hours'))
            ],
            [
                'user_id' => 1, // Admin user
                'target_user_id' => 2,
                'event_type' => 'updated',
                'module' => 'users',
                'title' => 'User Updated',
                'description' => 'Updated user contact information',
                'old_value' => json_encode(['email' => 'old@example.com', 'phone' => '123-456-7890']),
                'new_value' => json_encode(['email' => 'new@example.com', 'phone' => '098-765-4321']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-6 hours'))
            ],
            [
                'user_id' => 1, // Admin user
                'target_user_id' => null,
                'event_type' => 'updated',
                'module' => 'org_details',
                'title' => 'Organization Updated',
                'description' => 'Updated organization profile',
                'old_value' => json_encode(['name' => 'Old Company Name', 'industry' => 'IT']),
                'new_value' => json_encode(['name' => 'New Company Name', 'industry' => 'Technology']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'user_id' => 1, // Admin user
                'target_user_id' => null,
                'event_type' => 'updated',
                'module' => 'org_details',
                'title' => 'Organization Updated',
                'description' => 'Updated business hours configuration',
                'old_value' => json_encode(['monday_start' => '09:00', 'monday_end' => '17:00']),
                'new_value' => json_encode(['monday_start' => '08:00', 'monday_end' => '18:00']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'user_id' => 1, // Admin user
                'target_user_id' => null,
                'event_type' => 'disable',
                'module' => 'other_settings',
                'title' => 'Setting Disabled',
                'description' => 'Disabled notification settings',
                'old_value' => json_encode(['notifications_enabled' => true]),
                'new_value' => json_encode(['notifications_enabled' => false]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ]
        ];
        
        foreach ($auditLogs as $log) {
            $auditLogModel->insert($log);
        }
        
        echo "AuditLogSeeder completed successfully.\n";
    }
}
