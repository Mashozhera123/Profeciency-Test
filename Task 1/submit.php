<?php
session_start();
// Pulling the MongoDB driver from the vendor
require_once '../vendor/autoload.php';

// Connect to MongoDB
$client = new MongoDB\Client;
$database = $client->mydatabase; 
$collection = $database->users;

// Add a unique index to the idNumber field
$collection->createIndex(['idNumber' => 1], ['unique' => true]);

// Handle form submission and validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $idNumber = $_POST['idNumber'];
    $dob = $_POST['dob'];

    function isValidData($name, $surname, $idNumber, $dob)
    {
        // Validate data
        $errors = [];
        if (empty($name)) {
            $errors[] = 'Name is required.';
        }
        if (empty($surname)) {
            $errors[] = 'Surname is required.';
        }
        if (!is_numeric($idNumber) || strlen($idNumber) !== 13) {
            $errors[] = 'ID Number must be a 13-digit numeric value.';
        }
        if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dob)) {
            $errors[] = 'Invalid date format. Please use dd/mm/YYYY.';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    $validationResult = isValidData($name, $surname, $idNumber, $dob);

    if ($validationResult['valid']) {
        try {
            // Convert the date to MongoDB\BSON\UTCDateTime
            $dobDateTime = new MongoDB\BSON\UTCDateTime(strtotime(str_replace('/', '-', $dob)) * 1000);

            // Save to MongoDB
            $collection->insertOne([
                'name' => $name,
                'surname' => $surname,
                'idNumber' => $idNumber,
                'dob' => $dobDateTime,
            ]);

            // Output success message
            echo '<p class="success-message">Data saved successfully!</p>';
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            // Duplicate key error, handle accordingly
            $_SESSION['error_message'] = 'This ID Number already exists. Please check your input.';
            $_SESSION['form_data'] = [
                'name' => $name,
                'surname' => $surname,
                'idNumber' => $idNumber,
                'dob' => $dob,
            ];

            // Redirect back to index.php
            header('Location: http://localhost/mongo/Task%201/index.php');
            exit();
        } catch (Exception $e) {
            // Other errors
            $validationResult['valid'] = false;
            $validationResult['errors'][] = 'Error saving data. Please try again.';
        }
    }

    // Store validation errors and valid data in the session
    $_SESSION['validation_errors'] = $validationResult['errors'];
    $_SESSION['form_data'] = [
        'name' => $name,
        'surname' => $surname,
        'idNumber' => $idNumber,
        'dob' => $dob,
    ];
}
?>
<button onclick="window.location.href='http://localhost/mongo/Task 1/index.php'">Return to Home</button>
