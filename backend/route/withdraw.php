<?php
require_once '../database/config.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Check if required fields are present
    if (isset($data['user_id'], $data['withdrawal_address'], $data['amount'])) {
        $user_id = intval($data['user_id']);
        $withdrawal_address = $data['withdrawal_address'];
        $amount = floatval($data['amount']);

        // Define the minimum withdrawal amount
        $min_withdrawal = 0.001637;

        // Check if the withdrawal amount is below the minimum
        if ($amount < $min_withdrawal) {
            echo json_encode([
                'status' => 'error',
                'message' => 'The minimum withdrawal amount is ' . $min_withdrawal
            ]);
            exit;
        }

        // Fetch user's current balance from the database
        $sql = "SELECT balance FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $current_balance = floatval($row['balance']);
            
            // Check if user has enough balance for the withdrawal
            if ($current_balance < $amount) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Insufficient balance for this withdrawal'
                ]);
                exit;
            }

            // If balance is sufficient, proceed with withdrawal
            $sql = "INSERT INTO withdrawals (user_id, amount, withdrawal_address) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ids", $user_id, $amount, $withdrawal_address);

            if ($stmt->execute()) {
                // Deduct the withdrawn amount from the user's balance
                $new_balance = $current_balance - $amount;
                $update_sql = "UPDATE users SET balance = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("di", $new_balance, $user_id);

                if ($update_stmt->execute()) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Withdrawal request successfully submitted and balance updated',
                        'new_balance' => $new_balance
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to update balance'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to submit withdrawal request'
                ]);
            }

            $stmt->close();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'User not found'
            ]);
        }

    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid input data'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}

$conn->close();
?>
