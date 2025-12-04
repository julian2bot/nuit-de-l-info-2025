export default class DiscordeApp {
    constructor(container) {

        // ========================
        //      DONNÉES
        // ========================
        this.contacts = [
            { id: 1, name: "Fan2Linux", unread: 1 },
            { id: 2, name: "JeanMichelRoot", unread: 3 },
            { id: 3, name: "Emma", unread: 0 }
        ];

        this.messages = {
            1: [
                { from: "Fan2Linux", text: "Salut t'es chaud ?" },
                { from: "Toi", text: "Toujours prêt." }
            ],
            2: [
                { from: "JeanMichelRoot", text: "J’ai recompilé mon kernel hier" }
            ],
            3: [
                { from: "Emma", text: "Hey <3" },
                { from: "Toi", text: "Coucou :3" },
                { from: "Toi", text: "Tu veux faire une balade ?" },
                { from: "Emma", text: "Nan" }
            ]
        };

        // Contact sélectionné au début
        this.activeContact = this.contacts[0].id;

        // ========================
        //        TEMPLATE
        // ========================
        container.innerHTML = `
            <div class="discorde-root">
                <div class="discorde-contact" id="discorde-contact"></div>
                <div class="discorde-chat" id="discorde-chat">
                    <div class="discorde-chat-msg" id="discorde-chat-msg"></div>
                    <input class="discorde-chat-input"></input>
                </div>
            </div>
        `;

        this.contactList = container.querySelector("#discorde-contact");
        this.chatList = container.querySelector("#discorde-chat-msg");

        // ========================
        //       AFFICHAGE
        // ========================
        this.renderContacts();
        this.renderMessages();
    }

    // 🔵 Liste des contacts
    renderContacts() {
        this.contactList.innerHTML = "";

        this.contacts.forEach(contact => {
            const div = document.createElement("div");
            div.className = "discorde-contact-item";

            // highlight du contact sélectionné
            if (contact.id === this.activeContact) {
                div.style.background = "#4e5058";
            }

            div.innerHTML = `
                <h2>${contact.name}</h2>
                <p>${contact.unread} message(s) en attente</p>
            `;

            div.addEventListener("click", () => {
                this.activeContact = contact.id;
                contact.unread = 0;               // reset unread
                this.renderContacts();            // re-render contacts
                this.renderMessages();            // update chat
            });

            this.contactList.appendChild(div);
        });
    }

    // 🟣 Messages du contact actif
    renderMessages() {
        const msgs = this.messages[this.activeContact] || [];

        this.chatList.innerHTML = msgs.map(m => `
            <div class="discorde-chat-item">
                <h3>${m.from}</h3>
                <p>${m.text}</p>
            </div>
        `).join("");

    }
}
