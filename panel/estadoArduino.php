<?php
  session_start();
  include("../inc.php");

    if(isset($_POST['idCasa'])){
        $idCasa = $_POST['idCasa'];
        echo $listado->listarValorSensorPorNombre("arduino", $idCasa);
        //echo "actualizando estado arduino";
    }
?>