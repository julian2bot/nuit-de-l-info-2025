<link rel="stylesheet" href="{{ asset('style/snake.css')}}">
<link rel="stylesheet" href="{{ asset('style/bugged.css')}}">

<body class="bugged">
    <main>
        <div id="header">
            <div id="level">
                <p>Level :</p>
                <p id="levelValue">0</p>
            </div>
    
            <div id="lesScore">
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
            <canvas id="game" width="300" height="300"></canvas>
            <div id="centeredContainer">
                <button id="start" disabled onclick="start()">Start</button>
            </div>
        </div>
    </main>
</body>