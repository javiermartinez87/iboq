# i-BoQ: Intelligent System for the Acquisition and Management of information from Bill of Quantities in Building Projects


# Instalación

<ol>
    <li>Instalar servidor web apache (https://httpd.apache.org/download.cgi)</li>
    <li>Configurar el servidor con PHP5.4</li>
    <li>Instalar mysql</li>
    <li>Crear una base de datos con el fichero localizado en la carpeta fuente src en "sql/iboq.sql"</li>
    <li>Crear un usuario con contraseña en MD5 en la tabla "usuario"</li>
    <li>Modificar fichero /include/datos_bd.php con los datos de la conexión a la base de datos.</li>
    <li>Descargar las siguientes librerias en la carpeta lib:</li>
        <ul>
            <li>PHPExcel: "https://github.com/PHPOffice/PHPExcel"</li>
            <li>PHPMailer "https://github.com/PHPMailer/PHPMailer"</li>
        </ul>
    <li>El archivo principal es index.php</li> 
</ol>

María Martínez Rojas y Javier Martínez Rojas