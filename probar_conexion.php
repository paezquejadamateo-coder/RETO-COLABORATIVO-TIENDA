<?php

require_once __DIR__ . '/conexion.php';

$total = $conexion->query(
    "SELECT COUNT(*) FROM productos"
)->fetchColumn();

echo "<h1>Conexion exitosa</h1>";
echo "<p>PHP y MySQL funcionan correctamente.</p>";
echo "<p>Base de datos: tienda_db</p>";
echo "<p>Tabla: productos</p>";
echo "<p>Total de productos: $total</p>";