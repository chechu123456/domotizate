<?php

    session_start();
    include("../inc.php");
    comprobarSesionVacia();
    $idCasa = $_SESSION['idCasa'];
    $registrosCasa = $listado->listarRegistrosCasa($idCasa);

    $json_string = json_encode($registrosCasa);

    echo $json_string;
   /*

      $json_string = json_decode($string);
      echo '<pre>'; 
      print_r($json_string);
      echo '</pre>';
    echo $json_string;
    */

?>  