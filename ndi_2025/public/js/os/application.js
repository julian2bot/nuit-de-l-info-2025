let topZ = 10;
let focusedApp = null;

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
        this.el.appendChild(iframe);

        // Bugged mode
        if (cfg.bugged) {
            iframe.classList.add("bugged");
            this.el.classList.add("bugged");
            title.textContent += " (Ne répond pas)";
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

        this.bringToFront();
    }

    createTaskbarItem() {
        const container = document.querySelector('#taskbar > .items-container');
        if (!container) return;

        this.taskbarItem = document.createElement('div');
        this.taskbarItem.className = 'taskbar-item active';
        this.taskbarItem.textContent = this.config.title || 'Application';
        this.taskbarItem.addEventListener('click', () => {
            if (this.el.style.display === 'none') {
                this.el.style.display = '';
            }
            this.bringToFront();
        });
        container.appendChild(this.taskbarItem);
    }

    close() {
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
            focusedApp.taskbarItem.classList.remove('active');
        }
        topZ++;
        this.el.style.zIndex = topZ;
        focusedApp = this;
        if (this.taskbarItem) {
            this.taskbarItem.classList.add('active');
        }
    }
}

// ----------------------------
//        FONCTION GLOBALE
// ----------------------------
function openLogiciel(json) {
    new Application(json);
}

// ----------------------------
//   LANCER AUTOMATIQUEMENT
// ----------------------------
openLogiciel(apps.notepad);
// openLogiciel(apps.machin);
