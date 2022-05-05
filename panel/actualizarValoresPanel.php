<?php
  //Actualiza los valores constantemente de la página de Inicio del Panel (Conectado o Desconectado, Valores Temperatura y Humedad)
  session_start();
  include("../inc.php");
  comprobarSesionVacia();
    if(isset($_POST['idCasa'])){
        $idCasa = $_POST['idCasa'];
        echo "arduino=".$listado->listarValorSensorPorNombre("arduino", $idCasa). ";";
        echo "temperatura=".$listado->listarValorSensorPorNombre("temperatura", $idCasa) . ";";
        echo "humedad=".$listado->listarValorSensorPorNombre("humedad", $idCasa);

        //echo "actualizando estado arduino";
    }
?>