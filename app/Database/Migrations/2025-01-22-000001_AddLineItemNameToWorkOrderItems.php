<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLineItemNameToWorkOrderItems extends Migration
{
    public function up()
    {
        // Add line_item_name column to work_order_items table
        $this->forge->addColumn('work_order_items', [
            'line_item_name' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'after' => 'item_name'
            ]
        ]);
    }

    public function down()
    {
        // Drop the line_item_name column
        $this->forge->dropColumn('work_order_items', 'line_item_name');
    }
}
