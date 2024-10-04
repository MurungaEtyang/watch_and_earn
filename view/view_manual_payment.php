<?php
require '../vendor/autoload.php'; 

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

// Uncomment these lines if session management is required
// session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: /");
//     exit();
// }

$paymentAddress = "16JazUo99do1MQQBXUjSYDqz7JySiBuTLc";
$amount = 10; 

$data = "16JazUo99do1MQQBXUjSYDqz7JySiBuTLc";

$options = new QROptions([
    'version'      => 5, 
    'eccLevel'    => QRCode::ECC_L, 
    'moduleSize'  => 5, 
]);

$qrcode = (new QRCode($options))->render($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Payment</title>
    <link rel="stylesheet" href="css/payment.css">
    <script src="https://cdn.jsdelivr.net/npm/jwt-decode/build/jwt-decode.js"></script>
    <script src="js/payment.js" defer></script> <!-- Link to your external JS file -->
</head>
<body>

    <div class="container">
        <h2>Scan the QR Code to Deposit</h2>
        <form class="qr-code-container">
            <img src="<?php echo $qrcode; ?>" alt="Deposit QR Code">
        </form>

        <div class="payment-form">
            <form id="manualPaymentForm">
                <div class="form-group">
                    <label for="paymentAmount">Amount (BTC)</label>
                    <input type="number" step="0.0001" id="paymentAmount" name="paymentAmount" required>
                </div>
                <div class="form-group">
                    <label for="walletAddress">Your Bitcoin Wallet Address</label>
                    <input type="text" id="walletAddress" name="walletAddress" required>
                </div>
                <div class="form-group">
                    <label for="proofImage">Upload Proof of Payment</label>
                    <input type="file" id="proofImage" name="proofImage" accept="image/*" required>
                </div>
                <button type="submit">Confirm Payment</button>
            </form>

            <div class="alert" role="alert">
                <h4 class="alert-heading">🚨 Important Notice!</h4>
                <p>It looks like our automatic payment feature is currently in the works. Rest assured, we're working hard to get this feature up and running smoothly!</p>
                <hr>
                <p class="mb-0">In the meantime, you can still make manual payments by navigating to the payment section. Thank you for your patience!</p>
            </div>
        </div>

        <!-- Modal for deposit instructions -->
        <div id="depositInstructionsModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeModal">&times;</span>
                <h2>Deposit Instructions</h2>
                <p>To make a deposit, please follow these steps:</p>
                <ol>
                    <li>Open your Bitcoin wallet.</li>
                    <li>Enter the payment address: <strong><?php echo $paymentAddress; ?></strong></li>
                    <li>Enter the amount in BTC you wish to deposit.</li>
                    <li>Complete the transaction and upload proof of payment.</li>
                    <p class="mb-0">Minimum deposit amount: <strong>0.001627 BTC</strong></p>
                </ol>
                <p>If you have any questions, feel free to contact our support team!</p>
                <button id="cancelBtn">Cancel</button>
            </div>
        </div>

        <!-- Notification area -->
        <div id="notification" class="notification"></div>
    </div>
</body>
</html>
