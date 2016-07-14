<?php
	include ('./include/funciones_php.php');
	$post = filter_input_array(INPUT_POST);
	if (isset($post ['user'])) {
		include ('./include/conectar.php');

		ini_set("session.use_only_cookies", "1");
		ini_set("session.use_trans_sid", "0");
		// Inicio la sesiï¿½n
		session_name("loginusuario");
		session_start();
		session_set_cookie_params(0, "/", $HTTP_SERVER_VARS ["HTTP_HOST"], 0);
		
		$contra = md5($post [pass]);
		
		$ssql = "SELECT * FROM usuario WHERE user='$post[user]' and pass='$contra' limit 1";
		// Ejecuto la sentencia
		$rs = $mysqli->query($ssql);
		if ($rs->num_rows > 0) {
			
			$elusu = $rs->fetch_assoc();
			session_name("loginusuario");
			$_SESSION [autentificado] = "SI";
			
			$_SESSION ["usuario"] = $elusu ['user'];
			$_SESSION ["idusuario"] = $elusu ['id'];
			$_SESSION ["modo"] = 'pruebas';
			//$_SESSION ["modo"] = '';
			
			$_SESSION ["pag_actual"] = 'index';
			$_SESSION ["config_pag_actual"] = array();
			
	
			registro_acceso($_SESSION ["usuario"]);
			
			header('Location: ./index.php');
		}
		else{
			$nouser = "failed_login - " . $post ['user'];
			registro_acceso($nouser);
		}
		
		mysqli_free_result($rs);
		mysqli_close();
	}
	else{
		registro_acceso("no_login");
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Intelligent System for the Acquisition and Management of information from Bill of Quantities in Building Projects</title>
        
		<link rel="stylesheet" type="text/css" href="css/estructura.css">
		<link rel="stylesheet" type="text/css" href="css/login.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	
		<link rel="apple-touch-icon" sizes="57x57" href="/icons/apple-touch-icon-57x57.png">
			<link rel="apple-touch-icon" sizes="60x60" href="/icons/apple-touch-icon-60x60.png">
			<link rel="apple-touch-icon" sizes="72x72" href="/icons/apple-touch-icon-72x72.png">
			<link rel="apple-touch-icon" sizes="76x76" href="/icons/apple-touch-icon-76x76.png">
			<link rel="apple-touch-icon" sizes="114x114" href="/icons/apple-touch-icon-114x114.png">
			<link rel="apple-touch-icon" sizes="120x120" href="/icons/apple-touch-icon-120x120.png">
			<link rel="apple-touch-icon" sizes="144x144" href="/icons/apple-touch-icon-144x144.png">
			<link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-touch-icon-152x152.png">
			<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon-180x180.png">
			<link rel="icon" type="image/png" href="/icons/favicon-32x32.png" sizes="32x32">
			<link rel="icon" type="image/png" href="/icons/android-chrome-192x192.png" sizes="192x192">
			<link rel="icon" type="image/png" href="/icons/favicon-96x96.png" sizes="96x96">
			<link rel="icon" type="image/png" href="/icons/favicon-16x16.png" sizes="16x16">
			<link rel="manifest" href="/icons/manifest.json">
			<link rel="mask-icon" href="/icons/safari-pinned-tab.svg" color="#5bbad5">
			<meta name="msapplication-TileColor" content="#ffffff">
			<meta name="msapplication-TileImage" content="/mstile-144x144.png">
			<meta name="theme-color" content="#ffffff">
	</head>
    <body>
		<style>
			.name_log{font-weight:bold;font-size:30px;}
		</style>
		<div class="center">			
			<div class="formu_login">
				<div class="col-sm-5 fondo_login">
					<h3 class="name_log">i-BoQ</h3>
				</div>
				<div class="col-sm-7">
					<form id="form_subir" method="post">
						<h3>User</h3>
						<div class='input'>
							<input type="text" name="user"/>
						</div>
						<h3>Password</h3>
						<div class='input'>
							<input type="password" name="pass" />
						</div>
						<input type="submit" value="Enter" />
						
					</form>
					</br>	
				</div>			
			</div>
		</div>
		<footer>
			<div class="container">
				<p>i-BoQ: Intelligent System for the Acquisition and Management of information from Bill of Quantities in Building Projects</p>
				<p>&copy; Copyright 2016 - Mar&iacute;a Mart&iacute;nez Rojas <span class="email"></span></p>
			</div>
		</footer>
		
		</body>
	</html>	