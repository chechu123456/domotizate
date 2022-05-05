<?php
    require("../../inc.php");
    if(isset($_POST['nickname']) && isset($_POST['password'])){
        //Si ya tengo cuenta, entrar en la página
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
        $listado->buscarUsuario($nickname, $password);
        
    }else if(isset($_POST['nickname']) && isset($_POST['password']) &&  isset($_POST['localidad'])){
        //Si no tengo cuenta, registrarme
    }
?>