<?php
    session_start();

    require("../../inc.php");
    sleep(1);
    if(isset($_POST['nickname']) && isset($_POST['password']) &&  isset($_POST['localidad'])
                &&  isset($_POST['idCasa']) &&  isset($_POST['nombCasa'])){
        //Si no tengo cuenta, registrarme
        $nickname =  strtolower(trim(stripslashes(htmlspecialchars($_POST['nickname']))));
        $password = trim(stripslashes(htmlspecialchars($_POST['password'])));
        $idCasa = (int) trim(stripslashes(htmlspecialchars($_POST['idCasa'])));
        $nombCasa = trim(stripslashes(htmlspecialchars($_POST['nombCasa'])));
        $localidad = trim(stripslashes(htmlspecialchars($_POST['localidad'])));
        
        if($listado->buscarUsuario($nickname)){
            echo "El nickname ya existe";
        }else{
            $listado->crearUsuario($nickname, $password, $localidad, $nombCasa, $idCasa);
            $idCasa = $listado->buscarIdUltCasa();
            $_SESSION['nickname'] = $nickname;

            echo "Usuario creado correctamente";
        }

    }else if(isset($_POST['nickname']) && isset($_POST['password'])){
        //Si ya tengo cuenta, entrar en la página
        $nickname = strtolower(trim(stripslashes(htmlspecialchars($_POST['nickname']))));
        $password = trim(stripslashes(htmlspecialchars($_POST['password'])));
        $listado->comprobarUsuarioContrasena($nickname, $password);
        
       
    
        if($listado->comprobarUsuarioContrasena($nickname, $password)){
            echo "Usuario y contraseña OK";
            $_SESSION['nickname'] = $nickname;
            $_SESSION['idCasa'] = $listado->buscarIdCasaNickname($nickname);
            $idCasa =  $_SESSION['idCasa'];
    
            //Cada vez que se inicia sesión, el estado del arduino se pone como Apagado, así si el arduino está conectado,
            //creará el un nuevo registro diciendo que está conectado
            $desconectarArduino = $listado->crearTienenRegistro("arduino", "0", $idCasa,  "arduino_$idCasa");
        }else{
            echo "Usuario y contraseña no validos";
        }
    }else{
        echo "error";
    }

    
?>