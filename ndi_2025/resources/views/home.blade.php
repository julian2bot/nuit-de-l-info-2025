<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lock Screen - ndi 2025 js.remove </title>
    <link rel="stylesheet" href="{{ asset('style/auth.css') }}">
    <script src="{{ asset('js/os/auth.js')}}"></script>
</head>

<body>

    <div id="lockScreen">
        <div id="lockScreenContent">
            <div id="mainScreen">
                <div id="time">00:00</div>
                <div id="date">1 Janvier 2025</div>
            </div>
            <img class="avatar" src="{{ asset('images/profil.png')}}" alt="User Avatar">
            <h1>Appuyez sur une touche ou cliquez pour déverrouiller</h1>
        </div>
    </div>

    <div id="loginScreen">
        <form id="loginForm" method="POST" action="{{ route('auth.loginRegister') }}">
            @csrf
            <input type="text" name="name" placeholder="Pseudo" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit"> Continuer </button>
        </form>
    </div>

</body>
</html>
