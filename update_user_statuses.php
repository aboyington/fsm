<?php
require 'vendor/autoload.php';
$db = \Config\Database::connect();

// Update some users to have different statuses
$db->table('users')->where('email', 'dispatcher@fsm.local')->update(['status' => 'inactive']);
$db->table('users')->where('email', 'fieldtech@fsm.local')->update(['status' => 'suspended']);

echo "User statuses updated successfully!\n";

// Show current users
$users = $db->table('users')->select('first_name, last_name, email, status')->get()->getResultArray();
foreach ($users as $user) {
    echo $user['first_name'] . ' ' . $user['last_name'] . ' - ' . $user['email'] . ' - ' . $user['status'] . "\n";
}
