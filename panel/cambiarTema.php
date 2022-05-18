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

<body class="configPanel temaPanel">
    <img src="../imagenes/iconos/exit.svg" alt="Cerrar Sesión" class="icoCerrarSesion"></a>
    <div class="fondoPanel">
        <?= menuPanel() ?>
        <div class="contenedorPagPanel ">
            <div class="titPrincPanel">
                <h1>Panel Control <?= (!empty($nombCasa) ? " - " . $nombCasa : "") ?></h1>
            </div>

            <div class="contConfiguracion paneles">
                <div class="titConfigPanel">
                    <p>Cambiar tema de la página principal del panel</p>
                </div>
                <div class="tiempoConfig">
                    <form action="actualizarDatos.php" method="post" id="formTema">
                        <hr>

                        <div class="contTemaCols">
                            <div class="temaColIzq">
                                <label for="colorFondoPagPanel">Color de fondo de la Página Principal del panel: </label>
                                <input type="color" name="colorFondoPagPanel" id="colorFondoPagPanel" data-val='-1'>

                                <br>

                                <label for="colorFondoPanel">Color de fondo de los Paneles: </label>
                                <input type="color" name="colorFondoPanel" id="colorFondoPanel" data-val='-1'>

                                <br>

                                <label for="colorTitulosPanel">Color de los títulos de los Paneles: </label>
                                <input type="color" name="colorTitulosPanel" id="colorTitulosPanel" data-val='-1'>

                            </div>
                            <div class="temaColDer">
                                <br>

                                <label for="colorNombSensores">Color de los nombres de los sensores: </label>
                                <input type="color" name="colorNombSensores" id="colorNombSensores" data-val='-1'>

                                <br>

                                <label for="tamanoLetraTit">Tamaño letra títulos: </label>
                                <select name="tamanoLetraTit" id="tamanoLetraTit">
                                    <option value="-1" selected="selected">-- Seleccionar opción --</option>
                                    <option value="1">1x</option>
                                    <option value="1.5">1.5x</option>
                                    <option value="2">2x</option>
                                </select>

                                <br>

                                <label for="tamanoLetraNombSensores">Tamaño letra nombres sensores: </label>
                                <select name="tamanoLetraNombSensores" id="tamanoLetraNombSensores">
                                    <option value="-1" selected="selected">-- Seleccionar opción --</option>
                                    <option value="1">1x</option>
                                    <option value="1.5">1.5x</option>
                                    <option value="2">2x</option>
                                </select>

                                <br>
                            </div>
                        </div>


                        <div class="btnEnviarConf">
                            <button type="button" class="btnDefecto">Por defecto</button>
                            <input type="submit" value="Guardar">
                        </div>
                    </form>
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
        $(document).on('click', '#colorFondoPagPanel, #colorFondoPanel, #colorTitulosPanel, #colorNombSensores, #tamanoLetraTit, #tamanoLetraNombSensores', function(){
            $(this).data('val', '1');
        });


        $("#formTema").submit(function(e) {
            e.preventDefault();
            var datos={};

            ($('#colorFondoPagPanel').data('val') == 1) ? datos.colorFondoPagPanel = $('#colorFondoPagPanel').val() : datos.colorFondoPagPanel = "";
            ($('#colorFondoPanel').data('val') == 1) ? datos.colorFondoPanel = $('#colorFondoPanel').val() : datos.colorFondoPanel = "";
            ($('#colorTitulosPanel').data('val') == 1) ? datos.colorTitulosPanel = $('#colorTitulosPanel').val() : datos.colorTitulosPanel = "";
            ($('#colorNombSensores').data('val') == 1) ? datos.colorNombSensores = $('#colorNombSensores').val() : datos.colorNombSensores = "";
            ($('#tamanoLetraTit').data('val') == 1) ? datos.tamanoLetraTit = $('#tamanoLetraTit').val() : datos.tamanoLetraTit = "";
            ($('#colorNombSensores').data('val') == -1) ?  datos.colorNombSensores = "" : datos.colorNombSensores = $('#colorNombSensores').val();
            ($('#tamanoLetraNombSensores').data('val') == -1) ?  datos.tamanoLetraNombSensores = "" : datos.tamanoLetraNombSensores = $('#tamanoLetraNombSensores').val();

            console.log(datos);

            $.ajax({
                    method: "POST",
                    url: "./actualizarDatosTema.php",
                    data: datos,
                })
                .done(function(data) {
                    console.log(data);
                    swal("OK!", "Datos del tema actualizados", "success");
                    $("#colorFondoPagPanel, #colorFondoPanel, #colorTitulosPanel, #colorNombSensores, #tamanoLetraTit, #tamanoLetraNombSensores").data('val', '-1');
                })
                .fail(function() {
                    alert('Error!!');
                });;
        });
        
        $(document).on("click", ".btnDefecto", function() {

            $.ajax({
                method: "POST",
                url: "./actualizarDatosTema.php",
                data: {
                    defecto: "1",
                },
            })
            .done(function(data) {
                console.log(data);
                swal("OK!", "Datos del tema actualizados", "success");
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
            $(".iconoMP:nth-child(3)").css("background-color", "#293B97");

            if ($(".formaCirculo").attr("class").split(' ')[1] == "conectado") {
                $(".textoEstadoArduino").html("Conectado");
            } else if ($(".formaCirculo").attr("class").split(' ')[1] == "desconectado") {
                $(".textoEstadoArduino").html("Desconectado");
            }
        });
    </script>
</body>

</html>