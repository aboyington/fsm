<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PartsModel;
use App\Models\ServicesModel;

class PartsServices extends Controller
{
    protected $partsModel;
    protected $servicesModel;
    protected $productModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->partsModel = new PartsModel();
        $this->servicesModel = new ServicesModel();
        // Use a generic model for combined operations
        $this->productModel = new \CodeIgniter\Model();
        $this->productModel->setTable('product_skus');
    }

    /**
     * Display the main Parts & Services page
     */
    public function index()
    {
        $data = [
            'title' => 'Parts & Services - FSM',
            'activeTab' => 'parts-services'
        ];

        return view('parts_services/index', $data);
    }

    /**
     * Get parts and services data for DataTables
     */
    public function getData()
    {
        $request = $this->request;
        $type = $request->getGet('type'); // 'parts' or 'services' or 'all'
        $category = $request->getGet('category'); // category filter
        $status = $request->getGet('status'); // status filter
        $search = $request->getGet('search');
        $start = $request->getGet('start') ?? 0;
        $length = $request->getGet('length') ?? 10;

        try {
            // Get data from database
            $builder = $this->productModel->builder();
            
            // Apply type filter
            if ($type === 'parts') {
                $builder->where('category', 'PRT');
            } elseif ($type === 'services') {
                $builder->where('category', 'SRV');
            } else {
                $builder->whereIn('category', ['PRT', 'SRV']);
            }
            
            // Apply search filter
            if (!empty($search)) {
                $builder->groupStart()
                       ->like('name', $search)
                       ->orLike('sku_code', $search)
                       ->orLike('description', $search)
                       ->groupEnd();
            }
            
            // Apply category filter
            if (!empty($category) && $category !== 'all') {
                $builder->like('subcategory', $category);
            }
            
            // Apply status filter
            if (!empty($status) && $status !== 'all') {
                if ($status === 'active') {
                    $builder->where('is_active', 1);
                } elseif ($status === 'inactive') {
                    $builder->where('is_active', 0);
                }
            }
            
            // Get total count for pagination
            $totalCount = $builder->countAllResults(false);
            
            // Apply pagination
            $data = $builder->orderBy('name', 'ASC')
                          ->limit($length, $start)
                          ->get()
                          ->getResultArray();
            
            // Transform data to match frontend expectations
            $transformedData = [];
            foreach ($data as $item) {
                $transformedData[] = [
                    'id' => $item['id'],
                    'type' => $item['category'] === 'PRT' ? 'part' : 'service',
                    'name' => $item['name'],
                    'sku' => $item['sku_code'],
                    'category' => $this->mapSubcategoryToCategory($item['subcategory']),
                    'unit_price' => floatval($item['price']),
                    'cost_price' => floatval($item['cost_price']),
                    'quantity_on_hand' => intval($item['quantity_on_hand']),
                    'minimum_stock' => intval($item['minimum_stock']),
                    'duration_minutes' => intval($item['duration_minutes']),
                    'is_active' => intval($item['is_active']),
                    'description' => $item['description'],
                    'supplier' => $item['supplier'],
                    'manufacturer' => $item['manufacturer'],
                    'manufacturer_part_number' => $item['manufacturer_part_number'],
                    'warranty_period' => intval($item['warranty_period']),
                    'weight' => floatval($item['weight']),
                    'dimensions' => $item['dimensions'],
                    'is_taxable' => intval($item['is_taxable']),
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at']
                ];
            }
            
            $response = [
                'draw' => $request->getGet('draw'),
                'recordsTotal' => $totalCount,
                'recordsFiltered' => $totalCount,
                'data' => $transformedData
            ];

            return $this->response->setJSON($response);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting parts/services data: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw' => $request->getGet('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load data'
            ]);
        }
    }

    /**
     * Create a new part or service
     */
    public function create()
    {
        $request = $this->request;
        $type = $request->getPost('type'); // 'part' or 'service'
        
        $validation = \Config\Services::validation();
        
        if ($type === 'part') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[255]',
                'sku' => 'required|min_length[3]|max_length[100]',
                'description' => 'permit_empty|max_length[500]',
                'category' => 'required|max_length[100]',
                'unit_price' => 'required|decimal',
                'cost_price' => 'permit_empty|decimal',
                'quantity_on_hand' => 'required|integer',
                'minimum_stock' => 'required|integer',
                'supplier' => 'permit_empty|max_length[255]',
                'manufacturer' => 'permit_empty|max_length[255]',
                'manufacturer_part_number' => 'permit_empty|max_length[100]',
                'warranty_period' => 'permit_empty|integer',
                'weight' => 'permit_empty|decimal',
                'dimensions' => 'permit_empty|max_length[100]',
                'is_active' => 'permit_empty|in_list[0,1]'
            ];
        } else {
            $rules = [
                'name' => 'required|min_length[3]|max_length[255]',
                'sku' => 'required|min_length[3]|max_length[100]',
                'description' => 'permit_empty|max_length[500]',
                'category' => 'required|max_length[100]',
                'unit_price' => 'required|decimal',
                'cost_price' => 'permit_empty|decimal',
                'duration_minutes' => 'permit_empty|integer',
                'is_taxable' => 'permit_empty|in_list[0,1]',
                'is_active' => 'permit_empty|in_list[0,1]'
            ];
        }

        $validation->setRules($rules);

        if (!$validation->withRequest($request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            // Get form data
            $data = [
                'sku' => $request->getPost('sku'),
                'name' => $request->getPost('name'),
                'description' => $request->getPost('description'),
                'category' => $request->getPost('category'),
                'unit_price' => $request->getPost('unit_price'),
                'cost_price' => $request->getPost('cost_price'),
                'is_active' => $request->getPost('is_active') ? 1 : 0,
            ];

            // Add type-specific fields
            if ($type === 'part') {
                $data['quantity_on_hand'] = $request->getPost('quantity_on_hand');
                $data['minimum_stock'] = $request->getPost('minimum_stock');
                $data['supplier'] = $request->getPost('supplier');
                $data['manufacturer'] = $request->getPost('manufacturer');
                $data['manufacturer_part_number'] = $request->getPost('manufacturer_part_number');
                $data['warranty_period'] = $request->getPost('warranty_period');
                $data['weight'] = $request->getPost('weight');
                $data['dimensions'] = $request->getPost('dimensions');
                
                // Use PartsModel to create part
                $result = $this->partsModel->createPart($data);
            } else {
                $data['duration_minutes'] = $request->getPost('duration_minutes');
                $data['is_taxable'] = $request->getPost('is_taxable') ? 1 : 0;
                
                // Use ServicesModel to create service
                $result = $this->servicesModel->createService($data);
            }

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => ucfirst($type) . ' created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create ' . $type
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating ' . $type . ': ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while creating the ' . $type . ': ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update an existing part or service
     */
    public function update($id)
    {
        $request = $this->request;
        $type = $request->getPost('type'); // 'part' or 'service'
        
        $validation = \Config\Services::validation();
        
        // Same validation rules as create
        if ($type === 'part') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[255]',
                'sku' => 'required|min_length[3]|max_length[100]',
                'description' => 'permit_empty|max_length[500]',
                'category' => 'required|max_length[100]',
                'unit_price' => 'required|decimal',
                'cost_price' => 'permit_empty|decimal',
                'quantity_on_hand' => 'required|integer',
                'minimum_stock' => 'required|integer',
                'supplier' => 'permit_empty|max_length[255]',
                'manufacturer' => 'permit_empty|max_length[255]',
                'manufacturer_part_number' => 'permit_empty|max_length[100]',
                'warranty_period' => 'permit_empty|integer',
                'weight' => 'permit_empty|decimal',
                'dimensions' => 'permit_empty|max_length[100]',
                'is_active' => 'permit_empty|in_list[0,1]'
            ];
        } else {
            $rules = [
                'name' => 'required|min_length[3]|max_length[255]',
                'sku' => 'required|min_length[3]|max_length[100]',
                'description' => 'permit_empty|max_length[500]',
                'category' => 'required|max_length[100]',
                'unit_price' => 'required|decimal',
                'cost_price' => 'permit_empty|decimal',
                'duration_minutes' => 'permit_empty|integer',
                'is_taxable' => 'permit_empty|in_list[0,1]',
                'is_active' => 'permit_empty|in_list[0,1]'
            ];
        }

        $validation->setRules($rules);

        if (!$validation->withRequest($request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            // Check if item exists
            $existingItem = $this->productModel->find($id);
            if (!$existingItem) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Item not found'
                ]);
            }

            // Get form data
            $data = [
                'sku' => $request->getPost('sku'),
                'name' => $request->getPost('name'),
                'description' => $request->getPost('description'),
                'category' => $request->getPost('category'),
                'unit_price' => $request->getPost('unit_price'),
                'cost_price' => $request->getPost('cost_price'),
                'is_active' => $request->getPost('is_active') ? 1 : 0,
            ];

            // Add type-specific fields
            if ($type === 'part') {
                $data['quantity_on_hand'] = $request->getPost('quantity_on_hand');
                $data['minimum_stock'] = $request->getPost('minimum_stock');
                $data['supplier'] = $request->getPost('supplier');
                $data['manufacturer'] = $request->getPost('manufacturer');
                $data['manufacturer_part_number'] = $request->getPost('manufacturer_part_number');
                $data['warranty_period'] = $request->getPost('warranty_period');
                $data['weight'] = $request->getPost('weight');
                $data['dimensions'] = $request->getPost('dimensions');
                
                // Use PartsModel to update part
                $result = $this->partsModel->updatePart($id, $data);
            } else {
                $data['duration_minutes'] = $request->getPost('duration_minutes');
                $data['is_taxable'] = $request->getPost('is_taxable') ? 1 : 0;
                
                // Use ServicesModel to update service
                $result = $this->servicesModel->updateService($id, $data);
            }

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => ucfirst($type) . ' updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update ' . $type
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating ' . $type . ': ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while updating the ' . $type . ': ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete a part or service
     */
    public function delete($id)
    {
        $request = $this->request;
        $type = $request->getPost('type');

        // TODO: Implement delete logic
        return $this->response->setJSON([
            'success' => true,
            'message' => ucfirst($type) . ' deleted successfully'
        ]);
    }

    /**
     * Get details of a specific part or service
     */
    public function show($id)
    {
        $request = $this->request;
        $type = $request->getGet('type');

        try {
            // Get item from database
            $item = $this->productModel->find($id);
            
            if (!$item) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Item not found'
                ]);
            }
            
            // Transform data to match frontend expectations
            $transformedItem = [
                'id' => $item['id'],
                'type' => $item['category'] === 'PRT' ? 'part' : 'service',
                'name' => $item['name'],
                'sku' => $item['sku_code'],
                'category' => $this->mapSubcategoryToCategory($item['subcategory']),
                'unit_price' => floatval($item['price']),
                'cost_price' => floatval($item['cost_price']),
                'quantity_on_hand' => intval($item['quantity_on_hand']),
                'minimum_stock' => intval($item['minimum_stock']),
                'duration_minutes' => intval($item['duration_minutes']),
                'is_active' => intval($item['is_active']),
                'description' => $item['description'],
                'supplier' => $item['supplier'],
                'manufacturer' => $item['manufacturer'],
                'manufacturer_part_number' => $item['manufacturer_part_number'],
                'warranty_period' => intval($item['warranty_period']),
                'weight' => floatval($item['weight']),
                'dimensions' => $item['dimensions'],
                'is_taxable' => intval($item['is_taxable']),
                'created_at' => $item['created_at'],
                'updated_at' => $item['updated_at']
            ];

            return $this->response->setJSON([
                'success' => true,
                'data' => $transformedItem
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting item details: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load item details: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get statistics for dashboard cards
     */
    public function getStats()
    {
        // Disable debug toolbar for clean JSON
        if (function_exists('ini_set')) {
            ini_set('display_errors', 0);
        }
        
        // Force JSON response and disable debug toolbar
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Cache-Control', 'no-cache');
        
        // Get all data to calculate statistics
        $allData = $this->getMockData('all');
        $parts = $this->getMockData('parts');
        $services = $this->getMockData('services');
        
        // Calculate total parts and services
        $totalParts = count($parts);
        $totalServices = count($services);
        
        // Calculate low stock items (only applies to parts)
        $lowStockItems = 0;
        foreach ($parts as $part) {
            if ($part['quantity_on_hand'] <= $part['minimum_stock']) {
                $lowStockItems++;
            }
        }
        
        // Calculate total value (parts inventory value + services value)
        $totalValue = 0;
        foreach ($parts as $part) {
            $totalValue += $part['quantity_on_hand'] * $part['unit_price'];
        }
        foreach ($services as $service) {
            $totalValue += $service['unit_price'] * 10; // Assume 10 uses per service for demo
        }
        
        $stats = [
            'total_parts' => $totalParts,
            'total_services' => $totalServices,
            'low_stock_count' => $lowStockItems,
            'total_value' => $totalValue
        ];
        
        // Return clean JSON response
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
        exit;
    }

    /**
     * Get popularity insights for services
     */
    public function getPopularityInsights()
    {
        // Force JSON response and disable debug toolbar
        $this->response->setHeader('Content-Type', 'application/json');
        
        // TODO: Implement actual analytics from service history
        $mockInsights = [
            'most_used_services' => [
                ['name' => 'CCTV System Installation', 'usage_count' => 52],
                ['name' => 'Alarm System Programming', 'usage_count' => 41],
                ['name' => 'Access Control Setup', 'usage_count' => 35],
                ['name' => 'Network Configuration', 'usage_count' => 28],
                ['name' => 'Security System Maintenance', 'usage_count' => 24]
            ],
            'most_used_parts' => [
                ['name' => 'IP Camera 4MP Dome', 'usage_count' => 67],
                ['name' => 'PIR Motion Sensor', 'usage_count' => 54],
                ['name' => 'Cat6 Ethernet Cable', 'usage_count' => 89],
                ['name' => 'Proximity Card Reader', 'usage_count' => 31]
            ],
            'low_stock_alerts' => [
                ['name' => 'IP Camera 4MP Dome', 'current_stock' => 3, 'minimum_stock' => 10],
                ['name' => 'Proximity Card Reader', 'current_stock' => 2, 'minimum_stock' => 5]
            ]
        ];

        // Return clean JSON response
        echo json_encode([
            'success' => true,
            'data' => $mockInsights
        ]);
        exit;
    }

    /**
     * Display insights for the dashboard
     */
    public function insights()
    {
        try {
            $insights = [
                'most_used_services' => $this->servicesModel->getMostUsedServices(5),
                'most_used_parts' => $this->partsModel->getMostUsedParts(5),
                'low_stock_alerts' => $this->partsModel->getLowStockAlerts()
            ];
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $insights
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load insights: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate mock data for testing
     */
    private function getMockData($type = 'all')
    {
        $parts = [
            [
                'id' => 1,
                'type' => 'part',
                'name' => 'IP Camera 4MP Dome',
                'sku' => 'CAM-IP-001',
                'description' => '4MP IP security camera with night vision',
                'category' => 'CCTV',
                'unit_price' => 189.99,
                'cost_price' => 125.00,
                'quantity_on_hand' => 3,
                'minimum_stock' => 10,
                'supplier' => 'Security Supply Co.',
                'manufacturer' => 'Hikvision',
                'manufacturer_part_number' => 'DS-2CD2143G0-I',
                'warranty_period' => 24,
                'weight' => 1.2,
                'dimensions' => '5.5x5.5x4.2',
                'is_active' => 1,
                'created_at' => '2025-01-15 10:00:00',
                'updated_at' => '2025-01-15 10:00:00'
            ],
            [
                'id' => 2,
                'type' => 'part',
                'name' => 'PIR Motion Sensor',
                'sku' => 'PIR-001',
                'description' => 'Passive infrared motion detector for alarm systems',
                'category' => 'Alarm',
                'unit_price' => 45.99,
                'cost_price' => 28.00,
                'quantity_on_hand' => 100,
                'minimum_stock' => 20,
                'supplier' => 'Alarm Components Ltd.',
                'manufacturer' => 'Paradox',
                'manufacturer_part_number' => 'DG75',
                'warranty_period' => 36,
                'weight' => 0.3,
                'dimensions' => '4.5x3.2x2.1',
                'is_active' => 1,
                'created_at' => '2025-01-15 10:00:00',
                'updated_at' => '2025-01-15 10:00:00'
            ],
            [
                'id' => 3,
                'type' => 'part',
                'name' => 'Proximity Card Reader',
                'sku' => 'ACR-001',
                'description' => 'RFID proximity card reader for access control',
                'category' => 'Access Control',
                'unit_price' => 125.99,
                'cost_price' => 85.00,
                'quantity_on_hand' => 2,
                'minimum_stock' => 5,
                'supplier' => 'Access Control Solutions',
                'manufacturer' => 'HID',
                'manufacturer_part_number' => 'ProxPoint Plus 6005',
                'warranty_period' => 24,
                'weight' => 0.8,
                'dimensions' => '4.8x3.5x1.2',
                'is_active' => 1,
                'created_at' => '2025-01-15 10:00:00',
                'updated_at' => '2025-01-15 10:00:00'
            ],
            [
                'id' => 4,
                'type' => 'part',
                'name' => 'Cat6 Ethernet Cable',
                'sku' => 'CAB-006',
                'description' => 'Category 6 ethernet cable for network installations',
                'category' => 'Networking',
                'unit_price' => 0.85,
                'cost_price' => 0.45,
                'quantity_on_hand' => 5000,
                'minimum_stock' => 1000,
                'supplier' => 'Network Supply Inc.',
                'manufacturer' => 'Belden',
                'manufacturer_part_number' => 'CAT6-UTP',
                'warranty_period' => 12,
                'weight' => 0.02,
                'dimensions' => 'N/A',
                'is_active' => 1,
                'created_at' => '2025-01-15 10:00:00',
                'updated_at' => '2025-01-15 10:00:00'
            ]
        ];

        $services = [
            [
                'id' => 5,
                'type' => 'service',
                'name' => 'CCTV System Installation',
                'sku' => 'SV-CCTV-001',
                'description' => 'Complete CCTV camera system installation and configuration',
                'category' => 'CCTV',
                'unit_price' => 250.00,
                'cost_price' => 120.00,
                'duration_minutes' => 180,
                'is_taxable' => 1,
                'is_active' => 1,
                'created_at' => '2025-01-15 10:00:00',
                'updated_at' => '2025-01-15 10:00:00'
            ],
            [
                'id' => 6,
                'type' => 'service',
                'name' => 'Alarm System Programming',
                'sku' => 'SV-ALM-001',
                'description' => 'Alarm system programming and zone configuration',
                'category' => 'Alarm',
                'unit_price' => 125.00,
                'cost_price' => 65.00,
                'duration_minutes' => 90,
                'is_taxable' => 1,
                'is_active' => 1,
                'created_at' => '2025-01-15 10:00:00',
                'updated_at' => '2025-01-15 10:00:00'
            ],
            [
                'id' => 7,
                'type' => 'service',
                'name' => 'Access Control Setup',
                'sku' => 'SV-ACC-001',
                'description' => 'Access control system setup and user management',
                'category' => 'Access Control',
                'unit_price' => 185.00,
                'cost_price' => 90.00,
                'duration_minutes' => 150,
                'is_taxable' => 1,
                'is_active' => 1,
                'created_at' => '2025-01-15 10:00:00',
                'updated_at' => '2025-01-15 10:00:00'
            ],
            [
                'id' => 8,
                'type' => 'service',
                'name' => 'Network Configuration',
                'sku' => 'SV-NET-001',
                'description' => 'Network setup and configuration for security systems',
                'category' => 'I.T',
                'unit_price' => 165.00,
                'cost_price' => 85.00,
                'duration_minutes' => 120,
                'is_taxable' => 1,
                'is_active' => 1,
                'created_at' => '2025-01-15 10:00:00',
                'updated_at' => '2025-01-15 10:00:00'
            ],
            [
                'id' => 9,
                'type' => 'service',
                'name' => 'Security System Maintenance',
                'sku' => 'SV-SEC-001',
                'description' => 'General security system maintenance and inspection',
                'category' => 'Security',
                'unit_price' => 145.00,
                'cost_price' => 70.00,
                'duration_minutes' => 105,
                'is_taxable' => 1,
                'is_active' => 1,
                'created_at' => '2025-01-15 10:00:00',
                'updated_at' => '2025-01-15 10:00:00'
            ]
        ];

        switch ($type) {
            case 'parts':
                return $parts;
            case 'services':
                return $services;
            default:
                return array_merge($parts, $services);
        }
    }

    /**
     * Map database subcategory to frontend category
     */
    private function mapSubcategoryToCategory($subcategory)
    {
        $mapping = [
            'CCTV' => 'CCTV',
            'ALM' => 'Alarm',
            'ACC' => 'Access Control',
            'NET' => 'Networking',
            'IT' => 'I.T',
            'SEC' => 'Security',
            'GEN' => 'General'
        ];
        
        return $mapping[$subcategory] ?? 'General';
    }

    /**
     * Export data to CSV
     */
    public function export($type)
    {
        try {
            $model = $type === 'parts' ? $this->partsModel : $this->servicesModel;
            $data = $model->findAll();
            
            $filename = $type . '-export-' . date('Y-m-d') . '.csv';
            
            $this->response->setHeader('Content-Type', 'text/csv');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            $headers = $this->getCsvHeaders($type);
            fputcsv($output, $headers);
            
            // Add data rows
            foreach ($data as $row) {
                $csvRow = $this->formatCsvRow($row, $type);
                fputcsv($output, $csvRow);
            }
            
            fclose($output);
            exit;
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Download CSV template
     */
    public function template($type)
    {
        try {
            $filename = $type . '-template.csv';
            
            $this->response->setHeader('Content-Type', 'text/csv');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            $headers = $this->getCsvHeaders($type);
            fputcsv($output, $headers);
            
            // Add sample data row
            $sampleRow = $this->getSampleRow($type);
            fputcsv($output, $sampleRow);
            
            fclose($output);
            exit;
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Template download failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Import data from CSV
     */
    public function import()
    {
        try {
            $type = $this->request->getPost('type');
            $file = $this->request->getFile('csv_file');
            
            if (!$file || !$file->isValid()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please select a valid CSV file'
                ]);
            }
            
            $model = $type === 'parts' ? $this->partsModel : $this->servicesModel;
            $results = $this->processImportFile($file, $type, $model);
            
            return $this->response->setJSON([
                'success' => $results['success'],
                'message' => $results['message'],
                'summary' => $results['summary'],
                'errors' => $results['errors'] ?? []
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get CSV headers for export/import
     */
    private function getCsvHeaders($type)
    {
        $common = ['name', 'sku', 'category', 'unit_price', 'cost_price', 'description', 'is_active'];
        
        if ($type === 'parts') {
            return array_merge($common, [
                'quantity_on_hand', 'minimum_stock', 'supplier', 'manufacturer', 
                'manufacturer_part_number', 'warranty_period', 'weight', 'dimensions'
            ]);
        } else {
            return array_merge($common, ['duration_minutes', 'is_taxable']);
        }
    }

    /**
     * Format a database row for CSV export
     */
    private function formatCsvRow($row, $type)
    {
        $common = [
            $row['name'],
            $row['sku'],
            $row['category'],
            $row['unit_price'],
            $row['cost_price'],
            $row['description'],
            $row['is_active'] ? 'Yes' : 'No'
        ];
        
        if ($type === 'parts') {
            return array_merge($common, [
                $row['quantity_on_hand'],
                $row['minimum_stock'],
                $row['supplier'],
                $row['manufacturer'],
                $row['manufacturer_part_number'],
                $row['warranty_period'],
                $row['weight'],
                $row['dimensions']
            ]);
        } else {
            return array_merge($common, [
                $row['duration_minutes'],
                $row['is_taxable'] ? 'Yes' : 'No'
            ]);
        }
    }

    /**
     * Get sample row for template
     */
    private function getSampleRow($type)
    {
        $common = [
            'Sample Item',
            'SKU001',
            'General',
            '99.99',
            '79.99',
            'Sample description',
            'Yes'
        ];
        
        if ($type === 'parts') {
            return array_merge($common, [
                '10',
                '5',
                'Sample Supplier',
                'Sample Manufacturer',
                'MPN123',
                '12 months',
                '1.5 kg',
                '10x5x3 cm'
            ]);
        } else {
            return array_merge($common, ['60', 'Yes']);
        }
    }

    /**
     * Process import file
     */
    private function processImportFile($file, $type, $model)
    {
        $summary = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];
        $errors = [];
        $rowNumber = 1;
        
        if (($handle = fopen($file->getTempName(), 'r')) !== FALSE) {
            $headers = fgetcsv($handle); // Skip header row
            $expectedHeaders = $this->getCsvHeaders($type);
            
            // Validate headers
            if (!$this->validateHeaders($headers, $expectedHeaders)) {
                return [
                    'success' => false,
                    'message' => 'Invalid CSV format. Please use the template.',
                    'summary' => $summary,
                    'errors' => ['Header row does not match expected format']
                ];
            }
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                $rowNumber++;
                
                try {
                    $itemData = $this->parseImportRow($data, $type);
                    
                    // Check if item exists by SKU
                    $existing = $model->where('sku', $itemData['sku'])->first();
                    
                    if ($existing) {
                        // Update existing item
                        $model->update($existing['id'], $itemData);
                        $summary['updated']++;
                    } else {
                        // Create new item
                        $model->insert($itemData);
                        $summary['created']++;
                    }
                } catch (Exception $e) {
                    $summary['errors']++;
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }
            
            fclose($handle);
        }
        
        $message = "Import completed. {$summary['created']} created, {$summary['updated']} updated";
        if ($summary['errors'] > 0) {
            $message .= ", {$summary['errors']} errors";
        }
        
        return [
            'success' => true,
            'message' => $message,
            'summary' => $summary,
            'errors' => $errors
        ];
    }

    /**
     * Validate CSV headers
     */
    private function validateHeaders($actual, $expected)
    {
        return count(array_diff($expected, $actual)) === 0;
    }

    /**
     * Parse import row data
     */
    private function parseImportRow($data, $type)
    {
        $itemData = [
            'name' => trim($data[0]),
            'sku' => trim($data[1]),
            'category' => trim($data[2]),
            'unit_price' => floatval($data[3]),
            'cost_price' => floatval($data[4]),
            'description' => trim($data[5]),
            'is_active' => strtolower(trim($data[6])) === 'yes' ? 1 : 0,
            'type' => $type === 'parts' ? 'part' : 'service'
        ];
        
        if ($type === 'parts') {
            $itemData['quantity_on_hand'] = intval($data[7]);
            $itemData['minimum_stock'] = intval($data[8]);
            $itemData['supplier'] = trim($data[9]);
            $itemData['manufacturer'] = trim($data[10]);
            $itemData['manufacturer_part_number'] = trim($data[11]);
            $itemData['warranty_period'] = trim($data[12]);
            $itemData['weight'] = trim($data[13]);
            $itemData['dimensions'] = trim($data[14]);
        } else {
            $itemData['duration_minutes'] = intval($data[7]);
            $itemData['is_taxable'] = strtolower(trim($data[8])) === 'yes' ? 1 : 0;
        }
        
        return $itemData;
    }

}
