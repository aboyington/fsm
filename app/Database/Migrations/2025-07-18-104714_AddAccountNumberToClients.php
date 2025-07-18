<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAccountNumberToClients extends Migration
{
    public function up()
    {
        $fields = [
            'account_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'unique' => true,
                'comment' => 'Client account number in format ACC-001-ACME'
            ],
            'account_abbreviation' => [
                'type' => 'VARCHAR',
                'constraint' => 4,
                'null' => true,
                'comment' => '4-character abbreviation of client name'
            ]
        ];
        
        $this->forge->addColumn('clients', $fields);
        
        // Add index for account number (SQLite compatible)
        $this->db->query('CREATE INDEX idx_clients_account_number ON clients (account_number)');
    }

    public function down()
    {
        // Drop the index
        $this->db->query('DROP INDEX IF EXISTS idx_clients_account_number');
        
        // Drop the columns
        $this->forge->dropColumn('clients', ['account_number', 'account_abbreviation']);
    }
}
