<?php

$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$baseDatos = "tienda_db";

try {

    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4",
        $usuario,
        $contrasena
    );

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {

    error_log($e->getMessage());
    http_response_code(500);
    exit("Error al conectar con la base de datos.");

}