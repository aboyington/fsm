<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'email', 'username', 'password', 'first_name', 'last_name', 
        'phone', 'role', 'status', 'session_token', 'last_login'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'email'      => 'required|valid_email|is_unique[users.email,id,{id}]',
        'username'   => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'password'   => 'required|min_length[6]',
        'first_name' => 'required|max_length[100]',
        'last_name'  => 'required|max_length[100]',
        'role'       => 'required|in_list[admin,dispatcher,field_tech]',
        'status'     => 'required|in_list[active,inactive,suspended]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email address is already registered.',
        ],
        'username' => [
            'is_unique' => 'This username is already taken.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Hash password before saving
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Authenticate user
     */
    public function authenticate($username, $password)
    {
        $user = $this->where('username', $username)
                     ->orWhere('email', $username)
                     ->where('status', 'active')
                     ->first();

        if ($user && password_verify($password, $user['password'])) {
            // Generate new session token
            $token = bin2hex(random_bytes(32));
            $this->update($user['id'], [
                'session_token' => $token,
                'last_login' => date('Y-m-d H:i:s')
            ]);
            
            $user['session_token'] = $token;
            unset($user['password']); // Don't return password
            return $user;
        }

        return false;
    }

    /**
     * Validate session token
     */
    public function validateToken($token)
    {
        $user = $this->where('session_token', $token)
                     ->where('status', 'active')
                     ->first();

        if ($user) {
            unset($user['password']); // Don't return password
            return $user;
        }

        return false;
    }

    /**
     * Logout user (clear session token)
     */
    public function logout($userId)
    {
        return $this->update($userId, ['session_token' => null]);
    }
}
