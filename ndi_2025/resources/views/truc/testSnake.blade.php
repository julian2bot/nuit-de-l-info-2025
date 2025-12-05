

<body>
    

<canvas id="game" width="300" height="300"></canvas>
<canvas id="canva2" width="300" height="300"></canvas>
<script src="{{ asset('js/class/Snake.js')}}"></script>

</body>

<style>

canvas { background: #eee; display: block; margin: 10px 0; }


#game {
    position: absolute;
    z-index: 10;     /* au-dessus de Three.js */
}
</style>