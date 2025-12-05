<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorateur de fichier</title>
    <style>
        :root {
            --win-blue: #0078d7;
            --win-hover: #e5f3ff;
            --win-select: #cce8ff;
            --border-color: #d9d9d9;
            --header-bg: #f9f9f9;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #333; /* Dark background behind the window */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* --- Window Container --- */
        .window {
            width: 100vw;
            height: 100vh;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid #555;
        }

        /* --- Title Bar --- */
        .title-bar {
            background: #fff;
            padding: 8px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .window-controls span {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 8px;
            cursor: pointer;
        }
        .red { background: #ff5f56; }
        .yellow { background: #ffbd2e; }
        .green { background: #27c93f; }

        /* --- Toolbar / Address Bar --- */
        .toolbar {
            display: flex;
            padding: 10px;
            background: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
            gap: 10px;
            align-items: center;
        }

        .nav-buttons button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #555;
        }
        .nav-buttons button:hover { color: #000; }

        .address-bar {
            flex-grow: 1;
            border: 1px solid var(--border-color);
            background: #fff;
            padding: 4px 10px;
            font-size: 13px;
            display: flex;
            align-items: center;
        }

        .search-bar {
            width: 150px;
            border: 1px solid var(--border-color);
            background: #fff;
            padding: 4px 10px;
            font-size: 13px;
            color: #777;
        }

        /* --- Main Layout --- */
        .window-body {
            display: flex;
            flex-grow: 1;
            height: 100%;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 180px;
            background: #f0f0f0;
            border-right: 1px solid var(--border-color);
            padding-top: 10px;
            font-size: 13px;
        }

        .sidebar-item {
            padding: 6px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #444;
        }

        .sidebar-item:hover { background-color: #e0e0e0; }
        .sidebar-item.active { background-color: #dadada; font-weight: 500; }

        /* --- File Grid View --- */
        .main-content {
            flex-grow: 1;
            padding: 10px;
            background: #fff;
            overflow-y: auto;
        }

        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 10px;
        }

        .file-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            border: 1px solid transparent;
            cursor: default;
            text-align: center;
        }

        .file-item:hover {
            background-color: var(--win-hover);
            border-color: var(--win-hover);
        }

        .file-item.selected {
            background-color: var(--win-select);
            border-color: var(--win-select);
        }

        .icon {
            font-size: 32px;
            margin-bottom: 5px;
        }

        /* Specific Icon Colors via emoji or simple styling */
        .folder-icon { color: #ffe680; text-shadow: 0 1px 1px #dcb329; }
        .file-icon { color: #555; }

        .file-name {
            font-size: 12px;
            word-break: break-word;
            line-height: 1.3;
        }

        /* Status Bar */
        .status-bar {
            padding: 4px 10px;
            background: #fff;
            border-top: 1px solid var(--border-color);
            font-size: 11px;
            color: #666;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <div class="window">

        <div class="toolbar">
            <div class="nav-buttons">
                <button onclick="goUp()">⬆</button>
            </div>
            <div class="address-bar">
                <span style="margin-right: 5px">📁</span>
                <span id="address-text">This PC > Documents</span>
            </div>
            <div class="search-bar">Search</div>
        </div>

        <div class="window-body">
            <div class="sidebar">
                <div class="sidebar-item" onclick="navigateToRoot('Desktop')">🖥️ Desktop</div>
                <div class="sidebar-item active" onclick="navigateToRoot('Documents')">📄 Documents</div>
                <div class="sidebar-item" onclick="navigateToRoot('Downloads')">⬇️ Downloads</div>
                <div class="sidebar-item" onclick="navigateToRoot('Pictures')">🖼️ Pictures</div>
                <div class="sidebar-item" onclick="navigateToRoot('Music')">🎵 Music</div>
            </div>

            <div class="main-content">
                <div class="file-grid" id="file-grid">
                    </div>
            </div>
        </div>

        <div class="status-bar">
            <span id="item-count">0 items</span>
            <span>FakeFS v1.0</span>
        </div>
    </div>

    <script src="/js/os/score.js"></script>
    <script>
        const fileSystem = {
            "Documents": {
                type: "folder",
                children: [
                    { name: "Personal", type: "folder", children: [
                        { name: "mot_de_passe.txt", type: "file", ext: "txt", link: "/editeur-texte-mdp", nb_points: 100 }
                    ]},
                    { name : "PRIVÉ", type:'folder', children: [
        { name: "Shortcut to Chrome", type: "file", ext: "exe" },

        {
            name: "New Folder",
            type: "folder",
            children: [

                { name: "dossier_pas_louche", type: "folder", children: [] },

                { name: "le_vrai_dossier", type: "folder", children: [
                    { name: "ou_pas_finalement", type: "folder", children: [] }
                ]},

                { name: "pls_ouvre_pas", type: "folder", children: [
                    { name: "trop_tard", type: "folder", children: [
                        { name: "encore_pire", type: "folder", children: [] }
                    ]}
                ]},

                { name: "ratio", type: "folder", children: [] },

                { name: "skibidi_folder", type: "folder", children: [
                    { name: "ohio_lvl1", type: "folder", children: [
                        { name: "ohio_lvl2", type: "folder", children: [
                            { name: "ohio_lvl3", type: "folder", children: [] }
                        ]}
                    ]}
                ]},

                { name: "brain_time", type: "folder", children: [
                    { name: "more_brain", type: "folder", children: [
                        { name: "even_more_brain", type: "folder", children: [
                            { name: "ultimate_brain", type: "folder", children: [] }
                        ]}
                    ]}
                ]},

                { name: "exploration_interdite", type: "folder", children: [
                    { name: "encore_plus_interdit", type: "folder", children: [
                        { name: "bon_tu_l_auras_voulu", type: "folder", children: [
                            { name: "fin_du_game", type: "folder", children: [
                                { name: "message_final.txt", type: "file", ext: "txt", link: "/editeur-texte-secret", nb_points: 200 }
                            ]}
                        ]}
                    ]}
                ]}

            ]
        }
    ]
}
                ]
            },
            "Bureau": {
                type: "folder",
                children: [
                    { name: "Shortcut to Chrome", type: "file", ext: "exe" },
                    { name: "New Folder", type: "folder", children: [] }
                ]
            },
            "Downloads": { type: "folder", children: [
                { name: "InstallLinuxNIRD.exe", type: "file", ext: "exe", action: () => window.parent.bootLinux() }
            ]},
            "Pictures": {
                type: "folder",
                children: [
                    { name: "photo-de-nous.png", type: "file", ext: "img", link: "/images/photo_nous.jpg", nb_points: 100 },
                    { name: "push-magique.png", type: "file", ext: "img", link: "/images/pushMagique.png", nb_points: 50 },
                    { name: "sauccisonGEANNNNT.jpg", type: "file", ext: "img", link: "/images/sauccisonGEANNNNT.jpg", nb_points: 50 },
                    { name: "travail.png", type: "file", ext: "img", link: "/images/travail.png", nb_points: 50 },
                    { name: "chris.png", type: "file", ext: "img", link: "/images/chris.png", nb_points: 50 },
                    { name: "chris.png", type: "file", ext: "img", link: "/images/chris.png", nb_points: 50 },
                ]
            },
            "Music": { type: "folder", children: [
                { name: "banger.mp3", type: "file", ext: "mp4", link: "/lecteur-audio", nb_points: 100 }
            ] }
        };

        // --- 2. State Management ---
        let currentPath = ["Documents"]; // Start at Documents
        let currentFolder = fileSystem["Documents"];

        // --- 3. Render Function ---
        const grid = document.getElementById('file-grid');
        const addressText = document.getElementById('address-text');
        const itemCount = document.getElementById('item-count');

        function render() {
            grid.innerHTML = ""; // Clear current view

            // Update Address Bar
            addressText.innerText = "This PC > " + currentPath.join(" > ");

            // Render Children
            const items = currentFolder.children || [];
            itemCount.innerText = `${items.length} items`;

            if (items.length === 0) {
                grid.innerHTML = `<div style="color:#aaa; width:100%; text-align:left; padding:20px;">This folder is empty.</div>`;
                return;
            }

            items.forEach(item => {
                const el = document.createElement('div');
                el.className = 'file-item';
                el.onclick = () => selectItem(el);

                // Double click to open folder
                if (item.type === "folder") {
                    el.ondblclick = () => enterFolder(item);
                } else if (item.link) {
                    if (item.nb_points) {
                         el.ondblclick = () => {
                            window.parent.openLogiciel({
                                width: 700,
                                height: 400,
                                fullScreen: false,
                                title: item.name,
                                link: item.link,
                                reduire: true,
                                resizable: true
                            });
                           sendScore('easterEgg', item.name, item.nb_points);
                         };
                    } else {
                        el.ondblclick = () => window.parent.openLogiciel({
                            width: 700,
                            height: 400,
                            fullScreen: false,
                            title: item.name,
                            link: item.link,
                            reduire: true,
                            resizable: true
                        });
                    }
                } else {
                    el.ondblclick = item.action;
                }

                // Determine Icon
                let icon = "📄";
                if (item.type === "folder") icon = "📁";
                else if (item.ext === "img") icon = "🖼️";
                else if (item.ext === "exe") icon = "💾";
                else if (item.ext === "mp4") icon = "🎵";

               el.innerHTML = `
                    <div class="icon ${item.type === 'folder' ? 'folder-icon' : 'file-icon'}">${icon}</div>
                    <div class="file-name">${item.name}</div>
                `;
                grid.appendChild(el);
            });
        }

        // --- 4. Navigation Logic ---

        function enterFolder(folderItem) {
            // Find the actual folder object in the current children
            const folderObj = currentFolder.children.find(c => c.name === folderItem.name);
            if (folderObj) {
                currentPath.push(folderObj.name);
                currentFolder = folderObj;
                render();
            }
        }

        function goUp() {
            if (currentPath.length > 1) {
                currentPath.pop();
                // Re-traverse from root to find parent (simple method)
                let tempFolder = fileSystem[currentPath[0]];
                for (let i = 1; i < currentPath.length; i++) {
                    tempFolder = tempFolder.children.find(c => c.name === currentPath[i]);
                }
                currentFolder = tempFolder;
                render();
            }
        }

        function navigateToRoot(rootName) {
            currentPath = [rootName];
            currentFolder = fileSystem[rootName];

            // Visual sidebar active state update
            document.querySelectorAll('.sidebar-item').forEach(el => {
                el.classList.remove('active');
                if(el.innerText.includes(rootName)) el.classList.add('active');
            });

            render();
        }

        function selectItem(element) {
            // Remove selected class from all
            document.querySelectorAll('.file-item').forEach(el => el.classList.remove('selected'));
            // Add to clicked
            element.classList.add('selected');
        }

        // Initial Render
        render();

    </script>
</body>
</html>
