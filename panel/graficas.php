<?php
session_start();
include("../inc.php");
comprobarSesionVacia();
//Zona horaria
date_default_timezone_set("Europe/Madrid");

$infoUsuario = $listado->recuperarDatosUsuario($_SESSION['nickname']);
$nombCasa = $listado->buscarNombreCasa($_SESSION['idCasa']);
$datosSensoresUsuario = $listado->listarSensoresValoresPorCasa($_SESSION['idCasa']);

    //Coger registros de temperatura del día, mes y año
    $registrosTemperaturaDia = $listado->obtenerRegistrosTempHum($_SESSION['idCasa'], "temperatura", date('Y-m-d 00:00'), date('Y-m-d 23:59'));
    $registrosTemperaturaMes = $listado->obtenerRegistrosTempHum($_SESSION['idCasa'], "temperatura", date('Y-m-01 00:00'), date('Y-m-t 23:59'));
    $registrosTemperaturaAño = $listado->obtenerRegistrosTempHum($_SESSION['idCasa'], "temperatura", date('Y-01-01 00:00'), date('Y-12-t 23:59'));

    //Coger registros de humedad del día, mes y año
    $registrosHumedadDia = $listado->obtenerRegistrosTempHum($_SESSION['idCasa'], "humedad", date('Y-m-d 00:00'), date('Y-m-d 23:59'));
    $registrosHumedadMes = $listado->obtenerRegistrosTempHum($_SESSION['idCasa'], "humedad", date('Y-m-01 00:00'), date('Y-m-t 23:59'));
    $registrosHumedadAño = $listado->obtenerRegistrosTempHum($_SESSION['idCasa'], "humedad", date('Y-01-01 00:00'), date('Y-12-t 23:59'));

    //Coger el registro Máximo y Mínimo de temperatura del día 
    $registrosTemperaturaDiaMin = $listado->tempHumMinFecha($_SESSION['idCasa'], "temperatura", date('Y-m-d 00:00'), date('Y-m-d 23:59'));
    $registrosTemperaturaDiaMax = $listado->tempHumMaxFecha($_SESSION['idCasa'], "temperatura", date('Y-m-d 00:00'), date('Y-m-d 23:59'));

    //Coger el registro Máximo y Mínimo de temperatura del Mes 
    $registrosTemperaturaMesMin = $listado->tempHumMinFecha($_SESSION['idCasa'], "temperatura", date('Y-m-01 00:00'), date('Y-m-t 23:59'));
    $registrosTemperaturaMesMax = $listado->tempHumMaxFecha($_SESSION['idCasa'], "temperatura", date('Y-m-01 00:00'), date('Y-m-t 23:59'));

    //Coger el registro Máximo y Mínimo de temperatura del Año 
    $registrosTemperaturaAñoMin = $listado->tempHumMinFecha($_SESSION['idCasa'], "temperatura", date('Y-01-01 00:00'), date('Y-12-t 23:59'));
    $registrosTemperaturaAñoMax = $listado->tempHumMaxFecha($_SESSION['idCasa'], "temperatura", date('Y-01-01 00:00'), date('Y-12-t 23:59'));

    //Coger el registro Máximo y Mínimo de humedad del día 
    $registrosHumedadDiaMin = $listado->tempHumMinFecha($_SESSION['idCasa'], "humedad", date('Y-m-d 00:00'), date('Y-m-d 23:59'));
    $registrosHumedadDiaMax = $listado->tempHumMaxFecha($_SESSION['idCasa'], "humedad", date('Y-m-d 00:00'), date('Y-m-d 23:59'));

    //Coger el registro Máximo y Mínimo de humedad del Mes 
    $registrosHumedadMesMin = $listado->tempHumMinFecha($_SESSION['idCasa'], "humedad", date('Y-m-01 00:00'), date('Y-m-t 23:59'));
    $registrosHumedadMesMax = $listado->tempHumMaxFecha($_SESSION['idCasa'], "humedad", date('Y-m-01 00:00'), date('Y-m-t 23:59'));

    //Coger el registro Máximo y Mínimo de humedad del Año 
    $registrosHumedadAñoMin = $listado->tempHumMinFecha($_SESSION['idCasa'], "humedad", date('Y-01-01 00:00'), date('Y-12-t 23:59'));
    $registrosHumedadAñoMax = $listado->tempHumMaxFecha($_SESSION['idCasa'], "humedad", date('Y-01-01 00:00'), date('Y-12-t 23:59'));

/*
    echo "<br>----------------<br>";
    echo "$registrosTemperaturaMesMin<br>";
    echo "$registrosTemperaturaMesMax<br>";
    echo "<br>----------------<br>";
    echo date('Y-01-01 00:00') . "<br>";
    echo date('Y-12-t 23:59');
    echo "<br>----------------<br>";

/*
    var_dump($registrosTemperaturaMes);
    echo "<br>----------------------------<br>";
    echo count($registrosTemperaturaMes) . "<br>";
    echo $registrosTemperaturaMes[0]['fechaRegistro'] . "<br>";
    echo $registrosTemperaturaMes[0]['valor'] . "<br>";
    echo "<br>----------------------------<br>";
*/
//-------------------------------
//TEMPERATURA
    $valoresTempGraficaDia = "";
    for ($i = 0; $i < count($registrosTemperaturaDia); $i++) {
        if (count($registrosTemperaturaDia) - 1 == $i) {
            $valoresTempGraficaDia .= $registrosTemperaturaDia[$i]['valor'];
        } else {
            $valoresTempGraficaDia .= $registrosTemperaturaDia[$i]['valor'] . ",";
        }
    }
//echo $valoresTempGraficaDia . "<br>";

    $fechasTempGraficaDia = "";
    for ($i = 0; $i < count($registrosTemperaturaDia); $i++) {
        if (count($registrosTemperaturaDia) - 1 == $i) {
            $fechasTempGraficaDia .=  "'".date("H:i",  strtotime($registrosTemperaturaDia[$i]['fechaRegistro'])). "'";
        } else {
            $fechasTempGraficaDia .= "'".date("H:i",  strtotime($registrosTemperaturaDia[$i]['fechaRegistro'])). "'" . ",";
        }
    }
//echo $fechasTempGraficaDia . "<br>";

    $valoresTempGraficaMes = "";
    for ($i = 0; $i < count($registrosTemperaturaMes); $i++) {
        if (count($registrosTemperaturaMes) - 1 == $i) {
            $valoresTempGraficaMes .= $registrosTemperaturaMes[$i]['valor'];
        } else {
            $valoresTempGraficaMes .= $registrosTemperaturaMes[$i]['valor'] . ",";
        }
    }
//echo $valoresTempGraficaMes . "<br>";

    $fechasTempGraficaMes = "";
    for ($i = 0; $i < count($registrosTemperaturaMes); $i++) {
        if (count($registrosTemperaturaMes) - 1 == $i) {
            $fechasTempGraficaMes .=  "'".date("Y-m-d",  strtotime($registrosTemperaturaMes[$i]['fechaRegistro'])). "'";
        } else {
            $fechasTempGraficaMes .= "'".date("Y-m-d",  strtotime($registrosTemperaturaMes[$i]['fechaRegistro'])). "'" . ",";
        }
    }
//echo $fechasTempGraficaMes . "<br>";

    $valoresTempGraficaAño = "";
    for ($i = 0; $i < count($registrosTemperaturaAño); $i++) {
        if (count($registrosTemperaturaAño) - 1 == $i) {
            $valoresTempGraficaAño .= $registrosTemperaturaAño[$i]['valor'];
        } else {
            $valoresTempGraficaAño .= $registrosTemperaturaAño[$i]['valor'] . ",";
        }
    }
//echo $valoresTempGraficaAño . "<br>";

    $fechasTempGraficaAño = "";
    for ($i = 0; $i < count($registrosTemperaturaAño); $i++) {
        if (count($registrosTemperaturaAño) - 1 == $i) {
            $fechasTempGraficaAño .=  "'".date("Y-m-d",  strtotime($registrosTemperaturaAño[$i]['fechaRegistro'])). "'";
        } else {
            $fechasTempGraficaAño .= "'".date("Y-m-d",  strtotime($registrosTemperaturaAño[$i]['fechaRegistro'])). "'" . ",";
        }
    }
//echo $fechasTempGraficaAño . "<br>";

//-------------------------------
//HUMEDAD
$valoresHumGraficaDia = "";
for ($i = 0; $i < count($registrosHumedadDia); $i++) {
    if (count($registrosHumedadDia) - 1 == $i) {
        $valoresHumGraficaDia .= $registrosHumedadDia[$i]['valor'];
    } else {
        $valoresHumGraficaDia .= $registrosHumedadDia[$i]['valor'] . ",";
    }
}
//echo $valoresHumGraficaDia . "<br>";

$fechasHumGraficaDia = "";
for ($i = 0; $i < count($registrosHumedadDia); $i++) {
    if (count($registrosHumedadDia) - 1 == $i) {
        $fechasHumGraficaDia .=  "'".date("H:i",  strtotime($registrosHumedadDia[$i]['fechaRegistro'])). "'";
    } else {
        $fechasHumGraficaDia .= "'".date("H:i",  strtotime($registrosHumedadDia[$i]['fechaRegistro'])). "'" . ",";
    }
}
//echo $fechasTempGraficaDia . "<br>";

$valoresHumGraficaMes = "";
for ($i = 0; $i < count($registrosHumedadMes); $i++) {
    if (count($registrosHumedadMes) - 1 == $i) {
        $valoresHumGraficaMes .= $registrosHumedadMes[$i]['valor'];
    } else {
        $valoresHumGraficaMes .= $registrosHumedadMes[$i]['valor'] . ",";
    }
}
//echo $valoresTempGraficaMes . "<br>";

$fechasHumGraficaMes = "";
for ($i = 0; $i < count($registrosHumedadMes); $i++) {
    if (count($registrosHumedadMes) - 1 == $i) {
        $fechasHumGraficaMes .=  "'".date("Y-m-d",  strtotime($registrosHumedadMes[$i]['fechaRegistro'])). "'";
    } else {
        $fechasHumGraficaMes .= "'".date("Y-m-d",  strtotime($registrosHumedadMes[$i]['fechaRegistro'])). "'" . ",";
    }
}
//echo $fechasTempGraficaMes . "<br>";

$valoresHumGraficaAño = "";
for ($i = 0; $i < count($registrosHumedadAño); $i++) {
    if (count($registrosHumedadAño) - 1 == $i) {
        $valoresHumGraficaAño .= $registrosHumedadAño[$i]['valor'];
    } else {
        $valoresHumGraficaAño .= $registrosHumedadAño[$i]['valor'] . ",";
    }
}
//echo $valoresTempGraficaAño . "<br>";

$fechasHumGraficaAño = "";
for ($i = 0; $i < count($registrosHumedadAño); $i++) {
    if (count($registrosHumedadAño) - 1 == $i) {
        $fechasHumGraficaAño .=  "'".date("Y-m-d",  strtotime($registrosHumedadAño[$i]['fechaRegistro'])). "'";
    } else {
        $fechasHumGraficaAño .= "'".date("Y-m-d",  strtotime($registrosHumedadAño[$i]['fechaRegistro'])). "'" . ",";
    }
}
//echo $fechasTempGraficaAño . "<br>";


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

<body class="configPanel graficasPanel">
    <img src="../imagenes/iconos/exit.svg" alt="Cerrar Sesión" class="icoCerrarSesion"></a>
    <div class="fondoPanel">
        <?= menuPanel() ?>
        <div class="contenedorPagPanel ">
            <div class="titPrincPanel">
                <h1>Panel Control <?= (!empty($nombCasa) ? " - " . $nombCasa : "") ?></h1>
            </div>
            <div class="contGraficas contConfiguracion paneles">
                <div class="titConfigPanel">
                    <p>GRÁFICAS</p>
                    <hr>
                </div>
                <div class="grafTemp">
                    <div class="contSupTemp">
                        <div class="filtradoGraficas">
                            <h3>Temperatura</h3>
                            <select name="fechaTemp" id="fechaTemp">
                                <option value="diaTemp">Día</option>
                                <option value="mesTemp">Mes</option>
                                <option value="anhoTemp">Año</option>
                            </select>
                        </div>
                        <div class="iconosTemp">
                            <div class="maxTemp">
                                <img src="../imagenes/iconos/maxTemp.png" alt="Máxima Temperatura" title="Máxima temperatura">
                                <p id="valorMaxTemp">-</p>
                            </div>
                            <div class="minTemp">
                                <img src="../imagenes/iconos/minTemp.png" alt="Mínima Temperatura" title="Mínima temperatura">
                                <p id="valorMinTemp">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="contInfTemp">
                        <div id="contGraficaTemp">

                        </div>
                    </div>
                </div>
                <div class="grafHum">
                    <div class="contSupHum">
                        <div class="filtradoGraficas">
                            <h3>Humedad</h3>
                            <select name="fechaHum" id="fechaHum">
                                <option value="diaHum">Día</option>
                                <option value="mesHum">Mes</option>
                                <option value="anhoHum">Año</option>
                            </select>
                        </div>
                        <div class="iconosHum">
                            <div class="maxHum">
                                <img src="../imagenes/iconos/maxHum.png" alt="Máxima Humedad" title="Máxima humedad">
                                <p id="valorMaxHum">-</p>
                            </div>
                            <div class="minHum">
                                <img src="../imagenes/iconos/minHum.png" alt="Mínima Humedad" title="Mínima humedad">
                                <p id="valorMinHum">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="contInfHum">
                        <div id="contGraficaHum">

                        </div>
                    </div>
                </div>
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
        $(document).ready(function(){
            $("#valorMaxTemp").html("<?= (empty($registrosTemperaturaDiaMax) &&  $registrosTemperaturaDiaMax != 0) ? "-" : $registrosTemperaturaDiaMax ?>");
            $("#valorMinTemp").html("<?= (empty($registrosTemperaturaDiaMin) &&  $registrosTemperaturaDiaMin != 0) ? "-"  : $registrosTemperaturaDiaMin ?>");

            var data = [{
                x: [<?=$fechasTempGraficaDia?>],
                y: [<?=$valoresTempGraficaDia?>],
                type: 'scatter'
            }];

            var layout = {
                title: 'Hoy',
            };

            var config = {responsive: true}

            Plotly.newPlot('contGraficaTemp', data, layout, config);

            $("#valorMaxHum").html("<?= (empty($registrosHumedadDiaMax) &&  $registrosHumedadDiaMax != 0) ?  "-" : $registrosHumedadDiaMax ?>");
            $("#valorMinHum").html("<?= (empty($registrosHumedadDiaMin) &&  $registrosHumedadDiaMin != 0) ? "-"  : $registrosHumedadDiaMin ?>");

            var dataHum = [{
                x: [<?=$fechasHumGraficaDia?>],
                y: [<?=$valoresHumGraficaDia?>],
                type: 'scatter'
            }];

            var layout = {
                title: 'Hoy',
            };

            var config = {responsive: true}

            Plotly.newPlot('contGraficaHum', dataHum, layout, config);
           

            /* Poner fondo sobre la opción seleccionada en el menu vertical */
            $(".iconoMP:nth-child(2)").css("background-color", "#293B97");

            if ($(".formaCirculo").attr("class").split(' ')[1] == "conectado") {
                $(".textoEstadoArduino").html("Conectado");
            } else if ($(".formaCirculo").attr("class").split(' ')[1] == "desconectado") {
                $(".textoEstadoArduino").html("Desconectado");
            }
        });

     


        $(document).on("change", "#fechaTemp", function() {
            if ($("#fechaTemp option:selected").text() == "Día") {
                //console.log("Has elegido dia");
                $("#valorMaxTemp").html("<?= (empty($registrosTemperaturaDiaMax) &&  $registrosTemperaturaDiaMax != 0) ?  "-" : $registrosTemperaturaDiaMax ?>");
                $("#valorMinTemp").html("<?= (empty($registrosTemperaturaDiaMin) && $registrosTemperaturaDiaMin != 0) ? "-"  : $registrosTemperaturaDiaMin ?>");

                var dataTemp = [{
                    x: [<?=$fechasTempGraficaDia?>],
                    y: [<?=$valoresTempGraficaDia?>],
                    type: 'scatter'
                }];

                var layout = {
                    title: 'Hoy',
                };


                Plotly.newPlot('contGraficaTemp', dataTemp, layout);
                
                
            } else if ($("#fechaTemp option:selected").text() == "Mes") {
                //console.log("Has elegido mes");
                $("#valorMaxTemp").html("<?= (empty($registrosTemperaturaMesMax)) ?  "-" : $registrosTemperaturaMesMax ?>");
                $("#valorMinTemp").html("<?= (empty($registrosTemperaturaMesMin)) ? "-"  : $registrosTemperaturaMesMin ?>");

                var dataTemp = [{
                    x: [<?=$fechasTempGraficaMes?>],
                    y: [<?=$valoresTempGraficaMes?>],
                    type: 'scatter'
                }];

                var layout = {
                    autorange: true,
                    xaxis: {
                        range: ['<?=date('Y-m-01')?>', '<?=date('Y-m-t')?>'],
                        type: 'date'
                    }
                };


                Plotly.newPlot('contGraficaTemp', dataTemp, layout);

            } else if ($("#fechaTemp option:selected").text() == "Año") {
                //console.log("Has elegido año");
                $("#valorMaxTemp").html("<?= (empty($registrosTemperaturaAñoMax) && $registrosTemperaturaAñoMax != 0) ?  "-" : $registrosTemperaturaAñoMax ?>");
                $("#valorMinTemp").html("<?= (empty($registrosTemperaturaAñoMin) && $registrosTemperaturaAñoMin != 0) ? "-"  : $registrosTemperaturaAñoMin ?>");

                var dataTemp = [{
                    x: [<?=$fechasTempGraficaAño?>],
                    y: [<?=$valoresTempGraficaAño?>],
                    type: 'scatter'
                }];
                /*
                var layout = {
                    autorange: true,
                    xaxis: {
                        range: ['<?=date('Y-01-01')?>', '<?=date('Y-12-t')?>'],
                        type: 'date'
                    }
                };
                */
                Plotly.newPlot('contGraficaTemp', dataTemp);
            }
        });

        $(document).on("change", "#fechaHum", function() {
            if ($("#fechaHum option:selected").text() == "Día") {
                //console.log("Has elegido dia");
                $("#valorMaxHum").html("<?= (empty($registrosHumedadDiaMax) && $registrosHumedadDiaMax != 0) ?  "-" : $registrosHumedadDiaMax ?>");
                $("#valorMinHum").html("<?= (empty($registrosHumedadDiaMin) && $registrosHumedadDiaMin != 0) ? "-"  : $registrosHumedadDiaMin ?>");

                var dataHum = [{
                    x: [<?=$fechasHumGraficaDia?>],
                    y: [<?=$valoresHumGraficaDia?>],
                    type: 'scatter'
                }];

                var layout = {
                    title: 'Hoy',
                };

                Plotly.newPlot('contGraficaHum', dataHum, layout);

                
            } else if ($("#fechaHum option:selected").text() == "Mes") {
                //console.log("Has elegido mes");
                $("#valorMaxHum").html("<?= (empty($registrosHumedadMesMax) && $registrosHumedadMesMax != 0 ) ? "-" : $registrosHumedadMesMax ?>");
                $("#valorMinHum").html("<?= (empty($registrosHumedadMesMin) && $registrosHumedadMesMin != 0) ? "-"  : $registrosHumedadMesMin ?>");

                var dataHum = [{
                    x: [<?=$fechasHumGraficaMes?>],
                    y: [<?=$valoresHumGraficaMes?>],
                    type: 'scatter'
                }];

                var layout = {
                    xaxis: {
                        range: ['<?=date('Y-m-01')?>', '<?=date('Y-m-t')?>'],
                        type: 'date'
                    }
                };


                Plotly.newPlot('contGraficaHum', dataHum, layout);

            } else if ($("#fechaHum option:selected").text() == "Año") {
                //console.log("Has elegido año");
                $("#valorMaxHum").html("<?= (empty($registrosHumedadAñoMax) && $registrosHumedadAñoMax != 0) ?  "-" : $registrosHumedadAñoMax ?>");
                $("#valorMinHum").html("<?= (empty($registrosHumedadAñoMin) && $registrosHumedadAñoMin != 0) ? "-"  : $registrosHumedadAñoMin ?>");

                var dataHum = [{
                    x: [<?=$fechasHumGraficaAño?>],
                    y: [<?=$valoresHumGraficaAño?>],
                    type: 'scatter'
                }];

                /*
                var layout = {
                    autorange: true,
                    xaxis: {
                        range: ['<?=date('Y-01-01')?>', '<?=date('Y-12-t')?>'],
                        type: 'date'
                    }
                };
                */


                //Plotly.newPlot('contGraficaHum', dataHum, layout);
                Plotly.newPlot('contGraficaHum', dataHum);
            }
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
                    console.error('Error: No se han podido recuperar los datos de la temperatura, la humedad y del estado del arduino');
                });
        }

        //Ejecutar funcion repetidamente
        setInterval(function() {
            recuperarValores();
        }, 5000);

        
    </script>
</body>

</html>