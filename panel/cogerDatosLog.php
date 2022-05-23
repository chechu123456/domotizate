<?php
    session_start();
    include("../inc.php");
    comprobarSesionVacia();
    $idCasa = $_SESSION['idCasa'];
    $registrosCasa = $listado->listarRegistrosCasa($idCasa);

    $json_string = json_encode($registrosCasa);

    echo $json_string;

    
?>  