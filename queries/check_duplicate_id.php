<?php
// check_duplicate_id.php

require_once '../vendor/autoload.php';

use MongoDB\Client;

$client = new Client;
$database = $client->mydatabase; // Replace 'mydatabase' with your actual database name
$collection = $database->users;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idNumber = $_POST['idNumber'];

    // Check for duplicate ID Number
    $existingUser = $collection->findOne(['idNumber' => $idNumber]);
    if ($existingUser) {
        echo 'Duplicate ID Number. Please check your input.';
    }
}

?>

