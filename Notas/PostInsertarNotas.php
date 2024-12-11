<?php
header('Content-Type: application/json');
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'POST') {
    require_once '../conexion.php';
    $input = json_decode(file_get_contents('php://input'), true);

    $isbn = $input['isbn'];
    $tipoNota = $input['tipoNota'];
    $cuerpo = $input['descripcion'];
    $pagInicio = $input['pagInicio'];
    $pagFinal = $input['pagFinal'];

    $sql = "INSERT INTO notas (fk_isbn_libro, pag_inicio, pag_fin, tipoNota,descripcion) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiss", $isbn, $pagInicio, $pagFinal, $tipoNota,$cuerpo);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Libro insertado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al insertar el libro"]);
    }

    $stmt->close();
    $conn->close();
}
?>
