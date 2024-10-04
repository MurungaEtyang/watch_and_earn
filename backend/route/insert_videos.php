<?php
require_once '../database/config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $video_id = $_POST['video_id']; 
    $price = $_POST['price']; 

    if (!empty($video_id) && is_numeric($price)) {

        $sql = "INSERT INTO videos (video_id, price) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql)) {

            $stmt->bind_param("sd", $video_id, $price); 

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Video added successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add video.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
