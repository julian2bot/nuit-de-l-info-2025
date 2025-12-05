import DiscordeApp from "./discorde.js";

const content = document.getElementById("stage");
var JSON = await fetch("../data/messages.json").then(res => res.json());
var PHILO = await fetch("../data/philo.json").then(res => res.json());

window.app = new DiscordeApp(content, JSON, PHILO);


if (window.parent?.onIframeAction) {
    window.parent.onIframeAction({ type: "appReady", appId: "Discorde" });
}


window.addEventListener("message", (event) => {
    const data = event.data;
    console.log(data);
    if (!data?.action) return;

    switch (data.action) {
        case "reveal":
            app.revealMessage(data.messageId, data.contactId);
            break;
        case "windows":
            app.startWindowsAutoMessages();
            break;
        case "changeUser":
            app.activeContact = data.value;
            app.renderContacts();
            break;
        case "sendMessage":
            app.addLocalMessage(app.activeContact, { from: "Toi", text: data.text });
            app.renderMessages();
            break;
        case "clear":
            app.clearLocalStorage();
            break;
    }
});
// export const app = new DiscordeApp(content, JSON, PHILO);
// console.log(globalThis.app);
