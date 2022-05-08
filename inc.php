<?php
    include("funciones/reutilizables.inc.php");
    $ruta = localizacion();
    require("bd/consultas.php");

    $db_host = "domotizate.site";
    $db_user =  "k204701_sergio";
    $db_passwd = "LjSf&]0PP7$8"; 
    $db_name = "k204701_domotizate";
    $listado = new Listado($db_host, $db_user, $db_passwd, $db_name);
    
    