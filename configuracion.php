
<?php

// Path de las clases y de las paginas estándar
define("CLASS_PATH", dirname($_SERVER['PHP_SELF'])."/");
define("APPLICATION_PATH", dirname($_SERVER['PHP_SELF'])."/");

// Configuración de la conexión a la base de datos
define("BD_SERVIDOR", "fdb1028.atspace.me"); // Host de la base de datos
define("BD_NOMBRE", "4560618_wordpress"); // Nombre de la base de datos
define("BD_USUARIO", "4560618_wordpress"); // Usuario de la base de datos
define("BD_PASSWORD", "w3:]((v#6:yfLypd"); // Reemplaza esto con la contraseña correcta

// Configuración de longitudes mínimas para login y password
define("LONGITUD_MINIMA_LOGIN", 6);
define("LONGITUD_MINIMA_PASSWORD", 6);

// Configuración de la cantidad de resultados por página
define("PAGINACION", 10);

?>
