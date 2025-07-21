<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescriptionToWorkOrders extends Migration
{
    public function up()
    {
        $fields = [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('work_orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('work_orders', 'description');
    }
}
