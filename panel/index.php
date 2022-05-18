<?php
    session_start();
    include("../inc.php");
    comprobarSesionVacia();
    //Zona horaria
    date_default_timezone_set("Europe/Madrid");
    //Pasar los meses de Date a español
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");


    $datosUsuario = $listado->recuperarDatosUsuario($_SESSION['nickname']);
    $datosSensoresUsuario = $listado->listarSensoresValoresPorCasa($datosUsuario['idCasa']);
    $nombresSensoresCasa = $listado->listarNombresSensoresWeb($_SESSION['idCasa']);
    $idTemaUsuario = $listado->obtenerIdTemaUsuario($_SESSION['nickname']);
    $datosTema = $listado->obtenerDatosTema($idTemaUsuario);

    //var_dump($datosTema);
    //$listadoSensores = var_dump($datosSensoresUsuario);
    //var_dump($nombresSensoresCasa);
    $localidad = $datosUsuario['localidad'];
    if(!empty($localidad)){
        switch($localidad){
            case "ponferrada":
                $codigoLocalidad = "24115";
                break;
            case "barco":
                $codigoLocalidad = "32009";
                break;
            case "coruna":
                $codigoLocalidad = "15030";
                break;
            case "madrid":
                $codigoLocalidad = "28079";
                break;
            case "barcelona":
                $codigoLocalidad = "08019";
                break;
        }
    }

    $url = "http://www.aemet.es/xml/municipios/localidad_$codigoLocalidad.xml";
    $xml = simplexml_load_string(file_get_contents($url));
    if($xml){

        //Para visualizar el nombre lo podemos hacer de dos formas
        $nombreSitio = $xml -> nombre. "<br>"; //en este caso no se tiene en cuenta root
        $items = $xml -> prediccion -> dia;

        //Coger temperatura que más se aproxime a la hora del día
        $horaActual = date('G');
        if(!empty($horaActual)){
            if( ($horaActual >= 3) && ($horaActual < 9) ){
                $temp = $items[0] -> temperatura -> dato[0];
            }else if( ($horaActual >= 9) && ($horaActual < 15) ){
                $temp = $items[0] -> temperatura -> dato[1];
            }else if( ($horaActual >= 15) && ($horaActual < 21) ){
                $temp = $items[0] -> temperatura -> dato[2];
            }else if( ($horaActual >= 21) && ($horaActual < 3) ){
                $temp = $items[0] -> temperatura -> dato[3];
            }else{
                $temp = "<div class='errorDatosTiempo'>SIN DATOS</div>";
            }
        }else{
            $temp = "<div class='errorDatosTiempo'>SIN DATOS</div>";
        }

        //echo $temp;
        //echo $items[0] -> temperatura -> dato[2];
        $estadoDia = $items[0] -> estado_cielo[2] -> attributes() -> descripcion;

        if(empty($estadoDia)){
            $img = "/imagenes/iconos/advertencia.png";
        }else{
            if(  strpos($estadoDia, "soleado") || strpos($estadoDia, "poco nuboso") ||  strpos($estadoDia, "Despejado") ){
                $img = "/imagenes/iconos/soleado.svg";
            }else if(strpos($estadoDia, "nuboso")){
                $img = "/imagenes/iconos/nublado.svg";
            }else if(strpos($estadoDia, "lluvia")){
                $img = "/imagenes/iconos/lluvia.svg";
            }else{
                $img = "/imagenes/iconos/soleado.svg";
            }
        }
    }


    $nombCasa = $listado->buscarNombreCasa($_SESSION['idCasa']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOMOTIZATE</title>
    <?php echo cabeceraPaginasPanel();?>
</head>

<body class="inicioPanel">
    <img src="../imagenes/iconos/exit.svg" alt="Cerrar Sesión" class="icoCerrarSesion"></a>
    <div class="fondoPanel">
        <?=menuPanel()?>
        <div class="contenedorPagPanel">
            <div class="titPrincPanel">
                <h1>Panel Control <?=(!empty($nombCasa)? " - " . $nombCasa : "")?></h1>
            </div>
            <div class="contFechaTiempo">
                <div class="contFechaHora paneles">
                    <p class="subTitPanelFila1">Fecha & Hora</p>
                    <p class="fecha"><?=date('d ').$meses[date('n')-1].date(' Y');?></p>
                    <p class="hora" id="panelMostrarHora"><?=date('G:i');?></p>
                </div>
                <div class="contTiempo paneles">
                    <p class="subTitPanelFila1">Tiempo</p>
                    <div class="contIntTiempo">
                        <div class="imgTiempo">
                            <img src="<?=$img?>" alt="Imagen Tiempo" class="imgTiempo">
                        </div>
                        <div class="textoTiempo">
                            <p class="direccionTiempo"><?=$nombreSitio?></p>
                            <p class="gradosTiempo"><?=(strpos($temp, "div")?$temp:$temp."°C")?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contSensores">
                <form action="" method="post">


                    <div class="contIzqSensores paneles">
                        <div class="contDistribucionPanelLuces ">
                            <div>
                                <p class="subTitPanelesFila2">Luces</p>
                            </div>
                            <div class="opcionesLuces ">
                                <div class="opcLucesBloque1">
                                    <div class="opcL">
                                        <p><?=(empty($nombresSensoresCasa['luzPasilloPB']) || $nombresSensoresCasa['luzPasilloPB'])? "Pasillo PB" : $nombresSensoresCasa['luzPasilloPB']?></p>
                                        <input type="checkbox" class="luzPasilloPB" id="btn-switch" <?php echo ($datosSensoresUsuario['luzPasilloPB']) == 1 ?  "checked='checked'" :  ""?> >
                                        <label for="btn-switch" class="lbl-switch">
                                    </div>
                                    <div class="opcL">
                                        <p><?=(empty($nombresSensoresCasa['luzPasilloPA']))? "Pasillo PA" : $nombresSensoresCasa['luzPasilloPA']?></p>
                                        <input type="checkbox" class="luzPasilloPA" id="btn-switch2" <?php echo ($datosSensoresUsuario['luzPasilloPA']) == 1 ?  "checked='checked'" :  ""?> >
                                        <label for="btn-switch2" class="lbl-switch">
                                    </div>
                                    <div class="opcL">
                                        <p><?=(empty($nombresSensoresCasa['luzCocina']))? "Cocina" : $nombresSensoresCasa['luzCocina']?></p>
                                        <input type="checkbox" class="luzCocina" id="btn-switch3" <?php echo ($datosSensoresUsuario['luzCocina']) == 1 ?  "checked='checked'" :  ""?> >
                                        <label for="btn-switch3" class="lbl-switch">
                                    </div>
                                    <div class="opcL">
                                        <p><?=(empty($nombresSensoresCasa['luzSalon']))? "Salón" : $nombresSensoresCasa['luzSalon']?></p>
                                        <input type="checkbox" class="luzSalon" id="btn-switch4" <?php echo ($datosSensoresUsuario['luzSalon']) == 1 ?  "checked='checked'" :  ""?> >
                                        <label for="btn-switch4" class="lbl-switch">
                                    </div>
                                </div>
                                <div class="opcLucesBloque2">
                                    <div class="opcL">
                                        <p><?=(empty($nombresSensoresCasa['luzBanho']))? "Baño" : $nombresSensoresCasa['luzBanho']?></p>
                                        <input type="checkbox"  class="luzBanho" id="btn-switch5" <?php echo ($datosSensoresUsuario['luzBanho']) == 1 ?  "checked='checked'" :  ""?> >
                                        <label for="btn-switch5" class="lbl-switch">
                                    </div>
                                    <div class="opcL">
                                        <p><?=(empty($nombresSensoresCasa['luzHab1']))? "Habitación 1" : $nombresSensoresCasa['luzHab1']?></p>
                                        <input type="checkbox"  class="luzHab1" id="btn-switch6" <?php echo ($datosSensoresUsuario['luzHab1']) == 1 ?  "checked='checked'" :  ""?> >
                                        <label for="btn-switch6" class="lbl-switch">
                                    </div>
                                    <div class="opcL">
                                        <p><?=(empty($nombresSensoresCasa['luzHab2']))? "Habitación 2" : $nombresSensoresCasa['luzHab2']?></p>
                                        <input type="checkbox"  class="luzHab2" id="btn-switch7" <?php echo ($datosSensoresUsuario['luzHab2']) == 1 ?  "checked='checked'" :  ""?> >
                                        <label for="btn-switch7" class="lbl-switch">
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="contBarraDivisora">
                                <hr class="barraDivisora">
                            </div>
                            <div class="contAscensor">
                                <p class="titAscensor">Ascensor</p>
                                <div class="plantasAscensor">
                                    <div class="pb  <?php echo ($datosSensoresUsuario['ascensor'] == 0 || $datosSensoresUsuario['ascensor']) == "E" ?  "plantaSeleccionada" :  ""?> ">
                                        <p>Planta Baja</p>
                                    </div>
                                    <div class="pa <?php echo ($datosSensoresUsuario['ascensor']) == 1 ?  "plantaSeleccionada" :  ""?>">
                                        <p>Planta Alta</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="contDerSensores">
                        <div class="contTemperatura paneles">
                            <p>Temperatura - Humedad</p>
                            <div class="tempHum">
                                <div class="temp">
                                    <img src="../imagenes/iconos/temperatura.svg" alt="Temperatura Casa">
                                    <p class="valorTemp"><?php echo (isset($datosSensoresUsuario['temperatura'])) ?  $datosSensoresUsuario['temperatura'] . "°C":  "-"?></p>
                                </div>
                                <div class="hum">
                                    <img src="../imagenes/iconos/humedad.png" alt="Humedad Casa">
                                    <p class="valorHum"><?php echo (isset($datosSensoresUsuario['humedad'])) ?  $datosSensoresUsuario['humedad'] ."%" :  "-"?></p>
                                </div>
                            </div>
                        </div>
                        <div class="contSensor">
                            <div class="contGaraje paneles">
                                <p><?=(empty($nombresSensoresCasa['puertaGaraje']))? "Puerta Garaje" : $nombresSensoresCasa['puertaGaraje']?></p>
                                <input type="checkbox"  class="puertaGaraje" id="btn-switch9" <?php echo ($datosSensoresUsuario['puertaGaraje']) == 1 ?  "checked='checked'" :  ""?>>
                                <label for="btn-switch9" class="lbl-switch">
                            </div>
                            <div class="contPP paneles">
                                <p><?=(empty($nombresSensoresCasa['puertaPrincipal']))? "Puerta Principal" : $nombresSensoresCasa['puertaPrincipal']?></p>
                                <input type="checkbox"  class="puertaPrincipal" id="btn-switch10" <?php echo ($datosSensoresUsuario['puertaPrincipal']) == 1 ?  "checked='checked'" :  ""?>>
                                <label for="btn-switch10" class="lbl-switch">
                            </div>
                            <div class="ventilador paneles">
                                <p><?=(empty($nombresSensoresCasa['ventilador']))? "Ventilador" : $nombresSensoresCasa['ventilador']?></p>
                                <input type="checkbox"  class="ventilador" id="btn-switch8" <?php echo ($datosSensoresUsuario['ventilador']) == 1 ?  "checked='checked'" :  ""?> >
                                <label for="btn-switch8" class="lbl-switch">
                            </div>
                            <div class="contAlarma paneles">
                                <p><?=(empty($nombresSensoresCasa['alarma']))? "Alarma Cocina" : $nombresSensoresCasa['alarma']?></p>
                                <select name="alarma" id="alarma">
                                    <option disabled selected="selected">-- Seleccionar --</option>
                                    <option value="1">1sg</option>
                                    <option value="2">2sg</option>
                                    <option value="3">3sg</option>
                                    <option value="4">4sg</option>
                                    <option value="5">5sg</option>
                                    <option value="6">6sg</option>
                                    <option value="7">7sg</option>
                                    <option value="8">8sg</option>
                                    <option value="9">9sg</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="piePagPanel">
                <div class="estadoArduino">
                    <p>Estado: <span class="textoEstadoArduino">Conectado</span></p>
                    <div class="formaCirculo <?php echo ($datosSensoresUsuario['arduino']) == 0 ?  "desconectado" :  "conectado"?>"></div>
                </div>
                <div class="logoPagPanel">
                    <p><a href="./index.php">DOMOTIZATE</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
    <script>
        function getDate(){
            var myDate = new Date();
            //console.log(myDate)
            var mytime=myDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            $('#panelMostrarHora').html(mytime);
        }

        //Ejecutar funcion getDate cada sg
        setInterval(function(){ 
            getDate();
        },1000);

        $(document).on("click", ".icoCerrarSesion", function(){
            window.location.href = "<?php echo localizacion() . "funciones/cerrarSesion.php"?>";

        });

<?php
        if(empty($estadoDia)){
?>
            $(".contenedorPagPanel .contTiempo img").css("filter", "initial");
            $(".textoTiempo").html($(".textoTiempo").html().replace("°C", ""));
<?php
        }
?>

        //Cambiar estilo al hacer click en los botones del ascensor
        $(document).on("click", ".pa", function(){
            if($(this).attr("class").split(' ')[1] != "plantaSeleccionada"){
                guardarDatosSensoresBD("ascensor", "1");
                $(this).addClass("plantaSeleccionada");
                $(".pb").removeClass("plantaSeleccionada");
                $(".pb").addClass("botonBloqueado");
                $(".pb").css("pointerEvents", "none");
                setTimeout(function(){
                    $(".pb").css("pointerEvents", "auto");
                    $(".pb").removeClass("botonBloqueado");
                }, 10000)
            }else{
                swal("AVISO!", "El ascensor ya está en la planta alta", "info");
            }
        });

        $(document).on("click", ".pb", function(){
            if($(this).attr("class").split(' ')[1] != "plantaSeleccionada"){
                guardarDatosSensoresBD("ascensor", "0");
                $(this).addClass("plantaSeleccionada");
                $(".pa").addClass("botonBloqueado");
                $(".pa").removeClass("plantaSeleccionada");
                $(".pa").css("pointerEvents", "none");
                setTimeout(function(){
                    $(".pa").css("pointerEvents", "auto");
                    $(".pa").removeClass("botonBloqueado");
                }, 10000)
            }else{
                swal("AVISO!", "El ascensor ya está en la planta baja", "info");
            }
        });

         //Cambiar texto CONECTADO / DESCONECTADO
        function recuperarValores(){
              
            //AJAX - Guardar cambios en la bd
            $.ajax({
                method: "POST",
                url: "actualizarValoresPanel.php",            
                data: {
                    idCasa: <?=$_SESSION['idCasa']?>,
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

                if(msgRecibido[0].includes(estadoArduino)){
                    if($(".formaCirculo").attr("class").split(' ')[1] == "desconectado"){
                        $(".textoEstadoArduino").html("Conectado");
                        $(".formaCirculo").removeClass("desconectado");
                        $(".formaCirculo").addClass("conectado");
                    }
                }else{
                    if($(".formaCirculo").attr("class").split(' ')[1] == "conectado"){
                        $(".textoEstadoArduino").html("Desconectado");
                        $(".formaCirculo").removeClass("conectado");
                        $(".formaCirculo").addClass("desconectado");
                    }
                }
                
                if(Number(valorTemp[1])){
                    $(".valorTemp").html(valorTemp[1] + " ºC");
                }

                if(Number(valorHum[1])){
                    $(".valorHum").html(valorHum[1] + " %");
                }
             
            })
            .fail(function() {
                console.error('Error: No se han podido recuperar los datos de la temperatura, la humedad y del estado del arduino');
            });
        }

        //Ejecutar funcion repetidamente
        setInterval(function(){ 
            recuperarValores();
        },5000);

         
        $(document).ready(function(){
            if($(".formaCirculo").attr("class").split(' ')[1] == "conectado"){
                $(".textoEstadoArduino").html("Conectado");
            }else if($(".formaCirculo").attr("class").split(' ')[1] == "desconectado"){
                $(".textoEstadoArduino").html("Desconectado");
            }

            //Cambiar estilo al cargar la página si el ascensor no esta en la planta baja
            if($(".pa").attr("class").split(' ')[1] == "plantaSeleccionada"){
                $(".pb").removeClass("plantaSeleccionada");
            }

            /* Poner fondo sobre la opción seleccionada en el menu vertical */
            $(".iconoMP:first-child()").css("background-color", "#293B97");


            /******* CAMBIAR TEMA *********/
            var colorFondoPagPanel = "<?=(empty($datosTema['colorFondoPagPanel'])) ? "NULO": $datosTema['colorFondoPagPanel']?>";            
            var colorFondoPanel = "<?=(empty($datosTema['colorFondoPanel'])) ? "NULO": $datosTema['colorFondoPanel']?>";
            var colorTitulosPanel = "<?=(empty($datosTema['colorTitulosPanel'])) ? "NULO": $datosTema['colorTitulosPanel']?>";
            var colorNombSensores = "<?=(empty($datosTema['colorNombSensores'])) ? "NULO": $datosTema['colorNombSensores']?>";
            var tamanoLetraTit = "<?=(empty($datosTema['tamanoLetraTit'])) ? "NULO": $datosTema['tamanoLetraTit']?>";
            var tamanoLetraNombSensores = "<?=(empty($datosTema['tamanoLetraNombSensores'])) ? "NULO": $datosTema['tamanoLetraNombSensores']?>";
            console.log(colorFondoPagPanel);
            
            if(colorFondoPagPanel == "NULO"){
                $(".fondoPanel").css("background-image", "radial-gradient(circle at 25.82% -16.45%, #a1a3e3 0, #5f6ebb 50%, #003e94 100%)");
            }else{
                $(".fondoPanel").css("background-image", "initial");
                $(".fondoPanel").css("background-color", colorFondoPagPanel);
            }

            if(colorFondoPanel == "NULO"){
                $(".paneles").css("background-color", "#4F5C9C");
            }else{
                $(".paneles").css("background-color", colorFondoPanel);
            }

            if(colorTitulosPanel == "NULO"){
                $(".subTitPanelFila1").css("color", "#FFFFFF");
                $(".subTitPanelesFila2").css("color", "#FFFFFF");
                $(".contTemperatura p:first-child()").css("color", "#FFFFFF");
                $(".titAscensor").css("color", "#FFFFFF");
            }else{
                $(".subTitPanelFila1").css("color", colorTitulosPanel);
                $(".subTitPanelesFila2").css("color", colorTitulosPanel);
                $(".contTemperatura p:first-child()").css("color", colorTitulosPanel);
                $(".titAscensor").css("color", colorTitulosPanel);
            }

            if(colorNombSensores == "NULO"){
                $(".opcl p").css("color", "#FFFFFF");
                $(".contGaraje p").css("color", "#FFFFFF");
                $(".contPP p").css("color", "#FFFFFF");
                $(".ventilador p").css("color", "#FFFFFF");
                $(".contAlarma p").css("color", "#FFFFFF");
            }else{
                $(".opcL p").css("color", colorNombSensores);
                $(".contGaraje p").css("color", colorNombSensores);
                $(".contPP p").css("color", colorNombSensores);
                $(".ventilador p").css("color", colorNombSensores);
                $(".contAlarma p").css("color", colorNombSensores);
            }


            if(tamanoLetraTit == "NULO"){
                /*
                $(".subTitPanelFila1").css("font-size", "24px");
                $(".subTitPanelesFila2").css("font-size", "30px");
                $(".contTemperatura p:first-child()").css("font-size", "30px");
                $(".titAscensor").css("font-size", "30px");
                */
            }else{
                $(".subTitPanelFila1").css("font-size", parseInt($(".subTitPanelFila1").css("font-size"))*tamanoLetraTit);
                $(".subTitPanelesFila2").css("font-size", parseInt($(".subTitPanelFila1").css("font-size"))*tamanoLetraTit);
                $(".contTemperatura").css("font-size", parseInt($(".subTitPanelFila1").css("font-size"))*tamanoLetraTit);
                $(".titAscensor").css("font-size", parseInt($(".subTitPanelFila1").css("font-size"))*tamanoLetraTit);
            }


            if(tamanoLetraNombSensores == "NULO"){
                /*
                $(".opcl p").css("font-size", "16px");
                $(".contGaraje p").css("font-size", "16px");
                $(".contPP p").css("font-size", "16px");
                $(".ventilador p").css("font-size", "16px");
                $(".contAlarma p").css("font-size", "16px");
                */
            }else{
                $(".opcl p").css("font-size", parseInt($(".opcl p").css("font-size"))*tamanoLetraNombSensores);
                $(".contGaraje p").css("font-size", parseInt($(".contGaraje p").css("font-size"))*tamanoLetraNombSensores);
                $(".contPP p").css("font-size", parseInt($(".contPP p").css("font-size"))*tamanoLetraNombSensores);
                $(".ventilador p").css("font-size", parseInt($(".ventilador p").css("font-size"))*tamanoLetraNombSensores);
                $(".contAlarma p").css("font-size", parseInt($(".contAlarma p").css("font-size"))*tamanoLetraNombSensores);
            }

        });




        $(document).on("click","input",function(){
            
            var nombSensor = $(this).attr("class");
            if($(this).is(':checked')){
                console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") => Seleccionado");
                var valor = 1;
                console.log(valor);
                var input = $(this).siblings();
                input.css("pointerEvents", "none");
                setTimeout(function(){
                    input.css("pointerEvents", "auto");
                }, 2000)

            }else{
                console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") => Deseleccionado");
                var valor = 0;
                console.log(valor);
                var input = $(this).siblings();
                input.css("pointerEvents", "none");
                setTimeout(function(){
                    input.css("pointerEvents", "auto");
                }, 2000)

            }
            
            
            guardarDatosSensoresBD(nombSensor, valor);

        });


        $(document).on("change","select",function(){
            var valorAlarma = $(this).children("option:selected").text()[0];
            contador = valorAlarma + "000";
            guardarDatosSensoresBD("alarma", valorAlarma);
            $("select").css("pointerEvents", "none");
            setTimeout(function(){
                $("select").css("pointerEvents", "auto");
                    $("select").prop("selectedIndex", 0).val()
                }, parseInt(contador))
        });

      

        function guardarDatosSensoresBD(nombSensor, valor){
            
            //AJAX - Guardar cambios en la bd
            $.ajax({
                method: "POST",
                url: "actualizarDatosSensoresBD.php",            
                data: {
                    nombSensor: nombSensor,
                    valor: valor,
                },
            })
            .done(function(data) {
               console.log(data);
            })
            .fail(function() {
                alert('Error al realizar la petición!');
            });
        }

        
    </script>
</html>