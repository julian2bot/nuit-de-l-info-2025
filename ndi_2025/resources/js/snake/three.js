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


    render3d() {
      // Exemple cube
      const scene = new THREE.Scene();
      const camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);
      const myCanvas = document.getElementById('canva2');
      const renderer = new THREE.WebGLRenderer({ canvas: myCanvas });
      const center = new THREE.Vector3(0, 0, 0);

      renderer.setSize(window.innerWidth, window.innerHeight);
      document.body.appendChild(renderer.domElement);

      function alload(){
          loader.load( '/model/boite.glb', function ( gltf ) {

            tetesnake = gltf.scene;
            backsnake=gltf.scene;
            scene.add( tetesnake );

          }, undefined, function ( error ) {

            console.error( error );

          } );

          loader.load( '/model/fruit.glb', function ( gltf ) {
            fruit=gltf.scene;
            fruit.scale.set(0.5, 0.5, 0.5);
            fruit.position.x =1;
            scene.add( fruit );

          }, undefined, function ( error ) {

            console.error( error );

          } );

      };


      window.addEventListener('resize', () => {
          // Mise à jour de la taille du renderer
          renderer.setSize(window.innerWidth, window.innerHeight);

          // Mise à jour de l'aspect de la caméra
          camera.aspect = window.innerWidth / window.innerHeight;
          camera.updateProjectionMatrix();
      });

      // OrbitControls
      const controls = new OrbitControls(camera, renderer.domElement);

      let tetesnake = null;
      let backsnake = null;
      let fruit = null;
      let cube = null;
      let cases = {};
      let listecube = [];


      function deplacement3d(){
        
      };


      function wtf(){
        for (let y = 0; y < this.height; y++) {
            for (let x = 0; x < this.width; x++) {
                const val = this.plateau[y][x];
                if (val === Snake.APPLE) fruit.posi = '#ff4d4d';
                else if (val === Snake.HEAD) ctx.fillStyle = '#0b6623';
                else if (val === Snake.BODY) ctx.fillStyle = '#26a269';
                else if (val === Snake.TAIL) ctx.fillStyle = '#1b7a4a';

                ctx.fillRect(x * this.cellW, y * this.cellH, this.cellW, this.cellH);
                ctx.strokeStyle = '#e6e6e6';
                ctx.strokeRect(x * this.cellW, y * this.cellH, this.cellW, this.cellH);
            }
        }
      }


      function dessineplateau(){

        console.log();
        console.log();

        let x,y =0;
        const geometry = new THREE.BoxGeometry();
        const material = new THREE.MeshStandardMaterial({ color: 0x0077ff });
        

        for (let index = 0; index < snake.getPlateau().length; index++) {
          if(index!=0){
            y+=1;
          }
          x=0;
          for (let index2 = 0; index2 < snake.getPlateau()[0].length; index2++) {
              x+=1;
              cube = new THREE.Mesh(geometry, material);
              cube.position.set(x, 0, y);
              cases.cube =cube;
              cases.objet =[];
              listecube.push(cases);
              scene.add(cases.cube);
          }

        }





      }

      const light = new THREE.DirectionalLight(0xffffff, 1);
      light.position.set(5,5,5);
      scene.add(light);

      camera.position.set(0, 10, 0);
      camera.lookAt(center);

      const loader = new GLTFLoader();

      alload();
      dessineplateau();


      function animate(){
          requestAnimationFrame(animate);
          controls.update();
          renderer.render(scene, camera);
      }
      animate();


      





    }

}






console.log("Snake start !");
const snake = new Snake(10, 10, 500);
snake.play();


