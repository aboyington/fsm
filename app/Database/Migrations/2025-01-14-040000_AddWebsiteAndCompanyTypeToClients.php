<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWebsiteAndCompanyTypeToClients extends Migration
{
    public function up()
    {
        $fields = [
            'website' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email'
            ],
            'company_type' => [
                'type' => 'ENUM',
                'constraint' => [
                    'Analyst', 
                    'Competitor', 
                    'Customer', 
                    'Distributor', 
                    'Integrator', 
                    'Investor', 
                    'Other', 
                    'Partner', 
                    'Press', 
                    'Prospect', 
                    'Reseller', 
                    'Supplier', 
                    'Vendor'
                ],
                'null' => true,
                'after' => 'website'
            ]
        ];
        
        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', ['website', 'company_type']);
    }
}
