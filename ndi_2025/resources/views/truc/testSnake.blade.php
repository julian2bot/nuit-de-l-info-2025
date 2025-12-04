<style>
    canvas { background: #eee; display: block; margin: 10px 0; }
</style>

<canvas id="game" width="300" height="300"></canvas>


<script src="{{ asset('js/class/Snake.js')}}"></script>
<script>
    const snake = new Snake(500)
    snake.start();
</script>