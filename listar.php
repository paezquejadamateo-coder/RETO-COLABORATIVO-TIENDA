<?php

require_once __DIR__ . '/conexion.php';

// Consultar todos los productos
$sql = "SELECT * FROM productos ORDER BY id_producto DESC";

// Ejecutar la consulta
$consulta = $conexion->query($sql);

// Obtener los productos
$productos = $consulta->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($productos);
echo "</pre>";