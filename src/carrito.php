<?php
session_start();
include 'menu.php';

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<p>El carrito está vacío.</p>";
    exit();
}

$carrito = $_SESSION['carrito'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = (int)$_POST['id_producto'];

    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'incrementar') {
            $carrito[$id_producto]['cantidad']++;
        } elseif ($_POST['accion'] === 'decrementar' && $carrito[$id_producto]['cantidad'] > 1) {
            $carrito[$id_producto]['cantidad']--;
        } elseif ($_POST['accion'] === 'eliminar') {
            unset($carrito[$id_producto]);
        }
    }

    $_SESSION['carrito'] = $carrito;
    header('Location: carrito.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="css/carrito.css">
</head>

<body>
    <div class="carrito-container">
        <h1>Carrito de Compras</h1>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carrito as $id_producto => $item) { ?>
                    <tr>
                        <td>
                            <img src="img/productos/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" class="img-carrito">
                            <span><?php echo htmlspecialchars($item['nombre']); ?></span>
                        </td>
                        <td><?php echo $item['cantidad']; ?></td>
                        <td><?php echo $item['precio'] * $item['cantidad']; ?> €</td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id_producto" value="<?php echo $id_producto; ?>">
                                <button name="accion" value="incrementar">+</button>
                                <button name="accion" value="decrementar">-</button>
                                <button name="accion" value="eliminar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="container-button">
            <a href="checkout.php"><button type="button" class="go-to-checkout">Ir a pagar</button></a>
        </div>
    </div>
</body>

</html>