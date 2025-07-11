<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CustomerModel;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customerModel = new CustomerModel();

        $customers = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '555-0123',
                'company_name' => 'Doe Enterprises',
                'address' => '123 Main St',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62701',
                'latitude' => 39.7817,
                'longitude' => -89.6501,
                'customer_type' => 'commercial',
                'status' => 'active',
                'notes' => 'Has 3 outdoor cameras, needs regular maintenance'
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '555-0124',
                'address' => '456 Oak Ave',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62702',
                'latitude' => 39.7911,
                'longitude' => -89.6446,
                'customer_type' => 'residential',
                'status' => 'active',
                'notes' => 'New installation requested'
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Johnson',
                'email' => 'robert.j@example.com',
                'phone' => '555-0125',
                'company_name' => 'Johnson Security Solutions',
                'address' => '789 Elm St',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62703',
                'latitude' => 39.7984,
                'longitude' => -89.6543,
                'customer_type' => 'commercial',
                'status' => 'active',
                'notes' => 'Partner company, provides referrals'
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'email' => 'maria.g@example.com',
                'phone' => '555-0126',
                'address' => '321 Pine Rd',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62704',
                'latitude' => 39.7756,
                'longitude' => -89.6789,
                'customer_type' => 'residential',
                'status' => 'prospect',
                'notes' => 'Interested in camera installation, awaiting quote'
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@example.com',
                'phone' => '555-0127',
                'company_name' => 'Brown\'s Market',
                'address' => '555 Market St',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62705',
                'latitude' => 39.8012,
                'longitude' => -89.6234,
                'customer_type' => 'commercial',
                'status' => 'active',
                'notes' => 'Multiple locations, high priority customer'
            ]
        ];

        foreach ($customers as $customer) {
            $customerModel->insert($customer);
        }

        echo "Sample customers seeded successfully!\n";
    }
}
