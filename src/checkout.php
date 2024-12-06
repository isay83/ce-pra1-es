<?php
session_start();
include 'menu.php';
require_once 'db/db.php';

// Si el carrito está vacío, redirigir a la página de productos
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<p>El carrito está vacío.</p>";
    exit();
}

// Obtener el total de los productos en el carrito
$carrito = $_SESSION['carrito'];
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

$db->open();
$queryRoles = "SELECT * FROM Rol";
$resultRoles = mysqli_query($db->a_conexion, $queryRoles);
$db->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Compra</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>

<body>
    <div class="checkout-container">
        <h1>Proceso de Compra</h1>

        <form action="procesar-pedido.php" method="POST" class="checkout-form">
            <div class="tipo-usuario">
                <label for="tipo_usuario">Seleccione el tipo de usuario:</label><br>
                <input type="radio" name="tipo_usuario" value="invitado" checked> Invitado
                <input type="radio" name="tipo_usuario" value="registrado"> Registrado
            </div>

            <div id="invitado" class="user-form">
                <h2>Datos de usuario invitado</h2>
                <label for="email">Correo electrónico:</label>
                <input type="email" name="email" required><br>
                <label for="direccion">Dirección de envío:</label>
                <textarea name="direccion" required></textarea><br>
            </div>

            <div id="registrado" class="user-form" style="display:none;">
                <h2>Datos de usuario registrado</h2>
                <label for="nombre">Nombre completo:</label>
                <input type="text" name="nombre"><br>
                <label for="email">Correo electrónico:</label>
                <input type="email" name="email"><br>
                <label for="password">Contraseña:</label>
                <input type="password" name="password"><br>
                <label for="direccion">Dirección de envío:</label>
                <textarea name="direccion"></textarea><br>
            </div>

            <h2>Resumen del carrito</h2>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito as $id_producto => $item) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td><?php echo $item['cantidad']; ?></td>
                            <td><?php echo $item['precio'] * $item['cantidad']; ?> €</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p><strong>Total: </strong><?php echo $total; ?> €</p>

            <h2>Método de pago</h2>
            <label for="metodo_pago">Seleccione un método de pago:</label>
            <select name="id_metodo_pago">
                <option value="1">Efectivo</option>
                <option value="2">Débito/Crédito</option>
            </select><br><br>

            <button type="submit">Confirmar compra</button>
        </form>
    </div>

    <script>
        // Mostrar formulario de registro si el usuario selecciona "registrado"
        document.querySelectorAll('input[name="tipo_usuario"]').forEach((radio) => {
            radio.addEventListener('change', () => {
                if (document.querySelector('input[name="tipo_usuario"]:checked').value === 'registrado') {
                    document.getElementById('registrado').style.display = 'block';
                    document.getElementById('invitado').style.display = 'none';
                } else {
                    document.getElementById('invitado').style.display = 'block';
                    document.getElementById('registrado').style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>