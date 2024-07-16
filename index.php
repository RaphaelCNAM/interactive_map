<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Interactive avec Géolocalisation et Itinéraire</title>
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
    </style>
</head>
<body>
    <h1>Carte Interactive avec Géolocalisation et Itinéraire à Tahiti</h1>
    <div id="map"></div>
    
    <script>
        var map = L.map('map').setView([-17.6509, -149.4260], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        function createCustomIcon(iconEmoji) {
            return L.divIcon({
                html: iconEmoji,
                className: '',
                iconSize: [50, 50],
                iconAnchor: [25, 50],
                popupAnchor: [0, -50]
            });
        }

        var data = [
            {lat: -17.6509, lon: -149.4260, icon: '🏝️', text: 'Plage de Punaauia', isWater: false},
            {lat: -17.5768, lon: -149.6121, icon: '🏨', text: 'Hôtel InterContinental Tahiti Resort & Spa', isWater: false},
            {lat: -17.7368, lon: -149.5965, icon: '🌺', text: 'Jardin Botanique Harrison Smith', isWater: false},
            {lat: -18.1367, lon: -149.2627, icon: '🌊', text: 'Spot de surf Teahupo\'o', isWater: true},
            {lat: -17.6551, lon: -149.4340, icon: '🍽️', text: 'Restaurant Le Coco\'s', isWater: false}
        ];

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
                .addTo(map)
                .bindPopup(popupContent);
        });

        function onLocationFound(e) {
            userLocation = e.latlng;
            var userIcon = createCustomIcon('📍');
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

        map.locate({setView: true, maxZoom: 16});
    </script>
</body>
</html>
