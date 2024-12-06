<?php
session_start();
require_once 'db/db.php';

// Verificar que el carrito no esté vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<p>El carrito está vacío.</p>";
    exit();
}

$carrito = $_SESSION['carrito'];
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_usuario = $_POST['tipo_usuario'];
    $email = $tipo_usuario === 'invitado' ? $_POST['email'] : $_POST['email_reg'];
    $direccion = $_POST['direccion'];
    $id_metodo_pago = (int)$_POST['id_metodo_pago']; // Obtiene el ID del método de pago

    $db->open();

    // Si es un usuario registrado, verifica si existe
    if ($tipo_usuario === 'registrado') {
        $query = "SELECT * FROM Usuario WHERE email = '$email'";
        $result = mysqli_query($db->a_conexion, $query);

        if (mysqli_num_rows($result) > 0) {
            // El usuario ya existe, recuperar id_usuario
            $usuario = mysqli_fetch_assoc($result);
            $id_usuario = $usuario['id_usuario'];

            // Cambiar rol a "Usuario" si era invitado
            if ($usuario['id_rol'] === 1) {
                $updateQuery = "UPDATE Usuario SET id_rol = 2 WHERE id_usuario = $id_usuario";
                mysqli_query($db->a_conexion, $updateQuery);
            }
        } else {
            // El usuario no existe, crear un nuevo usuario
            $nombre = $_POST['nombre'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO Usuario (nombre, email, password, direccion, id_rol) 
                            VALUES ('$nombre', '$email', '$password', '$direccion', 2)";
            mysqli_query($db->a_conexion, $insertQuery);
            $id_usuario = mysqli_insert_id($db->a_conexion); // Obtener el id del nuevo usuario
        }
    } else {
        // Crear usuario invitado
        $password = password_hash('invitado', PASSWORD_DEFAULT);
        $insertQuery = "INSERT INTO Usuario (nombre, email, password, direccion, id_rol) 
                        VALUES ('Invitado', '$email', '$password', '$direccion', 1)";
        mysqli_query($db->a_conexion, $insertQuery);
        $id_usuario = mysqli_insert_id($db->a_conexion); // Obtener el id del nuevo usuario
    }

    // Crear pedido
    $queryPedido = "INSERT INTO Pedido (fecha, total, id_usuario, id_estado_pedido, id_metodo_pago) 
                    VALUES (NOW(), $total, $id_usuario, 1, $id_metodo_pago)";
    mysqli_query($db->a_conexion, $queryPedido);
    $id_pedido = mysqli_insert_id($db->a_conexion);

    // Insertar productos en el pedido
    foreach ($carrito as $id_producto => $item) {
        $queryDetallePedido = "INSERT INTO DetallePedido (id_pedido, id_producto, cantidad, precio_unitario) 
                           VALUES ($id_pedido, $id_producto, {$item['cantidad']}, {$item['precio']})";
        mysqli_query($db->a_conexion, $queryDetallePedido);
    }

    // Crear registro de envío
    $id_estado_envio = 1; // 'En proceso'
    $queryEnvio = "INSERT INTO Envio (direccion_envio, id_pedido, id_estado_envio) 
                   VALUES ('$direccion', $id_pedido, $id_estado_envio)";
    mysqli_query($db->a_conexion, $queryEnvio);

    // Limpiar carrito
    unset($_SESSION['carrito']);
    header('Location: confirmacion.php?id_pedido=' . $id_pedido);
    exit();
}

$db->close();
