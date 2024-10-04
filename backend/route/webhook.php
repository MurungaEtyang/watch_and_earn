<?php
require '../../vendor/autoload.php';
require_once '../database/config.php';

use CoinbaseCommerce\Webhook;

$coinbaseWebhookSecret = 'your_webhook_secret'; 

$payload = trim(file_get_contents('php://input'));
$sigHeader = $_SERVER['HTTP_X_CC_WEBHOOK_SIGNATURE'];

try {
    $event = Webhook::buildEvent($payload, $sigHeader, $coinbaseWebhookSecret);

    if ($event->type === 'charge:confirmed') {
        $chargeId = $event->data['id'];
        $userId = $event->data['metadata']['user_id'];
        $amount = $event->data['pricing']['local']['amount'];

        $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->bind_param("di", $amount, $userId);
        if ($stmt->execute()) {
            echo "User balance updated successfully.";
        } else {
            echo "Failed to update user balance.";
        }
    }
} catch (Exception $e) {
    http_response_code(400);
    echo "Error: " . $e->getMessage();
}
?>
