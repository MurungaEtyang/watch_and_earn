<?php
// Include the database configuration file
require_once '../database/config.php'; // Adjust path as needed

// Query to fetch videos and their prices
$sql = "SELECT video_id, price FROM videos";
$result = $conn->query($sql);

// Check if any rows were returned
if ($result->num_rows > 0) {
    $videos = array();

    // Fetch data for each row
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row; // Store video data in an array
    }

    // Return video data in JSON format
    echo json_encode(['status' => 'success', 'videos' => $videos]);
} else {
    // No videos found
    echo json_encode(['status' => 'error', 'message' => 'No videos found']);
}

// Close the database connection
$conn->close();
?>
