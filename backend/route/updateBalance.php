<?php
require_once '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all user payments
    $query = "
        SELECT 
            u.first_name, 
            u.last_name, 
            u.email, 
            u.phone_number, 
            u.balance, 
            mp.payment_address, 
            mp.status, 
            mp.id
        FROM 
            users u
        JOIN 
            manual_payments mp ON u.id = mp.user_id
    ";

    $result = $conn->query($query);

    if ($result) {
        $payments = [];

        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }

        echo json_encode([
            "status" => "success",
            "data" => $payments
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to fetch payment details."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}

$conn->close();
?>
