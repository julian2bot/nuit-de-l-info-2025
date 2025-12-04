class Snake {
    static EMPTY = 0;
    static APPLE = 1;
    static HEAD = 2;
    static BODY = 3;
    static TAIL = 4;

    constructor(width, height, tickrate) {
        this.width = width;
        this.height = height;
        this.tick = tickrate;

        this.canvas = document.getElementById('game');
        this.ctx = this.canvas.getContext('2d');

        this.reset();
    }

    reset(){
        this.posX = parseInt(this.width / 2);
        this.posY = parseInt(this.height / 2);

        this.added = false;
        this.playing = false;

        this.direction = 'd'; // Droite, Haut, Bas, Gauche

        const plateau = Array.from({ length: this.height }, () =>
            Array.from({ length: this.width }, () => Snake.EMPTY)
        );

        this.plateau = plateau;

        this.cellW = Math.floor(this.canvas.width / this.width);
        this.cellH = Math.floor(this.canvas.height / this.height);
        this.intervalId = null;

        this.BODY = [];

        this.BODY.push([this.posX, this.posY]);
        this.plateau[this.posY][this.posX] = Snake.HEAD;

        this.addItem()
        this.render();
    }

    static randomRange(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    // ADD

    addItem(){
        let x;
        let y;

        do {
            x = Snake.randomRange(0, this.width - 1);
            y = Snake.randomRange(0, this.height - 1);
        } while (this.plateau[y][x] !== Snake.EMPTY);

        this.plateau[y][x] = Snake.APPLE;
    }

    addBody(){
        this.added = true;
        const temp = this.BODY[this.BODY.length-1];
        this.BODY.push([temp[0], temp[1]]);
    }

    // POSITION

    calculateNextPose() {
        switch (this.direction) {
            case 'h':
                this.posY -= 1;
                break;
            case 'b':
                this.posY += 1;
                break;
            case 'g':
                this.posX -= 1;
                break;
            default: //d
                this.posX += 1;
                break;
        }
    }

    manageNextPose(){
        this.added = false;
        let okay = true;

        if(this.posX < 0 || this.posY < 0 || this.posX >= this.width || this.poxY >= this.height){
            this.die();
            okay = false;
        }
        else{
            switch (this.plateau[this.posY][this.posX]) {
                case Snake.APPLE:
                    this.addBody();
                    this.addItem()
                    break;
    
                case Snake.BODY:
                case Snake.TAIL:
                    this.die();
                    okay = false;
                    break;
            
                default: // 0
                    break;
            }
        }

        return okay;
    }

    move(){
        this.unDisplaySnake();

        let max = this.BODY.length -1;
        if(this.added){
            max -= 1; // Ne pas bouger le dernier si add
        }
        for (let index = max ; index > 0; index--) {
            this.BODY[index] = this.BODY[index-1];
        }

        this.BODY[0] = [this.posX, this.posY];

        this.displaySnake();
    }

    unDisplaySnake(){
        for (let index = 0; index < this.BODY.length; index++) {
            const element = this.BODY[index];
            this.plateau[element[1]][element[0]] = Snake.EMPTY;
        }
    }

    displaySnake(){
        let head = this.BODY[0];
        this.plateau[head[1]][head[0]] = Snake.HEAD;
        for (let index = 1; index < this.BODY.length - 1; index++) {
            const element = this.BODY[index];
            this.plateau[element[1]][element[0]] = Snake.BODY;
        }
        if(this.BODY.length>1){
            let tail = this.BODY[this.BODY.length - 1];
            this.plateau[tail[1]][tail[0]] = Snake.TAIL;
        }
    }

    changeDir(dir){
        if(! ['d', 'g', 'h', 'b'].includes(dir)){
            dir = 'd';
        }
        this.direction = dir;
    }

    // FIN

    die(){
        this.stop();
    }

    getScore(){
        return this.BODY.length;
    }

    // PLAY

    turn(){
        this.calculateNextPose();
        let ok = this.manageNextPose();
        if(ok){
            this.move();
        }

        return ok;
    }

    play(){
        this.addListener();

        this.startInterval();
    }

    // GAME MANAGEMENT

    startInterval(){
        this.intervalId = setInterval(() => {
            const alive = this.turn();
            this.render();
            if (!alive) {
                clearInterval(this.intervalId);
            }
        }, this.tick);
    }

    stop(){
        clearInterval(this.intervalId);
        this.intervalId = null;
    }

    render() {
        const ctx = this.ctx;
        ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        for (let y = 0; y < this.height; y++) {
            for (let x = 0; x < this.width; x++) {
                const val = this.plateau[y][x];
                if (val === Snake.EMPTY) ctx.fillStyle = '#ffffff';
                else if (val === Snake.APPLE) ctx.fillStyle = '#ff4d4d';
                else if (val === Snake.HEAD) ctx.fillStyle = '#0b6623';
                else if (val === Snake.BODY) ctx.fillStyle = '#26a269';
                else if (val === Snake.TAIL) ctx.fillStyle = '#1b7a4a';

                ctx.fillRect(x * this.cellW, y * this.cellH, this.cellW, this.cellH);
                ctx.strokeStyle = '#e6e6e6';
                ctx.strokeRect(x * this.cellW, y * this.cellH, this.cellW, this.cellH);
            }
        }
    }

    // AUTRE
    addListener(){
        document.addEventListener('keydown', (event) => {
            switch (event.key) {
                case 'ArrowUp':
                    this.changeDir('h')
                    break;

                case 'ArrowDown':
                    this.changeDir('b')
                    break;

                case 'ArrowLeft':
                    this.changeDir('g')
                    break;

                case 'ArrowRight':
                    this.changeDir('d')
                    break;
            }
        });
    }
}
