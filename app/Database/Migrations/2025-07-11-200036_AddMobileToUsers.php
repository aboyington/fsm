<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMobileToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => true,
                'after' => 'phone'
            ]
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'mobile');
    }
}

