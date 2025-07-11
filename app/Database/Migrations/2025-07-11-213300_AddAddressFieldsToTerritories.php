<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAddressFieldsToTerritories extends Migration
{
    public function up()
    {
        $fields = [
            'street' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'description',
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'street',
            ],
            'state' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'city',
            ],
            'zip_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'state',
            ],
            'country' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'zip_code',
            ],
        ];
        
        $this->forge->addColumn('territories', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('territories', ['street', 'city', 'state', 'zip_code', 'country']);
    }
}
