<?php
header('Content-Type: application/json');
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == "GET") {
    require_once '../conexion.php';
    // Validar la conexión
    if (!$conn) {
        echo json_encode(["error" => "Error de conexión a la base de datos"]);
        exit;
    }
    // Consulta a la base de datos
    $sql = "SELECT isbn, titulo, portada FROM libro";
    $result = $conn->query($sql);

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

    $conn->close();
} else {
    echo json_encode(["error" => "Método no permitido"]);
}
?>