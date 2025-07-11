<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrganizationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'company_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'industry_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'Commercial, Industrial, Residential',
            ],
            'industry' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Specific industry category',
            ],
            'website' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'fax' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'business_location' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'street' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'state' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'zip_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'country' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'time_zone' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'America/New_York',
                'null'       => false,
            ],
            'date_format' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'MM/DD/YYYY',
                'null'       => false,
            ],
            'time_format' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => '12 Hour',
                'null'       => false,
            ],
            'distance_unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'Miles',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('organizations');
        
        // Insert default organization record
        $this->db->table('organizations')->insert([
            'id'                => 1,
            'company_name'      => 'Udora Safety',
            'industry_type'     => 'Commercial',
            'industry'          => 'Security and CCTV',
            'website'           => 'https://udorasafety.com',
            'phone'             => '(555) 123-4567',
            'mobile'            => '(555) 987-6543',
            'fax'               => '(555) 123-4568',
            'business_location' => 'Main Office',
            'street'            => '123 Safety Street',
            'city'              => 'Udora',
            'state'             => 'ON',
            'zip_code'          => 'L0C 1L0',
            'country'           => 'Canada',
            'time_zone'         => 'America/Toronto',
            'date_format'       => 'MM/DD/YYYY',
            'time_format'       => '12 Hour',
            'distance_unit'     => 'Miles',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('organizations');
    }
}
