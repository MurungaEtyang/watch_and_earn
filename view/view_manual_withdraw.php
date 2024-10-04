<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal - Earn While You Watch</title>
    <link rel="stylesheet" href="css/payment.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">💸 Withdraw Funds 💸</h2>
    
    <div class="alert alert-info text-center" role="alert">
        <h4 class="alert-heading">🔔 Important Notice!</h4>
        <p>To withdraw funds, please ensure your Bitcoin wallet address is correct. Transactions cannot be reversed!</p>
        <p class="mb-0">Minimum withdrawal amount: <strong>0.001637 BTC</strong></p>
    </div>

    <form id="withdrawal-form">
        <div class="form-group">
            <label for="walletAddress">Bitcoin Wallet Address:</label>
            <input type="text" id="with-walletAddress" class="form-control" placeholder="Enter your Bitcoin wallet address" required>
        </div>

        <div class="form-group">
            <label for="amount">Withdrawal Amount (BTC):</label>
            <input type="number" id="with-amount" class="form-control" step="0.0001" placeholder="Enter amount to withdraw" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Withdraw</button>
    </form>

    <div id="notification" class="mt-3"></div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
    // withdraw
document.getElementById('withdrawal-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const userId = localStorage.getItem('userid'); 

    console.log('user id',userId)
    const walletAddress = document.getElementById('with-walletAddress').value;
    const amount = parseFloat(document.getElementById('with-amount').value);

    if (walletAddress === '' || isNaN(amount) || amount <= 0) {
        alert('Please provide a valid wallet address and amount.');
        return;
    }

    // Send withdrawal request
    fetch('../backend/route/withdraw.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ user_id: userId, withdrawal_address: walletAddress, amount: amount })
    })
    .then(response => response.json())
    .then(data => {
        const notification = document.getElementById('notification');
        notification.innerHTML = ''; 

        if (data.status === 'success') {  // Check if the PHP response has 'status' field
            notification.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        } else {
            notification.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const notification = document.getElementById('notification');
        notification.innerHTML = `<div class="alert alert-danger">An error occurred during the withdrawal process.</div>`;
    });
});
</script>
</body>
</html>
