
let podiumRendered = false; // pour savoir si le podium est déjà affiché

function createCard(userScore) {
    const card = document.createElement("div");
    card.className = "card";

    card.innerHTML = `
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f">
                <path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/>
            </svg>
        </div>
        <div class="info">
            <div class="name">${userScore.name}</div>
        </div>
        <div class="info">
            <div class="name">${userScore.total_points}</div>
        </div>
    `;

    // card.addEventListener("click", () => window.location.href = userScore.viewUrl);

    const container = document.getElementById("container");
    container.appendChild(card);
}

function renderScores(scores) {
    const podium = document.getElementById("podium");

    if (!podiumRendered) {
        console.log("icii ")
        // On rend le podium uniquement lors du premier fetch
        podium.innerHTML = "";
        const top3 = scores.slice(0, 3);

        const ranks = ["first", "second", "third"];
        const positions = [1, 0, 2]; // 1er centre, 2e gauche, 3e droite
        const podiumBlocks = Array(3);

        top3.forEach((userScore, index) => {
            const div = document.createElement("div");
            div.className = `rank ${ranks[index]}`;
            div.innerHTML = `
                <div class="name">${userScore.name}</div>
                <div class="points">${userScore.total_points} pts</div>
            `;
            // div.addEventListener("click", () => window.location.href = userScore.viewUrl);
            podiumBlocks[positions[index]] = div;
        });

        podiumBlocks.forEach(block => podium.appendChild(block));

        podiumRendered = true;
    }

    // Ajouter les autres scores en tant que cards
    const startIndex = podiumRendered ? 3 : 3; // top3 déjà utilisé
    const rest = scores.slice(3);
    rest.forEach(userScore => createCard(userScore));
}
