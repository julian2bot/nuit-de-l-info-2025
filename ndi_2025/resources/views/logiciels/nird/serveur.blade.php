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

<p>Héberger ses propres services (serveurs physiques ou virtuels gérés localement) permet de reprendre le contrôle des données et des usages, de créer des emplois locaux (administration, maintenance, support), et d'augmenter la sécurité en maîtrisant l'infrastructure. C'est une option particulièrement pertinente pour les collectivités, établissements scolaires et organisations qui veulent soberaineté et résilience.</p>

<h3>Principaux avantages</h3>

<h4>1. Création d'emplois locaux</h4>

<p>Installer et exploiter des serveurs nécessite des compétences humaines : administration système, réseaux, sécurité, support utilisateur, gestion matérielle. Ces activités génèrent des postes techniques et de gestion au niveau local (techs, administrateurs, techniciens de maintenance, formateurs).</p>

<ul>
    <li>Emplois directs : administrateurs, techniciens, ingénieurs.</li>
    <li>Emplois indirects : formation, maintenance, reconditionnement, logistique.</li>
    <li>Opportunités de formation et d'insertion (ateliers, filières locales, partenariats écoles/associations).</li>
</ul>

<h4>2. Indépendance vis-à-vis des plateformes (souveraineté)</h4>

<p>En hébergeant vous-même les services (applications, données, authentification), vous réduisez la dépendance aux grands fournisseurs étrangers ou aux modèles par abonnement. Cela favorise la portabilité des données et la continuité de service.</p>

<ul>
    <li>Contrôle des politiques de rétention et de localisation des données (important pour RGPD).</li>
    <li>Moins de verrouillage commercial ; possibilité de migrer ou d'adapter facilement les services.</li>
    <li>Capacité à choisir des solutions open source et à contribuer aux communs.</li>
    <li>Contrôle global des données</li>
</ul>
`

const text2 = `

<h4>3. Sécurité et confidentialité renforcées</h4>

<p>Maîtriser l'infrastructure facilite la mise en place de politiques de sécurité adaptées : segmentation réseau, chiffrement interne, sauvegardes fiables, audits réguliers. Pour des données sensibles (éducation, santé, administration locale), c'est un avantage majeur.</p>

<ul>
    <li>Possibilité d'appliquer des mesures de sécurité strictes et sur mesure.</li>
    <li>Moins d'exfiltration involontaire vers des tiers si les données restent internes.</li>
    <li>Audits et contrôles plus directs (logs, supervision, procédures d'incident).</li>
</ul>

<h3>Aspects économiques & stratégiques</h3>

<h4>Investissement et coûts</h4>

<p>L'hébergement local implique des coûts (matériel, énergie, locaux, personnel). Toutefois ces coûts peuvent être amortis et devenir compétitifs :</p>

<ul>
    <li>Réduction des factures récurrentes d'abonnements à long terme.</li>
    <li>Réemploi / reconditionnement de matériel pour limiter l'investissement initial.</li>
    <li>Mutualisation entre services d'une même collectivité (gain d'échelle).</li>
</ul>

<h4>Résilience et continuité</h4>

<p>La maîtrise locale permet de bâtir des plans de reprise et redondance adaptés (sauvegardes externes, PRA/PCA, réplication inter-sites). On ne dépend plus d'une seule couche externe.</p>

<h3>Impacts pédagogiques et sociaux</h3>

<p>Un projet local d'hébergement est aussi un projet pédagogique : il permet d'impliquer des élèves ou des citoyens dans l'administration, la sécurité, la gestion des services et la maintenance. C'est une vraie opportunité pour la formation et l'insertion professionnelle.</p>

<ul>
    <li>Ateliers d'initiation à l'administration système.</li>
    <li>Projets pédagogiques autour de la sécurité, de la sauvegarde et du développement local.</li>
    <li>Partenariats avec des structures de réemploi et des acteurs locaux.</li>
</ul>

`

const text3=`
<h3>Bonnes pratiques pour démarrer</h3>

<ul>
    <li>Commencer petit et progresser : prototypes, services non critiques, puis montée en charge.</li>
    <li>Choisir des solutions open source</li>
    <li>Documenter et former</li>
    <li>Mettre en place supervision et sauvegardes</li>
    <li>Prévoir redondance et PRA/PCA</li>
    <li>Externaliser intelligemment</li>
</ul

<h3>Risques et limites à prendre en compte</h3>

<p>Héberger localement n'est pas sans défis. Il faut anticiper :</p>

<ul>
    <li>Coût initial : matériel, aménagement des locaux, alimentation électrique et climatisation.</li>
    <li>Compétences : recrutement et formation d'équipes compétentes.</li>
    <li>Mise à jour & sécurité : obligation d'assurer veille & patching régulier.</li>
    <li>Redondance : nécessité d'avoir plans de sauvegarde et continuité (pour éviter une dépendance à un seul site)</li>
</ul>

<h3>Exemple réel : Mairie d'Échirolles</h3>

<ul>
    <li>Utilisation de ses propres serveurs hébergés à la mairie pour stocker les données des habitants.</li>
    <li>Utilisation de linux et de logiciels libres.</li>
    <li>Économie de 2 millions d'euros sur un mandat.</li>
</ul>`

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
