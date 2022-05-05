<?php
  session_start();
  include("../inc.php");
  comprobarSesionVacia();
    if(isset($_POST['idCasa'])){
        $idCasa = $_POST['idCasa'];
        echo $listado->listarSensorPorNombre("arduino", $idCasa);
        //echo "actualizando estado arduino";
    }
?>