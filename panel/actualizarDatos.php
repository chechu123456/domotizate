<?php
    session_start();
    include("../inc.php");
    comprobarSesionVacia();

    if(isset($_POST['localidad'])){
        $localidad = $_POST['localidad'];
        $nickname = $_SESSION['nickname'];
        $listado->editarLocalidad($nickname, $localidad);
        
    }else{
            
        $lCocina = trim(stripslashes(htmlspecialchars($_POST['lCocina'])));
        $lSalon = trim(stripslashes(htmlspecialchars($_POST['lSalon'])));
        $bano = trim(stripslashes(htmlspecialchars($_POST['bano'])));
        $lHab1 = trim(stripslashes(htmlspecialchars($_POST['lHab1'])));
        $lHab2 = trim(stripslashes(htmlspecialchars($_POST['lHab2'])));
        $lPlantaA = trim(stripslashes(htmlspecialchars($_POST['lPlantaA'])));
        $lPlantaB = trim(stripslashes(htmlspecialchars($_POST['lPlantaB'])));
        $alarma = trim(stripslashes(htmlspecialchars($_POST['alarma'])));
        $puertaGaraje = trim(stripslashes(htmlspecialchars($_POST['puertaGaraje'])));
        $puertaPrincipal = trim(stripslashes(htmlspecialchars($_POST['puertaPrincipal'])));
        $ventilador = trim(stripslashes(htmlspecialchars($_POST['ventilador'])));

        $nombresSensorWeb = Array($lCocina, $lSalon, $bano, $lHab1, $lHab2, $lPlantaA, $lPlantaB, $alarma, $puertaGaraje, $puertaPrincipal, $ventilador);
        $nombresSensor = Array("luzCocina","luzSalon","luzBanho","luzHab1","luzHab2","luzPasilloPA","luzPasilloPB","alarma","puertaGaraje", "puertaPrincipal", "ventilador");
        
        
        for($i = 0; $i < count($nombresSensorWeb); $i++){
            $listado->actualizarNombSensorWeb($nombresSensor[$i], $nombresSensorWeb[$i], $_SESSION['idCasa']);                
        }

    }
    die();exit();
