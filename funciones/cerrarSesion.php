<?php
    session_start();

    if (strpos(dirname(__DIR__), "htdocs") == true) {
        $ruta = explode("htdocs", dirname(__DIR__));
        $ruta = $ruta[1];
        $ruta = str_replace('\\', "/", $ruta) . "/";
    } else {
        $ruta = "/";
    }

    // Destruir todas las variables de sesión.
    $_SESSION = array();

    session_unset();
    // Finalmente, destruir la sesión.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params["httponly"]);
    }

    session_destroy();
    header('Location: '. $ruta. 'index.php');
    exit;

?>