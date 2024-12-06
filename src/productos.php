<?php
include 'db/db.php';
include 'menu.php';

// Obtener los parámetros de la URL
$id_categoria = isset($_GET['id_categoria']) ? (int)$_GET['id_categoria'] : 0;
$id_marca = isset($_GET['id_marca']) ? (int)$_GET['id_marca'] : 0;

// Consultar la categoría seleccionada
$queryCategoria = "SELECT * FROM Categoria WHERE id_categoria = $id_categoria";
$db->open();
$categoria = mysqli_query($db->a_conexion, $queryCategoria);
$categoria = mysqli_fetch_assoc($categoria);

// Consultar las marcas y productos de la categoría seleccionada
$queryMarcas = "SELECT * FROM Marca WHERE id_categoria = $id_categoria";
$marcas = mysqli_query($db->a_conexion, $queryMarcas);

// Consultar productos de la marca seleccionada si hay una marca específica
if ($id_marca > 0) {
    $queryProductos = "SELECT Producto.* FROM Producto WHERE id_marca = $id_marca";
} else {
    $queryProductos = "
        SELECT Producto.*
        FROM Producto
        INNER JOIN Marca ON Producto.id_marca = Marca.id_marca
        WHERE Marca.id_categoria = $id_categoria
    ";
}

$productos = mysqli_query($db->a_conexion, $queryProductos);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perlux - Productos</title>
    <link rel="stylesheet" href="css/productos.css">
</head>

<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($categoria['nombre']); ?></h1>

        <?php if ($id_marca > 0): ?>
            <h2>Marca: <?php
                        // Mostrar el nombre de la marca seleccionada
                        $queryMarca = "SELECT * FROM Marca WHERE id_marca = $id_marca";
                        $marcaResult = mysqli_query($db->a_conexion, $queryMarca);
                        $marca = mysqli_fetch_assoc($marcaResult);
                        echo htmlspecialchars($marca['nombre']);
                        ?></h2>
        <?php endif; ?>

        <div class="products">
            <?php while ($producto = mysqli_fetch_assoc($productos)): ?>
                <div class="product-card">
                    <img src="img/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p><?php echo htmlspecialchars($producto['precio']); ?> €</p>
                        <a href="detalle-producto.php?id_producto=<?php echo $producto['id_producto']; ?>" class="view-details">Ver detalles</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    </div>
</body>

</html>

<?php
$db->close();
?>