<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/mongo/css/styling.css">
    <script src="/mongo/js/script.js"></script>
    <title>CSV Upload to SQLite Database</title>
</head>
<body>
<div class="container">
        <h2>Upload CSV to SQLite Database</h2>

        <form action="" method="post" enctype="multipart/form-data">
        <p>Step 1:Click the button below to select a CSV file for uploading</p>
            <div id="upload-btn-wrapper">
                <label for="csv-file" class="btn">Choose a CSV file</label>
                <input type="file" name="csv-file" id="csv-file" accept=".csv" onchange="displayFileName()">
            </div>
            <p class="file-name" id="file-name-display"></p>
            <br>
            <p>Step 2:Click the button below to upload CSV</p>
            <button type="submit">Upload</button>
        </form>

        <?php

set_time_limit(300);  // Set to 0 for no time limit
ini_set('memory_limit', '512M');  // Adjust the memory limit as needed

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Handle CSV upload and SQLite insertion
            if (isset($_FILES["csv-file"]) && $_FILES["csv-file"]["error"] == UPLOAD_ERR_OK) {
                set_time_limit(0);  // Set to 0 for no time limit
                ini_set('memory_limit', '512M');  // Adjust the memory limit as needed

                $targetDir = "uploads/";
                $targetFile = $targetDir . basename($_FILES["csv-file"]["name"]);
                $uploadOk = 1;
                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check if the file is a CSV file
                if ($fileType != "csv") {
                    echo '<p class="error-message">Only CSV files are allowed.</p>';
                    $uploadOk = 0;
                }

                // Check if the file already exists
                if (file_exists($targetFile)) {
                    echo '<p class="error-message">Sorry, the file already exists.</p>';
                    $uploadOk = 0;
                }

                // Create the "uploads" directory if it does not exist
                if (!file_exists($targetDir) && !mkdir($targetDir, 0777, true)) {
                    echo '<p class="error-message">Failed to create the "uploads" directory.</p>';
                    $uploadOk = 0;
                }

                // Upload the file
                if ($uploadOk) {
                    if (move_uploaded_file($_FILES["csv-file"]["tmp_name"], $targetFile)) {
                        // Process the uploaded CSV file and insert into SQLite database
                        $sqliteFile = "myDatabase.db";
                        $db = new SQLite3($sqliteFile); $sqliteFile = "C:/Users/User/Desktop/sqlite-tools-win-x64-3440200/myDatabase.db";
                        $db = new SQLite3($sqliteFile);

                        // Read CSV file and insert into the users table
                        $handle = fopen($targetFile, "r");
                        if ($handle !== FALSE) {
                            // Skip the first row (headers)
                            fgetcsv($handle, 1000, ",");

                            // Start SQL transaction
                            $db->exec('BEGIN TRANSACTION');

                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $id = $db->escapeString($data[0]); // Assuming Id is the first column
                                $name = $db->escapeString($data[1]);
                                $surname = $db->escapeString($data[2]);
                                $initials = $db->escapeString($data[3]);
                                $age = intval($data[4]);
                                $dob = $db->escapeString($data[5]);
                                
                                // Insert data into the users table
                                $db->exec("INSERT INTO csv_import (Id, Name, Surname, Initials, Age, DateOfBirth) 
                                           VALUES ('$id', '$name', '$surname', '$initials', $age, '$dob')");
                            }

                            // Commit SQL transaction
                            $db->exec('COMMIT');
                            fclose($handle);

                            // Provide a success message
                            echo '<p class="success-message">CSV data uploaded to SQLite database successfully!</p>';
                        } else {
                            echo '<p class="error-message">Error reading the CSV file.</p>';
                        }

                        // Close the database connection
                        $db->close();
                    } else {
                        echo '<p class="error-message">Sorry, there was an error uploading your file.</p>';
                    }
                }
            }
        }
        ?>
    </div>
</body>
</html>
