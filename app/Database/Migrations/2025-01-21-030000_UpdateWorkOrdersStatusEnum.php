<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWorkOrdersStatusEnum extends Migration
{
    public function up()
    {
        // Update the status column to include new status values
        $this->forge->modifyColumn('work_orders', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['new', 'pending', 'in_progress', 'cannot_complete', 'completed', 'closed', 'cancelled', 'scheduled_appointment'],
                'default' => 'new',
            ]
        ]);
    }

    public function down()
    {
        // Revert back to original status values
        $this->forge->modifyColumn('work_orders', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'completed', 'cancelled'],
                'default' => 'pending',
            ]
        ]);
    }
}
