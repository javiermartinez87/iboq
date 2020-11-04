# i-BoQ: Intelligent System for the Acquisition and Management of information from Bill of Quantities in Building Projects

i-BoQ es un software no comercial desarrollado en php con arquitectura cliente-servidor cuya principal contribución es la gestión integral de la información contenida en el documento del presupuesto de proyectos de edificación. Para este propósito, i-BoQ dispone de tres módulos: adquisición, edición y recuperación. El módulo de adquisición hace posible cargar datos provenientes del documento del presupuesto y almacenarlos en un repositorio común estructurado, independientemente de la estructura y la descripción lingüística del documento. La clasificación se realiza mediante el uso de un modelo de agregación multi-criterio. El módulo de edición, a su vez, proporciona funciones de validación y edición fáciles de usar para los datos importados, mientras que el módulo de recuperación permite un acceso fácil a la información almacenada.

# Instalación

<ol>
    <li>Instalar servidor web apache (https://httpd.apache.org/download.cgi)</li>
    <li>Configurar el servidor con PHP7</li>
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

Descarga: https://github.com/javiermartinez87/iboq/releases/download/2.0/iboq_v2.0.exe

María Martínez Rojas y Javier Martínez Rojas
