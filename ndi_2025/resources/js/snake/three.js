import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';


class Snake {
    static EMPTY = 0;
    static APPLE = 1;
    static HEAD = 2;
    static BODY = 3;
    static TAIL = 4;
    static BOMB = 5;
    static BOMB1 = 6;
    static BOMB2 = 7;

    constructor(tickrate, onDeathFunction, maxScore) {
        this.tick = tickrate;
        this.onDeathFunction = onDeathFunction;
        this.maxScore = maxScore;

        this.canvas = document.getElementById('game');
        this.ctx = this.canvas.getContext('2d');
        this.nexDir = '';
    }

    setTupLevel(){
        switch (this.level) {
            case 1:
                this.width = 7;
                this.height = 7;
                this.bombNumber = 1;
                this.minFreeSpace = 0;
                break;
            
            case 2:
                this.width = 10;
                this.height = 10;
                this.bombNumber = 2;
                this.minFreeSpace = 5;
                break;

            case 3:
                this.width = 15;
                this.height = 15;
                this.bombNumber = 3;
                this.minFreeSpace = 5;
                break;

            case 4:
                this.width = 20;
                this.height = 20;
                this.bombNumber = 4;
                this.minFreeSpace = 5;
                break;
        
            default:
                this.width = 25;
                this.height = 25;
                this.minFreeSpace = 5;
                
                let bombN = 4 + (this.level - 4)*4;

                this.bombNumber = Math.min(bombN, 50);
                break;
        }

        const plateau = Array.from({ length: this.height }, () =>
            Array.from({ length: this.width }, () => Snake.EMPTY)
        );

        this.plateau = plateau;

        this.cellW = Math.floor(this.canvas.width / this.width);
        this.cellH = Math.floor(this.canvas.height / this.height);

        this.posX = parseInt(this.width / 2);
        this.posY = parseInt(this.height / 2);
        this.body = [];
        this.body.push([this.posX, this.posY]);
        this.bombs = []; // posX, posY, timeRemaining
        for (let index = 0; index < this.bombNumber; index++) {
            this.addBomb();
        }
        this.plateau[this.posY][this.posX] = Snake.HEAD;
        this.addItem()

        this.renderLevel();
        this.renderScore();
    }

    reset(){
        this.nexDir = ' ';
        this.level = 1;

        this.score = 0;

        this.added = false;
        this.playing = false;

        this.direction = 'd'; // Droite, Haut, Bas, Gauche
        
        this.intervalId = null;

        this.setTupLevel();

        this.render();
    }

    static randomRange(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    // ADD

    getFreeCoord(){
        let x;
        let y;

        do {
            x = Snake.randomRange(0, this.width - 1);
            y = Snake.randomRange(0, this.height - 1);

        } while (this.plateau[y][x] !== Snake.EMPTY);

        return [x,y];
    }

    getFreeCoordBomb(){
        let x;
        let y;
        let nearHead;
        let head = this.body[0];

        do {
            x = Snake.randomRange(0, this.width - 1);
            y = Snake.randomRange(0, this.height - 1);

            if (this.plateau[y][x] !== Snake.EMPTY) continue;

            nearHead = Math.abs(head[0] - x) <= 1 && Math.abs(head[1] - y) <= 1;
        } while (this.plateau[y][x] !== Snake.EMPTY || nearHead);
        return [x,y];
    }

    addBomb(){
        let pos = this.getFreeCoordBomb();
        this.plateau[pos[1]][pos[0]] = Snake.BOMB;
        this.bombs.push([pos[0], pos[1], Snake.randomRange(2,6)]);
    }

    manageBombs(){
        const toRemove = [];
        for (const element of this.bombs) {
            element[2] -= 1;
            let color = Snake.BOMB;
            switch (element[2]) {
                case 4:
                case 3:
                    color = Snake.BOMB1;
                    break;

                case 2:
                case 1:
                    color = Snake.BOMB2;
                    break;

                case 0:
                    color = Snake.EMPTY;
                    toRemove.push(element);
                    break;
            
                default:
                    color = Snake.BOMB;
                    break;
            }
            this.plateau[element[1]][element[0]] = color;
        }
        let bombsToAdd = toRemove.length;
        this.bombs = this.bombs.filter(item => !toRemove.includes(item));
        if(this.body.length + this.bombs.length + 1 + this.minFreeSpace < this.width*this.height){
            for (let index = 0; index < bombsToAdd; index++) {
                this.addBomb();        
            }
        }

    }

    addItem(){
        let pos = this.getFreeCoord();

        this.plateau[pos[1]][pos[0]] = Snake.APPLE;
    }

    addBody(){
        this.added = true;
        const temp = this.body[this.body.length-1];
        this.body.push([temp[0], temp[1]]);

        this.score += 1*this.level;

        this.renderScore();

        if(this.body.length == this.width*this.height - 1){
            this.level += 1;
            this.setTupLevel();
            return false;
        }
        return true;
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

        if(this.posX < 0 || this.posY < 0 || this.posX >= this.width || this.posY >= this.height){
            this.die();
            okay = false;
        }
        else{
            switch (this.plateau[this.posY][this.posX]) {
                case Snake.APPLE:
                    if(this.addBody())
                        this.addItem();
                    break;
    
                case Snake.BOMB:
                case Snake.BOMB1:
                case Snake.BOMB2:
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

        let max = this.body.length -1;
        if(this.added){
            max -= 1; // Ne pas bouger le dernier si add
        }
        for (let index = max ; index > 0; index--) {
            this.body[index] = this.body[index-1];
        }

        this.body[0] = [this.posX, this.posY];

        this.displaySnake();
    }

    unDisplaySnake(){
        for (let index = 0; index < this.body.length; index++) {
            const element = this.body[index];
            this.plateau[element[1]][element[0]] = Snake.EMPTY;
        }
    }

    displaySnake(){
        let head = this.body[0];
        this.plateau[head[1]][head[0]] = Snake.HEAD;
        for (let index = 1; index < this.body.length - 1; index++) {
            const element = this.body[index];
            this.plateau[element[1]][element[0]] = Snake.BODY;
        }
        if(this.body.length>1){
            let tail = this.body[this.body.length - 1];
            this.plateau[tail[1]][tail[0]] = Snake.TAIL;
        }
    }

    changeDir(){
        if(this.nexDir!= ' '){
            const opposites = {
                'd': 'g', 
                'g': 'd', 
                'h': 'b',
                'b': 'h'
            };
    
            if (!['d', 'g', 'h', 'b'].includes(this.nexDir)) {
                this.nexDir = 'd';
            }
    
            if (this.nexDir !== opposites[this.direction]) {
                this.direction = this.nexDir;
            }
            this.nexDir = ' ';

            console.log("Change dir");
        }
    }

    // FIN

    die(){
        this.stop();
        this.onDeathFunction(this.maxScore);
    }

    getScore(){
        return this.score;
    }

    // PLAY

    turn(){
        this.changeDir();
        this.calculateNextPose();
        let ok = this.manageNextPose();
        if(ok){
            this.move();
        }
        this.manageBombs();

        return ok;
    }

    play(){
        this.addListener();

        this.startInterval();
    }

    start(){
        this.reset();
        this.play();
    }

    pause(){
        if(this.intervalId === null){
            this.play();
        }
        else{
            this.stop();
        }
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
                else if (val === Snake.BOMB) ctx.fillStyle = '#1b2c7aff';
                else if (val === Snake.BOMB1) ctx.fillStyle = '#384ba1ff';
                else if (val === Snake.BOMB2) ctx.fillStyle = '#6e83deff';

                ctx.fillRect(x * this.cellW, y * this.cellH, this.cellW, this.cellH);
                ctx.strokeStyle = '#e6e6e6';
                ctx.strokeRect(x * this.cellW, y * this.cellH, this.cellW, this.cellH);
            }
        }
    }

    renderScore(){
        if(this.scoreP == null){
            this.scoreP = document.getElementById("scoreValue");
        }
        if(this.scoreP){
            this.scoreP.innerHTML = this.score;
        }

        this.renderMaxScore();
    }

    renderMaxScore(){
        if(this.score>this.maxScore){
            this.maxScore = this.score;
            if(this.maxScoreP == null){
                this.maxScoreP = document.getElementById("scoreMaxValue");
            }
            if(this.maxScoreP){
                this.maxScoreP.innerHTML = this.maxScore;
            }
        }
    }

    renderLevel(){
        if(this.levelP == null){
            this.levelP = document.getElementById("levelValue");
        }
        if(this.levelP){
            this.levelP.innerHTML = this.level;
        }
    }

    // AUTRE
    addListener(){
        document.addEventListener('keydown', (event) => {
            switch (event.key) {
                case 'ArrowUp':
                    this.nexDir = 'h';
                    break;

                case 'ArrowDown':
                    this.nexDir = 'b';
                    break;

                case 'ArrowLeft':
                    this.nexDir = 'g';
                    break;

                case 'ArrowRight':
                    this.nexDir = 'd';
                    break;
            }
        });
    }
}


class Snake3D extends Snake{
    constructor(ticks, onDeathFunction) {
        super(ticks, onDeathFunction);

        this.body3d = [];

        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);
        this.renderer = new THREE.WebGLRenderer({ canvas: document.getElementById('canva2') });
        this.renderer.setSize(window.innerWidth, window.innerHeight);


        this.controls = new OrbitControls(this.camera, this.renderer.domElement);

        this.camera.position.set(this.width/2, 10, this.height/2);
        this.camera.lookAt(new THREE.Vector3(this.width/2, 0, this.height/2));

        const light = new THREE.DirectionalLight(0xffffff, 1);
        light.position.set(5,5,5);
        this.scene.add(light);

        // LOAD MODELS
        this.loader = new GLTFLoader();
        this.head3d = null;
        this.body3d = [];
        this.apple3d = null;

        this.loadModels();

        // this.animate();
    }
    

    initPlateau() {
        const geometry = new THREE.BoxGeometry(1, 0.1, 1);
        const material = new THREE.MeshStandardMaterial({ color: 0x0077ff });

        this.cases = [];

        for (let y = 0; y < this.height; y++) {
            for (let x = 0; x < this.width; x++) {
                const cube = new THREE.Mesh(geometry, material);
                cube.position.set(x, 0, y);
                this.scene.add(cube);
                this.cases.push(cube);
            }
        }
    }

    setTupLevel(){
        super.setTupLevel();
        this.initPlateau();
    }

    loadModels() {
        // HEAD
        this.loader.load("/model/boite.glb", gltf => {
            this.head3d = gltf.scene;
            this.scene.add(this.head3d);
        });

        this.loader.load("/model/corp.glb", gltf => {
          this.bodyTemplate = gltf.scene;
        });

        // APPLE
        this.loader.load("/model/fruit.glb", gltf => {
            this.apple3d = gltf.scene;
            this.apple3d.scale.set(0.5,0.5,0.5);
            this.scene.add(this.apple3d);
        });
    }

    render() {
        if (!this.head3d || !this.bodyTemplate) return;
        
        
        if (this.apple3d) {
            for (let y=0; y<this.plateau.length; y++) {
                for (let x=0; x<this.plateau[0].length; x++) {
                    if (this.plateau[y][x] === Snake.APPLE) {
                        this.apple3d.position.set(x, 0.5, y);
                    }
                }
            }
        }

        while (this.body3d.length < this.body.length - 1) {
          const segment = this.bodyTemplate.clone(true);
          segment.scale.set(0.2, 0.2, 0.2);
          this.scene.add(segment);
          this.body3d.push(segment);
        }

        if (this.head3d) {
            const [hx, hy] = this.body[0];
            this.head3d.position.set(hx, 0.5, hy);
        }
        for (let i = 1; i < this.body.length; i++) {
            const [x, y] = this.body[i];
            this.body3d[i - 1].position.set(x, 0.5, y);
        }
    }

    animate = () => {
        requestAnimationFrame(this.animate);
        this.controls.update();
        this.renderer.render(this.scene, this.camera);
    }

    play(){
        super.play();
        this.animate();
    }
}


    // console.log("Snake start !");
    // const snake = new Snake(500);
    // const snake3d = new Snake3D(10, 10);
    const snake3d = new Snake3D(500,gg);
    snake3d.start();
    // snake.render3d = function() {
    //     snake3d.update(this.plateau, this.BODY);
    // };

    function gg(){

    }