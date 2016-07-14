<?php

// Inicio la sesiï¿½n
session_name("loginusuario");
if (@session_start() === false) {
    session_destroy();
    session_start();
}
session_set_cookie_params(0, "/", $_SERVER ["HTTP_HOST"], 0);
// cambiamos la duraciï¿½n a la cookie de la sesiï¿½n
// COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO
if ($_SESSION ["autentificado"] != "SI") {
    // si no existe, envio a la pï¿½gina de autentificacion
    header("Location: ../login.php");

    exit();
}

include($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');


if (count($_POST) > 0) {
    foreach ($_POST as $k => $v) {
        if (is_string($v) && $k != 'descripcion'){
            $_POST [$k] = $mysqli->real_escape_string($v);
        }
    }
}

if (count($_GET) > 0) {
    foreach ($_GET as $k => $v) {
        if (is_string($v))
            $_GET [$k] = $mysqli->real_escape_string($v);
    }
}