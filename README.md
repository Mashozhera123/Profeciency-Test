# Project README

This repository combines two web applications: one for managing user data using MongoDB and another for generating and uploading CSV data to an SQLite database.

## MongoDB User Data Management

This part of the project is built using PHP for server-side scripting, MongoDB as the database, and JavaScript for client-side validation. The primary functionality includes data submission, validation, and interaction with the MongoDB database.

### Getting Started

To run the MongoDB User Data Management application locally, follow the steps below:

1. **Clone the Repository:**
   ```
   git clone <repository_url>
   cd <repository_name>
   ```

2. **Set Up MongoDB:**
   - Ensure that you have MongoDB installed and running.
   - Update MongoDB connection details in `index.php` as needed.

3. **Install Dependencies:**
   - Make sure you have [Composer](https://getcomposer.org/) installed.
   - Run `composer install` to install required PHP dependencies.

4. **Run the Application:**
   - Start a local server, for example, using PHP's built-in server:
     ```
     php -S localhost:8000
     ```
   - Access the application in your web browser at `http://localhost:8000/mongo/Task%201/index.php`.

### Form Validation

The application includes client-side form validation using JavaScript. The validation checks are defined in the `script.js` file. The primary validation checks include:

- **Date Format:** Ensures that the date of birth is entered in the format `dd/mm/YYYY`.
- **Date Validity:** Validates the entered date for correctness using the JavaScript `Date` object.

### JavaScript Functions

The `script.js` file includes JavaScript functions for form validation and interaction with the server:

- **validateForm():** Validates the entire form before submission, calling additional validation checks, and clearing form data from the session.

- **validateDate():** Validates the date of birth input, checking both the format and the correctness of the entered date.

- **displayErrorMessage(elementId, message):** Displays an error message in the specified HTML element identified by `elementId`.

- **clearErrorMessage(elementId):** Clears the error message in the specified HTML element identified by `elementId`.

- **clearSessionData():** Clears form data from the session on the server side using the Fetch API.

- **clearSessionAndRedirect():** Combines clearing session data and redirecting to the home page.

## Data Generation and CSV Upload to SQLite Database

This part of the project generates random data, creates a CSV file, and provides functionality to upload the CSV data to an SQLite database.

### Instructions

1. Open `index.html` in a web browser.
2. Enter the desired amount of data to generate in the provided form.
3. Click the "Generate Data" button.
4. Download the generated CSV file by clicking the download button.
5. Optionally, proceed to upload the CSV file to the database using the link provided.

### CSV Upload to SQLite Database

1. Open `upload2database.php` in a web browser.
2. Follow the provided steps:
   - Click the "Choose a CSV file" button to select a CSV file for uploading.
   - Click the "Upload" button to upload the selected CSV file.
3. Check for success or error messages displayed on the page.
4. If successful, the CSV data has been uploaded to the SQLite database.

## Notes

- **Memory Limit:** The PHP scripts may set and adjust memory limits to handle large data generation or file uploads. Please ensure your server configuration allows for these adjustments.

- **File Paths:** Ensure that the necessary paths for CSS, JavaScript, and SQLite database are correctly configured based on your project structure.

- **SQLite Database:** The project assumes an SQLite database named `myDatabase.db` and a table named `csv_import` for storing CSV data. Make sure the SQLite database is properly configured and accessible.

**Author:** Nigel Mashozhera

Feel free to use, modify, and distribute the code. Contributions are welcome! If you find any issues or have improvements to suggest, feel free to open an issue or create a pull request.
