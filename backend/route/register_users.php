<?php
require_once '../database/config.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone_number = sanitizeInput($_POST['phone_number']);
    $password = sanitizeInput($_POST['password']);

    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone_number) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    if (!$email) {
        echo json_encode(["status" => "error", "message" => "Invalid email address."]);
        exit;
    }

    // if (!preg_match('/^\+1[0-9]{10}$|^\+43[0-9]{9,13}$|^\+44[0-9]{10}$/', $phone_number)) {
    //     echo json_encode(["status" => "error", "message" => "Invalid phone number format."]);
    //     exit;
    // }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone_number = ?");
    $stmt->bind_param("ss", $email, $phone_number);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email or phone number already registered."]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone_number, password, balance) VALUES (?, ?, ?, ?, ?, 10.00)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone_number, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User registered successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error registering user: " . $stmt->error]);
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
