<?php
session_start();
include("../inc.php");
comprobarSesionVacia();
//Zona horaria
date_default_timezone_set("Europe/Madrid");

$infoUsuario = $listado->recuperarDatosUsuario($_SESSION['nickname']);
$nombCasa = $listado->buscarNombreCasa($_SESSION['idCasa']);
$datosSensoresUsuario = $listado->listarSensoresValoresPorCasa($_SESSION['idCasa']);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOMOTIZATE</title>
    <?php echo cabeceraPaginasPanel(); ?>
</head>

<body class="configPanel logPanel">
    <img src="../imagenes/iconos/exit.svg" alt="Cerrar Sesión" class="icoCerrarSesion"></a>
    <div class="fondoPanel">
        <?= menuPanel() ?>
        <div class="contenedorPagPanel ">
            <div class="titPrincPanel">
                <h1>Panel Control <?= (!empty($nombCasa) ? " - " . $nombCasa : "") ?></h1>
            </div>
            <div class="contLog contConfiguracion paneles">
                <div class="titConfigPanel">
                    <p>LOG</p>
                    <hr>
                </div>
                <table id="tablaLogs" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>IdRegistro</th>
                            <th>fechaRegistro</th>
                            <th>Valor</th>
                            <th>Nickname</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>IdRegistro</th>
                            <th>fechaRegistro</th>
                            <th>Valor</th>
                            <th>Nickname</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="piePagPanel">
                <div class="estadoArduino">
                    <p>Estado: <span class="textoEstadoArduino">Conectado</span></p>
                    <div class="formaCirculo <?php echo ($datosSensoresUsuario['arduino']) == 0 ?  "desconectado" :  "conectado" ?>"></div>
                </div>
                <div class="logoPagPanel">
                    <p><a href="./index.php">DOMOTIZATE</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
  
        $(document).ready( function () {
            $('#tablaLogs').DataTable( {
                "ajax": "./cogerDatosLog.php",
                "columns": [
                    { "data": "idRegistro" },
                    { "data": "fechaRegistro" },
                    { "data": "valor" },
                    { "data": "nickname" },
                ]
            } );
        });

        $(document).on("click", ".icoCerrarSesion", function() {
            window.location.href = "<?php echo localizacion() . "funciones/cerrarSesion.php" ?>";
        });


        //Cambiar texto CONECTADO / DESCONECTADO
        function recuperarValores() {

            //AJAX - Guardar cambios en la bd
            $.ajax({
                    method: "POST",
                    url: "actualizarValoresPanel.php",
                    data: {
                        idCasa: <?= $_SESSION['idCasa'] ?>,
                    },
                })
                .done(function(data) {
                    //console.log(data);

                    var msgRecibido = data.split(";");
                    var estadoArduino = "arduino=1";
                    var temperatura = "temperatura=1";
                    var humedad = "humedad=1";
                    var valorTemp = msgRecibido[1].split("=");
                    var valorHum = msgRecibido[2].split("=");

                    //console.log(msgRecibido);

                    if (msgRecibido[0].includes(estadoArduino)) {
                        if ($(".formaCirculo").attr("class").split(' ')[1] == "desconectado") {
                            $(".textoEstadoArduino").html("Conectado");
                            $(".formaCirculo").removeClass("desconectado");
                            $(".formaCirculo").addClass("conectado");
                        }
                    } else {
                        if ($(".formaCirculo").attr("class").split(' ')[1] == "conectado") {
                            $(".textoEstadoArduino").html("Desconectado");
                            $(".formaCirculo").removeClass("conectado");
                            $(".formaCirculo").addClass("desconectado");
                        }
                    }

                })
                .fail(function() {
                    alert('Error al realizar la petición!');
                });
        }

        //Ejecutar funcion repetidamente
        setInterval(function() {
            recuperarValores();
        }, 5000);

        $(document).ready(function() {
 
        });
    </script>
</body>

</html>