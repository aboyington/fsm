<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsToContacts extends Migration
{
    public function up()
    {
        $fields = [
            'fax' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'birthday' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'lead_source' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'assistant' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'assistant_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'home_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'other_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'skype_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'linkedin_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'reports_to' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('contacts', $fields);
        
        // Add foreign key for reports_to
        $this->forge->addForeignKey('reports_to', 'contacts', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $fields = [
            'fax',
            'title',
            'department',
            'birthday',
            'lead_source',
            'assistant',
            'assistant_phone',
            'home_phone',
            'other_phone',
            'skype_id',
            'linkedin_url',
            'reports_to',
            'description'
        ];
        
        $this->forge->dropColumn('contacts', $fields);
    }
}
