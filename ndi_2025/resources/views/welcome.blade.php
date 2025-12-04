<button id="btn">Clique moi</button>
<script>
const btn = document.getElementById("btn");
btn.addEventListener("click", () => {
    // Appel direct au parent
    window.parent.onIframeAction({"truc":"fsfsd"}); // todo a definir pour l'os 
});
</script>
