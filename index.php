<?php
session_start();
if (isset($_SESSION['jwt'])) {
    header('Location: ./view/dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>
<body>

    <div class="loader-container">
        <div id="loader"></div>
        <p id="checkingMessage">Please wait, we are checking...</p>
        <p id="goodToGoMessage">Good to go!</p>
    </div>

    <div id="locationError" class="alert alert-danger d-none" role="alert">
        We regret to inform you that our services are unavailable in your country. 
        If you are traveling, kindly wait until you return home to access the services.
    </div>

    <div class="container main-container" id="mainContent" style="display: none;">
        <h1 class="text-center">To gain access, kindly click on either Login or Register below.</h1>
        
        <div id="response" class="mt-3"></div>

        <div class="mt-4 text-center">
            <button id="loginButton" class="btn btn-primary btn-lg mx-2">Login</button>
            <button id="registerButton" class="btn btn-success btn-lg mx-2">Register</button>
        </div>
    </div>

    <!-- Bootstrap and jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="./location/location_checker.js"></script>
    <script src="./main.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Show the checking message after the loader is displayed
            setTimeout(function() {
                document.getElementById('checkingMessage').style.display = 'block'; // Show checking message
            }, 500); // Delay before showing the message

            // Wait for 5 seconds, then show "Good to go!" message
            setTimeout(function() {
                document.getElementById('checkingMessage').style.display = 'none'; // Hide checking message
                document.getElementById('goodToGoMessage').style.display = 'block'; // Show good to go message
            }, 5000); // Show "Good to go!" after 5 seconds

            // Wait for another 5 seconds, then hide the loader and good to go message
            setTimeout(function() {
                document.getElementById('loader').style.display = 'none'; // Hide loader
                document.getElementById('goodToGoMessage').style.display = 'none'; // Hide good to go message

                // Show the main content after loading
                document.getElementById('mainContent').style.display = 'block';
            }, 10000); // Hide everything and show main content after 10 seconds
        });
    </script>

</body>
</html>
