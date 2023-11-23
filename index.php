<!DOCTYPE html>
<?php
    session_start();
    ?>
<html>
<head>
<link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
<link rel="manifest" href="favicon_io/site.webmanifest">
    <meta charset="UTF-8">
    <title>Lagos geosaude</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital@0;1&display=swap');
    body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: 'Poppins', sans-serif;
}
    
    .tela {
    width: 100%;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;}

    #titulosite {font-size: 28px;
    color: aliceblue;
    flex: 1;}

    #divtitulo {
    background-color: black;
    z-index: 1000;
    width: 100%;
    height: 15vh;
    font-size: 25px;
    padding-left: 40px;
    display: flex;
    position: fixed;
    top: 0;
    transition: opacity 0.5s ease;
}

    .tela1 {background-image: url(https://images.unsplash.com/photo-1451481454041-104482d8e284?q=80&w=1469&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D);
        background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed; /* Isso impede que a imagem role com a página */
    background-position: center center;}

    .tela2 {
    background-image: url(https://images.unsplash.com/photo-1627888464406-a32b933c6b97?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDEwfHx8ZW58MHx8fHx8&w=1000&q=80);
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed; /* Isso impede que a imagem role com a página */
    background-position: center center;}

    .tela3 {
    background-image: url(https://images.unsplash.com/photo-1604552514256-b39243568d92?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDE0fHx8ZW58MHx8fHx8&w=1000&q=80);
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed; /* Isso impede que a imagem role com a página */
    background-position: center center;}

    .infoboxs {
    padding-left: 20px;
    border-radius: 20px;
    background-color: #0f2133c0;
    color: aliceblue;
    position: absolute;
    bottom: 80px;
    left: 80px;
    width: 600px;
    box-shadow: 0 0 10px rgb(0, 0, 0);}

    .h1box {font-size: 50px;}

    .pbox {font-size: 25px;}

    .linkbox {
    text-decoration: none;
    color: #fdfab8;
    display: inline-block;
    padding-bottom: 0cm;
    position: relative;
    }
    .linkbox::before{
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0;
    height: 2px;
    background-color: white;
    width: 0;
    background-image: linear-gradient(to right,#ccbf90, #fffefc);
    transition: width 0.25s ease-out;
    }
.linkbox:hover::before{
    width: 100%;
}

    nav{
    height: 50px;
    position: absolute;
    width: 100%;
    z-index: 990;
    background-color: #0f2d49;
    display: flex;
    justify-content: space-around;
    padding: 15;
    padding: 15px 0;
    margin-top: 0px;
    align-items: center;
    color: #fde6b8;
}
.logo{
    width: 30vw;
    font-weight: 200;
    font-size: 40px;
    margin-top: 12px;
}
.nav-itens{
    list-style: none;
    display: flex;
}
.nav-itens>li>a{
    padding-left: 20px;
    color: #fdfab8;
    font-weight: 700;
    text-decoration: none;
    font-weight: 400;
}

#mapa{
    border: #fde6b8 solid 2px;
    border-radius: 12px;
    padding-right: 20px;
}

.form {
    background-color: #f5f5f5;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.buttons {
    width: 24vw;
    display: flex;
    align-items: center;
}
.login{
    font-weight: 550;
    padding: 10px 40px;
    border: none;
    color: #202020;
    background-color: #fdfab8;
    border-radius: 10px;
    cursor: pointer;
    margin-right: 10px;
}
.cadastro{
    font-weight: 500;
    padding: 10px 40px;
    font-size: 15px;
    border: none;
    color: #ffffff;
    background-color: #091b2b;
    border-radius: 10px;
    cursor: pointer;
}

.menu2 {
    color: rgb(0, 0, 0);
    padding: 10px 0;
    text-align: center;
    font-size: 35px;
}

    .menu-item2 {
    position: relative;
    display: inline-block;
    margin: 0 10px;
}

    .menu-btn2 {
    font-weight: 500;
    color: rgb(0, 0, 0);
    border: none;
    text-align: left;
    padding: 10px;
    cursor: pointer;
}

    .submenu2 {
    display: none;
    position: absolute;
    background-color: rgba(21, 1, 32, 0.722);
    list-style-type: none;
    margin: 0;
    padding: 0;
    left: -30px;
    top: 100%;
    z-index: 1;
}

    .menu-item2:hover .submenu2 {
    border-radius: 12px;
    display: block;
}

    .submenu2 a {
    display: block;
    padding: 10px;
    font-size: 20px;
    text-decoration: none;
    color: white;
}

    .submenu2 a:hover {
    background-color: rgba(21, 1, 32, 0.847);
    border-radius: 12px;
}


    table {
    width: 100%;
    border-collapse: collapse;
        }

    th, td {
    padding: 10px;
    text-align: left;
        }

    th {
    background-color: #1607218b;
    color: #fff;
        }

    tr:nth-child(even) {
    background-color: #20012f5a;
        }
        .carousel-container {
            overflow: hidden;
            position: relative;
        }

        .carousel {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel section {
            min-width: 100%;
            height: 100vh;
        }

        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            cursor: pointer;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 120px;
            width: 40px;
        }

        .arrow.left { left: 20px; }
        .arrow.right { right: 20px; }
    </style>
</head>
<body>

    <nav>
        <div class="logo"><img style="height: 220px; width: 220px;" src="imgs/logo.png" alt=""></div>
            
        <div style="width: 38vw;">
            <ul class="nav-itens">
                <li><a href="mapa.php" id="mapa">Mapa</a></li>
                <li><a href="howtouse.html">Ferramentas</a></li>
                <li><a href="listamarcadores.php">Pontos Sugeridos</a></li>
            </ul>
        </div>
        <div class="buttons">
            <?php
        if (!isset($_SESSION['usuario'])) {
          echo '
          <a href="logar.php"><button class="login">Login</button></a>
          <a href="registrar.php"><button class="cadastro">Cadastro</button></a>';
      } else {
        echo '
        <a href="logout-intro.php"><button class="login" style="background-color: #861010; color: white;">Sair da conta</button></a>';
      }      
      session_write_close();
        ?>
        </div>
    </nav>

    <div class="carousel-container">
        <div class="carousel">
    <section id="tela1" style="position: relative;" title="" class="tela tela1">
        <article class="infoboxs"> 
            <div>
              <h1 class="h1box">Olá, seja bem-vindo ao Lagos GeoSaúde</h1>
            </div>
            <p class="pbox">Este é um site para fins de pesquisa sobre instituições médicas.</p>
          </article>
    </section>

    <section id="tela2" style="position: relative;" title="" class="tela tela2">
        <article class="infoboxs" style="width: 550px;">
            <div>
              <h1 class="h1box">Explore!</h1>
            </div>
            <p class="pbox">Veja algumas de nossas seções: <a href="mapa.php" class="linkbox">mapa</a>, <a href="howtouse.html" class="linkbox">tutorial de ferramentas</a> e <a href="listamarcadores.php" class="linkbox">pontos sugeridos.</a>
            </p>
          </article>
    </section>

    <section id="tela3" style="position: relative;" title="" class="tela tela3">
        <article class="infoboxs">
            <div>
              <h1 class="h1box">Contribua com o site</h1>
            </div>
            <p class="pbox">Faça seu <a href="logar.php" class="linkbox">Cadastro</a> e <a href="logar.php" class="linkbox">Login</a> para liberar ferramentas do site.</p>
          </article>

          <footer style="padding-left: 8px; padding-right: 8px; border-radius: 15px; margin-bottom: 8px; background-color:#0f2133c0; position: absolute; bottom: 0; right: 12px; box-shadow: 0 0 10px black;"><p style="color: rgb(211, 211, 211); text-align: right; margin-right: 3px;">Copyright © 2023 | fotos do site tirada de algum lugar (isso vai ser editado)</p></footer>
    </section>
</div>
<div class="arrow left" onclick="prevSlide()">❮</div>
<div class="arrow right" onclick="nextSlide()">❯</div>
</div>
</body>
<script>
    let currentIndex = 0;

    function showSlide(index) {
        const carousel = document.querySelector('.carousel');
        const screenSize = window.innerWidth;

        currentIndex = index;
        const newTransformValue = -index * screenSize;
        carousel.style.transform = `translateX(${newTransformValue}px)`;
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % 3;
        showSlide(currentIndex);
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + 3) % 3;
        showSlide(currentIndex);
    }
</script>
</html>