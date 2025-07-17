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
        'phone', 'mobile', 'language', 'enable_rtl',
        'role', 'status', 'session_token', 'last_login', 'created_by',
        'employee_id', 'street', 'city', 'state', 'country', 'zip_code'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'email'      => 'required|valid_email',
        'username'   => 'permit_empty|min_length[3]|max_length[100]',
        'password'   => 'permit_empty|min_length[6]',
        'first_name' => 'required|max_length[100]',
        'last_name'  => 'required|max_length[100]',
        'role'       => 'required|in_list[admin,call_center_agent,dispatcher,field_agent,limited_field_agent]',
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
    protected $beforeUpdate   = ['hashPassword', 'validateUniqueOnUpdate'];

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
            // Update last login time
            $this->update($user['id'], [
                'last_login' => date('Y-m-d H:i:s')
            ]);
            
            // Create new session using UserSessionModel
            $userSessionModel = new \App\Models\UserSessionModel();
            $request = service('request');
            
            $token = $userSessionModel->createSession(
                $user['id'],
                $request->getIPAddress(),
                $request->getUserAgent()
            );
            
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
        $userSessionModel = new \App\Models\UserSessionModel();
        return $userSessionModel->getUserByToken($token);
    }

    /**
     * Logout user (remove specific session)
     */
    public function logout($userId, $token = null)
    {
        $userSessionModel = new \App\Models\UserSessionModel();
        
        if ($token) {
            // Remove specific session
            return $userSessionModel->removeSession($token);
        } else {
            // Remove all sessions for user (logout from all devices)
            return $userSessionModel->removeAllUserSessions($userId);
        }
    }

    /**
     * Validate unique fields on update
     */
    protected function validateUniqueOnUpdate(array $data)
    {
        if (!isset($data['id']) || !isset($data['data'])) {
            return $data;
        }

        $id = $data['id'][0] ?? null;
        if (!$id) {
            return $data;
        }

        // Check email uniqueness
        if (isset($data['data']['email'])) {
            $existingUser = $this->where('email', $data['data']['email'])
                                 ->where('id !=', $id)
                                 ->first();
            if ($existingUser) {
                throw new \Exception('This email address is already registered.');
            }
        }

        // Check username uniqueness if provided
        if (isset($data['data']['username']) && !empty($data['data']['username'])) {
            $existingUser = $this->where('username', $data['data']['username'])
                                 ->where('id !=', $id)
                                 ->first();
            if ($existingUser) {
                throw new \Exception('This username is already taken.');
            }
        }

        return $data;
    }
}
