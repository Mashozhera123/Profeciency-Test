<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .success-message {
            color: #008000;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
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

            if (isValidData($name, $surname, $idNumber, $dob)) {
                // Convert the date to MongoDB\BSON\UTCDateTime
                $dobDateTime = new \MongoDB\BSON\UTCDateTime(strtotime(str_replace('/', '-', $dob)) * 1000);

                // Save to MongoDB
                $collection->insertOne([
                    'name' => $name,
                    'surname' => $surname,
                    'idNumber' => $idNumber,
                    'dob' => $dobDateTime,
                ]);

                echo '<p class="success-message">Data saved successfully!</p>';
            } else {
                echo '<p class="error-message">Invalid data. Please check your input.</p>';
            }
        }
        ?>
<button onclick="window.location.href='http://localhost/mongo/index.php'">Return to Home</button>
    </div>
</body>

</html>



