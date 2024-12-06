<?php
require_once 'db/db.php';
include 'menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perlux - Inicio</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <?php
        // Crear una conexión para consultar las categorías
        $queryCategorias = "SELECT * FROM Categoria";
        $db->open();
        $categorias = mysqli_query($db->a_conexion, $queryCategorias);

        // Iterar sobre las categorías
        while ($categoria = mysqli_fetch_assoc($categorias)) {
            echo "<div class='category'>";
            echo "<h2 class='title'>" . htmlspecialchars($categoria['nombre']) . "</h2>";

            // Crear un enlace para la categoría
            echo "<a href='productos.php?id_categoria=" . $categoria['id_categoria'] . "' class='view-products-link'>Ver Productos</a>";

            // Crear una nueva conexión para consultar las marcas relacionadas
            $queryMarcas = "SELECT * FROM Marca WHERE id_categoria = " . $categoria['id_categoria'];
            $marcas = mysqli_query($db->a_conexion, $queryMarcas);

            echo "<div class='cards'>";
            while ($marca = mysqli_fetch_assoc($marcas)) {
                echo "<div class='card'>";
                //echo "<img src='img/logo/" . htmlspecialchars($marca['logo']) . "' alt='" . htmlspecialchars($marca['nombre']) . "'>";
                echo "<h3>" . htmlspecialchars($marca['nombre']) . "</h3>";
                echo "<p>" . htmlspecialchars($marca['descripcion']) . "</p>";
                echo "<a href='productos.php?id_categoria=" . $categoria['id_categoria'] . "&id_marca=" . $marca['id_marca'] . "' class='view-products-link'>Ver productos de " . htmlspecialchars($marca['nombre']) . "</a>";
                echo "</div>";
            }
            echo "</div>"; // Cierra cards
            echo "</div>"; // Cierra category
        }

        $db->close();
        ?>
    </div>
</body>

</html>