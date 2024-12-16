<?php

include_once("sesiones.php");
include_once("class.ConexionBD1.php");
include_once("configuracion.php");

function comprobar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Obtener valores enviados por el formulario y convertirlos a mayúsculas para insertarlos en la base de datos
foreach ($_POST as $nombre_campo => $valor) {
    $$nombre_campo = strtoupper($valor);
}

$error = false;
$existeUsuario = false;

// Si el formulario ha sido enviado -> Se valida y no hay errores en la introducción de datos
if (isset($insertar)) {
    // Comprobación de que se han introducido todos los datos obligatorios
    if (strcmp(trim($login), "") == 0) {
        $errores["login"] = "¡Hay que introducir el login del usuario!";
        $error = true;
    } else {
        if (strlen($login) < LONGITUD_MINIMA_LOGIN) {
            $errores["login"] = "¡El login del usuario tiene que tener al menos " . LONGITUD_MINIMA_LOGIN . " caracteres!";
            $error = true;
        } else {
            $errores["login"] = "";
        }
    }

    if (strcmp(trim($password), "") == 0) {
        $errores["password"] = "¡Hay que introducir el password del usuario!";
        $error = true;
    } else {
        if (strlen($password) < LONGITUD_MINIMA_PASSWORD) {
            $errores["password"] = "¡El password del usuario tiene que tener al menos " . LONGITUD_MINIMA_PASSWORD . " caracteres!";
            $error = true;
        } else {
            $errores["password"] = "";
        }
    }

    if (strcmp(trim($nombre), "") == 0) {
        $errores["nombre"] = "¡Hay que introducir el nombre del usuario!";
        $error = true;
    } else {
        $errores["nombre"] = "";
    }

    if (strcmp(trim($email), "") != 0 && comprobar_email($email) == false) {
        $errores["email"] = "¡Formato de email incorrecto!";
        $error = true;
    } else {
        $errores["email"] = "";
    }

    if ($error == false) { // Si no hay errores al introducir los datos
        $conexion = new ConexionBD1;
        $conexion->conectar_bd();
        $conn = $conexion->getConnection();

        // Comprobar si el login ya existe
        $sql = "SELECT * FROM usuarios WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $existeUsuario = true;
        }
    }
}

// Si se ha enviado el formulario y no existe un usuario con el mismo login, se inserta el usuario
if (isset($insertar) && $error == false && $existeUsuario == false) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertar usuario en la base de datos
    $sql = "INSERT INTO usuarios (login, password, nombre, apellidos, email, tipo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", strtoupper($login), $password_hash, $nombre, $apellidos, $email, $tipo);
    $stmt->execute();

    // Mensaje de confirmación
    ?>
    <table width="500" border="0" align="center" cellspacing="0" cellpadding="4">
        <tr>
            <td colspan="4" height="100"></td>
        </tr>
        <tr>
            <td colspan="4" class="formularioTitulo">Usuario registrado correctamente</td>
        </tr>
        <tr>
            <td colspan="4" class="formularioTitulo">Valídese para acceder al sistema</td>
        </tr>
    </table>
    <?php

    $conexion->cerrar_conexion();
} else { // Si no se ha enviado el formulario o ya existe un usuario con el mismo login
?>
<form name="formulario_registro" method="post" action="index.php">
    <input type="hidden" name="pagina" value="registrarUsuario.php">
    <table width="500" border="0" height="0" align="center" cellspacing="0">
        <tr>
            <td colspan="4" height="80"></td>
        </tr>

        <tr>
            <td class="formulario" colspan="4">REGISTRO DE USUARIO</td>
        </tr>
        <?php
        if ($existeUsuario == 1) {
        ?>
            <tr>
                <td class="formulario" colspan="4"><span class="textoAviso">¡YA EXISTE UN USUARIO CON ESE LOGIN!</span></td>
            </tr>
        <?php
        } else {
        ?>
            <tr>
                <td class="formulario" colspan="4" height="10"></td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td class="formulario">Login(*):</td>
            <td colspan="3" class="formulario"><input type="text" name="login" size="8" maxlength="12" value="<?php echo $login; ?>"></td>
        </tr>
        <?php
        if (isset($errores["login"]) && ($errores["login"] != "")) {
            echo "<tr class=\"formulario\">";
            echo "  <td class=\"formulario\" colspan=\"4\"><span class=\"textoAviso\">" . $errores["login"] . "</span></td>";
            echo "</tr>";
        }
        ?>
        <tr>
            <td class="formulario">Password(*):</td>
            <td colspan="3" class="formulario"><input type="password" name="password" size="10" maxlength="10"></td>
        </tr>
        <?php
        if (isset($errores["password"]) && ($errores["password"] != "")) {
            echo "<tr>";
            echo "  <td class=\"formulario\" colspan=\"4\"><span class=\"textoAviso\">" . $errores["password"] . "</span></td>";
            echo "</tr>";
        }
        ?>
        <tr>
            <td class="formulario">Nombre(*):</td>
            <td colspan="3" class="formulario"><input type="text" name="nombre" size="10" maxlength="30" value="<?php if (isset($nombre)) echo $nombre; ?>"></td>
        </tr>
        <?php
        if (isset($errores["nombre"]) && ($errores["nombre"] != "")) {
            echo "<tr>";
            echo "  <td class=\"formulario\" colspan=\"4\"><span class=\"textoAviso\">" . $errores["nombre"] . "</span></td>";
            echo "</tr>";
        }
        ?>
        <tr>
            <td class="formulario">Apellidos:</td>
            <td class="formulario" colspan="3"><input type="text" name="apellidos" size="30" maxlength="30" value="<?php if (isset($apellidos)) echo $apellidos; ?>"></td>
        </tr>
        <tr>
            <td class="formulario">Email:</td>
            <td class="formulario" colspan="3"><input type="text" name="email" size="30" maxlength="30" value="<?php if (isset($email)) echo $email; ?>"></td>
        </tr>
        <?php
        if (isset($errores["email"]) && ($errores["email"] != "")) {
            echo "<tr>";
            echo "  <td class=\"formulario\" colspan=\"4\"><span class=\"textoAviso\">" . $errores["email"] . "</span></td>";
            echo "</tr>";
        }
        ?>
        <tr>
            <td class="formulario" colspan="4">(*) Campos obligatorios</td>
        </tr>
        <tr>
            <td class="formulario" colspan="2" align="center"><input type="submit" name="insertar" value="insertar"></td>
            <td class="formulario" colspan="2" align="center"><input type="reset" name="borrar" value="borrar"></td>
        </tr>
    </table>
</form>
<?php
}

?>
