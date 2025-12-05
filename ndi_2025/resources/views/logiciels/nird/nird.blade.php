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
<h2>Qu'est-ce que la démarche NIRD ?</h2>

<p>La démarche NIRD (Numérique Inclusif, Responsable et Durable) vise à promouvoir un numérique scolaire plus inclusif, responsable et durable. Elle s'inscrit dans le cadre de la forge des communs numériques éducatifs, favorisant le partage et la collaboration entre enseignants et établissements.</p>

<ul>
    <li><b>Inclusif :</b> faciliter l'accès au numérique pour tous.</li>
    <li><b>Responsable :</b> adopter des pratiques respectueuses des données et utiliser des logiciels libres.</li>
    <li><b>Durable :</b> limiter l'obsolescence et prolonger la durée de vie du matériel informatique.</li>
</ul>

<h3>Actions concrètes de NIRD</h3>

<ul>
    <li>Passage progressif à des systèmes d'exploitation libres, comme GNU/Linux avec leurs propres systèmes Linux NIRD (Lycée) et PrimTux (École primaire).</li>
    <li>Reconditionnement du matériel informatique pour prolonger sa durée de vie.</li>
    <li>Promotion de l'accès équitable pour tous les élèves et leurs familles.</li>
    <li>Utilisation et partage de ressources éducatives libres via la forge des communs éducatifs.</li>
    <li>Intégration d'une stratégie numérique et écologique dans les établissements.</li>
</ul>

<h3>Enjeux et justification</h3>

<ul>
    <li>Réduire la dépendance à certains systèmes propriétaires et garantir la pérennité.</li>
    <li>Limiter la dépendance technologique et favoriser la souveraineté numérique.</li>
    <li>Assurer un accès numérique équitable à tous les élèves.</li>
    <li>Agir pour l'écologie en réduisant les déchets électroniques.</li>
</ul>
`

const text2 = `
<h3>Portée et statut actuel</h3>

<ul>
    <li>Soutenu par le ministère de l'Éducation nationale.</li>
    <li>Mis en œuvre dans des établissements pilotes, avec objectif d'extension progressive.</li>
    <li>Favorise la collaboration entre établissements, enseignants et élèves via des communs numériques.</li>
    <li>Sensibiliser les élèves et enseignants sur les bonnes démarches à prendre.</li>
</ul>

<h3>Résumé</h3>

<p>La démarche NIRD transforme le numérique scolaire en un projet éducatif, social et écologique. Elle favorise l'inclusion, la responsabilité et la durabilité, tout en donnant aux établissements un contrôle sur leur environnement numérique et en sensibilisant les élèves à un usage réfléchi.</p>
`

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
        
            const page1 = createPage(1);

            page1.innerHTML = initialText;
            container.appendChild(page1);

            const page2 = createPage(2);
            page2.innerHTML = text2;
            container.appendChild(page2);

            const page4 = createPage(3);
            page4.innerHTML = "<p><i>(Continue typing here...)</i></p>";
            container.appendChild(page4);
        }
        // Run initialization
        init();

    </script>
</body>
</html>
