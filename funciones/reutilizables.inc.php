<?php

//Obtener ruta donde se localiza el proyecto
function localizacion(){
    //echo dirname( __DIR__ );  

    if (strpos(dirname(__DIR__), "htdocs") === true) {
        $ruta = explode("htdocs", dirname(__DIR__));
        $ruta = $ruta[1];
        $ruta = str_replace('\\', "/", $ruta) . "/";
        return $ruta;
    } else {
        return "/";
    }
}

function cabeceraPaginasPanel(){
    $rutaActual =  $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    echo '<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
          <meta http-equiv="Pragma" content="no-cache">';
    echo '<link rel="stylesheet" href="' . localizacion() . 'css/normalize.css">';
    echo '<link rel="stylesheet" href="' . localizacion() . 'css/estilos.css">';
    echo "<!-- JQUERY --> \n
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
        \n<!--SWEETALERT-->
        \n<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
        \n<!--Plotly - Gráficas -->
        \n	<script src='https://cdn.plot.ly/plotly-2.11.1.min.js'></script>
        \n <!-- DATA TABLES -->
        \n<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css'>
        \n<script type='text/javascript' charset='utf8' src='https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js'></script>  
        \n<link rel='apple-touch-icon' sizes='180x180' href='../imagenes/logos/apple-touch-icon.png'>
        \n<link rel='icon' type='image/png' sizes='32x32' href='../imagenes/logos/favicon-32x32.png'>
        \n<link rel='icon' type='image/png' sizes='16x16' href='../imagenes/logos/favicon-16x16.png'>
        \n<link rel='manifest' href='../manifest.json'>
        \n<link rel='mask-icon' href='../imagenes/safari-pinned-tab.svg' color='#5bbad5'>
        \n<meta name='apple-mobile-web-app-title' content='DOMOTIZATE'>
        \n<meta name='application-name' content='DOMOTIZATE'>
        \n<meta name='msapplication-TileColor' content='#da532c'>
        \n<meta name='theme-color' content='#ffffff'>     
        ";
}

function obtenerDominio(){
    echo $_SERVER["HTTP_HOST"];
}

function cabeceraPaginasLanding(){
?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CACHE-->
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">

    <link rel="apple-touch-icon" sizes="180x180" href="../imagenes/logos/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../imagenes/logos/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../imagenes/logos/favicon-16x16.png">
    <link rel="manifest" href="../manifest.json">
    <link rel="mask-icon" href="../imagenes/logos/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="DOMOTIZATE">
    <meta name="application-name" content="DOMOTIZATE">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>DOMOTIZATE</title>


    <!-- JQUERY -->
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>

    <!--SWEETALERT-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Librería para encriptar las contraseñas-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/core.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/enc-base64.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/enc-utf8.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/hmac.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/md5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/sha256.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    

    <!-- NORMALIZE -->
    <link rel="stylesheet" href="<?= localizacion() ?>css/normalize.css">

    <!-- MIS ESTILOS -->
    <link rel="stylesheet" href="<?= localizacion() ?>landingPage/estilosLandingPage.css">    
    

<?php
}

function cabeceraLandingPage()
{
?>
    <div class="cabeceraPP">
        <div class="barraMenu">
            <div class="logoEmpresa">
                <h3><a href="<?= localizacion() ?>">DOMOTIZATE</a></h3>
            </div>
            <div class="opcMenu">
                <ul class="menuSup">
                    <li><a href="<?= localizacion() ?>">Inicio</a></li>
                    <li><a href="<?= localizacion() ?>landingPage/contacto.php">Contacto</a></li>
                    <li><a href="<?= localizacion() ?>landingPage/nosotros.php">Sobre Nosotros</a></li>
                </ul>
            </div>
            <div class="login">
                <a href="<?= localizacion() ?>landingPage/login.php"><img src="<?= localizacion() ?>imagenes/iconos/login.svg" alt="Acceso Portal Clientes"></a>
            </div>
        </div>
    </div>
<?php
}

function piePagLandingPage()
{
?>
    <div class="piePag">
        <div class="contSupPiePag">
            <div class="logoEmpresa">
                <h3><a href="<?= localizacion() ?>">DOMOTIZATE</a></h3>
            </div>
            <div class="menuInf">
                <ul>
                    <li><a href="<?= localizacion() ?>landingPage/cookies.php">Cookies</a></li>
                    <li><a href="<?= localizacion() ?>landingPage/privacidad.php">Aviso de Privacidad</a></li>
                    <li><a href="<?= localizacion() ?>landingPage/termsCond.php">Términos y Condiciones</a></li>
                </ul>
            </div>
        </div>
        <div class="contInfPiePag">
            <p>Copyright ⓒ 2022 | Todos los derechos reservados.</p>
        </div>
    </div>
<?php
}

function menuPanel(){
?>
    <div class="menuVertPanel">
        <div class="letraUserLogPanel">
            <p><?=strtoupper(substr($_SESSION['nickname'], 0, 1));?></p>
        </div>
        <div class="iconosMenuPanel">
            <div class="iconoMP">
                <a href="index.php"><img src="../imagenes/iconos/homePanel.svg" alt="Botón Inicio" title="Inicio"></a>
            </div>
            <div class="iconoMP">
                <a href="graficas.php"><img src="../imagenes/iconos/stats.png" alt="Botón Estadísticas" title="Estadísticas"></a>
            </div>
            <div class="iconoMP">
                <a href="cambiarTema.php"><img src="../imagenes/iconos/cambiarTema.svg" alt="Botón Cambiar Tema" title="Cambiar Tema"></a>
            </div>
            <div class="iconoMP">
                <a href="configuracion.php"><img src="../imagenes/iconos/opciones.svg" alt="Botón Configuración" title="Configuración"></a>
            </div>
        </div>
        <div class="userMenuInfPanel">
            <img src="/imagenes/iconos/iconoPerfil.jpg" alt="fotoPerfilUsuario">
            <p><?=$_SESSION['nickname']?></p>
        </div>
    </div>

<?php
}

include("configSesiones.inc.php");
?>