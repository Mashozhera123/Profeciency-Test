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
            $_SESSION['success_message'] = 'Data saved successfully! Please enter new data below.';
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            // Duplicate key error, handle accordingly
            $_SESSION['error_message'] = 'This ID Number already exists. Please check your input.';
            $_SESSION['form_data'] = [
                'name' => $name,
                'surname' => $surname,
                'idNumber' => $idNumber,
                'dob' => $dob,
            ];

            // Redirect back to index.php with clear_session parameter
            header('Location: http://localhost/mongo/Task%201/index.php?clear_session=true');
            exit();
        } catch (Exception $e) {
            // Other errors
            $validationResult['valid'] = false;
            $validationResult['errors'][] = 'Error saving data. Please try again.';
        }
    }

    // Store validation errors and valid data in the session
    $_SESSION['validation_errors'] = $validationResult['errors'];

    // Clear form data from session
    unset($_SESSION['form_data']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/mongo/css/styling.css">
    <script src="/mongo/js/script.js"></script>
    <title>Document</title>
</head>

<body>
    <!-- keep form data during a session -->
    <form method="post" onsubmit="return validateForm()">
        <?php
        // If there are validation errors or an error message, keep the form data in session
        if (isset($_SESSION['validation_errors']) || isset($_SESSION['error_message'])) {
            $form_data = $_SESSION['form_data'] ?? [
                'name' => '',
                'surname' => '',
                'idNumber' => '',
                'dob' => '',
            ];
        } else {
            $form_data = [
                'name' => '',
                'surname' => '',
                'idNumber' => '',
                'dob' => '',
            ];
        }
        ?>

        <?php
        // Display success message if it exists
        if (isset($_SESSION['success_message'])) {
            echo '<p class="success-message">' . $_SESSION['success_message'] . '</p>';

            // Clear success message
            unset($_SESSION['success_message']);
        }
        ?>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required placeholder="Enter your name"
               value="<?php echo htmlspecialchars($form_data['name']); ?>">

        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" required placeholder="Enter your surname"
               value="<?php echo htmlspecialchars($form_data['surname']); ?>">

        <label for="idNumber">ID Number:</label>
        <input type="text" id="idNumber" name="idNumber" pattern="\d{13}"
               required placeholder="Enter your 13-digit ID Number" oninput="setCustomValidity('')"
               value="<?php echo htmlspecialchars($form_data['idNumber']); ?>">
        <span id="idNumberError"
              class="error"><?php echo isset($_SESSION['error_message']) ? htmlspecialchars($_SESSION['error_message']) : ''; ?></span>

        <label for="dob">Date of Birth (dd/mm/YYYY):</label>
        <input type="text" id="dob" name="dob" required placeholder="Enter your date of birth"
               value="<?php echo htmlspecialchars($form_data['dob']); ?>">
        <div id="dobErrorContainer" class="error-container">
            <span id="dobError" class="error"></span>
        </div>

        <!-- Display validation errors -->
        <?php
        if (isset($_SESSION['validation_errors'])) {
            foreach ($_SESSION['validation_errors'] as $error) {
                echo '<p class="error-message">' . htmlspecialchars($error) . '</p>';
            }
        }
        ?>

        <button type="submit" id="submitBtn">Submit</button>
        <button type="button" onclick="window.location.href='/mongo/Task 1/cancel.php'">Cancel</button>
    </form>

    <?php
    // Clear validation errors and form data from session after displaying
    unset($_SESSION['validation_errors']);
    unset($_SESSION['form_data']);
    unset($_SESSION['error_message']);
    ?>
</body>

</html>
