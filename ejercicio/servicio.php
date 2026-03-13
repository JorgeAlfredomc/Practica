<?php

header('Content-Type: application/json; charset=utf-8');

$dsn = 'mysql:host=localhost;port=3307;dbname=quesos;charset=utf8';
$user = 'root';
$password = '';

try {
    $conexion = new PDO($dsn, $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_usuario'])) {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $correo = trim($_POST['correo'] ?? '');

        if ($nombre !== '' && $apellidos !== '' && $correo !== '') {
            $insertar = $conexion->prepare(
                'INSERT INTO alumnos (nombre, apellidos, correo) VALUES (:nombre, :apellidos, :correo)'
            );
            $insertar->execute([
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':correo' => $correo
            ]);
        }
    }

    $consulta = 'SELECT * FROM alumnos';
    $lectura = $conexion->prepare($consulta);
    $lectura->execute();
    $arreglo = $lectura->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($arreglo);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error en el servidor',
        'detalle' => $e->getMessage()
    ]);
}
