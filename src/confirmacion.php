<?php
session_start();
include 'menu.php';
require_once 'db/db.php';

if (!isset($_GET['id_pedido'])) {
    echo "<p>Pedido no encontrado.</p>";
    exit();
}

$id_pedido = (int)$_GET['id_pedido'];
$db->open();
$queryPedido = "SELECT p.*, u.nombre, u.email, u.direccion, ep.estado_pedido 
                FROM Pedido p 
                JOIN Usuario u ON p.id_usuario = u.id_usuario 
                JOIN EstadoPedido ep ON p.id_estado_pedido = ep.id_estado_pedido 
                WHERE p.id_pedido = $id_pedido";
$resultPedido = mysqli_query($db->a_conexion, $queryPedido);
$pedido = mysqli_fetch_assoc($resultPedido);


$queryProductos = "SELECT p.nombre, dp.cantidad, dp.precio_unitario AS precio 
                   FROM Producto p 
                   JOIN DetallePedido dp ON p.id_producto = dp.id_producto 
                   WHERE dp.id_pedido = $id_pedido";
$resultProductos = mysqli_query($db->a_conexion, $queryProductos);


$db->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link rel="stylesheet" href="css/confirmacion.css">
</head>

<body>
    <div class="confirmacion-container">
        <h1>Confirmación de Pedido</h1>

        <h2>Datos del Usuario</h2>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['nombre']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($pedido['email']); ?></p>
        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion']); ?></p>

        <h2>Productos Comprados</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = mysqli_fetch_assoc($resultProductos)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo $producto['cantidad']; ?></td>
                        <td><?php echo $producto['precio'] * $producto['cantidad']; ?> €</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <p><strong>Total: </strong><?php echo $pedido['total']; ?> €</p>
        <p><strong>Estado del Pedido:</strong> Pendiente</p>
    </div>
</body>

</html>