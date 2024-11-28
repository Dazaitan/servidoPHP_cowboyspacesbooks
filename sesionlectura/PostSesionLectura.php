<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $isbn = $data['libroId'];
    $tiempo = $data['tiempoTotal']; // Tiempo en segundos

    $sql = "INSERT INTO sesionlectura (libroId, totalTime) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $isbn, $tiempo);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Tiempo de lectura guardado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al guardar tiempo de lectura"]);
    }

    $stmt->close();
    $conn->close();
}
?>