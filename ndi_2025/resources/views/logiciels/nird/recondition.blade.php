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
<h3>1. Réduire l'impact environnemental</h3>

<p>Le reconditionnement est l'un des moyens les plus efficaces pour diminuer l'empreinte écologique du numérique. Produire un ordinateur neuf nécessite :</p>

<ul>
    <li>des matières premières difficiles à extraire (métaux rares, plastique, silicium),</li>
    <li>des milliers de litres d'eau,</li>
    <li>beaucoup d'énergie pour la fabrication et le transport.</li>
</ul>

<p>En réutilisant et en réparant un appareil déjà existant, on évite la production d'un nouveau modèle, ce qui réduit considérablement les émissions de CO₂ et les déchets électroniques, aujourd'hui l'un des flux de déchets qui augmente le plus rapidement dans le monde.</p>

<h3>2. Prolonger la durée de vie du matériel</h3>

<p>Le matériel informatique moderne est souvent remplacé alors qu'il pourrait encore fonctionner plusieurs années. Le reconditionnement permet :</p>

<ul>
    <li>de réparer ce qui est défaillant,</li>
    <li>de remplacer certains composants (SSD, batterie…),</li>
    <li>de redonner une seconde jeunesse à des appareils parfaitement fonctionnels.</li>
</ul>

<p>Avec un système comme GNU/Linux, moins exigeant en ressources, ces machines restent fluides et performantes pendant longtemps.</p>

<h3>3. Une économie réelle pour les particuliers, les écoles et les collectivités</h3>

<p>Le matériel reconditionné est souvent entre 30 % et 70 % moins cher que le neuf, tout en offrant des performances largement suffisantes pour les usages quotidiens : navigation, bureautique, éducation, multimédia, programmation, etc.</p>

<p>Pour les établissements scolaires ou les collectivités, cela permet :</p>

<ul>
    <li>d'équiper plus d'élèves,</li>
    <li>de réduire les coûts d'investissement,</li>
    <li>de consacrer davantage de budget à la formation ou aux projets éducatifs.</li>
</ul>
`

const text2 = `
<h3>4. Renforcer l'indépendance et la résilience numérique</h3>

<p>Couplé à des logiciels libres ou à Linux, le reconditionné libère des contraintes matérielles imposées par certains systèmes propriétaires qui deviennent rapidement obsolètes.</p>

<p>Cela permet :</p>

<ul>
    <li>de garder un contrôle total sur le matériel,</li>
    <li>d'éviter les mises à jour forcées qui ralentissent les machines,</li>
    <li>d'allonger la durée de vie des appareils sans dépendre de cycles commerciaux.</li>
</ul>

<h3>5. Encourager une économie locale et solidaire</h3>

<p>De nombreuses structures locales reconditionnent du matériel : associations, entreprises d'insertion, ateliers de réparation, ressourceries, etc.</p>

<p>Acheter reconditionné contribue donc à :</p>

<ul>
    <li>soutenir des acteurs de l'économie circulaire,</li>
    <li>favoriser l'emploi local,</li>
    <li>valoriser la réparation plutôt que le remplacement systématique.</li>
</ul>

<h3>6. Un geste éducatif et citoyen</h3>

<p>Utiliser du matériel reconditionné sensibilise élèves, familles et citoyens :</p>

<ul>
    <li>à l'impact réel du numérique sur la planète,</li>
    <li>à la nécessité de réduire les déchets électroniques,</li>
    <li>à la valeur de la réparation et du réemploi.</li>
</ul>

<p>C'est un choix cohérent avec une démarche de sobriété numérique et de responsabilité écologique.</p>

<h3>En résumé</h3>

<p>Le matériel reconditionné est bon pour la planète, bon pour le budget, bon pour les écoles et bon pour la société. C'est un choix écologique, économique, durable et éthique, particulièrement pertinent dans un monde où le numérique doit devenir plus responsable.</p>`


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
