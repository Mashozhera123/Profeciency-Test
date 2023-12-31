<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/mongo/css/styling.css">
    <script src="/mongo/js/script.js"></script>
    <title>Data Generation Form</title>
</head>
<body>
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
    echo '<p style="color: red;">Step 1: Click the button below to download your CSV flie.</p>';
    echo '<a class="download-button" href="output.csv" download><button>Download CSV</button></a>';
    echo '<p style="color: red;">Step 2: Once the download is complete, please click the link below <br>to upload your CSV file to the database.</p>';
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
