<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class AuthController extends BaseController
{
    use ResponseTrait;

    /**
     * Login user
     */
    public function login()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->authenticate($username, $password);

        if (!$user) {
            return $this->failUnauthorized('Invalid username or password');
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $user['session_token']
            ]
        ]);
    }

    /**
     * Logout user
     */
    public function logout()
    {
        $user = $this->request->user ?? null;
        
        if ($user) {
            // Get the token from the request
            $token = null;
            $authHeader = $this->request->getHeaderLine('Authorization');
            if ($authHeader) {
                $matches = [];
                if (preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
                    $token = $matches[1];
                }
            }
            
            if (!$token) {
                $token = $this->request->getHeaderLine('X-API-Token');
            }
            
            $userModel = new UserModel();
            $userModel->logout($user['id'], $token);
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Get current user info
     */
    public function me()
    {
        $user = $this->request->user ?? null;
        
        if (!$user) {
            return $this->failUnauthorized('Not authenticated');
        }

        return $this->respond([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * Register new user (admin only)
     */
    public function register()
    {
        $user = $this->request->user ?? null;
        
        if (!$user || $user['role'] !== 'admin') {
            return $this->failForbidden('Only admins can register new users');
        }

        $rules = [
            'email'      => 'required|valid_email',
            'username'   => 'required|min_length[3]|max_length[100]',
            'password'   => 'required|min_length[6]',
            'first_name' => 'required|max_length[100]',
            'last_name'  => 'required|max_length[100]',
            'role'       => 'required|in_list[admin,dispatcher,field_tech]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $userModel = new UserModel();
        
        $data = [
            'email'      => $this->request->getPost('email'),
            'username'   => $this->request->getPost('username'),
            'password'   => $this->request->getPost('password'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'phone'      => $this->request->getPost('phone'),
            'role'       => $this->request->getPost('role'),
            'status'     => 'active'
        ];

        try {
            $userId = $userModel->insert($data);
            
            if (!$userId) {
                return $this->fail($userModel->errors());
            }

            $newUser = $userModel->find($userId);
            unset($newUser['password']);

            return $this->respondCreated([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $newUser
            ]);
        } catch (\Exception $e) {
            return $this->fail('Failed to create user: ' . $e->getMessage());
        }
    }
}
