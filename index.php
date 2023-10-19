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

    <button id="toggleButton" style="bottom: 130px;" class="location-button">
<i class="fa fa-eye"></i>
</button>
    
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
    <ul style="text-align:center;">
        <a href="#"><li>Sobre o site (em breve)</li></a>
        <a href="howtouse.html"><li>Como usar o site</li></a>
        <a href="logar.php"><li>Log In / Sign Up</li></a>
        <a href="listamarcadores.php"><li>Pontos Sugeridos</li></a>
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
                color: 'purple'
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

        // adicionar novos pontos ao mapa
        <?php require_once 'pontos.php'; ?>

        // Obtém o elemento do botão pelo ID
        var toggleButton = document.getElementById('toggleButton');

        // Adiciona um ouvinte de evento de clique ao botão
        toggleButton.addEventListener('click', function() {
            // Define a URL para a qual você deseja redirecionar a página
            var novaURL = 'index2.php'; // Substitua com a URL desejada

            // Redireciona para a nova URL
            window.location.href = novaURL;
        });

        map.on('click', function (e) {
    const sideMenu = document.getElementById('side-menu');
    if (sideMenu.classList.contains('open')) {
        sideMenu.classList.remove('open');
    } else {
        const latitude = e.lngLat.lat;
        const longitude = e.lngLat.lng;

        // Feche todos os popups abertos
        const openPopups = document.querySelectorAll('.mapboxgl-popup');
        openPopups.forEach(popup => popup.remove());

        // Verificar se o ponto clicado já existe
        const features = map.queryRenderedFeatures(e.point, { layers: ['marker-layer'] });
        if (features.length === 0) {
            // Se não houver marcador existente, abra a caixa de registro
            abrirCaixaRegistro(latitude, longitude);
        } else {
            const marker = features[0];
            const popup = marker.getPopup();

            if (popup.isOpen()) {
                // Se a caixa de informações do ponto estiver aberta, feche-a
                popup.remove();
            } else {
                // Se a caixa do marcador não estiver aberta, abra-a
                popup.addTo(map);
            }
        }
    }
});


let currentPopup; // Variável para manter o popup atual

// Função para abrir a caixa de registro
function abrirCaixaRegistro(latitude, longitude) {
    if (currentPopup) {
        currentPopup.remove(); // Remove o popup anterior, se houver
    }

    const popup = new mapboxgl.Popup({
        closeButton: false,
        className: 'popup',
        anchor: 'bottom'
    });

    popup.setHTML(`
        <div class='popup-title' style="text-align: center;"><h3>Registrar Ponto</h3></div>
        <div class='popup-content'>
            <form method='POST' action='registrar_ponto.php' style="text-align: center;">
                <input type='hidden' name='latitude' value='${longitude}'>
                <input type='hidden' name='longitude' value='${latitude}'>

                <div class='form-group' style="text-align: center;">
                    <input placeholder="Digite o nome do Hospital" type='text' name='titulo' id='titulo' required>
                </div>

                <div class='form-group' style="text-align: center;">
                <input placeholder="Digite a rua do Hospital" type='text' name='rua' id='rua' required>
                <input placeholder="Digite a cidade do Hospital" type='text' name='cidade' id='titulo' required>
                <input placeholder="Digite o estado do Hospital" type='text' name='estado' id='estado' required>
                <input placeholder="Digite o horário do Hospital" type='text' name='horario' id='horario' required>
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
    currentPopup = popup;

    popup.on('close', () => {
        currentPopup = null; // Limpa a referência ao popup atual
        marker.remove(); // Remover o marcador ao fechar a caixa
    });

    // Ajustar a visão do mapa para mostrar completamente o popup
    map.easeTo({
        center: [longitude, latitude+0.005],
        zoom: 14 // Zoom desejado
    });
}
    </script>
</body>
</html>
