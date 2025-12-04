<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>NIRD - OS pas fou</title>
<link rel="stylesheet" href="{{ asset('style/os.css')}}">


</head>

<body>
    <script src="{{ asset('js/apps/apps.js')}}"></script>
    <script src="{{ asset('js/os/application.js')}}"></script>
</body>
</html>
<script>

const iframe = document.getElementById("myIframe");

function onIframeAction(data) {
    console.log("L'iframe a envoyé :", data);
    console.log(data)
    alert("Action reçue de l'iframe : " + data);
}

</script>
