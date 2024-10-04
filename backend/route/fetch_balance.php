<?php
require_once '../database/config.php';  

if (isset($_GET['user_id'])) {
    // Fetch user_id from $_GET instead of $_POST
    $user_id = intval($_GET['user_id']); 

    $sql = "SELECT balance FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'balance' => $row['balance']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'User ID not provided']);
}

$conn->close();
?>
