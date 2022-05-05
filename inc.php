<?php
    include("funciones/reutilizables.inc.php");
    $ruta = localizacion();
    require("bd/consultas.php");

    $db_host = "allnatural.site";
    $db_user =  "r165651";
    $db_passwd = "p-MD*zWaEf_v"; 
    $db_name = "r165651_domotizate";
    $listado = new Listado($db_host, $db_user, $db_passwd, $db_name);
    
    