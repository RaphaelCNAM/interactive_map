<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Interactive avec Localisation et Filtre</title>
    <!-- Inclure Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Inclure Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Inclure Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <!-- Inclure Leaflet Routing Machine JavaScript -->
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <style>
        #map {
            height: 800px;
            width: 100%;
        }
        .controls {
            margin-bottom: 10px;
        }
        .controls button {
            margin-right: 5px;
            padding: 5px 10px;
        }
        .controls select {
            margin-right: 5px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <h1>Carte Interactive avec Localisation et Filtre</h1>
    <div class="controls">
        <button onclick="showAllMarkers()">Tous</button>
        <button onclick="filterMarkers('media/fenuama/marker_amp.png')">Plages</button>
        <button onclick="filterMarkers('media/fenuama/marker_dec.png')">Hôtels</button>
        <button onclick="filterMarkers('media/fenuama/marker_ele.png')">Jardins</button>
        <button onclick="filterMarkers('media/fenuama/marker_med.png')">Surf</button>
        <button onclick="filterMarkers('media/fenuama/markerb.png')">Restaurants</button>
        <select onchange="zoomToCity(this.value)">
            <option value="">Sélectionner une ville</option>
            <option value="arue">Arue</option>
            <option value="punaauia">Punaauia</option>
            <option value="faaa">Faa'a</option>
        </select>
    </div>
    <div id="map"></div>
    
    <script>
        var map = L.map('map').setView([-17.6509, -149.4260], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        function createCustomIcon(iconUrl) {
            return L.icon({
                iconUrl: iconUrl,
                iconSize: [50, 50],
                iconAnchor: [25, 50],
                popupAnchor: [0, -50]
            });
        }

        var data = [
            {lat: -17.6509, lon: -149.4260, icon: 'media/fenuama/marker_amp.png', text: 'Plage de Punaauia', isWater: false},
            {lat: -17.5768, lon: -149.6121, icon: 'media/fenuama/marker_dec.png', text: 'Hôtel InterContinental Tahiti Resort & Spa', isWater: false},
            {lat: -17.7368, lon: -149.5965, icon: 'media/fenuama/marker_ele.png', text: 'Jardin Botanique Harrison Smith', isWater: false},
            {lat: -18.1367, lon: -149.2627, icon: 'media/fenuama/marker_med.png', text: 'Spot de surf Teahupo\'o', isWater: true},
            {lat: -17.6551, lon: -149.4340, icon: 'media/fenuama/markerb.png', text: 'Restaurant Le Coco\'s', isWater: false}
        ];

        var markers = [];
        var userLocation = null;
        var control = L.Routing.control({
            waypoints: [],
            routeWhileDragging: true,
            createMarker: function() { return null; }
        }).addTo(map);

        function createRoute(destination) {
            if (userLocation) {
                control.setWaypoints([userLocation, destination]);
            } else {
                alert("Géolocalisation non trouvée.");
            }
        }

        data.forEach(function(item) {
            var customIcon = createCustomIcon(item.icon);
            var popupContent = '<b>' + item.text + '</b>';
            if (!item.isWater) {
                popupContent += '<br><button onclick="createRoute([' + item.lat + ',' + item.lon + '])">Itinéraire</button>';
            }
            var marker = L.marker([item.lat, item.lon], {icon: customIcon})
                .bindPopup(popupContent);
            marker.addTo(map);
            markers.push({marker: marker, icon: item.icon});
        });

        function onLocationFound(e) {
            userLocation = e.latlng;
            var userIcon = createCustomIcon('media/fenuama/markerf.png');
            var radius = e.accuracy / 2;

            L.marker(e.latlng, {icon: userIcon}).addTo(map)
                .bindPopup("Vous êtes ici").openPopup();

            L.circle(e.latlng, radius).addTo(map);
        }

        function onLocationError(e) {
            alert(e.message);
        }

        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);

        // Appel à la fonction locate sans setView pour éviter de zoomer automatiquement
        map.locate({maxZoom: 13});

        function showAllMarkers() {
            markers.forEach(function(item) {
                item.marker.addTo(map);
            });
        }

        function filterMarkers(iconUrl) {
            markers.forEach(function(item) {
                if (item.icon === iconUrl) {
                    item.marker.addTo(map);
                } else {
                    map.removeLayer(item.marker);
                }
            });
        }

        var cities = {
            "arue": [-17.5171, -149.5238],
            "punaauia": [-17.6333, -149.6060],
            "faaa": [-17.5580, -149.6061]
        };

        function zoomToCity(city) {
            if (city in cities) {
                map.setView(cities[city], 13);
            }
        }
    </script>
</body>
</html>
