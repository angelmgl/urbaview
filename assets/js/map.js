function initMap() {
    // Aquí se ingresan las coordenadas guardadas en la base de datos
    let lat = document.getElementById("lat").value; 
    let lng = document.getElementById("lng").value;

    let mapOptions = {
        center: new google.maps.LatLng(lat, lng),
        zoom: 14,
    };

    // Inicializa el mapa
    let map = new google.maps.Map(
        document.getElementById("map"),
        mapOptions
    );

    // Crea el marcador
    let marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(lat, lng),
    });
}
