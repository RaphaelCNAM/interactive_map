<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Interactive avec Pictogrammes</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map {
            height: 900px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    
    <script>
        var map = L.map('map').setView([-17.6509, -149.4260], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var data = [
            {lat: -17.6509, lon: -149.4260, icon: '🏝️', text: 'Plage de Tahiti'},
            {lat: -17.6575, lon: -149.4146, icon: '🏨', text: 'Hôtel de luxe'},
            {lat: -17.6618, lon: -149.4212, icon: '🌺', text: 'Jardin botanique'},
            {lat: -17.6640, lon: -149.4310, icon: '🌊', text: 'Spot de surf'},
            {lat: -17.6500, lon: -149.4350, icon: '🍽️', text: 'Restaurant local'}
        ];

        function getRandomCoordinates() {
            var latOffset = (Math.random() - 0.5) * 0.1;
            var lonOffset = (Math.random() - 0.5) * 0.1;
            return [-17.6509 + latOffset, -149.4260 + lonOffset];
        }

        data.forEach(function(item) {
            var coordinates = getRandomCoordinates();
            L.marker(coordinates)
                .addTo(map)
                .bindPopup('<b>' + item.icon + '</b><br>' + item.text);
        });
    </script>
</body>
</html>
