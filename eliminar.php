
<?php
session_start();
require_once 'conexion.php';

$_SESSION['csrf_eliminar'] ??= bin2hex(random_bytes(32));

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit('Identificador de producto inválido.');
}

$consulta = $conexion->prepare(
    "SELECT * FROM productos WHERE id_producto = ?"
);

$consulta->execute([$id]);
$producto = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    exit('El producto no existe.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf'] ?? '';

    if (!is_string($token) ||
        !hash_equals($_SESSION['csrf_eliminar'], $token)) {
        exit('Solicitud inválida.');
    }

    $sql = "DELETE FROM productos
            WHERE id_producto = ?";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([$id]);

    if ($consulta->rowCount() > 0) {

        echo "<script>
            alert('Producto eliminado correctamente');
            window.location.href = 'listar.php';
        </script>";

    } else {

        echo "El producto ya no existe.";
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Eliminar producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-danger text-white">
                    <h3>Eliminar producto</h3>
                </div>

                <div class="card-body">

                    <div class="alert alert-warning">

                        ¿Está seguro de eliminar este producto?

                        Esta acción no se puede deshacer.

                    </div>

                    <p>
                        <strong>Nombre:</strong>
                        <?= htmlspecialchars($producto['nombre']) ?>
                    </p>

                    <p>
                        <strong>Categoría:</strong>
                        <?= htmlspecialchars($producto['categoria']) ?>
                    </p>

                    <p>
                        <strong>Precio:</strong>
                        $<?= htmlspecialchars($producto['precio']) ?>
                    </p>

                    <p>
                        <strong>Cantidad:</strong>
                        <?= htmlspecialchars($producto['cantidad']) ?>
                    </p>

                    <form method="POST"
                          onsubmit="return confirm('¿Desea eliminar definitivamente este producto?');">

                        <input type="hidden"
                               name="csrf"
                               value="<?= htmlspecialchars($_SESSION['csrf_eliminar']) ?>">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="listar.php"
                               class="btn btn-secondary">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="btn btn-danger">
                                Eliminar producto
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>