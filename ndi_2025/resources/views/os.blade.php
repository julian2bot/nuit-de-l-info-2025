<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>NIRD - OS pas fou</title>
    <link rel="stylesheet" href="{{ asset('style/os.css')}}">
    <link rel="stylesheet" href="{{ asset('style/discordNotif.css')}}">
</head>

<body>
    <div id="notifbar-discorde"></div>

    <div class="desktop" id="desktopArea">
        <div class="icon" id="browser" data-name="Internet Browser">
            <span class="icon-img">🌐</span>
            <span class="icon-label">Browser</span>
        </div>

        <div class="icon" id="notepad" data-name="Notes">
            <span class="icon-img">📝</span>
            <span class="icon-label">Notes</span>
        </div>

         <div class="icon" id="filesystem" data-name="System Settings">
            <span class="icon-img">📁</span>
            <span class="icon-label">Explorateur de fichier</span>
        </div>

         <div class="icon" id="discorde" data-name="Discorde app">
            <span class="icon-img">📩</span>
            <span class="icon-label">Discorde</span>
        </div>

        <div class="icon" id="snake" data-name="Snake app">
            <span class="icon-img">📦</span>
            <span class="icon-label">Snake</span>
        </div>

        <div class="icon" id="poduim" data-name="poduim app">
            <span class="icon-img">🥉</span>
            <span class="icon-label">Podium</span>

        </div>
        
        <div class="icon" id="nird_site" data-name="nird site">
            <span class="icon-img">🌐</span>
            <span class="icon-label">Site de la NIRD</span>
        </div>

        <div class="icon linux" id="nird" data-name="nird app">
            <span class="icon-img">📝</span>
            <span class="icon-label">Nird présentation</span>
        </div>


        <div class="icon linux" id="linux" data-name="linux app">
            <span class="icon-img">📝</span>
            <span class="icon-label">Pourquoi linux ?</span>
        </div>

        <div class="icon linux" id="libres" data-name="libres app">
            <span class="icon-img">📝</span>
            <span class="icon-label">Les logiciels libres</span>
        </div>

        <div class="icon linux" id="serveur" data-name="serveur app">
            <span class="icon-img">📝</span>
            <span class="icon-label">Héberger ses propres serveurs</span>
        </div>

        <div class="icon linux" id="recondition" data-name="recondition app">
            <span class="icon-img">📝</span>
            <span class="icon-label">Le matériel reconditionné</span>
        </div>
    </div>

    <div class="taskbar" id="taskbar">
        <div class="items-container">
            <div class="start-btn">
                <span class="start-icon">🪟</span> Fenetre
            </div>
        </div>
        <div class="tray">
            <span id="clock">12:00 PM</span>
        </div>
    </div>

    <script src="{{ asset('js/apps/apps.js')}}"></script>
    <script src="{{ asset('js/os/application.js')}}"></script>
    <script src="{{ asset('js/os/notifDiscorde.js')}}"></script>
    <script src="{{ asset('js/os/score.js')}}"></script>


<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        document.getElementById('clock').textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    const icons = document.querySelectorAll('.icon');
    const desktop = document.getElementById('desktopArea');

    icons.forEach(icon => {
        icon.addEventListener('click', (e) => {
            e.stopPropagation();
            if (!e.ctrlKey) {
                 icons.forEach(i => i.classList.remove('selected'));
            }
            icon.classList.add('selected');
        });

        icon.addEventListener('dblclick', (e) => {
            if(typeof openLogiciel === 'function' && typeof apps !== 'undefined') {
                openLogiciel(apps[icon.id]);
            } else {
                console.log("Open Logiciel function missing");
            }
        });
    });

    let isDragging = false;
    let startX = 0;
    let startY = 0;
    let selectionBox = null;

    desktop.addEventListener('mousedown', (e) => {
        if (e.target !== desktop) return;

        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;

        if (!e.ctrlKey) {
            icons.forEach(i => i.classList.remove('selected'));
        }

        selectionBox = document.createElement('div');
        selectionBox.classList.add('marquee-selection');
        selectionBox.style.left = startX + 'px';
        selectionBox.style.top = startY + 'px';
        desktop.appendChild(selectionBox);
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging || !selectionBox) return;

        const currentX = e.clientX;
        const currentY = e.clientY;

        const width = Math.abs(currentX - startX);
        const height = Math.abs(currentY - startY);
        const newLeft = Math.min(currentX, startX);
        const newTop = Math.min(currentY, startY);

        selectionBox.style.width = width + 'px';
        selectionBox.style.height = height + 'px';
        selectionBox.style.left = newLeft + 'px';
        selectionBox.style.top = newTop + 'px';

        checkSelectionCollision(selectionBox);
    });

    document.addEventListener('mouseup', () => {
        if (isDragging) {
            isDragging = false;
            if (selectionBox) {
                selectionBox.remove();
                selectionBox = null;
            }
        }
    });

    function checkSelectionCollision(box) {
        const boxRect = box.getBoundingClientRect();

        icons.forEach(icon => {
            const iconRect = icon.getBoundingClientRect();

            const isOverlapping = !(
                boxRect.right < iconRect.left ||
                boxRect.left > iconRect.right ||
                boxRect.bottom < iconRect.top ||
                boxRect.top > iconRect.bottom
            );

            if (isOverlapping) {
                icon.classList.add('selected');
            } else {
               icon.classList.remove('selected');
            }
        });
    }



    function onIframeAction(data) {
        console.log("Données reçues depuis l'iframe :", data);

        if(data.type === "notif") {
            // alert("Notification depuis l'iframe : " + data.content.message);
            addNotif(data);
        }
        if(data.type === "appReady") {
            var discord = document.getElementById("Discorde_id");
            discord.contentWindow.postMessage({
                action: "windows"
            }, "*");
        }
    }

    // Rendre la fonction accessible globalement pour l'iframe
    window.onIframeAction = onIframeAction;

    

</script>
</body>
</html>
