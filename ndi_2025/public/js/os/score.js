const tokenElement = document.querySelector('meta[name="csrf-token"]');
const csrf_token = tokenElement ? tokenElement.content : null;

if (!csrf_token) console.warn("⚠️ CSRF token not found in page!");

function sendScore(type, ref, nbpoint) {
    fetch("/score", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrf_token
        },
        body: JSON.stringify({
            type: type,
            ref: ref,
            nbpoint: nbpoint
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Score saved:", data);
    })
    .catch(err => console.error(err));
}
