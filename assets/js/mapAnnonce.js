// import '../../node_modules/leaflet/dist/leaflet.js'

import L from 'Leaflet'

const geolocator = document.querySelector(".geolocate")
const map = L.map('map').setView([48, 7], 7)

const icon = L.divIcon({
    className: 'custom-div-icon',
    html: "<div style='background-color:#ee7c01;' class='marker-pin'></div><i class='material-icons'></i>",
    iconSize: [30, 42],
    iconAnchor: [15, 42]
})

var pinpoint = L.marker([48, 7], {icon: icon}).addTo(map).bindPopup("Vous êtes ici.");

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
function geolocate() {
    // geolocator.remove()
    if("geolocation" in navigator){
        var options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        }
    
        function success(position){
            var latitude = `${position.coords.latitude}`
            var longitude = `${position.coords.longitude}`
    
            map.setView([latitude, longitude], 13)
            pinpoint.setLatLng(L.latLng(latitude, longitude))
        }
    
        function fail(error) {
            alert(`Une erreur s'est produite: ${error.message} (erreur ${error.code}))`)
        }
    
        navigator.geolocation.getCurrentPosition(success, fail, options);
        
    }
    
    // Si elle ne l'est pas...
    else {
        alert("Nous n'avons pas pu démarrer le processus de géolocalisation.");
    }
}

geolocator.addEventListener("click", geolocate)