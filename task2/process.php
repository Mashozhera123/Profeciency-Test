<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Generation Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 20px;
        }

        a {
            text-decoration: none;
            color: #4CAF50; /* Change link color */
        }

        .download-container {
            text-align: center;
            margin-top: 20px;
        }

        .success-message {
            color: green;
        }

        .download-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .download-button:hover {
            background-color: #45a049;
        }

        .upload-link {
            display: block; /* Make the link a block-level element for spacing */
            margin-top: 10px;
        }

        .upload-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="data-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="amount">Enter the amount of data to generate:</label>
            <input type="number" id="amount" name="amount" value="1" min="1" required>
            <button type="submit">Generate Data</button>
        </form>

        <?php
set_time_limit(0);
ini_set('memory_limit', '256M');

// Function to generate random data and create a CSV file
function generateCSV($numVariations, $batchSize = 1000) {
    // Arrays of names and surnames
    $names = ["John", "Jane", "David", "Emily", "Michael", "Olivia", "Robert", "Sophia", "William", "Emma", "Matthew", "Ava", "Christopher", "Mia", "Daniel", "Isabella", "Andrew", "Abigail", "James", "Charlotte"];
    $surnames = ["Smith", "Johnson", "Williams", "Jones", "Brown", "Davis", "Miller", "Wilson", "Moore", "Taylor", "Anderson", "Thomas", "Jackson", "White", "Harris", "Martin", "Thompson", "Garcia", "Martinez", "Robinson"];

    // Create CSV file
    $csvFile = fopen('output.csv', 'w');

    // Add header
    fputcsv($csvFile, ["Id", "Name", "Surname", "Initials", "Age", "DateOfBirth"]);

    // Generate random data
    $uniqueCombinations = new HashSet(); // Keep track of generated combinations
    $i = 0;

    while ($i < $numVariations) {
        $batch = min($batchSize, $numVariations - $i);

        for ($j = 0; $j < $batch; $j++) {
            $name = $names[array_rand($names)];
            $surname = $surnames[array_rand($surnames)];
            $initials = strtoupper(substr($name, 0, 1) . substr($surname, 0, 1)); 
            $age = mt_rand(18, 99);
            $dob = date('d/m/Y', strtotime("-$age years"));
            $id = $i + 1;

            $combination = $dob . $id;

            // Check if the combination is unique
            if (!$uniqueCombinations->contains($combination)) {
                $uniqueCombinations->add($combination);

                // Add data to the CSV file
                fputcsv($csvFile, ["$id", $name, $surname, $initials, $age, $dob]);

                $i++; // Increment index
            }
        }

        // Flush the output to free memory
        fflush($csvFile);
    }

    fclose($csvFile);

    // Provide a download link for the generated CSV file
    echo '<div class="download-container">';
    echo '<p class="success-message">CSV file generated successfully!</p>';
    echo '<a class="download-button" href="output.csv" download><button>Download CSV</button></a>';
    echo '<a class="upload-link" href="upload2database.php">Upload CSV to SQLite Database</a>';
    echo '</div>';
    echo '<script>document.getElementById("data-form").style.display = "none";</script>';
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the number of variations from the form
    $numVariations = isset($_POST["amount"]) ? intval($_POST["amount"]) : 0;

    // Ensure the number of variations is within a reasonable range
    $numVariations = max(1, min(1000000, $numVariations));

    // Generate the CSV file
    generateCSV($numVariations);
}

// Define a simple HashSet class
class HashSet {
    private $data = [];

    public function add($value) {
        $this->data[$value] = true;
    }

    public function contains($value) {
        return isset($this->data[$value]);
    }
}
?>


    </div>
</body>
</html>
