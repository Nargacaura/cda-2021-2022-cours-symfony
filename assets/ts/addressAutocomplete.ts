const API_URL = "https://api-adresse.data.gouv.fr/search/?q=";
const autocompleteInput = document.querySelector(".addressAutocomplete");

const latitude = document.querySelector("#annonce_address_latitude");
const longitude = document.querySelector("#annonce_address_longitude");
const streetNumber = document.querySelector("#annonce_address_streetNumber");
const street = document.querySelector("#annonce_address_street");
const zipCode = document.querySelector("#annonce_address_zipCode");
const city = document.querySelector("#annonce_address_city");

const list = document.createElement("ul");
list.classList.add("addressAutocompleteResults");
autocompleteInput.after(list);

// quand on relâche une touche...
function suggest(e) {
  const promise = fetch(API_URL + e.target.value);

  promise
    .then((response) => response.json())
    .then((json) => {
      // console.log(json.features)
      list.innerHTML = "";
      json.features.forEach((element) => {
        const listItem = document.createElement("li");
        listItem.addEventListener("click", (e) => {
          (autocompleteInput as HTMLInputElement).value = (
            e.target as HTMLElement
          ).textContent;
          list.innerHTML = "";
          console.log(element);

          let coordinates = element.geometry.coordinates;
          let humanReadableCoordinates = (element.properties(
            latitude as HTMLInputElement
          ).value = coordinates[1]);
          (longitude as HTMLInputElement).value = coordinates[0];
          if (humanReadableCoordinates.housenumber) {
            (streetNumber as HTMLInputElement).value =
              humanReadableCoordinates.housenumber(
                street as HTMLInputElement
              ).value = humanReadableCoordinates.street;
          } else {
            (streetNumber as HTMLInputElement).value = "";
            (street as HTMLInputElement).value = humanReadableCoordinates.name;
          }
          (zipCode as HTMLInputElement).value =
            humanReadableCoordinates.postcode(city as HTMLInputElement).value =
              humanReadableCoordinates.city;
        });

        listItem.innerText = element.properties.label;
        list.append(listItem);
      });
    });
}

autocompleteInput.addEventListener("keyup", suggest);
