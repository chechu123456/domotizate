<?php 
    //Enviar datos de la bd al chip ESP32
    include("../inc.php");
    if(isset($_POST['idCasa'])){
        $idCasa = $_POST['idCasa']; 
        echo "idCasa: " .$idCasa." \n";
        $sensores = $listado->listarSensoresValoresPorCasa($idCasa);
        //echo var_dump($sensores);
      
       
        echo "ventilador=".$sensores['ventilador']." \n";
        echo "ledCocina=" .$sensores['luzCocina']. "  \n";
        echo "ledSalon=" .$sensores['luzSalon']. "  \n";
        echo "ledBano=".$sensores['luzBanho']."  \n";
        echo "ledHab1=".$sensores['luzHab1']."  \n";
        echo "ledHab2=".$sensores['luzHab2']."  \n";
        echo "ledPB=".$sensores['luzPasilloPB']."  \n";
        echo "ledPA=".$sensores['luzPasilloPA']."  \n";
        echo "ledGaraje=".$sensores['luzInteriorGaraje']."  \n";
        //echo "ledVGaraje=".$sensores['luzVerdeGaraje']."  \n";
        //echo "ledRGaraje=".$sensores['luzRojaGaraje']." \n";

        if($sensores['ascensor'] == 0){
            echo "ascensorPB=1 \n";
            echo "ascensorPA=0 \n";
            $listado->crearTienenRegistro("ascensor","E",$idCasa);
        }else if($sensores['ascensor'] == 1){
            echo "ascensorPA=1 \n";
            echo "ascensorPB=0 \n";
            $listado->crearTienenRegistro("ascensor","E",$idCasa);
        }else{
            echo "ascensorPB=E \n";
            echo "ascensorPA=E \n";
        }

        if($sensores['puertaGaraje'] == 1){
            echo "puertaGaraje=1 \n";
            $listado->crearTienenRegistro("puertaGaraje","E",$idCasa);
        }else if($sensores['puertaGaraje'] == 0){
            echo "puertaGaraje=0 \n";
            $listado->crearTienenRegistro("puertaGaraje","E",$idCasa);
        }else{
            echo "puertaGaraje=E \n";
        }

        if($sensores['puertaPrincipal'] == 1){
            echo "puertaPP=1 \n";
            $listado->crearTienenRegistro("puertaPrincipal","E",$idCasa);
        }else if($sensores['puertaPrincipal'] == 0){
            echo "puertaPP=0 \n";
            $listado->crearTienenRegistro("puertaPrincipal","E",$idCasa);
        }else{
            echo "puertaPP=E \n";
        }

        if(is_numeric($sensores['alarma'])){
            echo "cuentaReg=". $sensores['alarma']." \n";
            $listado->crearTienenRegistro("alarma","E",$idCasa);
        }else{
            echo "cuentaReg=E \n";
        }

    }
?>