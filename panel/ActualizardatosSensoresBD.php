<?php
    session_start();
    include("../inc.php");

    if(isset($_POST['nombSensor']) || isset($_POST['valor']) ){
        $nombSensor = $_POST['nombSensor'];
        $valor = $_POST['valor'];
        echo "El sensor: $nombSensor con valor: $valor de la casa: ". $_SESSION['idCasa'];
        $idCasa = $_SESSION['idCasa'];
        $nickname = $_SESSION['nickname'];
        $listado->crearTienenRegistro($nombSensor, $valor, $idCasa, $nickname);
    }
?>