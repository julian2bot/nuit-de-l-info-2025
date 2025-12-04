// Draggable square using Pointer Events
const square = document.getElementById('square');
const stage = document.getElementById('stage');


const win = document.getElementById("window");
const topbar = document.getElementById("window-topbar");

let dragging = false;
let offset = { x: 0, y: 0 };
let pos = { x: 0, y: 0 };

function applyPos() {
    win.style.transform = `translate(${pos.x}px, ${pos.y}px)`;
}

topbar.addEventListener("pointerdown", (e) => {
    dragging = true;
    topbar.setPointerCapture(e.pointerId);

    // Calcul du décalage entre le curseur et le coin de la fenêtre
    const rect = win.getBoundingClientRect();
    offset.x = e.clientX - rect.left;
    offset.y = e.clientY - rect.top;
});

window.addEventListener("pointermove", (e) => {
    if (!dragging) return;

    pos.x = e.clientX - offset.x;
    pos.y = e.clientY - offset.y;

    applyPos();
});

window.addEventListener("pointerup", (e) => {
    dragging = false;
    try { topbar.releasePointerCapture(e.pointerId); } catch (_) {}
});

// Limiter à l’intérieur du stage
function clampToStage(x, y) {
    const s = stage.getBoundingClientRect();
    const w = win.offsetWidth;
    const h = win.offsetHeight;

    return {
        x: Math.min(Math.max(x, 0), s.width - w),
        y: Math.min(Math.max(y, 0), s.height - h)
    };
}

window.addEventListener('pointermove', (e) => {
    if (!dragging) return;

    const nextX = e.clientX - offset.x;
    const nextY = e.clientY - offset.y;

    const clamped = clampToStage(nextX, nextY);
    pos.x = clamped.x;
    pos.y = clamped.y;

    applyPos();
});




import DiscordeApp from "./discorde.js";

const content = document.getElementById("window-content");
new DiscordeApp(content);
