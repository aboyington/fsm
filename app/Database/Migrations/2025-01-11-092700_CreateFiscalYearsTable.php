<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFiscalYearsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'organization_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 1,
            ],
            'fiscal_year_start' => [
                'type' => 'VARCHAR',
                'constraint' => 5, // Format: MM-DD
                'default' => '01-01',
            ],
            'fiscal_year_end' => [
                'type' => 'VARCHAR',
                'constraint' => 5, // Format: MM-DD
                'default' => '12-31',
            ],
            'current_fiscal_year' => [
                'type' => 'INT',
                'constraint' => 4,
                'default' => date('Y'),
            ],
            'fiscal_year_format' => [
                'type' => 'ENUM',
                'constraint' => ['calendar', 'custom'],
                'default' => 'calendar',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('organization_id');
        $this->forge->createTable('fiscal_years');
        
        // Insert default fiscal year record
        $this->db->table('fiscal_years')->insert([
            'organization_id' => 1,
            'fiscal_year_start' => '01-01',
            'fiscal_year_end' => '12-31',
            'current_fiscal_year' => date('Y'),
            'fiscal_year_format' => 'calendar',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('fiscal_years');
    }
}
