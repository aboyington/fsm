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
        $status = $this->request->getGet('status') ?: 'active';
        
        // Set the title based on status
        $titles = [
            'active' => 'Active Users',
            'inactive' => 'Inactive Users',
            'invited' => 'Invited Users',
            'deleted' => 'Deleted Users',
            'all' => 'All Users'
        ];
        
        $title = $titles[$status] ?? 'Active Users';
        
        // Fetch users based on status with created_by user name
        $builder = $this->userModel->db->table('users u')
            ->select('u.*, COALESCE(creator.first_name, "System") as created_by_name')
            ->join('users creator', 'creator.id = u.created_by', 'left');
        
        if ($status !== 'all') {
            $builder->where('u.status', $status);
        }
        
        $users = $builder->get()->getResultArray();
        
        // Remove sensitive data
        foreach ($users as &$user) {
            unset($user['password']);
            unset($user['session_token']);
        }
        
        $data = [
            'title' => $title,
            'users' => $users,
            'current_status' => $status
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
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
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

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $email,
            'username' => $email, // Use email as username
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'employee_id' => $this->request->getPost('employee_id'),
            'phone' => $this->request->getPost('phone'),
            'mobile' => $this->request->getPost('mobile'),
            'language' => $this->request->getPost('language') ?: 'en-US',
            'status' => $this->request->getPost('status') ?: 'active',
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
            'status' => 'required|in_list[active,inactive,suspended]',
            'street' => 'permit_empty|max_length[255]',
            'city' => 'permit_empty|max_length[100]',
            'state' => 'permit_empty|max_length[100]',
            'country' => 'permit_empty|max_length[100]',
            'zip_code' => 'permit_empty|max_length[20]'
        ];
        
        // Add password validation only if password is provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }

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
            'status' => $this->request->getPost('status') ?: 'active',
            'street' => $this->request->getPost('street'),
            'city' => $this->request->getPost('city'),
            'state' => $this->request->getPost('state'),
            'country' => $this->request->getPost('country'),
            'zip_code' => $this->request->getPost('zip_code')
        ];
        
        // Only update password if it's provided
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

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
            // Use query builder to include created_by_name
            $builder = $this->userModel->db->table('users u')
                ->select('u.*, COALESCE(creator.first_name, "System") as created_by_name')
                ->join('users creator', 'creator.id = u.created_by', 'left');
            
            if ($status !== 'all') {
                $builder->where('u.status', $status);
            }
            
            if (!empty($searchTerm)) {
                $builder->groupStart()
                    ->like('u.first_name', $searchTerm)
                    ->orLike('u.last_name', $searchTerm)
                    ->orLike('u.email', $searchTerm)
                    ->orLike('u.employee_id', $searchTerm)
                    ->groupEnd();
            }
            
            if (!empty($role)) {
                $builder->where('u.role', $role);
            }
            
            $users = $builder->get()->getResultArray();
            
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

    public function userProfile($id)
    {
        // Simple test first - return basic data
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/workforce/users')->with('error', 'User not found');
        }
        
        // Remove sensitive data
        unset($user['password']);
        unset($user['session_token']);
        
        $data = [
            'user' => $user,
            'skills' => [],
            'territories' => [],
            'activities' => []
        ];
        
        return view('workforce/user_profile', $data);
    }
}
