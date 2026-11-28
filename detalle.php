<?php

require_once __DIR__ . '/conexion.php';

$buscar = $_GET['buscar'] ?? '';

$buscar = is_string($buscar) ? trim($buscar) : '';

// Validar el identificador recibido
$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

$producto = false;

// Consultar el producto si el ID es válido
if ($id !== false && $id !== null && $id > 0) {

    $sql = "SELECT * FROM productos
            WHERE id_producto = :id";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    $producto = $consulta->fetch(PDO::FETCH_ASSOC);
}

// Indicar que el producto no existe
if (!$producto) {
    http_response_code(404);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Detalle del producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">
                Sistema de gestión de productos
            </span>
        </div>
    </nav>

    <div class="container mt-5">

        <h2 class="mb-4">Detalle del producto</h2>

        <?php if ($producto): ?>

            <div class="card shadow-sm">

                <div class="card-header bg-dark text-white">
                    Información del producto
                </div>

                <div class="card-body p-4">

                    <p class="text-muted">
                        Producto #<?= (int) $producto['id_producto'] ?>
                    </p>

                    <h2>
                        <?= htmlspecialchars($producto['nombre']) ?>
                    </h2>

                    <?php if ($producto['cantidad'] > 0): ?>

                        <span class="badge bg-success">
                            Disponible
                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger">
                            Agotado
                        </span>

                    <?php endif; ?>

                    <hr>

                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <h6 class="text-muted">Precio</h6>

                            <h3 class="text-success">
                                $<?= number_format(
                                    $producto['precio'],
                                    2,
                                    ',',
                                    '.'
                                ) ?>
                            </h3>

                        </div>

                        <div class="col-md-6 mb-4">

                            <h6 class="text-muted">
                                Cantidad disponible
                            </h6>

                            <h3>
                                <?= (int) $producto['cantidad'] ?>
                                unidades
                            </h3>

                        </div>

                        <div class="col-md-6 mb-4">

                            <h6 class="text-muted">Categoría</h6>

                            <p>
                                <?= htmlspecialchars($producto['categoria']) ?>
                            </p>

                        </div>

                        <div class="col-md-6 mb-4">

                            <h6 class="text-muted">Identificador</h6>

                            <p>
                                <?= (int) $producto['id_producto'] ?>
                            </p>

                        </div>

                    </div>

                    <hr>

                    <h5>Descripción</h5>

                    <p>
                        <?= nl2br(htmlspecialchars(
                            $producto['descripcion']
                        )) ?>
                    </p>

                    <a href="listar.php?buscar=<?= urlencode($buscar) ?>"
   class="btn btn-dark">

    Volver al listado

</a>

                </div>

            </div>

        <?php else: ?>

            <div class="alert alert-warning">

                <h4>Producto no encontrado</h4>

                <p>
                    El producto solicitado no existe
                    o el identificador no es válido.
                </p>

                <a href="listar.php" class="btn btn-dark">
                    Volver al listado
                </a>

            </div>

        <?php endif; ?>

    </div>

</body>

</html>