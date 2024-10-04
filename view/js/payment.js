

// payment.js

// Function to get user ID from the JWT token
function getUserIdFromToken() {
    const token = localStorage.getItem('jwt'); 
    console.log('JWT Token:', token); 
            
    if (!token) {
        console.error('Token not found'); 
        return null; 
    }

    try {
        const decoded = jwt_decode(token);
        const userId = decoded.data.id; 
        console.log('User ID:', userId);
        return userId;
    } catch (error) {
        console.error('Error decoding token:', error); 
        return null;
    }
}

document.getElementById('manualPaymentForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    const userId = getUserIdFromToken(); 

    if (userId) {
        formData.append('user_id', userId);
        const paymentAmount = document.getElementById('paymentAmount').value;
        const walletAddress = document.getElementById('walletAddress').value;

        formData.append('amount', paymentAmount); 
        formData.append('payment_address', walletAddress);
    } else {
        console.error('User ID not found in token');
        return; 
    }

    const proofImage = document.getElementById('proofImage').files[0];
    if (proofImage) {
        formData.append('proofImage', proofImage);
    } else {
        console.error('No image file uploaded.');
        return; 
    }

    fetch('../backend/route/manual_payment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const notification = document.getElementById('notification');
        notification.style.display = 'block';
        if (data.status === "success") {
            notification.className = 'notification success';
            notification.innerHTML = 'Payment submitted successfully!'; 
        } else {
            notification.className = 'notification error'; 
            notification.innerHTML = 'Error: ' + data.message; 
        }

        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    })
    .catch(error => {
        console.error('Error:', error);
        const notification = document.getElementById('notification');
        notification.style.display = 'block'; 
        notification.className = 'notification error';
        notification.innerHTML = 'An error occurred while submitting the payment.';
        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    });
});

// Modal handling
var modal = document.getElementById("depositInstructionsModal");
var closeModal = document.getElementById("closeModal");
var cancelBtn = document.getElementById("cancelBtn");

window.onload = function() {
    modal.style.display = "block";
};

closeModal.onclick = function() {
    modal.style.display = "none";
};

cancelBtn.onclick = function() {
    modal.style.display = "none";
};

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};


// approve payment






