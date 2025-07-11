<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class UpdateUserStatuses extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'app:update-user-statuses';
    protected $description = 'Updates user statuses for testing filters';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // Update some users to have different statuses
        $db->table('users')->where('email', 'dispatcher@fsm.local')->update(['status' => 'inactive']);
        $db->table('users')->where('email', 'fieldtech@fsm.local')->update(['status' => 'suspended']);
        
        CLI::write('User statuses updated successfully!', 'green');
        
        // Show current users
        $users = $db->table('users')->select('first_name, last_name, email, status')->get()->getResultArray();
        CLI::write("\nCurrent users:", 'yellow');
        foreach ($users as $user) {
            $color = $user['status'] == 'active' ? 'green' : ($user['status'] == 'inactive' ? 'yellow' : 'red');
            CLI::write($user['first_name'] . ' ' . $user['last_name'] . ' - ' . $user['email'] . ' - ' . $user['status'], $color);
        }
    }
}
