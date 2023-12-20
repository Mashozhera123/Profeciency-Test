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
            margin-top: 10px; /* Adjusted margin */
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
    </style>
</head>

<body>
    <form action="queries/submit.php" method="post" onsubmit="return validateForm()">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required placeholder="Enter your name">

        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" required placeholder="Enter your surname">

        <label for="idNumber">ID Number:</label>
        <input type="text" id="idNumber" name="idNumber" pattern="\d{13}" required placeholder="Enter your 13-digit ID Number">
        <span id="idNumberError" class="error"></span>

        <label for="dob">Date of Birth (dd/mm/YYYY):</label>
        <input type="text" id="dob" name="dob" required placeholder="Enter your date of birth">
        <span id="dobError" class="error"></span>

        <button type="submit">Submit</button>
        <button type="button" onclick="window.location.href='queries/cancel.php'">Cancel</button>
    </form>

    <script>
        var timer;

        document.getElementById('dob').addEventListener('input', function () {
            clearTimeout(timer);
            // Validate date after a short delay
            timer = setTimeout(function () {
                validateDate();
            }, 500);
        });

        function validateForm() {
            // Checking for duplicate ID Number using AJAX
            var idNumber = document.getElementById('idNumber').value;

            // Creating a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configuring it to make a POST request to the server-side PHP script
            xhr.open('POST', 'queries/check_duplicate_id.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Setting up a callback function to handle the response from the server
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Response from the server
                    var response = xhr.responseText;

                    // Display the error message if the ID Number is duplicate
                    document.getElementById('idNumberError').innerText = response;

                    // If response is empty, no duplicate ID Number, proceed with form submission
                    if (!response.trim()) {
                        // Additional validation checks...
                        if (validateDate()) {
                            document.forms[0].submit();
                        }
                    }
                }
            };

            // Send the ID Number to the server
            xhr.send('idNumber=' + encodeURIComponent(idNumber));

            // Prevent the form from being submitted immediately
            return false;
        }

        function validateDate() {
            var dobInput = document.getElementById('dob');
            var dobValue = dobInput.value;

            // Checking if the entered date matches the pattern dd/mm/YYYY
            var dateRegex = /^\d{2}\/\d{2}\/\d{4}$/;
            if (!dateRegex.test(dobValue)) {
                document.getElementById('dobError').innerText = 'Please enter a valid date in the format dd/mm/YYYY.';
                return false;
            } else {
                document.getElementById('dobError').innerText = '';
                return true;
            }
        }
    </script>
</body>
</html>
