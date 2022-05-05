<?php 
    //Enviar datos de la bd al chip ESP32
    include("../inc.php");
    if(isset($_POST['idCasa'])){
        $idCasa = $_POST['idCasa']; 
        echo "idCasa: " .$idCasa." \n";
        $sensores = $listado->listarSensoresValoresPorCasa($idCasa);
        //echo var_dump($sensores);
      
       
        echo "ventilador=".$sensores['ventilador']." \n";
       

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


        if(is_numeric($sensores['alarma'])){
            echo "cuentaReg=". $sensores['alarma']." \n";
            $listado->crearTienenRegistro("alarma","E",$idCasa);
        }else{
            echo "cuentaReg=E \n";
        }

    }
?>