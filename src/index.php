<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/control/seguridad.php');

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">


        <meta charset="UTF-8" />
        <title>i-BoQ</title>


        <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">   
        <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">   
        <link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">   
        <link rel="stylesheet" type="text/css" href="/css/estructura.css">   
        <link rel="stylesheet" type="text/css" href="/css/proyectos.css">   

        <script src="/js/funciones.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/angular.min.js"></script>
        <script src="/js/jquery/jquery-ui-1.10.3.custom.js"></script>

        <script src="/js/angular/app_analisis.js"></script>
        <script src="/js/angular/controller_menu.js"></script>
			
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
		<div id="wrapper">
			<?php include('./menu.php'); ?>
			<div class="container content">
				<?php
				$parametros = explode('/', $_SERVER['REQUEST_URI']);
				$seccion = $parametros[1];
				$pagina = '';
				if (isset($parametros[2])) {
					$pagina = $parametros[2];
				}
				if ($seccion == 'abrir') {
					include($_SERVER['DOCUMENT_ROOT'] . "/secciones/proyectos/abrir.php");
				} elseif (file_exists("./secciones/$seccion/$pagina.php")) {
					include($_SERVER['DOCUMENT_ROOT'] . "/secciones/$seccion/$pagina.php");
				} else {
					include($_SERVER['DOCUMENT_ROOT'] . "/secciones/principal.php");
				}
				?> 
			</div>
			<footer>
				<div class="container">
					<p>i-BoQ: Intelligent System for the Acquisition and Management of information from Bill of Quantities in Building Projects
</p>
					<p>&copy; Copyright 2016 - Mar&iacute;a Mart&iacute;nez Rojas <span class="email"></span></p>
				</div>
			</footer>
		</div>
    </body>
</html>