function initMap() {
    // Definimos una ubicación estática como centro del mapa
    let latInput = document.getElementById("lat");
    let longInput = document.getElementById("lng");
    let initialLat = latInput.value ? latInput.value : -25.2637; 
    let initialLong = longInput.value ? longInput.value : -57.5759; 

    let mapOptions = {
        center: new google.maps.LatLng(initialLat, initialLong),
        zoom: 14,
    };

    // Usamos ese objeto para inicializar el mapa
    let map = new google.maps.Map(
        document.getElementById("map"),
        mapOptions
    );

    // Creamos el marcador en el mapa
    let marker = new google.maps.Marker({
        map: map,
        draggable: true, // importante que sea arrastrable
        animation: google.maps.Animation.DROP,
        position: new google.maps.LatLng(initialLat, initialLong),
    });

    // Con este listener agregamos la funcionalidad deseada al evento "dragend"
    google.maps.event.addListener(marker, "dragend", function () {
        // Establecemos un nuevo centro
        map.setCenter(marker.getPosition());
        // Establecemos el valor de los campos ocultos de acuerdo
        // a las coordenadas del marcador en el mapa
        latInput.value = marker.getPosition().lat();
        longInput.value = marker.getPosition().lng();
    });
}
