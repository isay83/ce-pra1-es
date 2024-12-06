<?php
session_start();
include 'db/db.php';
include 'menu.php';

// Obtener el ID del producto desde la URL
$id_producto = isset($_GET['id_producto']) ? (int)$_GET['id_producto'] : 0;

// Consultar la información del producto seleccionado
$db->open();
$queryProducto = "SELECT * FROM Producto WHERE id_producto = $id_producto";
$resultProducto = mysqli_query($db->a_conexion, $queryProducto);

// Verificar si el producto existe
if (mysqli_num_rows($resultProducto) == 0) {
    echo "<p>El producto no existe.</p>";
    $db->close();
    exit();
}

// Obtener la información del producto
$producto = mysqli_fetch_assoc($resultProducto);

// Manejar la adición al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $id_producto = (int)$_POST['id_producto'];
    $cantidad = 1;

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto]['cantidad'] += $cantidad;
    } else {
        $_SESSION['carrito'][$id_producto] = [
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'imagen' => $producto['imagen'],
            'cantidad' => $cantidad,
        ];
    }

    // Redirigir al carrito o mostrar un mensaje de éxito
    header('Location: detalle-producto.php?id_producto=' . $id_producto);
    exit();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perlux - <?php echo htmlspecialchars($producto['nombre']); ?></title>
    <link rel="stylesheet" href="css/detalle-producto.css">
</head>

<body>
    <div class="container">
        <div class="product-details">
            <!-- Imagen del producto -->
            <div class="product-image">
                <img src="img/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
            </div>

            <!-- Información del producto -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
                <p class="description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                <p class="price"><?php echo htmlspecialchars($producto['precio']); ?> €</p>

                <!-- Botón para añadir al carrito -->
                <form action="detalle-producto.php?id_producto=<?php echo $producto['id_producto']; ?>" method="POST">
                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                    <button type="submit" class="add-to-cart">Añadir al carrito</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>