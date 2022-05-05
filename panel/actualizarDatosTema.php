<?php
    session_start();
    include("../inc.php");
    comprobarSesionVacia();

    if(isset($_POST['defecto'])){
        $colorFondoPagPanel = "";
        $colorFondoPanel = "";
        $colorTitulosPanel = "";
        $colorNombSensores = "";
        $tamanoLetraTit = "";
        $tamanoLetraNombSensores = "";

        $nickname = $_SESSION['nickname'];
        $idTema = $listado->obtenerIdTemaUsuario($nickname);
        $listado->modificarTema($idTema, $colorFondoPagPanel, $colorFondoPanel, $colorTitulosPanel, $colorNombSensores, $tamanoLetraTit, $tamanoLetraNombSensores);
    
    }else if(isset($_POST['colorFondoPagPanel']) && isset($_POST['colorFondoPanel']) && isset($_POST['colorTitulosPanel']) && 
        isset($_POST['colorNombSensores']) && isset($_POST['tamanoLetraTit']) && isset($_POST['tamanoLetraNombSensores'])){
       
        $colorFondoPagPanel = trim(stripslashes(htmlspecialchars($_POST['colorFondoPagPanel'])));
        $colorFondoPanel = trim(stripslashes(htmlspecialchars($_POST['colorFondoPanel'])));
        $colorTitulosPanel = trim(stripslashes(htmlspecialchars($_POST['colorTitulosPanel'])));
        $colorNombSensores = trim(stripslashes(htmlspecialchars($_POST['colorNombSensores'])));
        $tamanoLetraTit = trim(stripslashes(htmlspecialchars($_POST['tamanoLetraTit'])));
        $tamanoLetraNombSensores = trim(stripslashes(htmlspecialchars($_POST['tamanoLetraNombSensores'])));
        
        $nickname = $_SESSION['nickname'];
        $idTema = $listado->obtenerIdTemaUsuario($nickname);
        $listado->modificarTema($idTema, $colorFondoPagPanel, $colorFondoPanel, $colorTitulosPanel, $colorNombSensores, $tamanoLetraTit, $tamanoLetraNombSensores);
    }

  
?>