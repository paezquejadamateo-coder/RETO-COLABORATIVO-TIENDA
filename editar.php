
<?php
require_once 'conexion.php';

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

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = $_POST['precio'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $categoria = trim($_POST['categoria'] ?? '');

    if ($nombre === '' || $descripcion === '' ||
        $precio === '' || $cantidad === '' ||
        $categoria === '') {

        $mensaje = "Todos los campos son obligatorios.";

    } elseif (!preg_match('/^\d{1,10}(\.\d{1,2})?$/D', $precio) ||
              filter_var($cantidad, FILTER_VALIDATE_INT, [
                  'options' => [
                      'min_range' => 0,
                      'max_range' => 2147483647
                  ]
              ]) === false ||
              mb_strlen($nombre) > 100 ||
              mb_strlen($categoria) > 100 ||
              strlen($descripcion) > 65535) {

        $mensaje = "Verifique los valores ingresados.";

    } else {

        $sql = "UPDATE productos SET
                nombre = ?,
                descripcion = ?,
                precio = ?,
                cantidad = ?,
                categoria = ?
                WHERE id_producto = ?";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            $nombre,
            $descripcion,
            $precio,
            $cantidad,
            $categoria,
            $id
        ]);

        echo "<script>
            alert('Producto actualizado correctamente');
            window.location.href = 'listar.php';
        </script>";

        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header bg-warning">
                    <h3>Editar producto</h3>
                </div>

                <div class="card-body">

                    <?php if ($mensaje !== ''): ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($mensaje) ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label>Nombre</label>

                            <input type="text"
                                   name="nombre"
                                   class="form-control"
                                   maxlength="100"
                                   value="<?= htmlspecialchars($producto['nombre']) ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Descripción</label>

                            <textarea name="descripcion"
                                      class="form-control"
                                      required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Precio</label>

                            <input type="number"
                                   name="precio"
                                   class="form-control"
                                   min="0"
                                   step="0.01"
                                   value="<?= htmlspecialchars($producto['precio']) ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Cantidad</label>

                            <input type="number"
                                   name="cantidad"
                                   class="form-control"
                                   min="0"
                                   step="1"
                                   value="<?= htmlspecialchars($producto['cantidad']) ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Categoría</label>

                            <input type="text"
                                   name="categoria"
                                   class="form-control"
                                   maxlength="100"
                                   value="<?= htmlspecialchars($producto['categoria']) ?>"
                                   required>
                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="listar.php"
                               class="btn btn-secondary">
                                Volver
                            </a>

                            <button type="submit"
                                    class="btn btn-warning">
                                Guardar cambios
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