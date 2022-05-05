<?php require("../funciones/reutilizables.inc.php") ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?=cabeceraPaginasLanding()?>
</head>

<body id="pagNosotros">
    <?= cabeceraLandingPage() ?>
    <div class="titPagsInternas">
        <h1>SOBRE NOSOTROS</h1>
    </div>
    <div class="contenedorPagsInternas">
        <div class="contenedorIntPagsInternas">
            <div class="contIzqPagsInternas">
                <h1>DOMOTIZATE</h1>
                <img src="<?= localizacion() ?>imagenes/imagenesSitio/nosotros.jpg" alt="Imagen Nosotros">
            </div>
            <div class="contDerPagsInternas">
                <div class="descNosotros">
                    <p>Somos una pequeña empresa que llevamos años especializados en el sector del IOT (Internet Of Things). </p>

                    <p>Hemos decidido emprender y lanzar este proyecto para que la gente pueda disfrutar de nuestra tecnología y así demostrar nuestro potencial y nuestros productos de calidad.</p>

                    <p>Cada día seguimos superándonos, e innovando.</p>

                    <p>Queremos ofrecer la mejor comodidad para nuestros clientes, para que puedan controlar todo su entorno de casa desde su propio teléfono.</p>

                    <p>¿A que esperas? Pruébalo.</p>
                </div>
            </div>
        </div>
    </div>
    <?= piePagLandingPage() ?>
</body>

</html>