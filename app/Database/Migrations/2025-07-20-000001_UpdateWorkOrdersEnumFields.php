<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWorkOrdersEnumFields extends Migration
{
    public function up()
    {
        // Update priority field to include new values
        $this->forge->modifyColumn('work_orders', [
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['none', 'low', 'medium', 'critical', 'high'],
                'default' => 'medium',
            ],
        ]);
        
        // Update type field to include new values
        $this->forge->modifyColumn('work_orders', [
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['none', 'corrective', 'preventive', 'service', 'site_survey', 'inspection', 'installation', 'maintenance', 'emergency', 'scheduled_maintenance', 'standard'],
                'default' => 'service',
            ],
        ]);
    }

    public function down()
    {
        // Revert to original priority values
        $this->forge->modifyColumn('work_orders', [
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high'],
                'default' => 'medium',
            ],
        ]);
        
        // Revert to original type values
        $this->forge->modifyColumn('work_orders', [
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['service', 'corrective', 'preventive'],
                'default' => 'service',
            ],
        ]);
    }
}
