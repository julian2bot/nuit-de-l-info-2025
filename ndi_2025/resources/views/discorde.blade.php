<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Discorde</title>

  <link href="{{ asset('css/discorde-window.css') }}" rel="stylesheet">
  <link href="{{ asset('css/discorde.css') }}" rel="stylesheet">
  
</head>
<body>

  <div class="stage" id="stage">

    <div class="window" id="window">
        <div class="window-topbar" id="window-topbar">
          <p>Discorde</p>
        </div>
        <div class="window-content" id="window-content">
          <!-- <p>Bonjour je mange le saucisson</p> -->
        </div>
    </div>

  </div>

  <script type="module" src="{{asset('js/discorde-window.js') }}"></script>

</body>
</html>
