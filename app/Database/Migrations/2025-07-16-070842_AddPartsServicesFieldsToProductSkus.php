<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPartsServicesFieldsToProductSkus extends Migration
{
    public function up()
    {
        // Add fields for parts-specific data
        $fields = [
            'cost_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'after' => 'price'
            ],
            'quantity_on_hand' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
                'after' => 'cost_price'
            ],
            'minimum_stock' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
                'after' => 'quantity_on_hand'
            ],
            'supplier' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'minimum_stock'
            ],
            'manufacturer' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'supplier'
            ],
            'manufacturer_part_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'manufacturer'
            ],
            'warranty_period' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Warranty period in months',
                'after' => 'manufacturer_part_number'
            ],
            'weight' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => true,
                'comment' => 'Weight in lbs',
                'after' => 'warranty_period'
            ],
            'dimensions' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Dimensions (L x W x H)',
                'after' => 'weight'
            ],
            'duration_minutes' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Service duration in minutes',
                'after' => 'dimensions'
            ],
            'is_taxable' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'default' => 0,
                'comment' => '1 = taxable, 0 = non-taxable',
                'after' => 'duration_minutes'
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'default' => 1,
                'comment' => '1 = active, 0 = inactive',
                'after' => 'is_taxable'
            ]
        ];
        
        $this->forge->addColumn('product_skus', $fields);
        
        // Add index for better performance
        $this->forge->addKey(['category', 'status'], false, false, 'idx_category_status');
        $this->db->query('CREATE INDEX idx_category_status ON product_skus(category, status)');
    }

    public function down()
    {
        // Remove the added fields
        $this->forge->dropColumn('product_skus', [
            'cost_price',
            'quantity_on_hand',
            'minimum_stock',
            'supplier',
            'manufacturer',
            'manufacturer_part_number',
            'warranty_period',
            'weight',
            'dimensions',
            'duration_minutes',
            'is_taxable',
            'is_active'
        ]);
        
        // Drop the index
        $this->forge->dropKey('product_skus', 'idx_category_status');
    }
}
