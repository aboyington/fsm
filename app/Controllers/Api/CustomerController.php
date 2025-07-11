<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use CodeIgniter\API\ResponseTrait;

class CustomerController extends BaseController
{
    use ResponseTrait;

    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    /**
     * Get all customers
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $limit = $this->request->getGet('limit') ?? 20;
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->customerModel;

        if ($search) {
            $customers = $builder->search($search);
        } elseif ($status) {
            $customers = $builder->getByStatus($status);
        } else {
            $customers = $builder->paginate($limit);
        }

        return $this->respond([
            'status' => 'success',
            'data' => $customers,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $this->customerModel->countAllResults()
            ]
        ]);
    }

    /**
     * Get single customer
     */
    public function show($id = null)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return $this->failNotFound('Customer not found');
        }

        return $this->respond([
            'status' => 'success',
            'data' => $customer
        ]);
    }

    /**
     * Create new customer
     */
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->fail('No data provided');
        }

        try {
            $customerId = $this->customerModel->insert($data);
            
            if (!$customerId) {
                return $this->fail($this->customerModel->errors());
            }

            $customer = $this->customerModel->find($customerId);

            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Customer created successfully',
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            return $this->fail('Failed to create customer: ' . $e->getMessage());
        }
    }

    /**
     * Update customer
     */
    public function update($id = null)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return $this->failNotFound('Customer not found');
        }

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->fail('No data provided');
        }

        try {
            if (!$this->customerModel->update($id, $data)) {
                return $this->fail($this->customerModel->errors());
            }

            $updatedCustomer = $this->customerModel->find($id);

            return $this->respond([
                'status' => 'success',
                'message' => 'Customer updated successfully',
                'data' => $updatedCustomer
            ]);
        } catch (\Exception $e) {
            return $this->fail('Failed to update customer: ' . $e->getMessage());
        }
    }

    /**
     * Delete customer
     */
    public function delete($id = null)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return $this->failNotFound('Customer not found');
        }

        try {
            $this->customerModel->delete($id);

            return $this->respond([
                'status' => 'success',
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->fail('Failed to delete customer: ' . $e->getMessage());
        }
    }

    /**
     * Get customers near location
     */
    public function nearby()
    {
        $lat = $this->request->getGet('lat');
        $lng = $this->request->getGet('lng');
        $radius = $this->request->getGet('radius') ?? 10;

        if (!$lat || !$lng) {
            return $this->fail('Latitude and longitude are required');
        }

        $customers = $this->customerModel->getNearLocation($lat, $lng, $radius);

        return $this->respond([
            'status' => 'success',
            'data' => $customers,
            'params' => [
                'latitude' => $lat,
                'longitude' => $lng,
                'radius_km' => $radius
            ]
        ]);
    }

    /**
     * Sync customers from Canvass Global
     */
    public function syncFromCanvassGlobal()
    {
        // This would connect to Canvass Global API
        // For now, it's a placeholder
        
        return $this->respond([
            'status' => 'success',
            'message' => 'Sync functionality will be implemented to connect with Canvass Global API'
        ]);
    }
}
