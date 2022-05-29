<?php
    session_start();
    include("../inc.php");
    comprobarSesionVacia();
    //Zona horaria
    date_default_timezone_set("Europe/Madrid");
?>

<script>var pruebaDatos = [];</script>
<script>var pruebaDatosArray = [];</script>

<?php
$infoUsuario = $listado->recuperarDatosUsuario($_SESSION['nickname']);
$nombCasa = $listado->buscarNombreCasa($_SESSION['idCasa']);
$datosSensoresUsuario = $listado->listarSensoresValoresPorCasa($_SESSION['idCasa']);

//Recoger los datos de la base de datos y guardarlos en una variable
$registrosCasa = $listado->listarRegistrosCasa($_SESSION['idCasa']);
$datosObjeto = $registrosCasa->data;
if(count($registrosCasa->data) <= 3000){
    $numDatosRecibir = count($registrosCasa->data);
}else{
    $numDatosRecibir = 3000;
}

echo "<script>";
    for($i= 0; $i < $numDatosRecibir; $i++){
        $datosTabla = $registrosCasa->data[$i];
        echo " pruebaDatos = [];\n";

        foreach ($datosTabla as $item => $value){
            echo "pruebaDatos.push('" . $value   . "');\n";
        }

        echo "pruebaDatosArray[$i] = pruebaDatos;\n";
    }

echo "</script>";

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
                            <th>Sensor</th>
                            <th>Fecha Registro</th>
                            <th>Valor</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Sensor</th>
                            <th>Fecha Registro</th>
                            <th>Valor</th>
                            <th>Usuario</th>
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
            if(navigator.onLine) {
                var tabla = $('#tablaLogs').DataTable( {
                    "ajax": "./cogerDatosLog.php",
                    "columns": [
                        { "data": "nombsensor" },
                        { "data": "fechaRegistro" },
                        { "data": "valor" },
                        { "data": "nickname" },
                        ],
                    order: [[1, 'desc']],
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ entradas",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords": "Sin resultados encontrados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Ultimo",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                } );
            }else{
                $('#tablaLogs').DataTable({
                    data: pruebaDatosArray,
                    order: [[1, 'desc']],
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ entradas",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords": "Sin resultados encontrados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Ultimo",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                });
            }


            //Cada vez que se carguen los datos de la tabla, cambiar los valores
            //de la columna valor de cualquier sensor que no sea de temperatura y humedad
            //por Texto
            $('#tablaLogs').DataTable().on("draw.dt", function(){

                $('tbody tr').each(function(){
                    var nombSensor = $(this).children("td:nth-child(1)");
                    //console.log(nombSensor.html());

                    if(nombSensor.html() != "temperatura" && nombSensor.html() != "humedad" && nombSensor.html() != "ascensor" ){
                        var valorSensor = $(this).children("td:nth-child(3)");
                        //console.log(valorSensor.html());

                        if(valorSensor.html() == "0" || valorSensor.html() == "E"){
                            valorSensor.html("Apagado");
                        }else if(valorSensor.html() == "1"){
                            valorSensor.html("Encendido");
                        }
                        
                    }else if( nombSensor.html() == "ascensor" ){
                        var valorSensor = $(this).children("td:nth-child(3)");
                        //console.log("----------\n"+valorSensor.html());

                        if(valorSensor.html() == "0"){
                            valorSensor.html("Planta Baja");
                        }else if(valorSensor.html() == "1"){
                            valorSensor.html("Planta Alta");
                        }else if(valorSensor.html() == "E" ){
                            valorSensor.html("Parado");
                        }
                    }
                });
            
            });

            
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