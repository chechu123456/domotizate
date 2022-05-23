<?php
/* DEBUG

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
*/
include("funciones/reutilizables.inc.php");
$ruta = localizacion();

spl_autoload_register('load');

function load($classname)
{
    // En caso de necesitar cargar variables estaticas por namespace de librerias de PHP hacer un `use as` primero;
    // como ejemplo \bd\Conexion
    $exparray = explode("\\", $classname); // $exparray[0] = "" && $exparray[1] = bd && $exparray[2] = Conexion 
    $classname = end($exparray); //Conexion
    unset($exparray[array_key_last($exparray)]); // /bd
    $nspath=implode('/',$exparray);// /bd
    $filename = $nspath .'/'. $classname . ".php";   // /bd/Conexion.php
    require_once($filename);

}

use bd\Listado;

$db_host = "domotizate.site";
$db_user =  "k204701_sergio";
$db_passwd = "LjSf&]0PP7$8";
$db_name = "k204701_domotizate";
$listado = new Listado($db_host, $db_user, $db_passwd, $db_name);
