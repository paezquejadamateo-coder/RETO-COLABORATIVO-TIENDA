<?php
require_once __DIR__ . "/conexion.php";

$mensaje = "";
$tipo = "";

$nombre = "";
$descripcion = "";
$precio = "";
$cantidad = "";
$categoria = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = trim($_POST["precio"] ?? "");

if (preg_match('/^-?\d{1,3}(\.\d{3})+$/', $precio)) {
    $precio = str_replace(".", "", $precio);
}
    $cantidad = trim($_POST["cantidad"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");

    // VALIDACIONES

    if ($nombre === "") {
        $mensaje = "El nombre es obligatorio.";

    } elseif ($categoria === "") {
        $mensaje = "La categoría es obligatoria.";

    } elseif ($descripcion === "") {
        $mensaje = "La descripción es obligatoria.";

    } elseif ($precio === "") {
        $mensaje = "El precio es obligatorio.";

    } elseif (!is_numeric($precio) || $precio < 0) {
        $mensaje = "El precio no puede ser negativo y debe ser numérico.";

    } elseif ($cantidad === "") {
        $mensaje = "La cantidad es obligatoria.";

    } elseif (!is_numeric($cantidad) || $cantidad < 0) {
        $mensaje = "La cantidad no puede ser negativa y debe ser numérica.";

    } elseif (filter_var($cantidad, FILTER_VALIDATE_INT) === false) {
        $mensaje = "La cantidad debe ser un número entero.";
    } elseif (!preg_match('/^\d+$/', $precio)) {

    $mensaje = "El precio debe ser un número entero.";

    } elseif (mb_strlen($nombre) > 100 || mb_strlen($categoria) > 100) {
        $mensaje = "El nombre y la categoría deben tener máximo 100 caracteres.";

    } elseif ($precio > 9999999999 || $cantidad > 2147483647) {
        $mensaje = "Los valores superan los límites permitidos.";
    }

    // GUARDAR SOLAMENTE SI NO HAY ERRORES

    if ($mensaje === "") {

        try {

            $sql = "INSERT INTO productos
                    (nombre, descripcion, precio, cantidad, categoria)
                    VALUES (?, ?, ?, ?, ?)";

            $consulta = $conexion->prepare($sql);

            $consulta->execute([
                $nombre,
                $descripcion,
                $precio,
                $cantidad,
                $categoria
            ]);

            $mensaje = "Producto registrado correctamente.";
            $tipo = "success";

            $nombre = "";
            $descripcion = "";
            $precio = "";
            $cantidad = "";
            $categoria = "";

        } catch (PDOException $e) {

            error_log($e->getMessage());
            $mensaje = "Error al registrar el producto.";
            $tipo = "danger";
        }

    } else {

        $tipo = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registrar producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-8 col-lg-7">

                <div class="card shadow border-0 rounded-4">

                    <div class="card-header bg-success text-white p-4">

                        <h3 class="mb-1">Registrar producto</h3>

                        <p class="mb-0">Gestión de inventario</p>

                    </div>

                    <div class="card-body p-4">

                      
                        <?php if ($mensaje !== "") { ?>

                            <div class="alert alert-<?php echo $tipo; ?> alert-dismissible fade show"
                                 role="alert">

                                <?php echo htmlspecialchars($mensaje); ?>

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="alert"
                                        aria-label="Cerrar">
                                </button>

                            </div>

                        <?php } ?>

                        <!-- FORMULARIO -->

                        <form method="POST"
                              action="registrar.php"
                              novalidate>

                            <div class="mb-3">

                                <label class="form-label">
                                    Nombre del producto *
                                </label>

                                <input type="text"
                                       name="nombre"
                                       class="form-control"
                                       placeholder="Ingrese el nombre"
                                       maxlength="100"
                                       value="<?php echo htmlspecialchars($nombre); ?>"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Categoría *
                                </label>

                           <input type="text"
       name="categoria"
       class="form-control"
       placeholder="Ingrese la categoría"
       maxlength="100"
       value="<?php echo htmlspecialchars($categoria); ?>"
       required>
       

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Descripción *
                                </label>

                                <textarea name="descripcion"
                                          class="form-control"
                                          placeholder="Ingrese la descripción"
                                          rows="3"
                                          required><?php echo htmlspecialchars($descripcion); ?></textarea>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Precio *
                                    </label>

                                    <input type="text"
       name="precio"
       id="precio"
       class="form-control"
       placeholder="0"
       inputmode="numeric"
       value="<?php echo htmlspecialchars($precio); ?>"
       required>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Cantidad *
                                    </label>

                                    <input type="number"
                                           name="cantidad"
                                           class="form-control"
                                           placeholder="0"
                                           min="0"
                                           step="1"
                                           value="<?php echo htmlspecialchars($cantidad); ?>"
                                           required>

                                </div>

                            </div>

                            <p class="text-secondary small">
                                * Todos los campos son obligatorios.
                            </p>

                            <div class="d-flex justify-content-end gap-2 mt-3">

                                <a href="registrar.php"
                                   class="btn btn-outline-secondary">

                                    Limpiar

                                </a>

                                <button type="submit"
                                        class="btn btn-success">

                                    Registrar producto

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>
<script>
    const precio = document.getElementById("precio");

    function formatearPrecio() {
        let valor = precio.value;

        if (/^-?\d*$/.test(valor) ||
            /^-?\d{1,3}(\.\d{3})*(\.\d{0,3})?$/.test(valor)) {

            valor = valor.replace(/\./g, "");

            precio.value = valor.replace(
                /\B(?=(\d{3})+(?!\d))/g, "."
            );
        }
    }

    precio.addEventListener("input", formatearPrecio);

    formatearPrecio();
</script>
</body>

</html>