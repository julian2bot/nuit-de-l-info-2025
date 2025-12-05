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
<h2>Résumé rapide</h2>

<p>Les logiciels libres et les logiciels créés localement renforcent l'autonomie des usagers, garantissent une meilleure protection des données, réduisent les coûts, soutiennent l'économie locale, facilitent le réemploi du matériel et favorisent l'innovation dans les établissements scolaires et les collectivités.</p>

<h3>Autonomie et souveraineté numérique</h3>

<p>Les logiciels libres ne dépendent pas d'un éditeur unique : le code est ouvert, auditable et modifiable. Cela permet de garder la maîtrise de l'outil utilisé.</p>

<ul>
    <li>Pas de verrouillage propriétaire ou d'abonnement imposé.</li>
    <li>Possibilité d'adapter l'outil aux besoins locaux.</li>
    <li>Capacité de continuer à utiliser et faire évoluer un logiciel même si l'éditeur disparaît.</li>
</ul>

<h3>Sécurité et confidentialité des données</h3>

<p>Avec un logiciel libre, le code peut être vérifié : aucune fonctionnalité cachée ou exploitation commerciale forcée des données.</p>

<ul>
    <li>Transparence totale du fonctionnement.</li>
    <li>Contrôle sur la localisation et la conservation des données.</li>
    <li>Pas d'exploitation publicitaire ou marketing des usages.</li>
</ul>

<p>Les solutions créées localement peuvent être hébergées sur des serveurs institutionnels, garantissant la conformité RGPD et un suivi réel.</p>

`

const text2 = `
<h3>Coût et durabilité économique</h3>

<p>Les logiciels libres sont la plupart du temps gratuits : aucune licence annuelle, pas de surcoût par poste, ni de dépendance à des modèles par abonnement.</p>

<ul>
    <li>Réduction immédiate des dépenses logicielles.</li>
    <li>Meilleure prévisibilité budgétaire (pas de hausses tarifaires imposées).</li>
    <li>Capacité à mutualiser les développements entre établissements et collectivités.</li>
</ul>

<p>Les logiciels créés localement font travailler des acteurs du territoire, renforcent l'emploi local et évitent la fuite budgétaire vers des multinationales du numérique.</p>

<p>Il est également possible de contacter des écoles comme les IUT Informatiques, qui seront ravies de confier vos projets à des élèves compétents et ainsi offrir une expérience professionnelle concrète tout en apportant une solution locale, innovante et économique à votre organisation en plus de permettre aux élèves de réaliser un projet concret !</p>

<h3>Compatibilité et longévité du matériel</h3>

<p>Les logiciels libres sont souvent optimisés pour fonctionner sur une grande variété de matériels, y compris anciens ou reconditionnés. Ils permettent de prolonger la durée de vie des équipements, ce qui réduit les déchets électroniques et les coûts.</p>

<ul>
    <li>Fonctionne sur du matériel plus ancien.</li>
    <li>Moins de besoin en ressources que certains logiciels propriétaires.</li>
    <li>Facilite la stratégie de reconditionnement (ex : NIRD).</li>
</ul>

<h3>Innovation et adaptabilité</h3>

<p>Le libre encourage l'expérimentation et la collaboration. Un logiciel libre peut être modifié, amélioré, traduit, adapté, partagé. C'est un terrain fertile pour l'innovation.</p>

<ul>
    <li>Développement agile et réactif.</li>
    <li>Adaptation aux réalités pédagogiques locales.</li>
    <li>Création d'outils sur mesure pour les besoins du territoire.</li>
</ul>`

const text3=`
<h3>Pédagogie et culture numérique</h3>

<p>Les logiciels libres permettent aux élèves, enseignants et citoyens d'apprendre comment fonctionne réellement un outil numérique. On passe d'un rôle de simple consommateur à un rôle d'acteur.</p>

<ul>
    <li>Compréhension du code, de la collaboration et des contributions.</li>
    <li>Éducation à la citoyenneté numérique : autonomie, esprit critique, partage.</li>
    <li>Possibilité d'impliquer les élèves dans des projets réels (forge, développement local).</li>
</ul>

<h3>Éthique et valeurs</h3>

<p>Le logiciel libre repose sur des valeurs fortes : partage, coopération, transparence, équité. Les logiciels locaux, eux, défendent les valeurs du territoire et de la communauté.</p>

<ul>
    <li>Respect de l'utilisateur et de ses droits.</li>
    <li>Indépendance vis-à-vis des grandes plateformes.</li>
    <li>Construction de communs numériques accessibles à tous.</li>
</ul>

<h3>Conclusion</h3>

<p>Utiliser des logiciels libres et des logiciels créés localement, c'est faire le choix d'un numérique plus souverain, plus durable, plus éthique et plus accessible. Cela permet de soutenir l'innovation locale, de protéger les données, de réduire les coûts et de renforcer la liberté d'usage. Dans les écoles comme dans les collectivités, ce choix s'inscrit naturellement dans une démarche numérique responsable — comme le propose NIRD.</p>`

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

            const page3 = createPage(3);
            page3.innerHTML = text3;
            container.appendChild(page3);

            const page4 = createPage(4);
            page4.innerHTML = "<p><i>(Continue typing here...)</i></p>";
            container.appendChild(page4);
        }
        // Run initialization
        init();

    </script>
</body>
</html>
