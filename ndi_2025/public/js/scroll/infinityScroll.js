let offset = 0;
let limit = 10;
let loading = false;

let container;
let sentinel;
let sortSelect;
let direction;
let searchInput;




// Reset list + offset, then reload
function resetAndLoad() {
    container.innerHTML = "";
    offset = 0;
    loadMore();
}


async function loadMore() {
    showLoader();
    if (loading) return;
    loading = true;
    let search = searchInput.value;
    let sort = sortSelect.value;
    let directionValue = direction.value ?? "asc";
    console.log(direction)
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = endPoint+`?offset=${offset}&sort=${sort}&search=${search}&direction=${directionValue}`;

   const response = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
    });    const result = await response.json();

    if (result.data && result.data.length > 0) {
        result.data.forEach(p =>createCard(p));
    }else{
        createEmpty();
    }

    if (result.has_more) {
        offset = result.next_offset;
    } else {
        offset = null; // No more loading
    }
    hideLoader();

    loading = false;
}


function createEmpty() {
    const card = document.createElement("div");
    card.className = " ";

    card.innerHTML = ` Aucune soutenance corresponds a la recherche : `;

    container.appendChild(card);
}

function orderBySwitch() {

    const isDesc = direction.value = (direction.value === 'desc' ? 'asc' : 'desc');

    document.getElementById("direction").classList.toggle('desc', isDesc === 'desc');
}

document.addEventListener('DOMContentLoaded', fn=>{

    container = document.getElementById("container");
    sentinel = document.getElementById("sentinel");
    sortSelect = document.getElementById("sort");
    direction = document.getElementById("directionInput");
    searchInput = document.getElementById("search");


    // Observer - infinite scroll
    const observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting && offset !== null) {
            showLoader();
            loadMore();
        }else{
            hideLoader();
        }
    });

    observer.observe(sentinel);

    // Initial load
    loadMore();



    searchInput.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault(); // empêche le form de se soumettre
            resetAndLoad();
        }
    });

});



function showLoader() {
    document.getElementById('loader').style.display = 'flex';
}

function hideLoader() {
    document.getElementById('loader').style.display = 'none';
}
