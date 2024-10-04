<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Video</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Video</h2>
        <form id="videoForm">
            <div class="form-group">
                <label for="video_id">Video ID</label>
                <input type="text" class="form-control" id="video_id" name="video_id" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <div id="resultMessage" class="mt-3"></div>
    </div>

    <script>
        document.getElementById('videoForm').addEventListener('submit', function(event) {
            event.preventDefault(); 

            const videoId = document.getElementById('video_id').value;
            const price = document.getElementById('price').value;

            if (!videoId || isNaN(price)) {
                document.getElementById('resultMessage').innerHTML = '<div class="alert alert-danger">Please provide valid data.</div>';
                return;
            }

            const formData = new FormData();
            formData.append('video_id', videoId);
            formData.append('price', price);

            fetch('../backend/route/insert_videos.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('resultMessage').innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                } else {
                    document.getElementById('resultMessage').innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                }
            })
            .catch(error => {
                document.getElementById('resultMessage').innerHTML = '<div class="alert alert-danger">An error occurred while adding the video.</div>';
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
