<?php

/**
 * Script to retroactively generate account numbers for existing contacts without companies
 * 
 * This script will:
 * 1. Generate contact abbreviations from first and last names
 * 2. Generate sequential account numbers using the ACC prefix (continuing from companies)
 * 3. Update the contacts table with the new account numbers
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

// Get all contacts without companies and without account numbers
$stmt = $pdo->prepare("SELECT id, first_name, last_name FROM contacts WHERE (company_id IS NULL OR company_id = '') AND (account_number IS NULL OR account_number = '')");
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Found " . count($contacts) . " contacts without companies and without account numbers.\n";

// Get the current ACC sequence value
$stmt = $pdo->prepare("SELECT current_sequence FROM account_sequences WHERE prefix = 'ACC'");
$stmt->execute();
$sequenceResult = $stmt->fetch(PDO::FETCH_ASSOC);
$currentSequence = $sequenceResult ? $sequenceResult['current_sequence'] : 0;

echo "Current ACC sequence value: " . $currentSequence . "\n";

/**
 * Generate contact abbreviation from first and last name
 */
function generateContactAbbreviation($firstName, $lastName) {
    $words = [$firstName, $lastName];
    $abbreviation = '';
    
    // Take first 2 chars from first name and first 2 chars from last name
    if (count($words) >= 2) {
        $firstNameClean = preg_replace('/[^A-Z0-9]/', '', strtoupper($words[0]));
        $lastNameClean = preg_replace('/[^A-Z0-9]/', '', strtoupper($words[1]));
        
        $abbreviation = substr($firstNameClean, 0, 2) . substr($lastNameClean, 0, 2);
    } else {
        // If only one name, take first 4 characters
        $singleName = preg_replace('/[^A-Z0-9]/', '', strtoupper($words[0]));
        $abbreviation = substr($singleName, 0, 4);
    }
    
    // Ensure we have at least 4 characters, pad with first letters if needed
    if (strlen($abbreviation) < 4) {
        foreach ($words as $word) {
            $word = preg_replace('/[^A-Z0-9]/', '', strtoupper($word));
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

echo "\nGenerating account numbers for individual contacts...\n";
echo "====================================================\n";

try {
    foreach ($contacts as $contact) {
        $currentSequence++;
        
        // Generate abbreviation
        $abbreviation = generateContactAbbreviation($contact['first_name'], $contact['last_name']);
        
        // Generate account number: ACC-011-JOHN (continuing from company sequence)
        $accountNumber = sprintf('ACC-%03d-%s', $currentSequence, $abbreviation);
        
        // Update contact record
        $updateStmt = $pdo->prepare("UPDATE contacts SET account_number = ?, account_abbreviation = ?, updated_at = ? WHERE id = ?");
        $updateStmt->execute([$accountNumber, $abbreviation, date('Y-m-d H:i:s'), $contact['id']]);
        
        echo "ID: {$contact['id']} | {$contact['first_name']} {$contact['last_name']} | {$accountNumber} | {$abbreviation}\n";
    }
    
    // Update the sequence table
    $sequenceStmt = $pdo->prepare("UPDATE account_sequences SET current_sequence = ?, updated_at = ? WHERE prefix = 'ACC'");
    $sequenceStmt->execute([$currentSequence, date('Y-m-d H:i:s')]);
    
    echo "\nUpdated ACC sequence to: " . $currentSequence . "\n";
    
    // Commit transaction
    $pdo->commit();
    
    echo "\nSUCCESS: All account numbers generated successfully!\n";
    echo "\nSummary:\n";
    echo "- Updated " . count($contacts) . " individual contacts\n";
    echo "- Next available ACC sequence: " . ($currentSequence + 1) . "\n";
    echo "\nNote: Contacts associated with companies will use their company's account number.\n";
    echo "Only standalone contacts (residential clients) received individual account numbers.\n";
    
} catch (Exception $e) {
    $pdo->rollback();
    echo "\nERROR: Transaction failed! " . $e->getMessage() . "\n";
}

echo "\nScript completed.\n";
