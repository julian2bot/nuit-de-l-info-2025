document.addEventListener("DOMContentLoaded",()=>{


const lockScreen = document.getElementById('lockScreen');
const loginScreen = document.getElementById('loginScreen');
const timeEl = document.getElementById('time');
const dateEl = document.getElementById('date');

// Déverrouillage
function unlockScreen() {
    lockScreen.style.transform = "translateY(-100%)";
    loginScreen.style.top = "0";
}

document.addEventListener('keydown', unlockScreen);
document.addEventListener('click', unlockScreen);



// Horloge
function startClock() {
    function updateClock() {
        const now = new Date();
        let h = now.getHours().toString().padStart(2, '0');
        let m = now.getMinutes().toString().padStart(2, '0');
        timeEl.textContent = `${h}:${m}`;

        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateEl.textContent = now.toLocaleDateString('fr-FR', options);
    }
    updateClock();
    setInterval(updateClock, 1000);
}
});
