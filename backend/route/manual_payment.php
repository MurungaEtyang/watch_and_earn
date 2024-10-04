<?php
require_once '../database/config.php';

$minimumDeposit = 0.001627;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id']; 
    $amount = $_POST['amount'];
    $paymentAddress = $_POST['payment_address'];

    // Input validation
    if (empty($userId) || empty($amount) || empty($paymentAddress) || !is_numeric($amount) || $amount <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid input data."]);
        exit;
    }

    // Validate minimum deposit amount
    if ($amount < $minimumDeposit) {
        echo json_encode(["status" => "error", "message" => "The minimum deposit amount is 0.001627 BTC."]);
        exit;
    }

    $imagePath = null;

    // Handle proof of payment image upload (optional)
    if (isset($_FILES['proofImage']) && $_FILES['proofImage']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['proofImage']['name']);
        $uploadDir = 'uploads/';

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                echo json_encode(["status" => "error", "message" => "Failed to create uploads directory."]);
                exit;
            }
        }

        $targetFilePath = $uploadDir . uniqid() . '_' . $fileName;

        if (move_uploaded_file($_FILES['proofImage']['tmp_name'], $targetFilePath)) {
            $imagePath = $targetFilePath;  // Save image path to the database if needed
        } else {
            echo json_encode(["status" => "error", "message" => "Image upload failed."]);
            exit;
        }
    }

    // Insert deposit request into manual_payments table
    $stmt = $conn->prepare("INSERT INTO manual_payments (user_id, amount, payment_address, image_path) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("idss", $userId, $amount, $paymentAddress, $imagePath);

    if ($stmt->execute()) {
        // Success: Payment request has been recorded, but balance is not updated yet.
        echo json_encode(["status" => "success", "message" => "Payment request submitted successfully. Awaiting approval."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to submit payment request: " . $stmt->error]);
    }

    $stmt->close();
}
$conn->close();
?>
