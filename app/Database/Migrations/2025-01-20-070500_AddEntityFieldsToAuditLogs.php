<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEntityFieldsToAuditLogs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('audit_logs', [
            'entity_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'target_user_id'
            ],
            'entity_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'entity_type'
            ]
        ]);
        
        // Add indexes for the new fields
        $this->forge->addKey(['entity_type', 'entity_id']);
    }

    public function down()
    {
        $this->forge->dropColumn('audit_logs', ['entity_type', 'entity_id']);
    }
}
