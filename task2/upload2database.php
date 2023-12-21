<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Upload to SQLite Database</title>
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
            text-align: center;
        }

        input[type="file"] {
            display: none;
        }

        label {
            display: block;
            margin: 20px 0;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        label:hover {
            background-color: #45a049;
        }

        #upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        #upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }

        #upload-btn-wrapper .btn {
            border: 2px solid gray;
            color: gray;
            background-color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
        }

        #upload-btn-wrapper .btn:hover {
            border-color: #4CAF50;
            color: #4CAF50;
        }

        .file-name {
            margin-top: 10px;
            font-weight: bold;
        }

        .success-message {
            color: green;
            margin-top: 20px;
        }

        .error-message {
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Upload CSV to SQLite Database</h2>

        <form action="" method="post" enctype="multipart/form-data">
            <div id="upload-btn-wrapper">
                <label for="csv-file" class="btn">Choose a CSV file</label>
                <input type="file" name="csv-file" id="csv-file" accept=".csv" onchange="displayFileName()">
            </div>
            <p class="file-name" id="file-name-display"></p>
            <br>
            <button type="submit">Upload</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Handle CSV upload and SQLite insertion
            if (isset($_FILES["csv-file"]) && $_FILES["csv-file"]["error"] == UPLOAD_ERR_OK) {
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

                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $id = $db->escapeString($data[0]); // Assuming Id is the first column
                                $name = $db->escapeString($data[1]);
                                $surname = $db->escapeString($data[2]);
                                $initials = $db->escapeString($data[3]);
                                $age = intval($data[4]);
                                $dob = $db->escapeString($data[5]);
                            
                                // Insert data into the users table
                                $db->exec("INSERT INTO Users (Id, Name, Surname, Initials, Age, DateOfBirth) 
                                           VALUES ('$id', '$name', '$surname', '$initials', $age, '$dob')");                            
                            }
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

        <script>
            function displayFileName() {
                var fileNameDisplay = document.getElementById("file-name-display");
                var inputFile = document.getElementById("csv-file");
                fileNameDisplay.innerText = "Selected file: " + inputFile.files[0].name;
            }
        </script>
    </div>
</body>
</html>
