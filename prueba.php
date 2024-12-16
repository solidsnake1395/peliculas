<?php
$conn = new mysqli("fdb1028.atspace.me", "4560618_wordpress", "a;V)e46H6Tr5GO_w", "4560618_wordpress");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
echo "Conexión exitosa!";
?>
