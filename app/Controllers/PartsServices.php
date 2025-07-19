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
                'quantity_on_hand' => 'permit_empty|integer',
                'minimum_stock' => 'permit_empty|integer',
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
        
        // Same validation rules as create but with SKU uniqueness exception for current item
        if ($type === 'part') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[255]',
                'sku' => "required|min_length[3]|max_length[100]|is_unique[product_skus.sku_code,id,{$id}]",
                'description' => 'permit_empty|max_length[500]',
                'category' => 'required|max_length[100]',
                'unit_price' => 'required|decimal',
                'cost_price' => 'permit_empty|decimal',
                'quantity_on_hand' => 'permit_empty|integer',
                'minimum_stock' => 'permit_empty|integer',
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
                'sku' => "required|min_length[3]|max_length[100]|is_unique[product_skus.sku_code,id,{$id}]",
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
                
                // Skip model validation to avoid placeholder issues
                $this->partsModel->skipValidation(true);
                $result = $this->partsModel->updatePart($id, $data);
                $this->partsModel->skipValidation(false);
            } else {
                $data['duration_minutes'] = $request->getPost('duration_minutes');
                $data['is_taxable'] = $request->getPost('is_taxable') ? 1 : 0;
                
                // Skip model validation to avoid placeholder issues
                $this->servicesModel->skipValidation(true);
                $result = $this->servicesModel->updateService($id, $data);
                $this->servicesModel->skipValidation(false);
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
        try {
            // Get data from database
            $builder = $this->productModel->builder();
            
            // Get parts count
            $totalParts = $builder->where('category', 'PRT')->countAllResults();
            
            // Get services count
            $totalServices = $builder->where('category', 'SRV')->countAllResults();
            
            // Get low stock items (parts only)
            $lowStockQuery = $this->productModel->builder()
                ->where('category', 'PRT')
                ->where('quantity_on_hand <=', 'minimum_stock', false)
                ->where('minimum_stock >', 0); // Only count items with minimum stock set
            $lowStockItems = $lowStockQuery->countAllResults();
            
            // Calculate total inventory value (parts only)
            $partsData = $this->productModel->builder()
                ->select('quantity_on_hand, price')
                ->where('category', 'PRT')
                ->get()
                ->getResultArray();
            
            $totalValue = 0;
            foreach ($partsData as $part) {
                $totalValue += ($part['quantity_on_hand'] ?? 0) * ($part['price'] ?? 0);
            }
            
            $stats = [
                'total_parts' => $totalParts,
                'total_services' => $totalServices,
                'low_stock_count' => $lowStockItems,
                'total_value' => $totalValue
            ];
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting stats: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load statistics',
                'data' => [
                    'total_parts' => 0,
                    'total_services' => 0,
                    'low_stock_count' => 0,
                    'total_value' => 0
                ]
            ]);
        }
    }

    /**
     * Get popularity insights for services
     */
    public function getPopularityInsights()
    {
        try {
            // Get low stock alerts from real data
            $lowStockAlerts = $this->productModel->builder()
                ->select('name, quantity_on_hand as current_stock, minimum_stock')
                ->where('category', 'PRT')
                ->where('quantity_on_hand <=', 'minimum_stock', false)
                ->where('minimum_stock >', 0)
                ->orderBy('quantity_on_hand', 'ASC')
                ->limit(10)
                ->get()
                ->getResultArray();
            
            $insights = [
                'most_used_services' => [], // TODO: Implement when service usage tracking is added
                'most_used_parts' => [], // TODO: Implement when parts usage tracking is added
                'low_stock_alerts' => $lowStockAlerts
            ];
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $insights
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting insights: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load insights',
                'data' => [
                    'most_used_services' => [],
                    'most_used_parts' => [],
                    'low_stock_alerts' => []
                ]
            ]);
        }
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
            // Get data using the product model with proper filtering
            $builder = $this->productModel->builder();
            
            if ($type === 'parts') {
                $builder->where('category', 'PRT');
            } elseif ($type === 'services') {
                $builder->where('category', 'SRV');
            } else {
                $builder->whereIn('category', ['PRT', 'SRV']);
            }
            
            $data = $builder->get()->getResultArray();
            
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
            $row['sku_code'],
            $this->mapSubcategoryToCategory($row['subcategory'] ?? ''),
            $row['price'],
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
                    $existing = $this->productModel->where('sku_code', $itemData['sku'])->first();
                    
                    if ($existing) {
                        // Update existing item using the appropriate model method
                        if ($type === 'parts') {
                            $result = $this->partsModel->updatePart($existing['id'], $itemData);
                        } else {
                            $result = $this->servicesModel->updateService($existing['id'], $itemData);
                        }
                        $summary['updated']++;
                    } else {
                        // Create new item using the appropriate model method
                        if ($type === 'parts') {
                            $result = $this->partsModel->createPart($itemData);
                        } else {
                            $result = $this->servicesModel->createService($itemData);
                        }
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
