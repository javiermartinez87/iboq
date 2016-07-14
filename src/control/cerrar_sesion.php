<?php
include ("conectar.php");
ini_set("session.use_only_cookies", "1");
ini_set("session.use_trans_sid", "0");
//Inicio la sesiï¿½n
session_name("loginusuario");
session_start();
session_set_cookie_params(0, "/", $HTTP_SERVER_VARS["HTTP_HOST"], 0);
//mysql_query("insert into control_accesos (user,operacion,hora) values('$_SESSION[usuario]','CERRAR',NOW())");
//cambiamos la duraciï¿½n a la cookie de la sesiï¿½n 

session_destroy(); // destruyo la sesiï¿½n
header("Location: ../login.php"); //envï¿½o al usuario a la pag. de autenticaciï¿½n


