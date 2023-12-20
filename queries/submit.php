<?php

// Pulling the MongoDB driver from the vendor
require_once '../vendor/autoload.php';

use MongoDB\BSON\UTCDateTime;

// Connect to MongoDB
$client = new MongoDB\Client;
$database = $client->mydatabase; // Replace 'mydatabase' with your actual database name
$collection = $database->users;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $idNumber = $_POST['idNumber']; // Cast to integer
    $dob = $_POST['dob'];

    function isValidData($name, $surname, $idNumber, $dob)
    {
        // Validate data
        return (
            !empty($name) &&
            !empty($surname) &&
            is_numeric($idNumber) &&
            strlen($idNumber) === 13 &&
            preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dob)  // Adjusted regex for dd/mm/YYYY format
        );
    }

    // Convert the date to MongoDB\BSON\UTCDateTime
    $dobDateTime = new \MongoDB\BSON\UTCDateTime(strtotime(str_replace('/', '-', $dob)) * 1000);

    // Save to MongoDB
    $collection->insertOne([
        'name' => $name,
        'surname' => $surname,
        'idNumber' => $idNumber,
        'dob' => $dobDateTime,
    ]);

    echo 'Data saved successfully!';
}


