<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecteur Audio</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #1a1a1a;
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 40px;
        }

        .player {
            text-align: center;
        }

        .icon {
            font-size: 80px;
            margin-bottom: 40px;
        }

        .controls {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .play-btn {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #1a1a1a;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .play-btn:hover {
            transform: scale(1.05);
            background: #333;
        }

        .play-btn:active {
            transform: scale(0.95);
        }

        .timeline {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            cursor: pointer;
            position: relative;
        }

        .progress {
            height: 100%;
            background: #1a1a1a;
            border-radius: 3px;
            width: 0%;
            transition: width 0.1s linear;
        }

        .time {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #666;
        }

        .volume-control {
            display: flex;
            align-items: center;
            gap: 15px;
            justify-content: center;
        }

        .volume-icon {
            font-size: 20px;
            color: #666;
        }

        .volume-slider {
            width: 120px;
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            cursor: pointer;
            position: relative;
        }

        .volume-level {
            height: 100%;
            background: #1a1a1a;
            border-radius: 3px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="player">
            <div class="icon">🎵</div>

            <div class="controls">
                <button class="play-btn" id="playBtn">▶</button>
                <div class="timeline">
                    <div class="progress-bar" id="progressBar">
                        <div class="progress" id="progress"></div>
                    </div>
                    <div class="time">
                        <span id="currentTime">0:00</span>
                        <span id="duration">0:00</span>
                    </div>
                </div>
            </div>

            <div class="volume-control">
                <div class="volume-icon">🔊</div>
                <div class="volume-slider" id="volumeSlider">
                    <div class="volume-level" id="volumeLevel"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- METS TON FICHIER AUDIO ICI -->
    <audio id="audio" src="{{ asset('audio\musique_de_fou.m4a')}}"></audio>

    <script>
        const audio = document.getElementById('audio');
        const playBtn = document.getElementById('playBtn');
        const progress = document.getElementById('progress');
        const progressBar = document.getElementById('progressBar');
        const currentTime = document.getElementById('currentTime');
        const duration = document.getElementById('duration');
        const volumeSlider = document.getElementById('volumeSlider');
        const volumeLevel = document.getElementById('volumeLevel');

        playBtn.addEventListener('click', () => {
            if (audio.paused) {
                audio.play();
                playBtn.textContent = '⏸';
            } else {
                audio.pause();
                playBtn.textContent = '▶';
            }
        });

        audio.addEventListener('timeupdate', () => {
            const percent = (audio.currentTime / audio.duration) * 100;
            progress.style.width = percent + '%';
            currentTime.textContent = formatTime(audio.currentTime);
        });

        audio.addEventListener('loadedmetadata', () => {
            duration.textContent = formatTime(audio.duration);
        });

        progressBar.addEventListener('click', (e) => {
            const rect = progressBar.getBoundingClientRect();
            const percent = (e.clientX - rect.left) / rect.width;
            audio.currentTime = percent * audio.duration;
        });

        volumeSlider.addEventListener('click', (e) => {
            const rect = volumeSlider.getBoundingClientRect();
            const percent = (e.clientX - rect.left) / rect.width;
            audio.volume = percent;
            volumeLevel.style.width = (percent * 100) + '%';
        });

        audio.addEventListener('ended', () => {
            playBtn.textContent = '▶';
        });

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        }
    </script>
</body>
</html>
