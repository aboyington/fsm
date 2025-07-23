<?php

// Simple test to debug work order number generation
require_once '/Users/anthony/Sites/fsm/vendor/autoload.php';

use App\Models\WorkOrderModel;

// Initialize CodeIgniter manually
$app = \Config\Services::codeigniter();
$app->initialize();

$workOrderModel = new WorkOrderModel();

try {
    $nextNumber = $workOrderModel->getNextWorkOrderNumber();
    echo "Next work order number would be: " . $nextNumber . "\n";
    
    // Also test the SQL query directly
    $prefix = 'WRK-';
    $year = substr(date('Y'), -3);
    $yearPrefix = $prefix . $year . '-';
    
    echo "Year prefix: " . $yearPrefix . "\n";
    
    // Run the query to see what we get
    $query = "SELECT work_order_number FROM work_orders 
              WHERE work_order_number LIKE ? 
              AND deleted_at IS NULL 
              ORDER BY work_order_number DESC";
              
    $db = \Config\Database::connect();
    $result = $db->query($query, [$yearPrefix . '%']);
    $existingNumbers = $result->getResultArray();
    
    echo "Existing non-deleted work orders:\n";
    foreach ($existingNumbers as $row) {
        echo "- " . $row['work_order_number'] . "\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
