<?php

/**
 * Simple script to retroactively generate account numbers for existing clients
 * 
 * This script will:
 * 1. Generate client abbreviations from company names
 * 2. Generate sequential account numbers using the ACC prefix
 * 3. Update the clients table with the new account numbers
 * 4. Update the account_sequences table to reflect the next available number
 */

// Database connection
$dbPath = '/Users/anthony/Sites/fsm/writable/database/fsm.db';

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to database successfully.\n";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}

// Get all clients without account numbers
$stmt = $pdo->prepare("SELECT id, client_name FROM clients WHERE account_number IS NULL OR account_number = ''");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Found " . count($clients) . " clients without account numbers.\n";

// Get the current ACC sequence value
$stmt = $pdo->prepare("SELECT current_sequence FROM account_sequences WHERE prefix = 'ACC'");
$stmt->execute();
$sequenceResult = $stmt->fetch(PDO::FETCH_ASSOC);
$currentSequence = $sequenceResult ? $sequenceResult['current_sequence'] : 0;

echo "Current ACC sequence value: " . $currentSequence . "\n";

/**
 * Generate client abbreviation from company name
 * Based on the logic in ClientModel::generateClientAbbreviation()
 */
function generateClientAbbreviation($clientName) {
    // Remove common words and get first 4 letters of significant words
    $commonWords = ['THE', 'AND', 'OR', 'BUT', 'IN', 'ON', 'AT', 'TO', 'FOR', 'OF', 'WITH', 'BY', 'FROM', 'UP', 'ABOUT', 'INTO', 'THROUGH', 'DURING', 'BEFORE', 'AFTER', 'ABOVE', 'BELOW', 'BETWEEN', 'AMONG', 'WITHIN', 'WITHOUT', 'AGAINST', 'TOWARD', 'UPON', 'COMPANY', 'CORP', 'LLC', 'LTD', 'INC', 'LIMITED', 'CORPORATION', 'INCORPORATED'];
    
    $words = explode(' ', strtoupper($clientName));
    $abbreviation = '';
    
    foreach ($words as $word) {
        $word = preg_replace('/[^A-Z0-9]/', '', $word);
        if (!in_array($word, $commonWords) && strlen($word) > 0) {
            $abbreviation .= substr($word, 0, 4);
            if (strlen($abbreviation) >= 4) {
                break;
            }
        }
    }
    
    // If we don't have enough characters, pad with first letters of all words
    if (strlen($abbreviation) < 4) {
        $abbreviation = '';
        foreach ($words as $word) {
            $word = preg_replace('/[^A-Z0-9]/', '', $word);
            if (strlen($word) > 0) {
                $abbreviation .= substr($word, 0, 1);
                if (strlen($abbreviation) >= 4) {
                    break;
                }
            }
        }
    }
    
    return substr(strtoupper($abbreviation), 0, 4);
}

// Start transaction
$pdo->beginTransaction();

echo "\nGenerating account numbers...\n";
echo "============================\n";

try {
    foreach ($clients as $client) {
        $currentSequence++;
        
        // Generate abbreviation
        $abbreviation = generateClientAbbreviation($client['client_name']);
        
        // Generate account number: ACC-001-ABBR
        $accountNumber = sprintf('ACC-%03d-%s', $currentSequence, $abbreviation);
        
        // Update client record
        $updateStmt = $pdo->prepare("UPDATE clients SET account_number = ?, account_abbreviation = ?, updated_at = ? WHERE id = ?");
        $updateStmt->execute([$accountNumber, $abbreviation, date('Y-m-d H:i:s'), $client['id']]);
        
        echo "ID: {$client['id']} | {$client['client_name']} | {$accountNumber} | {$abbreviation}\n";
    }
    
    // Update the sequence table
    $sequenceStmt = $pdo->prepare("UPDATE account_sequences SET current_sequence = ?, updated_at = ? WHERE prefix = 'ACC'");
    $sequenceStmt->execute([$currentSequence, date('Y-m-d H:i:s')]);
    
    echo "\nUpdated ACC sequence to: " . $currentSequence . "\n";
    
    // Commit transaction
    $pdo->commit();
    
    echo "\nSUCCESS: All account numbers generated successfully!\n";
    echo "\nSummary:\n";
    echo "- Updated " . count($clients) . " clients\n";
    echo "- Next available ACC sequence: " . ($currentSequence + 1) . "\n";
    
} catch (Exception $e) {
    $pdo->rollback();
    echo "\nERROR: Transaction failed! " . $e->getMessage() . "\n";
}

echo "\nScript completed.\n";
