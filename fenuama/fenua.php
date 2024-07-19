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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Carte Interactive avec Localisation et Filtre</h1>
    <div class="controls">
        <button class="filter-button filter-tous" onclick="setActiveButton(this); showAllMarkers()">Tous</button>
        <button class="filter-button filter-huile" onclick="setActiveButton(this); filterMarkers('media/fenuama/markerh.png')"><img src="media/fenuama/markerh.png" alt="Huiles de Moteur">Huiles de Moteur</button>
        <button class="filter-button filter-batteries" onclick="setActiveButton(this); filterMarkers('media/fenuama/markerb.png')"><img src="media/fenuama/markerb.png" alt="Batteries">Batteries</button>
        <button class="filter-button filter-piles" onclick="setActiveButton(this); filterMarkers('media/fenuama/markerp.png')"><img src="media/fenuama/markerp.png" alt="Piles">Piles</button>
        <button class="filter-button filter-verre" onclick="setActiveButton(this); filterMarkers('media/fenuama/markerv.png')"><img src="media/fenuama/markerv.png" alt="Verre">Verre</button>
        <button class="filter-button filter-med" onclick="setActiveButton(this); filterMarkers('media/fenuama/marker_med.png')"><img src="media/fenuama/marker_med.png" alt="Médicaments Usagés">Médicaments Usagés</button>
        <button class="filter-button filter-amp" onclick="setActiveButton(this); filterMarkers('media/fenuama/marker_amp.png')"><img src="media/fenuama/marker_amp.png" alt="Ampoules et Néons">Ampoules et Néons</button>
        <button class="filter-button filter-dechetterie" onclick="setActiveButton(this); filterMarkers('media/fenuama/marker_dec.png')"><img src="media/fenuama/marker_dec.png" alt="Déchetterie">Déchetterie</button>
        <button class="filter-button filter-electronique" onclick="setActiveButton(this); filterMarkers('media/fenuama/marker_ele.png')"><img src="media/fenuama/marker_ele.png" alt="Électronique">Électronique</button>
        <button class="filter-button filter-fusees" onclick="setActiveButton(this); filterMarkers('media/fenuama/markerf.png')"><img src="media/fenuama/markerf.png" alt="Fusées de Détresse">Fusées de Détresse</button>
    </div>
    <div id="map"></div>
    <div id="citySelector">
        <select onchange="zoomToCity(this.value)">
            <option value="">Sélectionner une ville</option>
            <option value="arue">Arue</option>
            <option value="mahina">Mahina</option>
            <option value="papara">Papara</option>
            <option value="teva_i_uta">Teva I Uta</option>
            <option value="papeete">Papeete</option>
            <option value="hitiaa_o_te_ra">Hitiaa O Te Ra</option>
            <option value="pirae">Pirae</option>
            <option value="punaauia">Punaauia</option>
            <option value="taiarapu_est">Taiarapu Est</option>
            <option value="faa_a">Faa'a</option>
            <option value="paea">Paea</option>
            <option value="taiarapu_ouest">Taiarapu Ouest</option>
            <option value="papenoo">Papenoo</option>
        </select>
    </div>
    
    <script>
        var map = L.map('map').setView([-17.6509, -149.4260], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        function createCustomIcon(iconUrl) {
            return L.icon({
                iconUrl: iconUrl,
                iconSize: [25, 30],
                iconAnchor: [15, 30],
                popupAnchor: [0, -30]
            });
        }

        var data = [
            {lat: -17.6509, lon: -149.4260, icon: 'media/fenuama/marker_amp.png', text: 'Plage de Punaauia', isWater: false},
            {lat: -17.5768, lon: -149.6121, icon: 'media/fenuama/marker_dec.png', text: 'Hôtel InterContinental Tahiti Resort & Spa', isWater: false},
            {lat: -17.7368, lon: -149.5965, icon: 'media/fenuama/marker_ele.png', text: 'Jardin Botanique Harrison Smith', isWater: false},
            {lat: -17.5855, lon: -149.6186, icon: 'media/fenuama/marker_med.png', text: 'Université de la Polynésie française', isWater: false}
        ];

        var markers = [];
        var visibleMarkers = [];

        data.forEach(function(item) {
            var marker = L.marker([item.lat, item.lon], {icon: createCustomIcon(item.icon)}).bindPopup(item.text);
            markers.push({marker: marker, icon: item.icon});
            marker.addTo(map);
        });

        function showAllMarkers() {
            visibleMarkers.forEach(function(item) {
                map.removeLayer(item.marker);
            });
            visibleMarkers = markers.slice();
            visibleMarkers.forEach(function(item) {
                item.marker.addTo(map);
            });
        }

        function filterMarkers(iconUrl) {
            visibleMarkers.forEach(function(item) {
                map.removeLayer(item.marker);
            });
            visibleMarkers = markers.filter(function(item) {
                return item.icon === iconUrl;
            });
            visibleMarkers.forEach(function(item) {
                item.marker.addTo(map);
            });
        }

        function setActiveButton(button) {
            var buttons = document.querySelectorAll('.filter-button');
            buttons.forEach(function(btn) {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        }

        var cities = {
            "arue": [-17.5171, -149.5238],
            "mahina": [-17.5184, -149.4880],
            "papara": [-17.7564, -149.5219],
            "teva_i_uta": [-17.7654, -149.3348],
            "papeete": [-17.5516, -149.5585],
            "hitiaa_o_te_ra": [-17.6250, -149.2583],
            "pirae": [-17.5439, -149.5333],
            "punaauia": [-17.6333, -149.6060],
            "taiarapu_est": [-17.7417, -149.3241],
            "faa_a": [-17.5580, -149.6061],
            "paea": [-17.6822, -149.5943],
            "taiarapu_ouest": [-17.7686, -149.2827],
            "papenoo": [-17.5092, -149.4124]
        };

        function zoomToCity(city) {
            if (city in cities) {
                map.setView(cities[city], 13);
            }
        }
    </script>
</body>
</html>
