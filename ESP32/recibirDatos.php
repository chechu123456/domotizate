<?php
    include("../inc.php");
    //Recibir datos del estado, si esta conectado el arduino, sensor temperatura, humedad
    if(isset($_POST['idCasa'])){
        $idCasa = $_POST['idCasa'];
        if(isset($_POST['arduino'])){
            $listado->crearTienenRegistro("arduino","1",$idCasa, "arduino_$idCasa");
        }

        if(isset($_POST['temperatura']) && isset($_POST['humedad'])){
            $valorTemperatura = $_POST['temperatura'];
            $valorHumedad = $_POST['humedad'];

            if($listado->buscarExisteNombSensor("temperatura",$idCasa)){
                //Si existe el sensor, agregar el registro
                $listado->crearTienenRegistro("temperatura",$valorTemperatura,$idCasa, "arduino_$idCasa");
            }else{
                //Si no existe, crear el sensor y añadir el registro
                $listado->crearSensor("temperatura",$idCasa);
                $listado->crearTienenRegistro("temperatura",$valorTemperatura,$idCasa, "arduino_$idCasa");

            }

            if($listado->buscarExisteNombSensor("humedad",$idCasa)){
                //Si existe el sensor, agregar el registro
                $listado->crearTienenRegistro("humedad",$valorHumedad,$idCasa, "arduino_$idCasa");
            }else{
                //Si no existe, crear el sensor y añadir el registro
                $listado->crearSensor("humedad",$idCasa);
                $listado->crearTienenRegistro("humedad",$valorTemperatura,$idCasa, "arduino_$idCasa");

            }

        }

    }else{
        $idCasa = 1;
        echo $listado->buscarExisteNombSensor("temperatura",$idCasa);
    }
?>