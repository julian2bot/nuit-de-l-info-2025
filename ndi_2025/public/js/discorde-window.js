import DiscordeApp from "./discorde.js";

const content = document.getElementById("stage");
var JSON = null;
var PHILO = null;

await fetch("../data/messages.json")
    .then(res => res.json())
    .then(json => {
        JSON = json
    }
);

await fetch("../data/philo.json")
    .then(res => res.json())
    .then(json => {
        PHILO = json
    }
);

window.app = new DiscordeApp(content, JSON, PHILO);