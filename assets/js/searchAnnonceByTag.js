const tagSelector = document.querySelector('[name="search_by_tag"]');

// function onChange() {
//     alert("How dare you summon me?");
// }

// Callback
// tagSelector.addEventListener("change", onChange);

// Fonction anonyme:
tagSelector.addEventListener("change", (event) => {
    // ref à la fonction
    console.log(event.target.value)
    document.location = `/annonce-by-tag/${event.target.value}`;
});

// tagSelector.addEventListener("change", function() {
//     // ref à l'élément
//     console.log(this.value)
// });