<?php

require_once __DIR__ . '/conexion.php';

// Consultar todos los productos
$sql = "SELECT * FROM productos ORDER BY id_producto DESC";

// Ejecutar la consulta
$consulta = $conexion->query($sql);

// Obtener los registros
$productos = $consulta->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Listado de productos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Encabezado -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">
                Sistema de gestión de productos
            </span>
        </div>
    </nav>

    <div class="container mt-5">

        <h2>Listado de productos</h2>

        <p class="text-muted">
            Consulta los productos registrados en el sistema.
        </p>

        <!-- Total de productos -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted">Total de productos</h6>

                <h3>
                    <?= count($productos) ?>
                </h3>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="card shadow-sm">

            <div class="card-header bg-dark text-white">
                Inventario de productos
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Categoría</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (empty($productos)): ?>

                                <tr>
                                    <td colspan="6" class="text-center">
                                        No hay productos registrados.
                                    </td>
                                </tr>

                            <?php else: ?>

                                <?php foreach ($productos as $producto): ?>

                                    <tr>
                                        <td>
                                            <?= (int) $producto['id_producto'] ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($producto['nombre']) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($producto['descripcion']) ?>
                                        </td>

                                        <td>
                                            $<?= number_format($producto['precio'], 2, ',', '.') ?>
                                        </td>

                                        <td>
                                            <?php if ($producto['cantidad'] > 0): ?>

                                                <span class="badge bg-success">
                                                    <?= (int) $producto['cantidad'] ?>
                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-danger">
                                                    Agotado
                                                </span>

                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($producto['categoria']) ?>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="card-footer text-muted">
                Total de registros: <?= count($productos) ?>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>