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

    <form action="process.php" method="post">
        <label for="amount">Enter the amount of data to generate:</label>
        <input type="number" id="amount" name="amount" value="1" min="1" required>
        <button type="submit">Generate Data</button>
    </form>

   
</body>
</html>
