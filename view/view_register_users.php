<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">User Registration</h2>
    
    <div id="locationError" class="alert alert-danger d-none" role="alert">
        Registration is not supported in your country.
    </div>

    <div id="locationLoader" class="text-center d-none">Checking your location...</div>

    <form id="registrationForm" method="POST" class="d-none">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control" name="first_name" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control" name="last_name" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <div class="input-group">
                <span class="input-group-text" id="countryCode">+1</span>
                <input type="text" class="form-control" name="phone_number" required placeholder="XXXXXXXXXX" aria-describedby="countryCode">
            </div>
            <small class="form-text text-muted">Please enter your phone number without the country code.</small>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>

    <div id="response" class="mt-3"></div>

    <div class="text-center mt-3">
        <a href="view_login_users.php" class="btn btn-link">Already have an account? Login here</a>
    </div>
</div>

<!-- Bootstrap and jQuery JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../location/location_checker.js"></script>

<script>
    document.getElementById('registrationForm').onsubmit = async function(event) {
        event.preventDefault(); 

        const formData = new FormData(this);
        const registerUrl = '../backend/route/register_users.php'; 

        // Check if the country code element exists and is visible
        const countryCodeElement = document.getElementById('countryCode');
        let phoneNumber = '';

        if (countryCodeElement) {
            const phoneCode = countryCodeElement.innerText;
            phoneNumber = `+${phoneCode}${formData.get('phone_number')}`;
        } else {
            // Handle the case where the country code is not available
            document.getElementById('response').innerText = "Unable to retrieve country code. Please try again.";
            document.getElementById('response').className = "alert alert-danger";
            return;
        }

        fetch(registerUrl, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('response').innerText = data.message; 
            if (data.status === "success") {
                document.getElementById('response').className = "alert alert-success";
            } else {
                document.getElementById('response').className = "alert alert-danger";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('response').innerText = "An error occurred. Please try again.";
            document.getElementById('response').className = "alert alert-danger";
        });
    };
</script>

</body>
</html>
