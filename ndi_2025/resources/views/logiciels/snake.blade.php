<link rel="stylesheet" href="{{ asset('style/snake.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<main>
    <div id="header">
        
        <div id="lesScore">
            <div id="level">
            <p>Level :</p>
            <p id="levelValue">0</p>
        </div>
            <div id="score">
                <p>Score :</p>
                <p id="scoreValue">0</p>
            </div>
    
            <div id="maxScore">
                <p>Meilleur Score :</p>
                <p id="scoreMaxValue">0</p>
            </div>
        </div>

    </div>
    <div id="gameDiv">
        <canvas id="game" width="500" height="500"></canvas>
        <div id="centeredContainer">
            <button id="start">Start</button>
            <div id="deadContainer">
                <h2>Vous êtes morts</h2>
                <button id="restart">Recommencer</button>
                <div class="deadBackGround"></div>
            </div>
            <div id="pauseContainer">
                <h2>Pause</h2>
                <div class="deadBackGround"></div>
            </div>
        </div>
        <button id="pause">Pause</button>
    </div>
</main>


<!-- <script type="module" src="{{ asset('js/class/Snake.js')}}"></script> -->

<script type="module">
    import Snake3D from "{{ asset('js/class/Snake.js') }}";
    document.addEventListener('DOMContentLoaded', () => {

        let maxScore = 0;
        const snake = new Snake3D(500, death, maxScore);
    
        let startButton = document.getElementById("start");
        let pauseButton = document.getElementById("pause");
        let deadContainer = document.getElementById("deadContainer");
        let pauseContainer = document.getElementById("pauseContainer");
    
        startButton.addEventListener('click', () => start());
        pauseButton.addEventListener('click', () => pause());
        document.getElementById('restart').addEventListener('click', () => start());
    
        function start(){
            startButton.style.display = "none";
            pauseButton.style.display = "inherit";
            deadContainer.style.display = "none";
            pauseContainer.style.display = "none";
            snake.start();
        }
    
        function pause(){
            pauseButton.style.display = "inherit";
            startButton.style.display = "none";
            deadContainer.style.display = "none";
            if(pauseContainer.style.display == "flex"){
                pauseContainer.style.display = "none";
            }
            else{
                pauseContainer.style.display = "flex";
            }
            snake.pause();
        }
    
        function death(score){
            startButton.style.display = "none";
            pauseButton.style.display = "none";
            deadContainer.style.display = "flex";
            pauseContainer.style.display = "none";
            if(score>maxScore){
                maxScore = score;
                sendScore("snake", "level", maxScore);
            }
        }
    });
</script>