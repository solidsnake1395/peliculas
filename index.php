<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Asegura que se muestren los errores
session_start();
include_once("sesiones.php");
include_once("class.ConexionBD1.php");

$pagina = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : "buscarPeliculas.php";
$validar = isset($_POST['validar']) ? $_POST['validar'] : null;
$login = isset($_POST['login']) ? $_POST['login'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

// Si se ha enviado el formulario de login
if (isset($validar)) {
    try {
        // Validar que los campos no están vacíos
        if (empty($login) || empty($password)) {
            echo "<script>alert('Login y Password son requeridos');</script>";
        } else {
            // Conectar a la base de datos
            $conexion = new ConexionBD1();
            $conexion->conectar_bd();

            // Usar consulta preparada para evitar inyecciones SQL
            $sql = "SELECT * FROM usuarios WHERE login = ?";
            $stmt = $conexion->getConexion()->prepare($sql);
            $loginStr = strtoupper($login); // Convierte el login a mayúsculas
            $stmt->bind_param("s", $loginStr); // Bind el login
            $stmt->execute();
            $result = $stmt->get_result();

            // Si el usuario existe
            if ($result->num_rows > 0) {
                $reg = $result->fetch_assoc();

                // Verificar la contraseña usando password_verify()
                if (password_verify($password, $reg['password'])) {
                    // Iniciar sesión si las credenciales son correctas
                    iniciar_sesion($reg["tipo"], $login);
                } else {
                    echo "<script>alert('Usuario/Password incorrecto');</script>";
                }
            } else {
                echo "<script>alert('Usuario/Password incorrecto');</script>";
            }

            $conexion->cerrar_conexion();
        }
    } catch (Exception $e) {
        echo "Error en la conexión: " . $e->getMessage();
    }
}

// Se controla si se ha iniciado sesión
$VALIDADO = validar_sesion($login, $tipo_usuario);
?>
<html>
<head>
    <link rel="STYLESHEET" type="text/css" href="css/estilos.css">
</head>
<body>

<form name="menu" method="post" action="index.php">
    <input type="hidden" name="pagina">
</form>

<div id="TODO" align="center">
    <table width="950" border="0" cellspacing="0">
        <tr>
            <td class="izquierda" width="200" valign="top">
                <table width="200" border="0" cellspacing="0">
                    <?php
                    // Si no se ha iniciado sesión
                    if ($VALIDADO == 0) {
                    ?>

                    <form name="formulario_login" method="post" action="index.php">
                    <tr>
                        <td height="180" colspan="2"></td>
                    </tr>
                    <td align="center">
                        <table cellspacing="0" cellpadding="4">
                            <tr>
                                <td class="formulario">Login: </td>
                                <td class="formulario"><input type="text" name="login" size="8" maxlength="12"></td>
                            </tr>
                            <tr>
                                <td class="formulario">Password: </td>
                                <td class="formulario"><input type="password" name="password" size="8" maxlength="15"></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center" class="formulario">
                                    <input type="submit" name="validar" value="Validar"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="reset" name="borrar" value="Borrar">
                                </td>
                            </tr>
                            <tr>
                                <td width="150" colspan="2" class="Registrar" align="center">
                                    <a class="textoRegistrar" href="#" onClick="document.menu.pagina.value='registrarUsuario.php';document.menu.submit()">Registrar usuario</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                    </form>
                    <?php
                    } else { // Si el usuario ha iniciado sesión
                    ?>
                    <tr>
                        <td height="130" colspan="2"></td>
                    </tr>
                    <td colspan="2" align="center">
                        <table cellspacing="0">
                            <tr>
                                <td height="50" class="Registrar"><span class="textoUsuario">Usuario actual:</span></td>
                                <td height="50" class="Registrar"><span class="textoLogin"><?php echo $login; ?></span></td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                    <tr>
                        <td height="50" colspan="2"></td>
                    </tr>
                    <tr>
                        <td width="200" colspan="2" height="30" class="formulario">
                            <a class="textoFormulario" href="#" onClick="document.menu.pagina.value='buscarPeliculas.php';document.menu.submit()">Busqueda de películas</a>
                        </td>
                    </tr>
                    <?php
                        // Si el usuario es administrador
                        if ($tipo_usuario == 0) {
                    ?>
                    <tr>
                        <td width="200" height="30" colspan="2" class="formulario">
                            <a class="textoFormulario" href="#" onClick="document.menu.pagina.value='insertarPelicula.php';document.menu.submit()">Alta de películas</a>
                        </td>
                    </tr>
                    <tr>
                        <td width="200" height="30" colspan="2" class="formulario">
                            <a class="textoFormulario" href="#" onClick="document.menu.pagina.value='eliminarPeliculas.php';document.menu.submit()">Eliminar películas</a>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                    <tr>
                        <td width="200" height="30" class="formulario" colspan="2">
                            <a class="textoFormulario" href="#" onClick="document.menu.pagina.value='misPuntuaciones.php';document.menu.submit()">Mis puntuaciones</a>
                        </td>
                    </tr>
                    <tr>
                        <td width="200" height="30" colspan="2" class="formulario">
                            <a class="textoFormulario" href="#" onClick="document.menu.pagina.value='cerrarSesion.php';document.menu.submit()">Cerrar Sesión</a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </td>
            <td width="750" class="Derecha" align="center">
                <table cellspacing="0" border="0" height="500">
                    <tr height="80">
                        <td class="logo" align="center">Películas</td>
                    </tr>
                    <tr height="500">
                        <td valign="top"><?php include($pagina); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
