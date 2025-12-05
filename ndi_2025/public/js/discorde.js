export default class DiscordeApp {
    constructor(container, historyJson, philoJson) {
        this.container = container;

        // Données globales issues du JSON (histoire préfaite)
        this.historyMessages = historyJson.messages;
        this.contactHistory = historyJson.contactMessages;

        this.philo = philoJson.rep_philo;

        // Messages locaux utilisateur (stockés dans localStorage)
        this.localMessages = JSON.parse(localStorage.getItem("discorde_user_messages") || "{}");

        // Messages révélés stockés localement
        this.revealedMessages = JSON.parse(localStorage.getItem("discorde_revealed_messages") || "{}");

        // Fusion avec contactHistory
        for (const contactId in this.revealedMessages) {
            if (!this.contactHistory[contactId]) this.contactHistory[contactId] = [];
            this.contactHistory[contactId] = [
                ...new Set([...this.contactHistory[contactId], ...this.revealedMessages[contactId]])
            ];
        }


        // Liste des contacts
        this.contacts = [
            { id: 1, name: "Fan2Linux" },
            { id: 2, name: "JeanMichelRoot" },
            { id: 3, name: "Emma" },
            { id: 5, name: "Fenêtre enjoyer" },
            { id: 4, name: "JohnIA validay 😘" }
        ];

        // Contact sélectionné
        this.activeContact = 1;

        // Interface
        this.renderTemplate();
        this.renderContacts();
        this.renderMessages();
    }

    // ========================
    //     GESTION TEMPLATE
    // ========================
    renderTemplate() {
        this.container.innerHTML = `
        <div class="discorde-root">
            <div class="discorde-contact" id="discorde-contact"></div>

            <div class="discorde-chat">
                <div class="discorde-chat-msg" id="discorde-chat-msg"></div>

                <div class="discorde-chat-inputbar">
                    <input class="discorde-chat-input" id="discorde-chat-input" placeholder="Envoyer un message...">
                    <button id="discorde-send-btn">Send</button>
                </div>
            </div>
        </div>
        `;

        this.contactList = this.container.querySelector("#discorde-contact");
        this.chatList = this.container.querySelector("#discorde-chat-msg");
        this.inputField = this.container.querySelector("#discorde-chat-input");
        this.sendBtn = this.container.querySelector("#discorde-send-btn");

        this.sendBtn.addEventListener("click", () => this.sendMessage());
        this.inputField.addEventListener("keydown", e => {
            if (e.key === "Enter") this.sendMessage();
        });
    }

    // ========================
    //  CHARGEMENT DES MESSAGES
    // ========================
    getAllMessagesForContact(contactId) {
        const idList = this.contactHistory[contactId] || [];

        // Messages de l’histoire → résolus depuis le JSON
        const storyMessages = idList.map(id => this.historyMessages[id]).filter(Boolean);

        // Messages locaux → stockés dans localStorage
        const userMessages = this.localMessages[contactId] || [];

        return [...storyMessages, ...userMessages];
    }

    // ========================
    //       AFFICHAGE UI
    // ========================
    renderContacts() {
        this.contactList.innerHTML = "";

        this.contacts.forEach(contact => {
            const div = document.createElement("div");
            div.className = "discorde-contact-item";

            if (contact.id === this.activeContact) div.classList.add("active");

            div.innerHTML = `<h2>${contact.name}</h2>`;

            div.addEventListener("click", () => {
                this.activeContact = contact.id;
                this.renderContacts();
                this.renderMessages();
            });

            this.contactList.appendChild(div);
        });
    }

    renderMessageText(text) {
        if (!text || typeof text !== "string") {
            return "<i>(message vide)</i>";
        }

        const urlRegex = /(https?:\/\/[^\s]+)/g;
        const urls = text.match(urlRegex);

        let html = text.replace(urlRegex, `<a href="$1" target="_blank">$1</a>`);

        if (!urls) return html;

        const previews = urls
            .map(url => `
                <div class="discorde-link-preview">
                    <div class="preview-title">Aperçu du lien</div>
                    <a class="preview-url" href="${url}" target="_blank">${url}</a>
                </div>
            `)
            .join("");

        return html + previews;
    }



    renderMessages() {
        const msgs = this.getAllMessagesForContact(this.activeContact);

        this.chatList.innerHTML = msgs
            .map(m => `
                <div class="discorde-chat-item">
                    <span class="from">${m.from}</span>
                    <p>${this.renderMessageText(m.text)}</p>
                </div>
            `)
            .join("");

        this.chatList.scrollTop = this.chatList.scrollHeight;
    }


    // ========================
    //   ENVOI D’UN MESSAGE
    // ========================
    sendMessage() {
        if (this.activeContact == 3) {alert("Cet utilisateur vous à bloqué"); return;}

        const text = this.inputField.value.trim();
        if (!text) return;

        // 1. message de l'utilisateur
        this.addLocalMessage(this.activeContact, {
            from: "Toi",
            text
        });

        // 2. réponse automatique
        if (this.activeContact !== 4) {
            this.addLocalMessage(this.activeContact, {
                from: this.contacts.find(c => c.id === this.activeContact).name,
                text: "Att... jpp te répondre maintenant."
            }); 
        }
        else {
            this.addLocalMessage(this.activeContact, {
                from: this.contacts.find(c => c.id === this.activeContact).name,
                text: this.messagePhilo(text)
            }); 
        }
        

        this.inputField.value = "";
        this.renderMessages();
    }

    // ========================
    //  STOCKAGE LOCAL
    // ========================
    addLocalMessage(contactId, msg) {
        if (!this.localMessages[contactId]) this.localMessages[contactId] = [];
        this.localMessages[contactId].push(msg);
        localStorage.setItem("discorde_user_messages", JSON.stringify(this.localMessages));
    }

    // ========================
    //  FONCTIONS PUBLIQUES
    // ========================

    /** Efface les messages locaux et remet l’histoire à zéro */
    clearLocalStorage() {
        this.localMessages = {};
        this.revealedMessages = {};
        localStorage.removeItem("discorde_user_messages");
        localStorage.removeItem("discorde_revealed_messages");

        // Reset contactHistory à l’état initial du JSON
        for (const contactId in this.contactHistory) {
            this.contactHistory[contactId] = this.contactHistory[contactId].filter(id =>
                Object.keys(this.historyMessages).includes(id)
            );
        }

        this.renderMessages();
    }


    /** Ajoute un message de l’histoire à la conversation du contact correspondant */
    revealMessage(messageId, contactId) {
        const msg = this.historyMessages[messageId];
        if (!msg) return;

        if (!this.contactHistory[contactId]) this.contactHistory[contactId] = [];
        if (!this.contactHistory[contactId].includes(messageId)) {
            this.contactHistory[contactId].push(messageId);

            // Sauvegarde locale pour conserver l'avancement
            if (!this.revealedMessages[contactId]) this.revealedMessages[contactId] = [];
            this.revealedMessages[contactId].push(messageId);
            localStorage.setItem("discorde_revealed_messages", JSON.stringify(this.revealedMessages));
        }

        
        const contactName = this.contacts.find(c => c.id === contactId)?.name || "???";
        const messageText = msg.text;

        if (window.parent?.onIframeAction) {
            window.parent.onIframeAction({
                type: "notif",
                content: {
                    id: contactId,
                    user: contactName,
                    message: messageText
                }
            });
        }

        if (contactId === this.activeContact) this.renderMessages();
    }

    extraireMotCle(text) {
        // Regex : cherche les mots de 5 lettres ou plus
        const mots = text.match(/\b[a-zA-Zà-ü]{5,}\b/g);
        if (!mots || mots.length === 0) return null;
        return mots[Math.floor(Math.random() * mots.length)];
    }

    messagePhilo(text) {
        // HUMEUR
        const moodsKeys = Object.keys(this.philo.moods);
        const humeur = moodsKeys[Math.floor(Math.random() * moodsKeys.length)];
        const prefixeHumeur = this.philo.moods[humeur][Math.floor(Math.random() * this.philo.moods[humeur].length)];

        let reponseFinale = "";

        // 1. GESTION DES QUESTIONS
        if (text.includes("?") && Math.random() < 0.7) {
            const index = Math.floor(Math.random() * this.philo.questions.length);
            reponseFinale = this.philo.questions[index];
        }

        // 2. DÉTOURNEMENT
        else {
            const motCle = this.extraireMotCle(text);
            
            if (motCle && Math.random() < 0.7) {
                const templateIndex = Math.floor(Math.random() * this.philo.detournements.length);
                reponseFinale = this.philo.detournements[templateIndex].replace("{{mot}}", motCle);
            }

            // 3. DÉLIRE
            else {
                const delireIndex = Math.floor(Math.random() * this.philo.delires.length);
                let phraseDelire = this.philo.delires[delireIndex];

                if (Math.random() < 0.3) {
                    const liaisonIndex = Math.floor(Math.random() * this.philo.liaisons.length);
                    phraseDelire = `${this.philo.liaisons[liaisonIndex]} ${phraseDelire.toLowerCase()}`;
                }
                reponseFinale = phraseDelire;
            }
        }
        
        // Ajout le préfixe d'humeur
        return prefixeHumeur + reponseFinale;
    }



    startWindowsAutoMessages() {
        const contactId = 5;

        const windowsQueue = [
            "windows_4",
            "windows_5",
            "windows_6",
            "windows_7",
            "windows_8",
            "windows_9"
        ];

        let index = 0;

        const sendNext = () => {
            if (index >= windowsQueue.length) return;

            const msgId = windowsQueue[index];

            this.revealMessage(msgId, contactId);

            index++;

            const delay = Math.random() * 30000 + 6000;
            setTimeout(sendNext, delay);
        };

        const initialDelay = Math.random() * 24000 + 2000;
        setTimeout(sendNext, initialDelay);
    }



    

    

}
