<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/mongo/css/styling.css">
    <title>Document</title>
</head>

<body>
    <form action="/mongo/Task 1/submit.php" method="post" onsubmit="return validateForm()">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required placeholder="Enter your name" value="<?php echo isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : ''; ?>">

        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" required placeholder="Enter your surname" value="<?php echo isset($_SESSION['form_data']['surname']) ? $_SESSION['form_data']['surname'] : ''; ?>">

        <label for="idNumber">ID Number:</label>
        <input type="text" id="idNumber" name="idNumber" pattern="\d{13}" required placeholder="Enter your 13-digit ID Number" oninput="setCustomValidity('')"
            value="<?php echo isset($_SESSION['form_data']['idNumber']) ? $_SESSION['form_data']['idNumber'] : ''; ?>">
        <span id="idNumberError" class="error"><?php echo isset($_SESSION['error_message']) ? $_SESSION['error_message'] : ''; ?></span>

        <label for="dob">Date of Birth (dd/mm/YYYY):</label>
        <input type="text" id="dob" name="dob" required placeholder="Enter your date of birth"
            value="<?php echo isset($_SESSION['form_data']['dob']) ? $_SESSION['form_data']['dob'] : ''; ?>">
        <div id="dobErrorContainer" class="error-container">
            <span id="dobError" class="error"></span>
        </div>

        <!-- Display validation errors -->
        <?php
        if (isset($_SESSION['validation_errors'])) {
            foreach ($_SESSION['validation_errors'] as $error) {
                echo '<p class="error-message">' . $error . '</p>';
            }
            unset($_SESSION['validation_errors']);
        }
        ?>

        <button type="submit" id="submitBtn">Submit</button>
        <button type="button" onclick="window.location.href='/mongo/Task 1/cancel.php'">Cancel</button>
        <!-- <div id="loadingMessage" class="loading-message">Loading...</div> -->
    </form>

    <script src="/mongo/js/script.js"></script>
</body>

</html>
