function onIframeAction(data) {
    console.log("Données reçues depuis l'iframe :", data);

    if(data.type === "notif") {
        addNotif(data.content);
    }
}

function addNotif(data){
    const divPopup = document.getElementById("notifbar-discorde");

    const notif = document.createElement("div");
    notif.classList.add("notifDiscorde");

    let username = document.createElement("h4");
    username.innerText = "De : " + (data.user ?? "user");

    let message = document.createElement("p");
    message.innerText = data.message ?? "Vous a envoyé un message";

    notif.appendChild(username);
    notif.appendChild(message);
    divPopup.appendChild(notif);

    notif.addEventListener("click", (e) => {
        console.log("Recu");
        console.log(globalThis.app)
        // window.app.activeContact = data.id ?? 1;
        openLogiciel(apps["discorde"]);
    })
}

window.onIframeAction = onIframeAction;