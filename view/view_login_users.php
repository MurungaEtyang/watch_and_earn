<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">User Login</h2>
    
    <div id="response" class="mt-3"></div>

    <form id="loginForm" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>

    <div class="text-center mt-3">
        <a href="view_register_users.php" class="btn btn-link">Don't have an account? Register here</a>
    </div>
</div>

<!-- Bootstrap and jQuery JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../location/location_checker.js"></script>

<script>

    
    document.getElementById('loginForm').onsubmit = async function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const loginUrl = '../backend/route/login_users.php'; 

        fetch(loginUrl, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('response').innerText = data.message; 
            if (data.status === "success") {
                localStorage.setItem('jwt', data.token);
                setTimeout(() => {
                    window.location.href = "dashboard.php"; 
                }, 2000);
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
