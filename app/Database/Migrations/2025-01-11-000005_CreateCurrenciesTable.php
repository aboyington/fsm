<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCurrenciesTable extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'symbol' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
            ],
            'iso_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 3,
                'null'       => false,
            ],
            'exchange_rate' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,10',
                'null'       => false,
                'default'    => 1.0000000000,
            ],
            'thousand_separator' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null'       => true,
                'default'    => ',',
            ],
            'decimal_spaces' => [
                'type'       => 'INT',
                'constraint' => 2,
                'null'       => false,
                'default'    => 2,
            ],
            'decimal_separator' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null'       => true,
                'default'    => '.',
            ],
            'is_base' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
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
        $this->forge->addUniqueKey('iso_code');
        $this->forge->createTable('currencies');

        // Insert default base currency (CAD)
        $this->db->table('currencies')->insert([
            'name' => 'Canadian Dollar - CAD',
            'symbol' => 'CA$',
            'iso_code' => 'CAD',
            'exchange_rate' => 1.0000000000,
            'thousand_separator' => ',',
            'decimal_spaces' => 2,
            'decimal_separator' => '.',
            'is_base' => 1,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('currencies');
    }
}
