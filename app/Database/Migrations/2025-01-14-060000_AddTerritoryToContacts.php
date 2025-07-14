<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTerritoryToContacts extends Migration
{
    public function up()
    {
        $fields = [
            'territory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'company_id'
            ]
        ];
        
        $this->forge->addColumn('contacts', $fields);
        
        // Add foreign key constraint
        $this->forge->addForeignKey('territory_id', 'territories', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        // Drop foreign key first
        $this->forge->dropForeignKey('contacts', 'contacts_territory_id_foreign');
        
        // Then drop the column
        $this->forge->dropColumn('contacts', ['territory_id']);
    }
}
