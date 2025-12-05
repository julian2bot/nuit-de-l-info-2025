<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        const initialText = `<strong>Facebook:</strong>
<br>
papy.michel@gmail.com
<br>
pwetpwetpwet98
<br>
<br>
<br>

<br>
<strong>Gmail:</strong>
<br>
mamie-raymonde@wanadoo.fr
<br>
jesaisplusmonmdp
<br>
<br>
<br>

<br>
<strong>Netflix:</strong>
<br>
tata.giselle@orange.fr
<br>
cestquoicebordel123
<br>
<br>
<br>

<br>
<strong>Amazon:</strong>
<br>
roger.commande@free.fr
<br>
jaioubliéencore
<br>
<br>
<br>

<br>
<strong>Spotify:</strong>
<br>
papi.bernard@laposte.net
<br>
jaimelaccordeon57
<br>
<br>
<br>

<br>
<strong>Outlook:</strong>
<br>
tontonmarvin@aol.com
<br>
mdpdemdp
<br>
<br>
<br>

<br>
<strong>Instagram:</strong>
<br>
mamie-selfie@live.fr
<br>
jenfousdelainternet
<br>
<br>
<br>
`;

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
            page2.innerHTML = `

<br>
<strong>Yahoo:</strong>
<br>
papy.jeanpierre@yahoo.fr
<br>
sexiondassaut2010
<br>
<br>
<br>

<br>
<strong>Twitter:</strong>
<br>
gertrude.tweet@free.fr
<br>
jesuissurtwitter?
<br>
<br>
<br>

<br>
<strong>LinkedIn: </strong>
<br>
daron.pro@outlook.fr
<br>
motdepasseprodefou


<br>


<br><strong>GitHub:</strong>
<br>dev.rustacean@gmail.com
<br>crabCrabFerris42

<br>

<br><strong>Tinder:</strong>
<br>tinder.vibes@gmail.com
<br>jesuisungrandEnfantLOL
<br><br>

<br><strong>PMU:</strong>
<br>papy.course@pmu.fr
<br>chevalCraft69

<br>

<br><strong>ChatGPT:</strong>
<br>exlover.memory@gmail.com
<br>toujourspenseraelleOMG
<br><br>
<br><strong>SpaghettiApp:</strong>
<br>pasta.lord@gmail.com
<br>spaghettiSlurp2007`;
            container.appendChild(page2);
        }

        // Run initialization
        init();

    </script>
</body>
</html>
