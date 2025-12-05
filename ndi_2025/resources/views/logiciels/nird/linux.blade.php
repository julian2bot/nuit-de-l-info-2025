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

<p>Linux est souvent préféré à Windows dans le cadre scolaire et pour une politique de numérique durable car il réduit les coûts, prolonge la durée de vie du matériel (reconditionnement), offre plus de contrôle sur les données, favorise les logiciels libres et la souveraineté numérique, et s'intègre naturellement dans une démarche éducative tournée vers l'inclusion et l'écologie. Ces points sont au cœur de la démarche NIRD.</p>

<h3>Sécurité & respect de la vie privée</h3>

<p>Linux propose un modèle de sécurité différent : mises à jour centralisées via les dépôts, privilèges utilisateur bien séparés, et un écosystème de logiciels souvent auditable (open source). Dans un contexte scolaire cela facilite la maîtrise des flux de données et la réduction des dépendances à des services propriétaires.</p>

<ul>
    <li>Moins d'exécution automatique de logiciels propriétaires ; gestion fine des droits.</li>
    <li>Possibilité d'auditer et d'adapter le code (transparence).</li>
</ul>

<h3>Coût & autonomie</h3>

<p>Les distributions Linux et les logiciels libres sont majoritairement gratuits ; cela réduit les coûts de licences pour les établissements et les collectivités. L'autonomie technique permet aussi d'éviter des verrous commerciaux. La démarche NIRD met en avant cet avantage comme levier d'inclusion.</p>

<ul>
    <li>Réduction des licences logicielles et coûts de renouvellement.</li>
    <li>Réduction du budget via le reconditionnement (voir section « Durabilité »).</li>
</ul>

<h3>Performance & réemploi du matériel</h3>

<p>De nombreuses distributions Linux sont légères et fonctionnent correctement sur du matériel ancien : c'est l'un des usages concrets du reconditionnement en milieu scolaire (établissement passe de Windows à Linux pour prolonger la vie des PC). Cela répond directement à l'enjeu d'obsolescence et à la fracture numérique.</p>

`

const text2 = `

<h3>Durabilité & écologie</h3>

<p>Prolonger la durée de vie des ordinateurs (reconditionnement sous Linux) est une réponse tangible à la réduction des déchets électroniques et s'inscrit dans une stratégie numérique durable. La démarche NIRD recommande explicitement ces pratiques.</p>

<h3>Linux est une base</h3>

<p>Linux n'est pas un système d'exploitation unique, mais une base, un noyau sur lequel sont construits des dizaines d'OS différents appelés distributions. Chaque distribution adapte Linux à un usage particulier grâce à des outils, une interface et des choix techniques spécifiques.</p>

<p>Par exemple, pour l'usage scolaire, des distributions comme PrimTux, Ecosystem GNU/Linux ou Ubuntu Éducation proposent un environnement simple, stable et équipé d'outils pédagogiques.</p>

<p>Pour un usage quotidien, des systèmes comme Ubuntu, Linux Mint, Fedora ou elementary OS offrent une interface intuitive, fluide et accessible à tous.</p>

<p>Enfin, pour le gaming, des distributions comme Pop!_OS, Nobara ou SteamOS sont optimisées pour les performances graphiques, le support des pilotes et l'utilisation de plateformes comme Steam ou Lutris.</p>

<p>Ainsi, Linux sert de fondation polyvalente permettant de créer des systèmes adaptés à chaque besoin, du plus simple au plus exigeant.</p>

<h3>Contrôle, personnalisation et pédagogie</h3>

<p>Linux permet d'adapter l'environnement aux besoins pédagogiques : images système standardisées, clés USB bootables, configurations sur mesure pour ateliers, sandbox pour les TP, et possibilité d'impliquer les élèves dans le reconditionnement (compétences techniques, citoyenneté numérique). Ce sont des atouts pédagogiques majeurs mis en avant par NIRD.</p>

<ul>
    <li>Montée en compétences : administration, scripting, sécurité, contribution aux communs.</li>
    <li>Création et partage de ressources libres via la « forge » éducative.</li>
</ul>
`

const text3=`
<h3>Communauté & soutien</h3>

<p>L'écosystème du logiciel libre dispose d'une communauté active (listes, forums, documentations, associations, entreprises) qui accompagne les projets éducatifs. Plusieurs structures locales et académiques partagent retours d'expérience et tutoriels.</p>

<h3>Arguments courts</h3>

<ul>
    <li><b>Économique :</b> moins de licences, matériel reconditionné → économies immédiates.</li>
    <li><b>Pédagogique :</b> projet concret pour les élèves (reconditionnement, administration, logiciel libre).</li>
    <li><b>Écologique :</b> réduction des déchets électroniques par réemploi.</li>
    <li><b>Souveraineté :</b> maîtrise des données et environnement technique.</li>
    <li><b>Durabilité :</b> moderniser sans remplacer systématiquement le parc.</li>
</ul>

<h3>Conclusion</h3>

<p>Dans un objectif de numérique inclusif, responsable et durable (NIRD), Linux est un levier pragmatique : il rend possible la réutilisation du matériel, diminue les coûts et les verrous propriétaires, et offre un terrain pédagogique riche. Pour les établissements qui veulent réduire leur empreinte écologique, augmenter l'autonomie et renforcer la citoyenneté numérique, la bascule progressive vers Linux est un choix solide — à condition d'être accompagné et planifié.</p>`

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
