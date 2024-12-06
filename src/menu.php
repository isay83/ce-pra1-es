<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Contador de productos únicos en el carrito
$carritoCantidad = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;

// Consultar categorías de la base de datos
require_once 'db/db.php';
$db->open();
$queryCategorias = "SELECT * FROM Categoria";
$resultCategorias = mysqli_query($db->a_conexion, $queryCategorias);
$db->close();
?>

<link rel="stylesheet" href="css/menu.css">

<nav class="menu">
    <div class="logo">
        <a href="index.php">
            <h1>PERLUX</h1>
        </a>
    </div>
    <div class="links">
        <?php while ($categoria = mysqli_fetch_assoc($resultCategorias)) { ?>
            <a href="productos.php?id_categoria=<?php echo $categoria['id_categoria']; ?>" class="view-products-link">
                <?php echo htmlspecialchars($categoria['nombre']); ?>
            </a>
        <?php } ?>
    </div>
    <div class="carrito">
        <a href="carrito.php">
            <img src="img/carrito.svg" alt="Carrito">
            <span class="contador"><?php echo $carritoCantidad; ?></span>
        </a>
    </div>
</nav>