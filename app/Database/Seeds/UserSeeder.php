<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        // Create admin user
        $data = [
            'email'      => 'admin@fsm.local',
            'username'   => 'admin',
            'password'   => 'admin123', // Will be hashed by the model
            'first_name' => 'System',
            'last_name'  => 'Administrator',
            'phone'      => '555-0100',
            'role'       => 'admin',
            'status'     => 'active'
        ];

        $userModel->insert($data);

        // Create dispatcher user
        $data = [
            'email'      => 'dispatcher@fsm.local',
            'username'   => 'dispatcher',
            'password'   => 'dispatch123',
            'first_name' => 'Jane',
            'last_name'  => 'Dispatcher',
            'phone'      => '555-0101',
            'role'       => 'dispatcher',
            'status'     => 'active'
        ];

        $userModel->insert($data);

        // Create field tech user
        $data = [
            'email'      => 'tech@fsm.local',
            'username'   => 'fieldtech',
            'password'   => 'tech123',
            'first_name' => 'John',
            'last_name'  => 'Technician',
            'phone'      => '555-0102',
            'role'       => 'field_tech',
            'status'     => 'active'
        ];

        $userModel->insert($data);

        echo "Users seeded successfully!\n";
        echo "Login credentials:\n";
        echo "Admin: admin / admin123\n";
        echo "Dispatcher: dispatcher / dispatch123\n";
        echo "Field Tech: fieldtech / tech123\n";
    }
}
