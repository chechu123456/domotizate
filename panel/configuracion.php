<?php
session_start();
include("../inc.php");
comprobarSesionVacia();

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

<body class="configPanel">
    <img src="../imagenes/iconos/exit.svg" alt="Cerrar Sesión" class="icoCerrarSesion"></a>
    <div class="fondoPanel">
        <?= menuPanel() ?>
        <div class="contenedorPagPanel ">
            <div class="titPrincPanel">
                <h1>Panel Control <?= (!empty($nombCasa) ? " - " . $nombCasa : "") ?></h1>
            </div>

            <div class="contConfiguracion paneles">
                <div class="titConfigPanel">
                    <p>Configuración Panel</p>
                </div>
                <div class="tiempoConfig">
                    <form action="actualizarDatos.php" method="post" id="formLocalidad">
                        <div class="titSensoresConf">
                            <p>Tiempo</p>
                        </div>
                        <hr>
                        <p>Ubicación</p>
                        <select name="localidad" class="listaLocalidades" id="localidad">
                            <option value="" disabled selected="selected">-- Seleccione una --</option>
                            <option value="ponferrada" <?= ($infoUsuario['localidad'] == "Ponferrada") ? "selected='selected'" : "" ?>>Ponferrada</option>
                            <option value="barco" <?= ($infoUsuario['localidad'] == "barco") ? "selected='selected'" : "" ?>>O Barco de Valdeorras</option>
                            <option value="coruna" <?= ($infoUsuario['localidad'] == "coruna") ? "selected='selected'" : "" ?>>A Coruña</option>
                            <option value="madrid" <?= ($infoUsuario['localidad'] == "madrid") ? "selected='selected'" : "" ?>>Madrid</option>
                            <option value="barcelona" <?= ($infoUsuario['localidad'] == "barcelona") ? "selected='selected'" : "" ?>>Barcelona</option>
                        </select>

                        <div class="btnEnviarConf">
                            <input type="submit" value="Guardar">
                        </div>
                    </form>
                </div>
                <div class="nombSensores">
                    <div class="titSensoresConf">
                        <p>Nombre Sensores</p>
                    </div>
                    <hr>
                    <div class="sensoresConf">
                        <form action="actualizarDatos.php" method="post" id="formNombresSensores">
                            <div class="contSensoresConfig">
                                <div class="colIzqSensoresConf">
                                    <div class="contFormSensores">
                                        <p>Luz cocina</p>
                                        <input type="text" name="lCocina" id="lCocina">
                                    </div>
                                    <div class="contFormSensores">
                                        <p>Luz Salón</p>
                                        <input type="text" name="lSalon" id="lSalon">
                                    </div>
                                    <div class="contFormSensores">
                                        <p>Luz Baño</p>
                                        <input type="text" name="lBano" id="lBano">
                                    </div>
                                    <div class="contFormSensores">
                                        <p>Luz Hab1</p>
                                        <input type="text" name="lHab1" id="lHab1">
                                    </div>
                                    <div class="contFormSensores">
                                        <p>Luz Hab2</p>
                                        <input type="text" name="lHab2" id="lHab2">
                                    </div>
                                    <div class="contFormSensores">
                                        <p>Alarma</p>
                                        <input type="text" name="alarma" id="alarm">
                                    </div>
                                </div>
                                <div class="colDerSensoresConf">
                                    <div class="contFormSensores">
                                        <p>Luz Planta B</p>
                                        <input type="text" name="lPlantaB" id="lPlantaB">
                                    </div>
                                    <div class="contFormSensores">
                                        <p>Luz Planta A</p>
                                        <input type="text" name="lPlantaA" id="lPlantaA">
                                    </div>
                                    <div class="contFormSensores">
                                        <p>Puerta Garaje</p>
                                        <input type="text" name="puertaGaraje" id="garaje">
                                    </div>
                                    <div class="contFormSensores">
                                        <p>Ventilador</p>
                                        <input type="text" name="ventilador" id="ventilador">
                                    </div>
                                    <div class="contFormSensores">
                                        <p>Puerta Principal</p>
                                        <input type="text" name="puertaPrincipal" id="puertaPrincipal">
                                    </div>
                                </div>
                            </div>
                            <div class="btnEnviarConf">
                                <input type="submit" value="Guardar">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="log">
                    <div class="titLog">
                        <p>Log</p>
                    </div>
                    <hr>
                    <div class="contenidoLog">
                        <a href="./logCasa.php"><button class="mostrarLog">Mostrar log de las acciones de la casa</button></a>
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
        $("#formLocalidad").submit(function(e) {
            e.preventDefault();

            $.ajax({
                    method: "POST",
                    url: "./actualizarDatos.php",
                    data: {
                        localidad: $('#localidad').val(),
                    },
                })
                .done(function(data) {
                    console.log(data);
                    swal("OK!", "Localidad Actualizada", "success");
                })
                .fail(function() {
                    alert('Error!!');
                });;
        });

        $("#formNombresSensores").submit(function(e) {
            e.preventDefault();
            $.ajax({
                    method: "POST",
                    url: "./actualizarDatos.php",
                    //Los datos q envio:
                    // - Primer valor, es el valor del POST del  fichero "procesador"
                    // - Segundo valor es lo que almacena el input del formulario
                    data: {
                        lCocina: $('#lCocina').val(),
                        lSalon: $('#lSalon').val(),
                        bano: $('#lBano').val(),
                        lHab1: $('#lHab1').val(),
                        lHab2: $('#lHab2').val(),
                        alarma: $('#alarm').val(),
                        lPlantaA: $('#lPlantaA').val(),
                        lPlantaB: $('#lPlantaB').val(),
                        puertaGaraje: $('#garaje').val(),
                        ventilador: $('#ventilador').val(),
                        puertaPrincipal: $('#puertaPrincipal').val(),
                    },
                })
                .done(function(data) {
                    swal("OK!", "Datos Actualizados", "success");
                })
                .fail(function() {
                    alert('Error!!');
                });;
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

        $(document).ready(function() {
            /* Poner fondo sobre la opción seleccionada en el menu vertical */
            $(".iconoMP:last-child()").css("background-color", "#293B97");

            if ($(".formaCirculo").attr("class").split(' ')[1] == "conectado") {
                $(".textoEstadoArduino").html("Conectado");
            } else if ($(".formaCirculo").attr("class").split(' ')[1] == "desconectado") {
                $(".textoEstadoArduino").html("Desconectado");
            }
        });
    </script>
</body>

</html>