import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import Snake from './class/Snake.js';


// Exemple cube
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ antialias: true });
const center = new THREE.Vector3(0, 0, 0);


renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);


window.addEventListener('resize', () => {
    // Mise à jour de la taille du renderer
    renderer.setSize(window.innerWidth, window.innerHeight);

    // Mise à jour de l'aspect de la caméra
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
});

// OrbitControls
const controls = new OrbitControls(camera, renderer.domElement);

const geometry = new THREE.BoxGeometry();
const material = new THREE.MeshStandardMaterial({ color: 0x0077ff });
const cube = new THREE.Mesh(geometry, material);
scene.add(cube);

const light = new THREE.DirectionalLight(0xffffff, 1);
light.position.set(5,5,5);
scene.add(light);

camera.position.set(0, 10, 0);
camera.lookAt(center);

const loader = new GLTFLoader();

let objet1;
let objet2;

loader.load( '/model/boite.glb', function ( gltf ) {

  objet1 = gltf.scene;
  scene.add( objet1 );

}, undefined, function ( error ) {

  console.error( error );

} );

loader.load( '/model/fruit.glb', function ( gltf ) {
  objet2=gltf.scene;
  objet2.scale.set(0.5, 0.5, 0.5);
  objet2.position.x =1;
  scene.add( objet2 );

}, undefined, function ( error ) {

  console.error( error );

} );

function animate(){
    requestAnimationFrame(animate);
    cube.rotation.x += 0.01;
    cube.rotation.y += 0.01;

    objet2.rotation.y += 0.01;

    objet1.rotation.y -= 0.01;

    controls.update();
    renderer.render(scene, camera);
}
animate();
