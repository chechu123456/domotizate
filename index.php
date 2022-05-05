<?php require("funciones/reutilizables.inc.php")?>

<!DOCTYPE html>
<html lang="es">
<head>
   <?=cabeceraPaginasLanding()?>
</head>
<body>
    <div class="cabeceraPP">
        <div class="barraMenu">
            <div class="logoEmpresa">
                <h3><a href="<?=localizacion()?>">DOMOTIZATE</a></h3>
            </div>
            <div class="opcMenu">
                <ul class="menuSup">
                    <li><a href="<?=localizacion()?>">Inicio</a></li>
                    <li><a href="<?=localizacion()?>landingPage/contacto.php">Contacto</a></li>
                    <li><a href="<?=localizacion()?>landingPage/nosotros.php">Sobre Nosotros</a></li>
                </ul>
            </div>
            <div class="login">
                <a href="<?=localizacion()?>landingPage/login.php"><img src="imagenes/iconos/login.svg" alt="Acceso Portal Clientes"></a>
            </div>
        </div>
        <div class="contenedorSupPP">
            <div class="textoPortada">
                <div class="titTextoPortada">
                    <h1>Domotiza <br> Tu Casa Ahora</h1>
                </div>
                <div class="descTextoPortada">
                    <p>Para obtener más detalles sobre lo que <br> hacemos</p>
                </div>
                <div class="botonTextoPortada">
                    <button class="btn"><a href="<?=localizacion()?>landingPage/contacto.php">Contactar</a></button>
                </div>
            </div>
            <div class="imgPortada">
                <img src="imagenes/imagenesSitio/imagenPortadaCabecera.png" alt="Imagen Portada">
            </div>
        </div>
    </div>
    <div class="contenedorContenidoPP">
        <div class="fila1">
            <div class="titFila">
                <p>NUESTROS SERVICIOS</p>
            </div>
            <div class="descFila">
                <p>Nosotros te ofrecemos servicios de calidad</p>
            </div>
            <div class="contExtServiciosPP">
                <div class="contIntServiciosPP">
                    <img src="imagenes/iconos/soporte.svg" alt="Soporte">
                    <p class="titServicios">
                        Soporte
                    </p>
                    <p class="descServicios">
                        Ante cualquier problema tenemos un servicio técnico
                        durante las 24h para ofrecerle soluciones
                    </p>
                </div>
                <div class="contIntServiciosPP">
                    <img src="imagenes/iconos/accesibilidad.png" alt="Accesibilidad">
                    <p class="titServicios">
                        Accesibilidad
                    </p>
                    <p class="descServicios">
                        Accede desde cualquier dispositivo en cualquier momento
                    </p>
                </div>
                <div class="contIntServiciosPP">
                    <img src="imagenes/iconos/gestion.png" alt="Gestión">
                    <p class="titServicios">
                        Gestión
                    </p>
                    <p class="descServicios">
                        Panel de gestión donde podrás configurar, activar y programar
                        los sensores a tu gusto
                    </p>
                </div>
            </div>
        </div>
        <div class="fila2">
            <div class="imgPanelConfigPP">
                <img src="imagenes/imagenesSitio/imagenPP_PanelConf.png" alt="Iamgen Panel Configuración">
            </div>
            <div class="textoPanelConfigPP">
                <div class="titFila">
                    <p>PANEL CONFIGURACIÓN</p>
                </div>
                <div class="descFila">
                    <p>Tu casa a tu gusto</p>
                </div>
                <p>Dispones de tu propio panel, para ello debes tener una cuenta con nosotros y vinculada a tu casa.</p>
                <p>
                    Posteriormente estarás dentro de él. En él puedes gestionar todos los sensores configurados, cambiar 
                    el tema de tu panel y modificar tus datos personales.
                </p>
            </div>
        </div>
    </div>
    <div class="contenedorSolicitar">
        <div class="solicitudPP">
            <p>NO TE QUEDES CON LAS GANAS!</p>
            <button class="btn"><a href="<?=localizacion()?>landingPage/contacto.php">Solicítalo ya</a></button>
        </div>
        <div class="imgSolicitudPP">
            <img src="imagenes/imagenesSitio/imagenPortada_Solicitar.jpg" alt="Imagen Solicitar Servicio">
        </div>
    </div>
    <div class="piePag">
        <div class="contSupPiePag">
            <div class="logoEmpresa">
                <h3><a href="./index.php">DOMOTIZATE</a></h3>
            </div>
            <div class="menuInf">
                <ul>
                    <li><a href="<?=localizacion()?>landingPage/cookies.php">Cookies</a></li>
                    <li><a href="<?=localizacion()?>landingPage/privacidad.php">Aviso de Privacidad</a></li>
                    <li><a href="<?=localizacion()?>landingPage/termsCond.php">Términos y Condiciones</a></li>
                </ul>
            </div>
        </div>
        <div class="contInfPiePag">
            <p>Copyright ⓒ 2022  |  Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>