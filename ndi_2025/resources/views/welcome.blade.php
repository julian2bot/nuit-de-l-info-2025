<button id="btn">Clique moi</button>
<script>
const btn = document.getElementById("btn");
btn.addEventListener("click", () => {
    // Appel direct au parent
    window.parent.onIframeAction({"type":"notif", 'message': "bugevsyfsfsbhvfds"}); // todo a definir pour l'os
});
</script>
