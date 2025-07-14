<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccountRegistryTables extends Migration
{
    public function up()
    {
        // Create clients table (master client registry)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'client_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'contact_person' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'default' => 'Canada'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('client_name');
        $this->forge->createTable('clients');

        // Create service_registry table (client-service combinations)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false
            ],
            'service_type' => [
                'type' => 'ENUM',
                'constraint' => ['ALA', 'CAM', 'ITS', 'SUB'],
                'null' => false
            ],
            'service_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'account_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'client_abbreviation' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false
            ],
            'group_id' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active'
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('account_code');
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('service_registry');

        // Create account_sequences table (for auto-incrementing account numbers)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'prefix' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false
            ],
            'prefix_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'current_sequence' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 0
            ],
            'sequence_format' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'default' => '000'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active'
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('prefix');
        $this->forge->createTable('account_sequences');

        // Create invoice_sequences table (for invoice and estimate numbering)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'document_type' => [
                'type' => 'ENUM',
                'constraint' => ['INV', 'EST'],
                'null' => false
            ],
            'year' => [
                'type' => 'INT',
                'constraint' => 4,
                'unsigned' => true,
                'null' => false
            ],
            'current_sequence' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 0
            ],
            'sequence_format' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'default' => '0000'
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['document_type', 'year']);
        $this->forge->createTable('invoice_sequences');

        // Create product_skus table (for materials, hardware, parts, services)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'sku_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'category' => [
                'type' => 'ENUM',
                'constraint' => ['MAT', 'HRD', 'PRT', 'SRV'],
                'null' => false
            ],
            'subcategory' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active'
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('sku_code');
        $this->forge->createTable('product_skus');
    }

    public function down()
    {
        $this->forge->dropTable('product_skus');
        $this->forge->dropTable('invoice_sequences');
        $this->forge->dropTable('account_sequences');
        $this->forge->dropTable('service_registry');
        $this->forge->dropTable('clients');
    }
}
