<?php
require '../../vendor/autoload.php'; 
require_once '../database/config.php';

use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;

$coinbaseApiKey = 'your_coinbase_api_key'; 

ApiClient::init($coinbaseApiKey);

function createCryptoCharge($userId, $amount) {
    try {
        $chargeData = [
            'name' => 'Deposit to Account',
            'description' => 'Deposit funds into your account',
            'local_price' => [
                'amount' => $amount,
                'currency' => 'USD'
            ],
            'pricing_type' => 'fixed_price',
            'metadata' => [
                'user_id' => $userId
            ]
        ];

        $charge = Charge::create($chargeData);

        return [
            'status' => 'success',
            'checkout_url' => $charge->hosted_url,
            'charge_id' => $charge->id
        ];
    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => 'Unable to create charge: ' . $e->getMessage()
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id']; 
    $amount = $_POST['amount']; 

    if (empty($userId) || empty($amount) || !is_numeric($amount) || $amount <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid user ID or amount."]);
        exit;
    }

    $response = createCryptoCharge($userId, $amount);

    echo json_encode($response);
}
?>
