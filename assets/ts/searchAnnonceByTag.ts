const tagSelector = document.querySelector('[name="search_by_tag"]');

// Fonction anonyme:
tagSelector.addEventListener("change", (event: Event) => {
  // ref à la fonction
  console.log((event.target as HTMLInputElement).value);
  document.location = `/annonce-by-tag/${
    (event.target as HTMLInputElement).value
  }`;
});
