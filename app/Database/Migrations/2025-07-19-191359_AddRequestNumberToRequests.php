<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRequestNumberToRequests extends Migration
{
    public function up()
    {
        $fields = [
            'request_number' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'after' => 'id'
            ]
        ];
        
        $this->forge->addColumn('requests', $fields);
        
        // Add index for request_number
        $this->forge->addKey('request_number');
        
        // Update existing records to generate request numbers
        $db = \Config\Database::connect();
        $requests = $db->table('requests')->select('id')->get()->getResultArray();
        
        foreach ($requests as $request) {
            $requestNumber = 'REQ-' . str_pad($request['id'], 3, '0', STR_PAD_LEFT);
            $db->table('requests')
               ->where('id', $request['id'])
               ->update(['request_number' => $requestNumber]);
        }
        
        // Make request_number NOT NULL after populating existing records
        $this->forge->modifyColumn('requests', [
            'request_number' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
                'unique' => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('requests', 'request_number');
    }
}
