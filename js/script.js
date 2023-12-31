/**
 * Validates the entire form before submission.
 * @returns {boolean} True if the form is valid, false otherwise.
 */
function validateForm() {
    // Additional validation checks...
    if (!validateDate()) {
        return false; // Block form submission
    }

    // Clear form data from session
    clearSessionData();

    return true; // Allow form submission
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

/**
 * Clears form data from session.
 */
function clearSessionData() {
    // Using fetch to clear session data on the server side
    fetch('/mongo/Task%201/clear_session_data.php', {
        method: 'POST',
    });
}

function clearSessionAndRedirect() {
    // Using fetch to clear session data on the server side
    fetch('/mongo/Task%201/clear_session_data.php', {
        method: 'POST',
    }).then(() => {
        // Redirect to the home page
        window.location.href = 'http://localhost/mongo/Task%201/index.php';
    });
}

function displayFileName() {
    var fileNameDisplay = document.getElementById("file-name-display");
    var inputFile = document.getElementById("csv-file");
    fileNameDisplay.innerText = "Selected file: " + inputFile.files[0].name;
}