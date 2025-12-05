<button id="btn">Clique moi</button>
<script src="{{ asset('js/os/notifDiscorde.js')}}"></script>
<link rel="stylesheet" href="{{ asset('style/discordNotif.css')}}">

<div id="notifbar-discorde"></div>

<script>
    let i = 0;
    const btn = document.getElementById("btn");
    btn.addEventListener("click", () => {
        // Appel direct au parent
        window.parent.onIframeAction({"type":"notif", "content":{
            "user": "defaultUser",
            // "message": `Message test ${i++} eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee`
        }}); // todo a definir pour l'os 
    });
</script>
