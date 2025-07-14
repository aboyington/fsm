<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsToClients extends Migration
{
    public function up()
    {
        $fields = [
            'fax' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'tax_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'industry' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'employees' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'annual_revenue' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'billing_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'billing_city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'billing_state' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'billing_zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'billing_country' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'default' => 'Canada',
            ],
            'shipping_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'shipping_city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'shipping_state' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'shipping_zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'shipping_country' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'default' => 'Canada',
            ],
            'lead_source' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'rating' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'parent_company_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('clients', $fields);
        
        // Add foreign key for parent company
        $this->forge->addForeignKey('parent_company_id', 'clients', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $fields = [
            'fax',
            'tax_id', 
            'industry',
            'employees',
            'annual_revenue',
            'billing_address',
            'billing_city',
            'billing_state', 
            'billing_zip_code',
            'billing_country',
            'shipping_address',
            'shipping_city',
            'shipping_state',
            'shipping_zip_code', 
            'shipping_country',
            'lead_source',
            'rating',
            'parent_company_id'
        ];
        
        $this->forge->dropColumn('clients', $fields);
    }
}
