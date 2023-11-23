<!DOCTYPE html>
<html>

<head>
<link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lagos GeoSaude</title>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.3/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.3/mapbox-gl-geocoder.css" type="text/css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"/>
</head>

<body>
    <div id="map"></div>

    <section class="sidebar">
      <div class="nav-header">
        <pre class="logo"> Lagos
      GeoSaúde</pre>
        <i class="bx bx-menu btn-menu"></i>
      </div>
      <ul class="nav-links">
        <li>
          <a href="index.php">
            <i class="bx bx-home-alt-2"></i>
            <span class="title">Introdução</span>
          </a>
          <span class="tooltip">Introdução</span>
        </li>
        <li>
          <a href="listamarcadores.php">
            <i class="bx bx-book-content"></i>
            <span class="title">Lista dos pontos</span>
          </a>
          <span class="tooltip">Lista dos pontos</span>
        </li>
        <li style="position: absolute; bottom: 0; margin-bottom: 30px;">
        <?php
        session_start();
        if (!isset($_SESSION['usuario'])) {
          echo '
              <a href="logar.php">
                  <i class="bx bx-log-in-circle"></i>
                  <span class="title" style="padding-right: 28px;">Entrar na sua conta</span>
              </a>
              <span class="tooltip">Entrar na sua conta</span>';
      } else {
        echo '
        <a href="logout-mapa2.php" id="sair">
            <i class="bx bx-log-out-circle" style="color: white;"></i>
            <span class="title" style="padding-right: 73px; color: white;">Sair da conta</span>
        </a>
        <span class="tooltip">Sair da conta</span>';
      }      
      session_write_close();
        ?>
        </li>
      </ul>
    </section>

    <ul class="ul-botoes">
        <li>
            <a href="howtouse.html" title="Ver as utilidades dos botões">
                <button class="mug-button">
                <i class="fa fa-question" aria-hidden="true"></i>
                </button>
            </a>
        </li>
        <li>
            <a href="mapa.php" title="Mudar visibilidade dos pontos">
                <button class="mug-button">
                <i class="fa fa-eye" id="facolor"></i>
                </button>
            </a>
        </li>
        <li>
            <button id="location-button" class="mug-button" title="Se localizar">
                <i class="fa-solid fa-location-crosshairs" id="facolor"></i>
            </button>
        </li>
        <li>
            <a href="https://github.com/joenyrcouto/Lagos_GeoSaude/tree/main" target="_blank" title="Código fonte do site">
                <button id="mug-button" class="mug-button">
                    <i class="fa-brands fa-github"></i>
                </button>
            </a>
        </li>
    </ul>

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
        map.on('load', function () {
            map.removeLayer('road-label');
            map.removeLayer('poi-label');
        });

        // adicionar novos pontos ao mapa
        <?php require_once 'pontos_indicados.php'; ?>

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

        map.on('click', function (e) {
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
            <style>
            .form-control::placeholder { text-align: center; font-size: 20px; }
            </style>
            <div class='popup-title' style="text-align: center;"><h3>Registrar Ponto</h3></div>
            <div class='popup-content'>
                <form method='POST' action='registrar_ponto2.php' style="text-align: center;">
                    <input type='hidden' name='latitude' value='${longitude}'>
                    <input type='hidden' name='longitude' value='${latitude}'>
    
                    <div class='form-group' style="text-align: center;">
                        <p style="font-size:16px">Digite as informações da institução médica:<p>
                    </div>
    
                    <div class='form-group' style="text-align: center;">
                        <input class='form-control' style='height:30px; border: 1px solid grey' placeholder="Nome" type='text' name='titulo' id='titulo' required>
                    </div>
    
                    <div class='form-group' style="text-align: center;">
                        <input class='form-control' style='height:30px; border:1px solid grey; margin-bottom:2px;' placeholder="Rua" type='text' name='rua' id='rua' required>
                        <input class='form-control' style='height:30px; border:1px solid grey; margin-bottom:2px;' placeholder="Cidade" type='text' name='cidade' id='titulo' required>
                        <input class='form-control' style='height:30px; border:1px solid grey; margin-bottom:2px;' placeholder="Estado" type='text' name='estado' id='estado' required>
                        <input class='form-control' style='height:30px; border:1px solid grey' placeholder="Hora e dias aberto" type='text' name='horario' id='horario' required>
                    </div>
    
                    <button type='submit' class='btn btn-primary' style="background-color: rgb(225, 200, 31); color: black; border-color: black;">Registrar</button>
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
                center: [longitude, latitude + 0.005],
                zoom: 14 // Zoom desejado
            });
        }

        const searchInput = document.querySelector('.mapboxgl-ctrl-geocoder--input');
        searchInput.addEventListener('click', function () {
            // Limpar o valor do campo de pesquisa
            searchInput.value = '';
        });

        //menu
        const btn_menu = document.querySelector(".btn-menu");
      const side_bar = document.querySelector(".sidebar");

      btn_menu.addEventListener("click", function () {
        side_bar.classList.toggle("expand");
        changebtn();
      });

      function changebtn() {
        if (side_bar.classList.contains("expand")) {
          btn_menu.classList.replace("bx-menu", "bx-menu-alt-right");
        } else {
          btn_menu.classList.replace("bx-menu-alt-right", "bx-menu");
        }
      }

      const btn_theme = document.querySelector(".theme-btn");
      const theme_ball = document.querySelector(".theme-ball");

      const localData = localStorage.getItem("theme");

      if (localData == null) {
        localStorage.setItem("theme", "light");
      }

      if (localData == "dark") {
        document.body.classList.add("dark-mode");
        theme_ball.classList.add("dark");
      } else if (localData == "light") {
        document.body.classList.remove("dark-mode");
        theme_ball.classList.remove("dark");
      }

      btn_theme.addEventListener("click", function () {
        document.body.classList.toggle("dark-mode");
        theme_ball.classList.toggle("dark");
        if (document.body.classList.contains("dark-mode")) {
          localStorage.setItem("theme", "dark");
        } else {
          localStorage.setItem("theme", "light");
        }
      });
    </script>
</body>

</html>