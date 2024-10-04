// Check for token in localStorage
const token = localStorage.getItem('jwt');
const decoded = jwt_decode(token);
const userData = decoded.data;
localStorage.setItem('userid', userData.id);
    
if (!token) {
    window.location.href = "../";
} else {
    try {
    
        document.getElementById('userName').innerText = `Welcome, ${userData.first_name} ${userData.last_name}`;
        document.getElementById('userEmail').innerText = `Email: ${userData.email}`;
        // document.getElementById('userBalance').innerText = `Balance: $${userData.balance}`;
        
        const currentTime = Date.now() / 1000; 
        if (decoded.exp < currentTime) {
            alert('Session expired. Redirecting to login page.');
            logoutUser();
        }

    } catch (error) {
        console.error('Invalid token:', error);
        logoutUser();
    }
}

document.getElementById('logoutButton').onclick = function() {
    logoutUser();
};

function logoutUser() {
    localStorage.removeItem('jwt');
    window.location.href = "../";
}


// update balance after watching video
function updateBalanceAfterWatchVideo(userId, amount){
    fetch('../backend/route/updateUserBalance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}&amount=${amount}`
    })
    .then(response => response.json())
    .then(data => {
        
        if (data.status === 'success') {
            alert(data.message);
        } else {
            alert(`Error: ${data.message}`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });

}


const names = ["John Doe", "Jane Smith", "Chris Johnson", "Lisa Brown", "Michael Lee", "Emily Davis", "David Wilson", "Sophia Thomas", "James Taylor", "Olivia Harris"];
const countries = ["USA", "Canada", "UK"];
const maxEarnings = 500;

function getRandomName() {
    return names[Math.floor(Math.random() * names.length)];
}

function getRandomCountry() {
    return countries[Math.floor(Math.random() * countries.length)];
}

function getRandomEarnings() {
    return (Math.random() * maxEarnings).toFixed(2);
}

function populateTopEarners() {
    const tableBody = document.getElementById('topEarnersTable');
    tableBody.innerHTML = ""; 

    for (let i = 1; i <= 20; i++) {
        const row = document.createElement('tr');

        const rankCell = document.createElement('td');
        rankCell.textContent = i;
        row.appendChild(rankCell);

        const nameCell = document.createElement('td');
        nameCell.textContent = getRandomName();
        row.appendChild(nameCell);

        const countryCell = document.createElement('td');
        countryCell.textContent = getRandomCountry();
        row.appendChild(countryCell);

        const earningsCell = document.createElement('td');
        earningsCell.textContent = `$${getRandomEarnings()}`;
        row.appendChild(earningsCell);

        tableBody.appendChild(row);
    }
}

setInterval(populateTopEarners, 600000);

// Initial load
window.onload = populateTopEarners;


// fetch balance

function fetchUserBalance() {
    fetch(`../backend/route/fetch_balance.php?user_id=${encodeURIComponent(userData.id)}`) 
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('balanceAmount').textContent = `Balance: BTC ${parseFloat(data.balance).toFixed(10)}`;
                localStorage.setItem('balance', data.balance)
                
            } else {
                console.error('Error fetching balance:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

fetchUserBalance();


setInterval(() => fetchUserBalance(), 5000);
    
