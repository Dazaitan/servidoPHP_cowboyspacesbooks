<?php
header('Content-Type: application/json');
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == "GET") {
    require_once '../conexion.php';
    $estado = $_GET["estado"];
    // Validar la conexión
    if (!$conn) {
        echo json_encode(["error" => "Error de conexión a la base de datos"]);
        exit;
    }
    // Consulta a la base de datos
    $sql = $conn->prepare("SELECT isbn, titulo, portada FROM libro where estado =?");
    $sql->bind_param("s", $estado);
    $sql->execute();
    $result = $sql->get_result();
    // Validar resultados
    if ($result && $result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "isbn" => $row['isbn'],
                "titulo" => $row['titulo'],
                "portada" => $row['portada']
            ];
        }
        echo json_encode($data);
    } else {
        echo json_encode(["message" => "No se encontraron resultados"]);
    }
    $sql->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Método no permitido"]);
}
?>