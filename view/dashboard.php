<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earn While You Watch - User Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Earn While You Watch</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" id="userName">Welcome, User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="userEmail">Email: user@example.com</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#" id="balanceAmount">Balance: $0.00</a>
            </li>

            <li>
                <button class="btn btn-warning" id="withdrawButton" data-toggle="modal" data-target="#withdrawModal">Withdraw</button>
            </li>

            <li>
                <button class="btn btn-primary" id="depositButton" data-toggle="modal" data-target="#depositModal">Deposit</button>
            </li>
            <li class="nav-item">
                <button id="logoutButton" class="btn btn-danger btn-sm">Logout</button>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">💰 Earn While You Watch! 💰</h2>
    <p class="text-center">Watch YouTube videos, support our channel, and earn cash for each completed video!</p>
    
    <div class="mt-5" id="youtubeContainer">
        <h4 class="text-center">📹 Watch All Videos to Win Your Reward 📹</h4>
        <div id="videoContainer" class="row">
            <!-- YouTube videos will be dynamically injected here -->
        </div>

        <div class="text-center" id="insufficient-balance"></div>
    </div>

    <div id="totalEarnings" class="alert alert-info mt-3 d-none text-center">
        Total Earnings: $<span id="earnedAmount">0</span>
    </div>

    <div id="rewardMessage" class="alert alert-success mt-3 d-none">
        🎉 Congratulations! You've watched all the videos and won a total of $<span id="finalAmount">0</span>! 🎉
    </div>

    <!-- Top Earners Section -->
    <div class="mt-5">
        <h4 class="text-center">🌟 Top Earners 🌟</h4>
        <table class="table table-striped table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Earnings</th>
                </tr>
            </thead>
            <tbody class="text-info text-white hover-overlay hover-zoom hover-shadow ripple" id="topEarnersTable">
                <!-- Top earners will be populated here -->
            </tbody>
        </table>
    </div>
</div>

<!-- Deposit Modal -->
<div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="depositModalLabel">Deposit Funds</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="view_manual_payment.php" style="width: 100%; height: 400px; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">Withdraw Funds</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="view_manual_withdraw.php" style="width: 100%; height: 400px; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <div class="container">
        <p class="mb-0">&copy; 2024 Earn While You Watch. All rights reserved.</p>
    </div>
</footer>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jwt-decode/build/jwt-decode.min.js"></script>
<script src="https://www.youtube.com/iframe_api"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="js/script.js"></script>
<script src="js/videos.js"></script>

</body>
</html>
