# Project README

This repository contains a web application for managing user data using MongoDB. The application is built using PHP for server-side scripting, MongoDB as the database, and JavaScript for client-side validation. The primary functionality includes data submission, validation, and interaction with the MongoDB database.

## Getting Started

To run this application locally, follow the steps below:

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

## Form Validation

The application includes client-side form validation using JavaScript. The validation checks are defined in the `script.js` file. The primary validation checks include:

- **Date Format:** Ensures that the date of birth is entered in the format `dd/mm/YYYY`.
- **Date Validity:** Validates the entered date for correctness using the JavaScript `Date` object.

## JavaScript Functions

The `script.js` file includes the following JavaScript functions:

- **validateForm():** Validates the entire form before submission, calling additional validation checks, and clearing form data from the session.

- **validateDate():** Validates the date of birth input, checking both the format and the correctness of the entered date.

- **displayErrorMessage(elementId, message):** Displays an error message in the specified HTML element identified by `elementId`.

- **clearErrorMessage(elementId):** Clears the error message in the specified HTML element identified by `elementId`.

- **clearSessionData():** Clears form data from the session on the server side using the Fetch API.

- **clearSessionAndRedirect():** Combines clearing session data and redirecting to the home page.

## Contributing

Contributions are welcome! If you find any issues or have improvements to suggest, feel free to open an issue or create a pull request.

## License

 Feel free to use, modify, and distribute the code.
