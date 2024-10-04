const balance=localStorage.getItem('balance')


if(balance <= 0.0016){
    document.getElementById('insufficient-balance').innerHTML = 'Insufficient balance';
}else{
    let watchedVideos = [];
    let totalEarnings = 0;
    let players = [];

    fetch('../backend/route/get_videos.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const videoIDs = [];
                const videoEarnings = [];

                data.videos.forEach(video => {
                    videoIDs.push(video.video_id);
                    videoEarnings.push(video.price);
                });

                watchedVideos = Array(videoIDs.length).fill(false); 

                onYouTubeIframeAPIReady(videoIDs, videoEarnings);
            } else {
                alert('Error fetching videos: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));

    const tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    const firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    function onYouTubeIframeAPIReady(videoIDs = [], videoEarnings = []) {
        const videoContainer = document.getElementById('videoContainer');
        
        videoIDs.forEach((videoID, index) => {
            const videoDiv = document.createElement('div');
            videoDiv.className = 'col-md-4 video-card card mb-3';
            videoDiv.innerHTML = `
                <div class="card-body">
                    <div id="player${index}"></div>
                    <p class="card-text mt-2 text-center">💵 Earn $${videoEarnings[index]} for watching this video!</p>
                </div>
            `;
            videoContainer.appendChild(videoDiv);

            const player = new YT.Player(`player${index}`, {
                height: '200',
                width: '280',
                videoId: videoID,
                events: {
                    'onStateChange': event => onPlayerStateChange(event, index, videoEarnings)
                }
            });
            
            players.push(player);
        });
    }

    function onPlayerStateChange(event, index, videoEarnings) {
        if (event.data === YT.PlayerState.ENDED) {
            if (!watchedVideos[index]) {
                watchedVideos[index] = true; 
                totalEarnings += videoEarnings[index]; 

                const userId = userData.id;
                const amount = videoEarnings[index];
                updateBalanceAfterWatchVideo(userId, amount)

                alert(`Great job! You earned $${videoEarnings[index]} for watching this video, and we've updated your balance.`);
                updateEarnings(totalEarnings);

                checkAllVideosWatched();
            }
        }
    }

    // Update total earnings in the UI
    function updateEarnings(amount) {
        document.getElementById('earnedAmount').innerText = amount.toFixed(2);
        document.getElementById('totalEarnings').classList.remove('d-none');
    }

    function checkAllVideosWatched() {
        if (watchedVideos.every(Boolean)) {
            document.getElementById('rewardMessage').classList.remove('d-none');
            document.getElementById('finalAmount').innerText = totalEarnings.toFixed(2);
        }
    }
}


