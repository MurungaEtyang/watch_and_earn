<?php
require_once '../database/config.php';
require_once '../../vendor/autoload.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, phone_number, password, balance FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
        $stmt->close();
        exit;
    }

    $stmt->bind_result($userId, $firstName, $lastName, $email, $phoneNumber, $hashedPassword, $balance);
    $stmt->fetch();

    // Verify password
    if (password_verify($password, $hashedPassword)) {
        // Generate JWT
        $secretKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkV2YW5z
        IE11cnVuZ2EiLCJpYXQiOjE1MTYyMzkwMjJ9.pnKsvLKeZK4QV0lmydTyQUarBQmXHcosacot8g6B-g0"; 
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => [
                'id' => $userId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone_number' => $phoneNumber,
                'balance' => $balance,
            ]
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        echo json_encode([
            "status" => "success",
            "message" => "Login successful.",
            "token" => $jwt,
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
    }

    $stmt->close();
}

$conn->close();
?>
