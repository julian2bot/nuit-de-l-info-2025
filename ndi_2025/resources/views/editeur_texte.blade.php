<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editeur de texte - Presentation du NIRD</title>
    <style>
        /* --- 1. Basic Reset & Background --- */
        body {
            background-color: #f3f3f3; /* Darker gray for contrast */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        /* --- 2. Toolbar Styling --- */
        .toolbar {
            width: 100%;
            background: #fff;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: center;
            gap: 10px;
            border-bottom: 1px solid #ccc;
        }

        .toolbar button {
            background: white;
            border: 1px solid #ddd;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
            font-weight: 600;
            color: #444;
        }

        .toolbar button:hover {
            background-color: #eef;
            border-color: #bce;
        }

        /* --- 3. The Page Container (The "Desk") --- */
        #document-container {
            padding: 40px 0;
            overflow-y: auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px; /* Space between pages */
        }

        /* --- 4. The A4 Page Visuals --- */
        .page {
            width: 210mm;
            min-height: 297mm; /* A4 Height */
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            padding: 25mm; /* Standard margins */
            box-sizing: border-box;
            outline: none;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
            position: relative;
        }

        /* Visual cue for page number */
        .page::after {
            content: attr(data-page-number);
            position: absolute;
            bottom: 10px;
            right: 20px;
            font-size: 10px;
            color: #aaa;
        }

        /* When typing, if it overflows, we hide it visually in this demo
           (Real pagination requires complex JS logic) */
        .page {
            overflow: hidden;
        }

    </style>
</head>
<body>

    <div class="toolbar">
        <button onclick="document.execCommand('bold', false, '');"><b>B</b></button>
        <button onclick="document.execCommand('italic', false, '');"><i>I</i></button>
        <button onclick="document.execCommand('underline', false, '');"><u>U</u></button>
        <div style="width: 1px; background: #ccc; margin: 0 5px;"></div>
        <button onclick="document.execCommand('justifyLeft', false, '');">Left</button>
        <button onclick="document.execCommand('justifyCenter', false, '');">Center</button>
        <button onclick="document.execCommand('justifyFull', false, '');">Justify</button>
    </div>

    <div id="document-container">
        </div>

    <script>
        // --- Initialization Data ---
        const initialText = `
<h2 style="text-align: center;">🌿 NIRD : Pour un numérique scolaire qui a du sens</h2>

<p>Bienvenue dans la communauté <b>NIRD</b> (Numérique Inclusif, Responsable et Durable).</p>

<p>Derrière cet acronyme, il y a avant tout une aventure humaine : celle d'un collectif d'enseignants réunis au sein de la <i>Forge des communs numériques éducatifs</i>. Notre conviction ? Le numérique à l'école ne doit pas être une contrainte, mais une opportunité d'émancipation.</p>

<h3>🎯 Ce qui nous anime</h3>

<p>Nous refusons la fatalité du matériel obsolète et des logiciels fermés. NIRD accompagne les établissements et les collectivités pour bâtir une transition concrète autour de trois idées simples :</p>

<ul>
    <li><b>Retrouver notre liberté :</b> Nous privilégions les <b>Logiciels Libres</b>. Pourquoi ? Pour protéger les données de nos élèves et utiliser des outils qui émancipent, sans enfermer l'école dans des solutions propriétaires.</li>
    <li><b>Faire durer le matériel (Vraiment) :</b> Jeter des ordinateurs encore fonctionnels est un non-sens écologique. Nous prolongeons leur vie en installant <b>Linux</b>, un système léger, rapide et gratuit qui redonne un second souffle à vos PC.</li>
    <li><b>Responsabiliser les élèves :</b> L'écocitoyenneté ne s'apprend pas qu'au tableau. Via des clubs informatiques ou des ateliers de réparation, les élèves deviennent acteurs de leur propre outil de travail.</li>
</ul>

<h3>🚀 Envie d'essayer ?</h3>

<p>Que vous soyez enseignant, chef d'établissement ou élu, vous n'êtes pas seul. NIRD vous offre une distribution Linux clé en main, des ressources pratiques et, surtout, une communauté pour vous épauler à chaque étape.</p>

<p>Ensemble, construisons un numérique plus juste et plus durable.</p>

<p>👉 <b>Retrouvez nos guides et rejoignez le mouvement :</b> <a href="https://nird.forge.apps.education.fr/" target="_blank">https://nird.forge.apps.education.fr/</a></p>`;

        const container = document.getElementById('document-container');

        // --- Function to create a new page ---
        function createPage(pageNum) {
            const page = document.createElement('div');
            page.className = 'page';
            page.contentEditable = true; // Makes the div valid for typing
            page.setAttribute('data-page-number', 'Page ' + pageNum);
            return page;
        }

        // --- Logic to distribute text across pages (Simulated Pagination) ---
        function init() {
            // Create Page 1
            const page1 = createPage(1);

            // In a real sophisticated editor, we would calculate height
            // and split text. For this demo, we will inject the text
            // and add a second page to show the "View".

            page1.innerHTML = initialText;
            container.appendChild(page1);

            // Create Page 2 (Empty for user to continue)
            const page2 = createPage(2);
            page2.innerHTML = "<p><i>(Continue typing here...)</i></p>";
            container.appendChild(page2);
        }

        // Run initialization
        init();

    </script>
</body>
</html>
