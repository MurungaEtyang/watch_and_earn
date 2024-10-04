<?php
require_once '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    error_log(print_r($data, true)); 

    $paymentId = isset($data['payment_id']) ? $data['payment_id'] : null;
    $status = isset($data['status']) ? $data['status'] : null;

    if (is_null($paymentId) || is_null($status) || !in_array($status, ['pending', 'approved', 'not_legit'])) {
        echo json_encode(["status" => "error", "message" => "Invalid input data."]);
        exit;
    }

    // Update the status of the payment
    $stmt = $conn->prepare("UPDATE manual_payments SET status = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("si", $status, $paymentId);
    
    if ($stmt->execute()) {
        if ($status === 'approved') {
            $stmt = $conn->prepare("UPDATE users u JOIN manual_payments mp ON u.id = mp.user_id SET u.balance = u.balance + mp.amount WHERE mp.id = ?");
            $stmt->bind_param("i", $paymentId);
            $stmt->execute();
        } elseif ($status === 'pending' || $status === 'not_legit') {
            $stmt = $conn->prepare("UPDATE users u JOIN manual_payments mp ON u.id = mp.user_id SET u.balance = u.balance - mp.amount WHERE mp.id = ?");
            $stmt->bind_param("i", $paymentId);
            $stmt->execute();
        }

        echo json_encode(["status" => "success", "message" => "Payment status updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update payment status: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
