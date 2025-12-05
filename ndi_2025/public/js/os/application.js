let topZ = 10;
let focusedApp = null;
let appOpenCount = 0;
let isLinuxMode = false;

// ----------------------------
//       BLUESCREEN FUNCTION
// ----------------------------
function showBluescreen() {
    const bsod = document.createElement("div");
    bsod.id = "bsod";
    bsod.innerHTML = `
        <div class="bsod-content">
            <div class="bsod-emoticon">:(</div>
            <h1>Votre PC a rencontré un problème et doit redémarrer.</h1>
            <p>Nous collectons simplement des informations sur l'erreur.</p>
            <p class="bsod-stop">Code d'arrêt : SYSTEM_SERVICE_EXCEPTION</p>
            <p class="bsod-info">Si vous contactez le support technique, donnez-lui ces informations :<br>Échec de : nird_os.sys</p>
            <div class="bsod-buttons">
                <button id="bsod-restart">Redémarrer</button>
                <button id="bsod-linux" onclick="bootLinux()">Passer à Linux NIRD</button>
            </div>
        </div>
    `;

    // Add BSOD styles
    const style = document.createElement("style");
    style.textContent = `
        #bsod {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #0078d7;
            color: white;
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .bsod-content {
            max-width: 800px;
            padding: 40px;
        }
        .bsod-emoticon {
            font-size: 120px;
            margin-bottom: 20px;
        }
        #bsod h1 {
            font-size: 28px;
            font-weight: 300;
            margin-bottom: 10px;
        }
        #bsod p {
            font-size: 16px;
            margin: 10px 0;
        }
        .bsod-progress {
            font-size: 18px;
            margin: 30px 0;
        }
        .bsod-qr {
            display: inline-block;
            background: white;
            color: black;
            padding: 10px;
            font-family: monospace;
            font-size: 12px;
            margin: 20px 0;
        }
        .bsod-stop {
            font-size: 12px !important;
            margin-top: 30px !important;
        }
        .bsod-info {
            font-size: 12px !important;
        }
        #bsod-restart, #bsod-linux {
            padding: 12px 30px;
            font-size: 16px;
            background: transparent;
            color: white;
            border: 2px solid white;
            cursor: pointer;
            transition: background 0.2s;
        }
        #bsod-restart {
            position: relative;
            transition: transform 0.1s ease;
        }
        #bsod-linux:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .bsod-buttons {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            min-height: 150px;
            align-items: center;
        }
    `;
    document.head.appendChild(style);
    document.body.appendChild(bsod);

    // Make restart button dodge the mouse
    const restartBtn = document.getElementById('bsod-restart');
    restartBtn.addEventListener('mouseover', function() {
        const randomX = (Math.random() - 0.5) * 400;
        const randomY = (Math.random() - 0.5) * 300;
        this.style.transform = `translate(${randomX}px, ${randomY}px)`;
    });
}

// ----------------------------
//    WINDOWS 11 UPGRADE DIALOG
// ----------------------------
function showUpgradeDialog() {
    // Don't show if already exists
    if (document.getElementById("upgrade-dialog")) return;

    const dialog = document.createElement("div");
    dialog.id = "upgrade-dialog";
    dialog.innerHTML = `
        <div class="upgrade-content">
            <div class="upgrade-header">
                <span class="upgrade-icon">⚠️</span>
                <span class="upgrade-title">Windows</span>
                <button class="upgrade-close" onclick="this.parentElement.parentElement.parentElement.remove()">✕</button>
            </div>
            <div class="upgrade-body">
                <p><strong>Votre application ne répond pas.</strong></p>
                <p>Cela peut être dû à une version obsolète de Windows.</p>
                <p>Pour une meilleure expérience, nous vous recommandons de mettre à niveau vers <strong>Windows 11</strong>.</p>
            </div>
            <div class="upgrade-footer">
                <button class="upgrade-btn-secondary" onclick="this.parentElement.parentElement.parentElement.remove()">Me le rappeler plus tard</button>
            </div>
        </div>
    `;

    const style = document.createElement("style");
    style.textContent = `
        #upgrade-dialog {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
        }
        .upgrade-content {
            background: #f0f0f0;
            border-radius: 8px;
            width: 450px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .upgrade-header {
            background: #0078d4;
            color: white;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .upgrade-icon {
            font-size: 20px;
        }
        .upgrade-title {
            flex: 1;
            font-weight: 600;
        }
        .upgrade-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            padding: 5px;
        }
        .upgrade-close:hover {
            background: rgba(255,255,255,0.2);
        }
        .upgrade-body {
            padding: 20px;
            color: #333;
        }
        .upgrade-body p {
            margin: 8px 0;
        }
        .upgrade-features {
            background: #e3f2fd;
            padding: 10px 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .upgrade-features p {
            margin: 5px 0;
        }
        .upgrade-footer {
            padding: 15px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            border-top: 1px solid #ddd;
        }
        .upgrade-btn {
            background: #0078d4;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            font-weight: 600;
        }
        .upgrade-btn:hover {
            background: #006cbd;
        }
        .upgrade-btn-secondary {
            background: transparent;
            color: #666;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
        }
        .upgrade-btn-secondary:hover {
            background: #eee;
        }
    `;
    document.head.appendChild(style);
    document.body.appendChild(dialog);
}

// ----------------------------
//       LINUX BOOT FUNCTION
// ----------------------------
function bootLinux() {
    const bsod = document.getElementById("bsod");
    if (bsod) bsod.remove();

    const bootScreen = document.createElement("div");
    bootScreen.id = "linux-boot";
    bootScreen.innerHTML = `<div class="boot-content"></div>`;

    const style = document.createElement("style");
    style.textContent = `
        #linux-boot {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #000;
            color: #0f0;
            z-index: 999999;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            padding: 20px;
            box-sizing: border-box;
            overflow: hidden;
        }
        .boot-content {
            white-space: pre-wrap;
        }
        .boot-line {
            margin: 2px 0;
        }
        .boot-ok {
            color: #0f0;
        }
        .boot-info {
            color: #fff;
        }
    `;
    document.head.appendChild(style);
    document.body.appendChild(bootScreen);

    const bootMessages = [
        { ok: true, msg: "Starting Linux NIRD 6.1.0..." },
        { ok: true, msg: "Loading kernel modules..." },
        { ok: true, msg: "Mounting root filesystem..." },
        { ok: true, msg: "Checking root filesystem integrity..." },
        { ok: true, msg: "Remounting root filesystem read-write..." },
        { ok: true, msg: "Starting udev daemon..." },
        { ok: true, msg: "Loading hardware drivers..." },
        { ok: true, msg: "Detecting CPU: Intel Core i7-NIRD @ 4.2GHz..." },
        { ok: true, msg: "Detecting RAM: 16GB DDR4..." },
        { ok: true, msg: "Initializing swap partition..." },
        { ok: true, msg: "Starting device mapper..." },
        { ok: true, msg: "Activating LVM volumes..." },
        { ok: true, msg: "Mounting /home partition..." },
        { ok: true, msg: "Mounting /tmp with tmpfs..." },
        { ok: true, msg: "Setting system clock from hardware clock..." },
        { ok: true, msg: "Initializing network interfaces..." },
        { ok: true, msg: "Starting NetworkManager..." },
        { ok: true, msg: "Configuring eth0: DHCP..." },
        { ok: true, msg: "Acquired IPv4 address 192.168.1.42..." },
        { ok: true, msg: "Starting D-Bus system message bus..." },
        { ok: true, msg: "Starting system logger (rsyslog)..." },
        { ok: true, msg: "Starting ACPI daemon..." },
        { ok: true, msg: "Starting Bluetooth service..." },
        { ok: true, msg: "Starting CUPS printing service..." },
        { ok: true, msg: "Starting SSH daemon..." },
        { ok: true, msg: "Starting cron scheduler..." },
        { ok: true, msg: "Loading sound drivers (ALSA)..." },
        { ok: true, msg: "Starting PulseAudio sound server..." },
        { ok: true, msg: "Initializing graphics: NVIDIA GTX NIRD..." },
        { ok: true, msg: "Starting Xorg display server..." },
        { ok: true, msg: "Loading NIRD Desktop Environment..." },
        { ok: true, msg: "Starting window manager..." },
        { ok: true, msg: "Loading desktop icons..." },
        { ok: true, msg: "Starting notification daemon..." },
        { ok: true, msg: "Restoring user session..." },
        { ok: true, msg: "Welcome to Linux NIRD!" },
    ];

    const bootContent = bootScreen.querySelector(".boot-content");
    let i = 0;

    function showNextLine() {
        if (i < bootMessages.length) {
            const line = document.createElement("div");
            line.className = "boot-line";
            const msg = bootMessages[i];
            line.innerHTML = `<span class="boot-ok">[  OK  ]</span> <span class="boot-info">${msg.msg}</span>`;
            bootContent.appendChild(line);
            i++;
            setTimeout(showNextLine, Math.random() * 100);
        } else {
            setTimeout(() => {
                bootScreen.remove();
                switchToLinuxDesktop();
            }, 500);
        }
    }

    showNextLine();
}

// ----------------------------
//    SWITCH TO LINUX DESKTOP
// ----------------------------
function switchToLinuxDesktop() {
    // Enable Linux mode (no crashes)
    isLinuxMode = true;

    // Reset app counter
    appOpenCount = 0;

    // Close all open windows
    document.querySelectorAll(".window").forEach((win) => win.remove());

    // Clear taskbar items (except start button)
    document.querySelectorAll(".taskbar-item").forEach((item) => item.remove());

    // Change background
    const desktop = document.getElementById("desktopArea");
    desktop.style.backgroundImage = 'url("/images/NIRD_wallpaper.png")';
    desktop.style.backgroundColor = "#2d2d2d";

    // Change start button
    const startBtn = document.querySelector(".start-btn");
    if (startBtn) {
        startBtn.innerHTML = '<span class="start-icon">🐧</span> Apps';
        startBtn.style.background = "#ff6600";
    }

    // Update icons for Linux
    const linuxIcons = {
        browser: { emoji: "🦊", label: "Firefox" },
        notepad: { emoji: "📄", label: "GEdit" },
        filesystem: { emoji: "📂", label: "Fichiers" },
        discorde: { emoji: "💬", label: "Discorde" },
        snake: { emoji: "🐍", label: "Snake" },
        poduim: { emoji: "🥇", label: "poduim" },
    };

    Object.keys(linuxIcons).forEach((id) => {
        const icon = document.getElementById(id);
        if (icon) {
            const iconData = linuxIcons[id];
            icon.querySelector(".icon-img").textContent = iconData.emoji;
            icon.querySelector(".icon-label").textContent = iconData.label;
        }
    });

    // Update taskbar style for Linux
    const taskbar = document.getElementById("taskbar");
    if (taskbar) {
        taskbar.style.background = "#333";
    }
}

// ----------------------------
//       CLASS APPLICATION
// ----------------------------
class Application {
    constructor(config) {
        this.config = config;
        this.taskbarItem = null;
        this.createWindow();
        this.createTaskbarItem();
    }

    createWindow() {
        const cfg = this.config;

        // Check if Discord already exists - only for Discord app
        if (cfg.title === "Discorde") {
            const existing = document.getElementById("Discorde_id");
            if (existing) {
                console.log("Discord already open, restoring window");
                existing.parentElement.style.display = "block";
                topZ++;
                existing.parentElement.style.zIndex = topZ;
                
                // Find the existing taskbar item and make it active
                const existingTaskItem = document.getElementById("Discorde_taskid");
                if (existingTaskItem) {
                    existingTaskItem.classList.add("active");
                }
                return;
            }
        }

        // Créer la fenêtre
        this.el = document.createElement("div");
        this.el.className = "window";

        // Dimensions
        if (cfg.fullScreen) {
            this.el.style.width = "100vw";
            this.el.style.height = "100vh";
            this.el.style.left = "0";
            this.el.style.top = "0";
        } else {
            this.el.style.width = cfg.width + "px";
            this.el.style.height = cfg.height + "px";

            // Centrer la fenêtre
            this.el.style.left = (window.innerWidth - cfg.width) / 2 + "px";
            this.el.style.top = (window.innerHeight - cfg.height) / 2 + "px";
        }

        if (cfg.resizable !== false) {
            this.el.classList.add("resizable");
        }

        // En-tête Windows
        const header = document.createElement("div");
        header.className = "window-header";

        const title = document.createElement("div");
        title.className = "title";
        title.textContent = cfg.title || "Application";

        const buttons = document.createElement("div");
        buttons.className = "window-buttons";

        // Réduire
        const btnMin = document.createElement("button");
        btnMin.className = "btn-min";
        btnMin.textContent = "—";
        btnMin.onclick = () => this.minimize();

        // Maximiser
        const btnMax = document.createElement("button");
        btnMax.className = "btn-max";
        btnMax.textContent = "□";
        btnMax.onclick = () => this.toggleFullScreen();

        // Fermer
        const btnClose = document.createElement("button");
        btnClose.className = "btn-close";
        btnClose.textContent = "X";
        btnClose.onclick = () => this.close();

        buttons.append(btnMin, btnMax, btnClose);
        header.append(title, buttons);
        this.el.appendChild(header);

        // Iframe
        const iframe = document.createElement("iframe");
        iframe.src = cfg.link || "/";
        iframe.setAttribute("id", title.textContent+"_id");
        this.el.appendChild(iframe);

        // Bugged mode
        if (cfg.bugged) {
            iframe.classList.add("bugged");
            this.el.classList.add("bugged");
            title.textContent += " (Ne répond pas)";
            showUpgradeDialog();
        }

        iframe.onload = () => {
            try {
                const pageTitle = iframe.contentDocument.title;
                if (pageTitle) {
                    title.textContent = pageTitle;
                    if (cfg.bugged) {
                        title.textContent = pageTitle += " (Ne répond pas)";
                    }
                }
            } catch (error) {
                console.warn(
                    "Cannot read title: Blocked by Cross-Origin Policy",
                );
            }
        };

        document.body.appendChild(this.el);

        this.el.addEventListener("mousedown", () => this.bringToFront());
        this.initDrag(header);
    }

    createTaskbarItem() {
        const container = document.querySelector("#taskbar > .items-container");
        if (!container) return;

        // Check if taskbar item already exists for this app (mainly for Discord)
        const taskId = this.config.title + "_taskid";
        const existingTaskItem = document.getElementById(taskId);
        
        if (existingTaskItem) {
            // Reuse existing taskbar item (for Discord reopening)
            this.taskbarItem = existingTaskItem;
            this.taskbarItem.classList.add("active");
            return;
        }

        // Create new taskbar item
        this.taskbarItem = document.createElement("div");
        this.taskbarItem.className = "taskbar-item active";
        this.taskbarItem.id = taskId;
        this.taskbarItem.textContent = this.config.title || "Application";
        this.taskbarItem.addEventListener("click", () => {
            if (this.el.style.display === "none") {
                this.el.style.display = "";
            }
            this.bringToFront();
        });
        container.appendChild(this.taskbarItem);
    }

    close() {
        // Special handling for Discord - just hide it
        if (this.config.title === "Discorde") {
            this.el.style.display = "none";
            return;
        }

        // Normal close for other apps
        this.el.remove();
        if (this.taskbarItem) {
            this.taskbarItem.remove();
        }
        if (focusedApp === this) {
            focusedApp = null;
        }
    }

    minimize() {
        this.el.style.display = "none";
    }

    toggleFullScreen() {
        const isFull = this.el.classList.toggle("fullscreen");

        if (isFull) {
            this.prev = {
                w: this.el.style.width,
                h: this.el.style.height,
                l: this.el.style.left,
                t: this.el.style.top,
            };

            this.el.style.width = "100vw";
            this.el.style.height = "calc(100vh - 40px)";
            this.el.style.left = "0";
            this.el.style.top = "0";
        } else {
            this.el.style.width = this.prev.w;
            this.el.style.height = this.prev.h;
            this.el.style.left = this.prev.l;
            this.el.style.top = this.prev.t;
        }
    }

    initDrag(header) {
        let isDragging = false;
        let startX, startY;
        let offsetX, offsetY;
        const dragThreshold = 5; // seuil en pixels pour considérer comme drag

        header.addEventListener("mousedown", (e) => {
            startX = e.clientX;
            startY = e.clientY;
            offsetX = e.clientX - this.el.offsetLeft;
            offsetY = e.clientY - this.el.offsetTop;
            header.style.cursor = "grabbing";

            const onMouseMove = (e) => {
                const dx = e.clientX - startX;
                const dy = e.clientY - startY;

                if (
                    !isDragging &&
                    Math.sqrt(dx * dx + dy * dy) > dragThreshold
                ) {
                    // Commence le drag
                    isDragging = true;
                }

                if (isDragging) {
                    this.el.style.left = e.clientX - offsetX + "px";
                    this.el.style.top = e.clientY - offsetY + "px";
                }
            };

            const onMouseUp = (e) => {
                document.removeEventListener("mousemove", onMouseMove);
                document.removeEventListener("mouseup", onMouseUp);
                header.style.cursor = "grab";

                // Si pas de drag, c'est juste un click => bringToFront
                if (!isDragging) {
                    this.bringToFront();
                }

                isDragging = false;
            };

            document.addEventListener("mousemove", onMouseMove);
            document.addEventListener("mouseup", onMouseUp);
        });
    }

    bringToFront() {
        if (focusedApp && focusedApp !== this && focusedApp.taskbarItem) {
            focusedApp.taskbarItem.classList.remove("active");
        }
        topZ++;
        this.el.style.zIndex = topZ;
        focusedApp = this;
        if (this.taskbarItem) {
            this.taskbarItem.classList.add("active");
        }
    }
}

// ----------------------------
//        FONCTION GLOBALE
// ----------------------------
function openLogiciel(json) {
    // console.log(json)
    if (json.title != "Discorde") {
        appOpenCount++;
    }
    

    // Skip crashes in Linux mode
    if (isLinuxMode) {
        new Application(json);
        return;
    }

    // 5th app triggers bluescreen
    if (appOpenCount >= 5) {
        showBluescreen();
        return;
    }

    // 3rd and 4th apps are frozen
    if (appOpenCount >= 3) {
        json = { ...json, bugged: true };
    }

    new Application(json);
}
