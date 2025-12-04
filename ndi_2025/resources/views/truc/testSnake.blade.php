<style>
    canvas { background: #eee; display: block; margin: 10px 0; }
</style>


<canvas id="game" width="300" height="300"></canvas>

<div>
    <canvas id="canva2" width="300" height="300"></canvas>
</div>
@vite('resources/js/lancersnake.js')

<style>



#game {
    position: absolute;
    z-index: 10;     /* au-dessus de Three.js */
}
</style>