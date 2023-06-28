<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Lagos GeoSaude</title>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
</head>
<body>
    <div id="map"></div>
    
    <button id="location-button" class="location-button">
    <i class="fa-solid fa-location-crosshairs"></i>
</button>



<a href="https://github.com/joenyrcouto/Lagos_GeoSaude/tree/main" target="_blank" ><button id="mug-button" class="location-button">
<i class="fa-brands fa-github"></i>
</button></a>

    <div class="menu-container" id="menu-button">
        <i class="fa-solid fa-bars"></i>
    </div>
    <div class="side-menu" id="side-menu">
        <ul>
            <li><a href="index.php">Log In / Sign Up</a></li>
            <li><a href="listamarcadores.php">Pontos Sugeridos</a></li>
            <li><a href="info.html">Info</a></li>
        </ul>
    </div>

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.3/mapbox-gl-geocoder.min.js"></script>
    <link
        rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.3/mapbox-gl-geocoder.css"
        type="text/css"
    />

    <script>
        // token mapbox
        mapboxgl.accessToken = 'pk.eyJ1Ijoiam9lY291dG8iLCJhIjoiY2xmcHN5NndpMGN0MDN4bmw1ZTQ3N2owNSJ9.Wm1TXO5LIzXcvRPVkLdXJQ';

        // Create a new map instance
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [-42.280395, -22.805185],
            zoom: 9.5
        });

        // remove layers de mapa não necessárias
        map.on('load', function() {
            map.removeLayer('road-label');
            map.removeLayer('poi-label');
        });

        // Adicionar barra de pesquisa
        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            mapboxgl: mapboxgl,
            marker: {
                color: 'red'
            },
            placeholder: 'Pesquisar local',
            language: 'pt-BR',
            countries: 'br',
            bbox: [-43.1796, -23.0814, -41.4308, -22.4582] // limitar área
        });

        // Posiciona a barra de pesquisa no canto superior direito do mapa
        map.addControl(geocoder, 'top-right');

        // Adicionar funcionalidade ao botão de localização
const locationButton = document.getElementById('location-button');
locationButton.addEventListener('click', () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const lngLat = [position.coords.longitude, position.coords.latitude];
            map.flyTo({
                center: lngLat,
                zoom: 14
            });

            const userMarker = new mapboxgl.Marker({ color: 'red' }) // Define a cor do marcador como vermelho
                .setLngLat(lngLat)
                .addTo(map);
        });
    } else {
        alert('Geolocation is not supported by your browser.');
    }
});

        // Abrir/fechar o menu lateral
        const menuButton = document.getElementById('menu-button');
        const sideMenu = document.getElementById('side-menu');
        menuButton.addEventListener('click', () => {
            sideMenu.classList.toggle('open');
        });

        // Fechar o menu ao clicar no mapa
        map.on('click', () => {
            sideMenu.classList.remove('open');
        });

        // adicionar novos pontos ao mapa
        <?php require_once 'pontos.php'; ?>


    map.on('click', function (e) {
    const sideMenu = document.getElementById('side-menu');
    if (sideMenu.classList.contains('open')) {
        sideMenu.classList.remove('open');
    } else {
        const latitude = e.lngLat.lat;
        const longitude = e.lngLat.lng;

        // Verificar se o ponto clicado já existe
        const features = map.queryRenderedFeatures(e.point, { layers: ['marker-layer'] });
        if (features.length === 0) {
            abrirCaixaRegistro(latitude, longitude);
        } else {
            const marker = features[0];
            const popup = marker.getPopup();
            popup.addTo(map);
        }
    }
});



        // Função para abrir a caixa de registro
        function abrirCaixaRegistro(latitude, longitude) {
            const popup = new mapboxgl.Popup({
                closeButton: true,
                className: 'popup',
                anchor: 'bottom'
            });

            popup.setHTML(`
                <div class='popup-title'><h3>Registrar Ponto</h3></div>
                <div class='popup-content'>
                    <form method='POST' action='registrar_ponto.php'>
                        <input type='hidden' name='latitude' value='${latitude}'>
                        <input type='hidden' name='longitude' value='${longitude}'>
                        <div class='form-group'>
                            <label for='titulo'>Título:</label>
                            <input type='text' name='titulo' id='titulo' required>
                        </div>
                        <div class='form-group'>
                            <label for='informacoes'>Informações:</label>
                            <textarea class='form-control' name='comentario' required></textarea>
                        </div>
                        <button type='submit' class='btn btn-primary'>Registrar</button>
                    </form>
                </div>
            `);

            const marker = new mapboxgl.Marker({ color: 'green' }) // Define a cor do marcador como verde
                .setLngLat([longitude, latitude])
                .setPopup(popup)
                .addTo(map);

            marker.togglePopup(); // Abrir a caixa do marcador imediatamente

            popup.on('close', () => {
                marker.remove(); // Remover o marcador ao fechar a caixa
            });
        }
    </script>
</body>
</html>
