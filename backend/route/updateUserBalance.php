<?php
require_once '../database/config.php';

// Check if POST request contains user ID and amount
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

    if ($userId > 0 && $amount != 0) {
        try {
            // Update user balance query
            $query = "UPDATE users SET balance = balance + ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("di", $amount, $userId);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Balance updated successfully.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'User not found or no balance update needed.'
                    ]);
                }
            } else {
                throw new Exception('Failed to update balance.');
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid user ID or amount.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Please use POST.'
    ]);
}
?>
