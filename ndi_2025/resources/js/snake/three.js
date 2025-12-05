import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';


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
        this.render2d();
    }

    static randomRange(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    addItem(){
        let x = Snake.randomRange(0,this.width-1);
        let y = Snake.randomRange(0,this.height-1);

        this.plateau[y][x] = Snake.APPLE;
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
                case Snake.APPLE:
                    this.addBODY();
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

    addBODY(){
        this.added = true;
        const temp = this.BODY[this.BODY.length-1];
        this.BODY.push([temp[0], temp[1]]);
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

    die(){
        this.stop();
    }

    getScore(){
        return this.BODY.length;
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
            this.render2d();
            this.render3d();
            if (!alive) {
                clearInterval(this.intervalId);
            }
        }, this.tick);
    }

    stop(){
        clearInterval(this.intervalId);
        this.intervalId = null;
    }

    render2d() {
        const ctx = this.ctx;
        ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        console.log("help")

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


    render3d() {}
     
}







class Snake3D {
    constructor(width, height) {
        this.width = width;
        this.height = height;
        this.body3d = [];

        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);
        this.renderer = new THREE.WebGLRenderer({ canvas: document.getElementById('canva2') });
        this.renderer.setSize(window.innerWidth, window.innerHeight);


        this.controls = new OrbitControls(this.camera, this.renderer.domElement);

        this.camera.position.set(width/2, 10, height/2);
        this.camera.lookAt(new THREE.Vector3(width/2, 0, height/2));

        const light = new THREE.DirectionalLight(0xffffff, 1);
        light.position.set(5,5,5);
        this.scene.add(light);

        // LOAD MODELS
        this.loader = new GLTFLoader();
        this.head3d = null;
        this.body3d = [];
        this.apple3d = null;

        this.initPlateau();
        this.loadModels();

        this.animate();
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

    update(plateau, body) {
        if (!this.head3d || !this.bodyTemplate) return;
        
        
        if (this.apple3d) {
            for (let y=0; y<plateau.length; y++) {
                for (let x=0; x<plateau[0].length; x++) {
                    if (plateau[y][x] === Snake.APPLE) {
                        this.apple3d.position.set(x, 0.5, y);
                    }
                }
            }
        }

        while (this.body3d.length < body.length - 1) {
          const segment = this.bodyTemplate.clone(true);
          segment.scale.set(0.2, 0.2, 0.2);
          this.scene.add(segment);
          this.body3d.push(segment);
        }

        if (this.head3d) {
            const [hx, hy] = body[0];
            this.head3d.position.set(hx, 0.5, hy);
        }
        for (let i = 1; i < body.length; i++) {
            const [x, y] = body[i];
            this.body3d[i - 1].position.set(x, 0.5, y);
        }
    }

    animate = () => {
        requestAnimationFrame(this.animate);
        this.controls.update();
        this.renderer.render(this.scene, this.camera);
    }
}


console.log("Snake start !");
const snake = new Snake(10, 10, 150);
const snake3d = new Snake3D(10, 10);

snake.render3d = function() {
    snake3d.update(this.plateau, this.BODY);
};

snake.play();
