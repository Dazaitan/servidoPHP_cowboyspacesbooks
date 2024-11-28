<?php
$metodo =$_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');
if ($metodo=='POST'){
    require_once '../conexion.php';
    $data = json_decode(file_get_contents('php://input'), true);
    $nameList = $data['nameList'];

    $sql = "INSERT INTO listas (nameList) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nameList);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Lista guardada correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al guardar tiempo de lectura"]);
    }
}
?>