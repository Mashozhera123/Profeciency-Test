<?php
// check_duplicate_id.php

require_once '../vendor/autoload.php';

use MongoDB\Client;

// Connect to MongoDB
$client = new MongoDB\Client;
$database = $client->mydatabase; 
$collection = $database->users;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idNumber = $_POST['idNumber'];

    // Check for duplicate ID Number
    $existingUser = $collection->findOne(['idNumber' => $idNumber]);
    if ($existingUser) {
        echo 'This ID Number already exists. Please check your input.';
    }
}

?>

