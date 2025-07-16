<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToScheduledMaintenances extends Migration
{
    public function up()
    {
        $this->forge->addColumn('scheduled_maintenances', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('scheduled_maintenances', 'deleted_at');
    }
}
