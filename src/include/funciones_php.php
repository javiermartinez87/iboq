<?php
include ('../../../control/seguridad.php');
/*
 * 
 */
function lee_fichero($path) {
	$file = fopen ( $path, "r" ) or exit ( "Unable to open file! ". $path );
	
	$lineas = array ();
	while ( ! feof ( $file ) ) {
		array_push ( $lineas, utf8_encode ( fgets ( $file ) ) );
	}
	fclose ( $file );
	return $lineas;
}

function lee_fichero_frase($path) {
	//$lineas = array ();
	if($file = fopen ( $path, "r" )){
		$lineas = array ();
		while (! feof ( $file ) ) {
			array_push ( $lineas, utf8_encode ( fgets ( $file ) ) );
		}
		fclose ( $file );
	}
	return $lineas;
}

function lee_fichero_titulo($path) {
	//$lineas = array ();
	if($file = fopen ( $path, "r" )){
		$lineas = array ();
		while (! feof ( $file ) ) {
			array_push ( $lineas, utf8_encode ( fgets ( $file ) ) );
		}
		fclose ( $file );
	}
	return $lineas;
}

function lee_info_contenido($cap) {
	echo '<div class="descripcion">';
	$file = lee_fichero ( '../../informacion/' . $cap . '/resumen' . $cap .'.txt' );
	foreach ( $file as $linea ) {
		echo '<p>' . utf8_decode ( $linea ) . '</p>';
	}
	
	//echo '</div>';
	$file = lee_fichero_frase ( '../../informacion/' . $cap . '/frase' . $cap .'.txt' );
	if($file){
		//echo '<p style="text-align:right";>';
		echo '<div align="right">';
		$f=true;
		foreach ( $file as $linea ) {
			if($f){
				echo '<FONT SIZE=6>&#8220;</FONT><i><small>' . utf8_decode ( $linea ) . '</small></i><FONT SIZE=6>&#8221;</FONT></br>';
				$f=false;
			}
			else
				echo '<b><small>' . utf8_decode ( $linea ) . '</small></b></br>';
		}
		//echo '</p>';
		echo '</br>';
	}
	
	echo '</div>';
}

/*
 * 
 */
function lee_info_capitulo($cap) {
	echo '<div class="descripcion">';
	$file = lee_fichero ( '../../informacion/' . $cap . '/resumen' . $cap .''. $_SESSION[idioma] . '.txt' );
	foreach ( $file as $linea ) {
		echo '<p>' . utf8_decode ( $linea ) . '</p>';
	}
	
	//echo '</div>';
	$file = lee_fichero_frase ( '../../informacion/' . $cap . '/frase' . $cap .''. $_SESSION[idioma] . '.txt' );
	if($file){
		//echo '<p style="text-align:right";>';
		echo '<div align="right">';
		$f=true;
		foreach ( $file as $linea ) {
			if($f){
				echo '<FONT SIZE=6>&#8220;</FONT><i><small>' . utf8_decode ( $linea ) . '</small></i><FONT SIZE=6>&#8221;</FONT></br>';
				$f=false;
			}
			else
				echo '<b><small>' . utf8_decode ( $linea ) . '</small></b></br>';
		}
		//echo '</p>';
		echo '</br>';
	}
	
	echo '</div>';
	
	if($_SESSION[idioma]=="_en" && file_exists('../../informacion/' . $cap . '/publicaciones'.$cap.'.txt')){
		echo '<div class="separador_hor"></div>';
		echo '<div class="par" style="text-align:center;">';
		echo '<div class="clr"></div>';
		echo '<h1>Publications</h1>';
		
		lee_publicacionesCapitulo($cap);
		
		echo '<div class="clr"></div>';
		echo '</div>';
	}
}
/*
 * 
 */
function lee_publicaciones($cap) {
	echo '<div class="descripcion">';
	$path='./informacion/' . $cap . '/';
	if($_SESSION[idioma]=="_en" && file_exists('../../informacion/' . $cap . '/publicaciones_en.txt'))
		$file = lee_fichero ( '../../informacion/' . $cap . '/publicaciones_en.txt' );
	else
		$file = lee_fichero ( '../../informacion/' . $cap . '/publicaciones.txt' );
	
	echo '<table style="border-spacing:5px 20px";>';
	//echo '<ul>';
	$i=0;
	foreach ( $file as $linea ) {
		$datos = explode ( "\t", $linea );		
		$info = $datos[0];
		$pdf = $datos[1];
		//echo '<tr>';
		if(($i++ % 2) == 0)
			echo '<tr class="impar">';
		else
			echo '<tr class="par">';
				
		echo '<td>' . utf8_decode ( $info ) . '<td>';
		echo '<td><a target="_blank" href="'.$path.'pdf/'.$pdf.'"><img src="./estilo/iconos/pdf.png" width="50"/></a></td> ';		
		//echo '</div>';
		echo '</tr>';
	}
	//echo '</ul>';
	echo '</table>';
	echo '</div>';
}

function lee_publicacionesCapitulo($cap) {
	echo '<div class="descripcion">';
	$path='./informacion/C13/';
	$file = lee_fichero ( '../../informacion/' . $cap . '/publicaciones'.$cap.'.txt' );
	
	echo '<table style="border-spacing:5px 20px";>';
	//echo '<ul>';
	$i=0;
	foreach ( $file as $linea ) {
		$datos = explode ( "\t", $linea );		
		$info = $datos[0];
		$pdf = $datos[1];
		//echo '<tr>';
		if(($i++ % 2) == 0)
			echo '<tr class="impar">';
		else
			echo '<tr class="par">';
				
		echo '<td>' . utf8_decode ( $info ) . '<td>';
		echo '<td><a target="_blank" href="'.$path.'pdf/'.$pdf.'"><img src="./estilo/iconos/pdf.png" width="50"/></a></td> ';		
		//echo '</div>';
		echo '</tr>';
	}
	//echo '</ul>';
	echo '</table>';
	echo '</div>';
}
/*
 * 
 */
function carga_img($cap) {
	echo '<div class="imagenes">';
	
	echo '<h1>Images</h1>';
	$path = '../../informacion/' . $cap . '/imagenes/';
	$path2 = './informacion/' . $cap . '/imagenes/';
	$directorio = opendir ( $path ); // ruta actual
	$descripciones = array ();
	// leo descripciones
	while ( $ruta = readdir ( $directorio ) ) 	// obtenemos un archivo y luego otro sucesivamente
	{
		if (! is_dir ( $archivo ) && strstr ( strtolower ( $archivo ), '.txt' )) {
			$nombre = split ( '\.', $archivo );
			$nombre = $nombre [0];
			$descripciones [$nombre] = lee_fichero ( $path . $archivo );
		}
	}
	closedir ( $directorio );
	$directorio = opendir ( $path ); // ruta actual
	$mostradas = 0;
	$int_div = '';
	
	$imagenes_mostrar = array ();
	while ( $archivo = readdir ( $directorio ) ) 	// obtenemos un archivo y luego otro sucesivamente
	{
		if (! is_dir ( $archivo ) && (strstr ( strtolower ( $archivo ), '.jpg' ) != false || strstr ( strtolower ( $archivo ), '.png' ) != false)) {
			$nombre = split ( "\.", $archivo );
			$nombre = $nombre [0];
			if($mostradas%5 == 0 && $mostradas != 0){
				echo '<div class="clr"></div>';
				foreach($imagenes_mostrar as $ruta){
					echo '<div style="width:20%;float:left;text-align:center;">';
					echo "<img src='" . $ruta  . "'style='width:95%;border-radius: 40px;'/>";
					echo '</div>';					
				}
				$imagenes_mostrar = array ();
			}			
			array_push($imagenes_mostrar, $path2 . $archivo);	
			
			$mostradas ++;
		}
	}
	echo '<div class="clr"></div>';
	echo '<div style="float:left;height:50px;width:'. ((5-count($imagenes_mostrar))*20)/2 .'%;"></div>';
	foreach($imagenes_mostrar as $ruta){
		echo '<div style="width:20%;float:left;text-align:center;">';
		echo "<img src='" . $ruta  . "'style='width:95%;border-radius: 40px;'/>";
		echo '</div>';
	}
	
	echo '<div class="clr"></div>';
	
	
	closedir ( $directorio );
	echo '</div>';
}

function get_real_ip(){
        if (isset($_SERVER["HTTP_CLIENT_IP"]))
        {
            return $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
        {
            return $_SERVER["HTTP_X_FORWARDED"];
        }
        elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
        {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_FORWARDED"]))
        {
            return $_SERVER["HTTP_FORWARDED"];
        }
        else
        {
            return $_SERVER["REMOTE_ADDR"];
        }
 
}

function registro_acceso($cad){
$nombre_archivo = 'acceso.txt';

// Primero vamos a asegurarnos de que el archivo existe y es escribible.
if (is_writable($nombre_archivo)) {

    // En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adición.
    // El puntero al archivo está al final del archivo
    // donde irá $contenido cuando usemos fwrite() sobre él.
    if (!$gestor = fopen($nombre_archivo, 'a')) {
         echo "No se puede abrir el archivo ($nombre_archivo)";
         exit;
    }
    
    $ip = get_real_ip();
	$contenido = $cad . "\t" . $ip. "\t" . date(DATE_RFC2822) . "\t";
	//$contenido .= 'http://www.ip-tracker.org/locator/ip-lookup.php?ip='.$ip . "\r\n";
	$contenido .= "\r\n";
	
    // Escribir $contenido a nuestro archivo abierto.
    if (fwrite($gestor, $contenido) === FALSE) {
        echo "No se puede escribir en el archivo ($nombre_archivo)";
        exit;
    }

    //echo "Éxito, se escribió ($contenido) en el archivo ($nombre_archivo)";

    fclose($gestor);

} else {
    echo "El archivo $nombre_archivo no es escribible";
}
}

function registro_descarga($cad){
$nombre_archivo = 'descargas.txt';

// Primero vamos a asegurarnos de que el archivo existe y es escribible.
if (is_writable($nombre_archivo)) {

    // En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adición.
    // El puntero al archivo está al final del archivo
    // donde irá $contenido cuando usemos fwrite() sobre él.
    if (!$gestor = fopen($nombre_archivo, 'a')) {
         echo "No se puede abrir el archivo ($nombre_archivo)";
         exit;
    }
	$contenido = $cad . "\t" . get_real_ip(). "\t" . date(DATE_RFC2822) . "\r\n";
    // Escribir $contenido a nuestro archivo abierto.
    if (fwrite($gestor, $contenido) === FALSE) {
        echo "No se puede escribir en el archivo ($nombre_archivo)";
        exit;
    }

    //echo "Éxito, se escribió ($contenido) en el archivo ($nombre_archivo)";

    fclose($gestor);

} else {
    echo "El archivo $nombre_archivo no es escribible";
}
}

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}
function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function debug_to_console($data) {
    if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
}