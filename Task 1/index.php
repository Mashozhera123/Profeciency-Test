<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        form {
            max-width: 400px;
            margin: 50px auto;
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
            margin-top: 10px;
        }

        button [type="button"] {
            background-color: #ccc;
            margin-right: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: #ff0000;
            margin-top: -10px;
            margin-bottom: 10px;
        }

        .loading-message {
            display: none;
            color: #333;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <form action="queries/submit.php" method="post" onsubmit="return validateForm()">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required placeholder="Enter your name">

        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" required placeholder="Enter your surname">

        <label for="idNumber">ID Number:</label>
        <input type="text" id="idNumber" name="idNumber" pattern="\d{13}" required placeholder="Enter your 13-digit ID Number" oninput="setCustomValidity('')">
        <span id="idNumberError" class="error"></span>

        <label for="dob">Date of Birth (dd/mm/YYYY):</label>
        <input type="text" id="dob" name="dob" required placeholder="Enter your date of birth">
        <div id="dobErrorContainer" class="error-container">
            <span id="dobError" class="error"></span>
        </div>

        <button type="submit" id="submitBtn">Submit</button>
        <button type="button" onclick="window.location.href='queries/cancel.php'">Cancel</button>
        <div id="loadingMessage" class="loading-message">Loading...</div>
    </form>

    <script>
        var timer;

        document.getElementById('dob').addEventListener('input', function () {
            clearTimeout(timer);
            // Validate date after a short delay
            timer = setTimeout(validateDate, 500);
        });

        /**
         * Validates the entire form before submission.
         * @returns {boolean} True if the form is valid, false otherwise.
         */
        function validateForm() {
            // Checking for duplicate ID Number using AJAX
            var idNumber = document.getElementById('idNumber').value;
            var idNumberError = document.getElementById('idNumberError');
            var loadingMessage = document.getElementById('loadingMessage');

            // Show loading message
            loadingMessage.style.display = 'block';

            makeAjaxRequest(idNumber, function(response) {
                // Hide loading message
                loadingMessage.style.display = 'none';

                idNumberError.innerText = response.trim();

                // If the response is not empty, there is an error (duplicate ID)
                if (response.trim()) {
                    document.getElementById('idNumber').setCustomValidity('This ID Number already exists. Please check your input.');
                    return false; // Block form submission
                } else {
                    // No error, clear the custom validity
                    document.getElementById('idNumber').setCustomValidity('');
                }

                // Additional validation checks...
                if (!validateDate()) {
                    return false; // Block form submission
                }

                // Allow form submission by triggering the form submit event
                document.forms[0].submit();
            });

            // Prevent the form from being submitted immediately
            return false;
        }

        /**
         * Validates the date of birth input.
         * @returns {boolean} True if the date is valid, false otherwise.
         */
        function validateDate() {
            var dobInput = document.getElementById('dob');
            var dobValue = dobInput.value;

            // Checking if the entered date matches the pattern dd/mm/YYYY
            var dateRegex = /^\d{2}\/\d{2}\/\d{4}$/;

            if (!dateRegex.test(dobValue)) {
                displayErrorMessage('dobError', 'Please enter a valid date in the format dd/mm/YYYY.');
                return false;
            }

            // Parse the entered date using the Date object
            var dateParts = dobValue.split('/');
            var day = parseInt(dateParts[0]);
            var month = parseInt(dateParts[1]) - 1; // Months are zero-based in JavaScript
            var year = parseInt(dateParts[2]);

            var parsedDate = new Date(year, month, day);

            // Check if the parsed date is valid
            if (isNaN(parsedDate.getDate()) || parsedDate.getMonth() !== month || parsedDate.getFullYear() !== year) {
                displayErrorMessage('dobError', 'Please enter a valid date.');
                return false;
            }

            // Clear any previous error messages
            clearErrorMessage('dobError');
            return true;
        }

        /**
         * Makes an AJAX request to check for duplicate ID Number.
         * @param {string} idNumber - The ID Number to check.
         * @param {function} callback - The callback function to handle the response.
         */
        function makeAjaxRequest(idNumber, callback) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'queries/check_duplicate_id.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    callback(xhr.responseText);
                }
            };

            xhr.send('idNumber=' + encodeURIComponent(idNumber));
        }

        /**
         * Displays an error message in the specified element.
         * @param {string} elementId - The ID of the element to display the error message.
         * @param {string} message - The error message to display.
         */
        function displayErrorMessage(elementId, message) {
            var errorElement = document.getElementById(elementId);
            errorElement.innerText = message;
        }

        /**
         * Clears the error message in the specified element.
         * @param {string} elementId - The ID of the element to clear the error message.
         */
        function clearErrorMessage(elementId) {
            var errorElement = document.getElementById(elementId);
            errorElement.innerText = '';
        }
    </script>
</body>

</html>
