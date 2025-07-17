<?php

namespace App\Controllers;

use App\Models\UserModel;

class WorkforceController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function users()
    {
        $data = [
            'title' => 'Active Users',
            'users' => $this->userModel->findAll() // Fetch all users
        ];

        return view('workforce/users', $data);
    }

    public function addUserModal()
    {
        return view('workforce/add_user_modal');
    }

    public function createUser()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $rules = [
            'first_name' => 'required|max_length[100]',
            'last_name' => 'required|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'role' => 'required|in_list[admin,dispatcher,field_agent,call_center_agent,manager,technician,limited_field_agent]',
            'employee_id' => 'permit_empty|max_length[50]',
            'phone' => 'permit_empty|max_length[20]',
            'mobile' => 'permit_empty|max_length[20]',
            'language' => 'permit_empty|max_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'employee_id' => $this->request->getPost('employee_id'),
            'phone' => $this->request->getPost('phone'),
            'mobile' => $this->request->getPost('mobile'),
            'language' => $this->request->getPost('language') ?: 'en-US',
            'status' => 'active',
            'created_by' => session('user_id') ?? 1
        ];

        try {
            $userId = $this->userModel->insert($data);
            
            if ($userId) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User created successfully',
                    'user_id' => $userId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create user'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ]);
        }
    }

    public function getUser($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        try {
            $user = $this->userModel->find($id);
            
            if ($user) {
                // Remove sensitive data
                unset($user['password']);
                unset($user['session_token']);
                
                return $this->response->setJSON([
                    'success' => true,
                    'user' => $user
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error fetching user: ' . $e->getMessage()
            ]);
        }
    }

    public function updateUser($id = null)
    {
        // Handle both POST with ID in form and POST with ID in URL
        if ($id === null) {
            $id = $this->request->getPost('id');
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID is required'
            ]);
        }

        $rules = [
            'first_name' => 'required|max_length[100]',
            'last_name' => 'required|max_length[100]',
            'email' => 'required|valid_email',
            'role' => 'required|in_list[admin,dispatcher,field_agent,call_center_agent,manager,technician,limited_field_agent]',
            'employee_id' => 'permit_empty|max_length[50]',
            'phone' => 'permit_empty|max_length[20]',
            'mobile' => 'permit_empty|max_length[20]',
            'language' => 'permit_empty|max_length[10]',
            'street' => 'permit_empty|max_length[255]',
            'city' => 'permit_empty|max_length[100]',
            'state' => 'permit_empty|max_length[100]',
            'country' => 'permit_empty|max_length[100]',
            'zip_code' => 'permit_empty|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Check if user exists
        $existingUser = $this->userModel->find($id);
        if (!$existingUser) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'employee_id' => $this->request->getPost('employee_id'),
            'phone' => $this->request->getPost('phone'),
            'mobile' => $this->request->getPost('mobile'),
            'language' => $this->request->getPost('language') ?: 'en-US',
            'street' => $this->request->getPost('street'),
            'city' => $this->request->getPost('city'),
            'state' => $this->request->getPost('state'),
            'country' => $this->request->getPost('country'),
            'zip_code' => $this->request->getPost('zip_code')
        ];

        try {
            $result = $this->userModel->update($id, $data);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update user'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteUser($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        try {
            $user = $this->userModel->find($id);
            
            if (!$user) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // Instead of hard delete, update status to inactive
            $result = $this->userModel->update($id, ['status' => 'inactive']);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete user'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ]);
        }
    }

    public function searchUsers()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $searchTerm = $this->request->getGet('search');
        $role = $this->request->getGet('role');
        $status = $this->request->getGet('status') ?: 'active';

        try {
            $builder = $this->userModel->where('status', $status);
            
            if (!empty($searchTerm)) {
                $builder->groupStart()
                    ->like('first_name', $searchTerm)
                    ->orLike('last_name', $searchTerm)
                    ->orLike('email', $searchTerm)
                    ->orLike('employee_id', $searchTerm)
                    ->groupEnd();
            }
            
            if (!empty($role)) {
                $builder->where('role', $role);
            }
            
            $users = $builder->findAll();
            
            // Remove sensitive data
            foreach ($users as &$user) {
                unset($user['password']);
                unset($user['session_token']);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error searching users: ' . $e->getMessage()
            ]);
        }
    }
}
