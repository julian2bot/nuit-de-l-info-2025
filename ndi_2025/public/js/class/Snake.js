class Snake {
    static VIDE = 0;
    static POMME = 1;
    static TETE = 2;
    static CORPS = 3;
    static FINCORPS = 4;

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
            Array.from({ length: this.width }, () => Snake.VIDE)
        );

        this.plateau = plateau;

        this.cellW = Math.floor(this.canvas.width / this.width);
        this.cellH = Math.floor(this.canvas.height / this.height);
        this.intervalId = null;

        this.corps = [];

        this.corps.push([this.posX, this.posY]);
        this.plateau[this.posY][this.posX] = Snake.TETE;

        this.addItem()
        this.render();
    }

    static randomRange(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    addItem(){
        let x = Snake.randomRange(0,this.width-1);
        let y = Snake.randomRange(0,this.height-1);

        this.plateau[y][x] = Snake.POMME;
    }

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
                case Snake.POMME:
                    this.addCorps();
                    this.addItem()
                    break;
    
                case Snake.CORPS:
                case Snake.FINCORPS:
                    this.die();
                    okay = false;
                    break;
            
                default: // 0
                    break;
            }
        }

        return okay;
    }

    addCorps(){
        this.added = true;
        const temp = this.corps[this.corps.length-1];
        this.corps.push([temp[0], temp[1]]);
    }

    move(){
        this.unDisplaySnake();

        let max = this.corps.length -1;
        if(this.added){
            max = this.corps.length -2; // Ne pas bouger le dernier si add
        }
        for (let index = max ; index > 0; index--) {
            const elem = this.corps[index];
            this.corps[index] = this.corps[index-1];
        }

        this.corps[0] = [this.posX, this.posY];

        this.displaySnake();
    }

    unDisplaySnake(){
        for (let index = 0; index < this.corps.length; index++) {
            const element = this.corps[index];
            this.plateau[element[1]][element[0]] = Snake.VIDE;
        }
    }

    displaySnake(){
        let head = this.corps[0];
        this.plateau[head[1]][head[0]] = Snake.TETE;
        for (let index = 1; index < this.corps.length - 1; index++) {
            const element = this.corps[index];
            this.plateau[element[1]][element[0]] = Snake.CORPS;
        }
        if(this.corps.length>1){
            let tail = this.corps[this.corps.length - 1];
            this.plateau[tail[1]][tail[0]] = Snake.FINCORPS;
        }
    }

    changeDir(dir){
        if(! ['d', 'g', 'h', 'b'].includes(dir)){
            dir = 'd';
        }
        this.direction = dir;
    }

    die(){
        this.stop();
    }

    getScore(){
        return this.corps.length;
    }

    getPlateau(){
        return this.plateau;
    }

    turn(){
        this.calculateNextPose();
        let ok = this.manageNextPose();
        if(ok){
            this.move();
        }

        return ok;
    }

    play(){
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

        this.startInterval();
    }

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
                if (val === Snake.VIDE) ctx.fillStyle = '#ffffff';
                else if (val === Snake.POMME) ctx.fillStyle = '#ff4d4d';
                else if (val === Snake.TETE) ctx.fillStyle = '#0b6623';
                else if (val === Snake.CORPS) ctx.fillStyle = '#26a269';
                else if (val === Snake.FINCORPS) ctx.fillStyle = '#1b7a4a';

                ctx.fillRect(x * this.cellW, y * this.cellH, this.cellW, this.cellH);
                ctx.strokeStyle = '#e6e6e6';
                ctx.strokeRect(x * this.cellW, y * this.cellH, this.cellW, this.cellH);
            }
        }
    }
}
