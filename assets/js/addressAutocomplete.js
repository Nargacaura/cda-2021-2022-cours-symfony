const API_URL = "https://api-adresse.data.gouv.fr/search/?q="
const autocompleteInput = document.querySelector(".addressAutocomplete")

// const addresses = [
//     {
//         'street': "rue quelconque"
//     },
//     {
//         'street': "avenue bancale"
//     }
// ]

// addresses.forEach(el => {
//     const p = document.createElement("p")
//     p.classList.add("test")
//     p.innerText = el.street
//     p.addEventListener("click", e => {
//         console.log("click")
//     })
//     autocompleteInput.after(p)
// })
const list = document.createElement("ul")
list.classList.add("addressAutocompleteResults")
autocompleteInput.after(list)

// quand on relâche une touche...
function suggest(e) {
    // console.log(`Une touche a été relevée, l'input contient actuellement: ${e.target.value}`);
    
    const promise = fetch(API_URL + e.target.value)

    promise
        .then(response => response.json())
        .then(json => {
            console.log(json.features)
            list.innerHTML = ""
            json.features.forEach(element => {
                const listItem = document.createElement("li")
                listItem.innerText = element.properties.label
                list.append(listItem)
            });
        })
}

autocompleteInput.addEventListener("keyup", suggest);