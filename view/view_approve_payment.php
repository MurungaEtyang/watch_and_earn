<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Payment Details</title>
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <h1>User Payment Details</h1>
    <div id="userDetails"></div>

    <script>
        document.addEventListener('DOMContentLoaded', fetchUserPaymentDetails);

        function fetchUserPaymentDetails() {
            fetch(`../backend/route/updateBalance.php`)
                .then(response => response.json())
                .then(data => {
                console.log(data); 
                if (data.status === 'success') {
                    const userDetailsDiv = document.getElementById('userDetails');
                    userDetailsDiv.innerHTML = '<h3>User Details:</h3>';
                    
                    // Loop through each entry in data
                    data.data.forEach(user => {
                        userDetailsDiv.innerHTML += `
                            <div>
                                <p>First Name: ${user.first_name}</p>
                                <p>Last Name: ${user.last_name}</p>
                                <p>Email: ${user.email}</p>
                                <p>Phone Number: ${user.phone_number}</p>
                                <p>Balance: ${user.balance}</p>
                                <p>Payment Address: ${user.payment_address}</p>
                                <p>Status: 
                                    <select class="status-dropdown" onchange="updateStatus(${user.id}, this.value)">
                                        <option value="pending" ${user.status === 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="not_legit" ${user.status === 'not_legit' ? 'selected' : ''}>Not Legit</option>
                                        <option value="approved" ${user.status === 'approved' ? 'selected' : ''}>Approved</option>
                                    </select>

                                </p>
                            </div>
                            <hr>
                        `;
                    });
                } else {
                    document.getElementById('userDetails').innerHTML = '<p>Error: ' + data.message + '</p>';
                }
            })

                .catch(error => {
                    console.error('Error fetching user payment details:', error);
                    document.getElementById('userDetails').innerHTML = '<p>Error fetching data.</p>';
                });
        }

        function updateStatus(paymentId, status) {
            if (!paymentId || !status) {
                alert("Payment ID and Status are required.");
                return;
            }

            // Make an AJAX request to update the payment status
            fetch(`../backend/route/updatePaymentStatus.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ payment_id: paymentId, status: status }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Status updated successfully!');
                    fetchUserPaymentDetails(); // Refresh the data
                } else {
                    alert('Error updating status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error updating payment status:', error);
            });
        }
    </script>
</body>
</html>
