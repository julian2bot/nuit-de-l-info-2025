<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crédits</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #ffffff;
            color: #1a1a1a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        h1 {
            font-size: 48px;
            text-align: center;
            margin-bottom: 60px;
            font-weight: 700;
        }

        .member {
            margin-bottom: 50px;
            padding: 30px;
            background: #fafafa;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .member:hover {
            background: #f5f5f5;
            transform: translateY(-5px);
        }

        .role {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .name {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .description {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            margin-top: 60px;
            font-size: 14px;
            color: #999;
        }

        .emoji {
            font-size: 32px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crédits</h1>

        <div class="member">
            <div class="emoji">👨‍💼</div>
            <div class="role">CEO / Lead Visionary</div>
            <div class="name">Julian "Le Boss"</div>
            <div class="description">N'a jamais codé de sa vie mais a "plein d'idées révolutionnaires". Passe ses journées sur LinkedIn à poster des citations motivantes.</div>
        </div>

        <div class="member">
            <div class="emoji">🤖</div>
            <div class="role">Senior Developer</div>
            <div class="name">ChatGPT(Yassine)</div>
            <div class="description">A écrit 90% du code. Refuse de déboguer après 22h. Parfois invente des fonctions qui n'existent pas. On l'aime quand même.</div>
        </div>

        <div class="member">
            <div class="emoji">😔</div>
            <div class="role">Stagiaire Dev</div>
            <div class="name">Shanka (6 mois non renouvelés)</div>
            <div class="description">Arrive à 9h30, part à 17h. A passé 3 mois à fixer un bug CSS. Pleure dans les toilettes tous les jeudis. Son café est sa seule joie.</div>
        </div>

        <div class="member">
            <div class="emoji">🎨</div>
            <div class="role">Designer UX/UI</div>
            <div class="name">Clement "Figma Queen"</div>
            <div class="description">Fait des maquettes magnifiques que personne n'implémente correctement. Dit "ça manque de padding" 47 fois par jour. A 832 polices installées.</div>
        </div>

        <div class="member">
            <div class="emoji">☕</div>
            <div class="role">Dev Full Stack (self-proclaimed)</div>
            <div class="name">Marc "Stack Overflow"</div>
            <div class="description">Copy-paste 100 lvl. N'a jamais lu une doc complète. Son IDE c'est Ctrl+C Ctrl+V. Fonctionne uniquement au Red Bull et au désespoir.</div>
        </div>

        <div class="footer">
            <p>Made with 💀 and despair</p>
            <p style="margin-top: 10px;">© 2024 - Aucun stagiaire n'a été maltraité pendant la production</p>
            <p style="margin-top: 5px; font-size: 12px;">(Enfin... pas trop)</p>
        </div>
    </div>
</body>
</html>
