<?php
header('Content-Type: application/json');
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == "GET") {
    require_once '../conexion.php';
    $isbn = $_GET["isbn"];
    // Validar la conexión
    if (!$conn) {
        echo json_encode(["error" => "Error de conexión a la base de datos"]);
        exit;
    }
    // Consulta a la base de datos
    $sql = $conn->prepare("SELECT isbn, titulo, portada,descripcion,estado,autor,nPaginas,pagsLeidas,editorial,formato FROM libro where isbn =?");
    $sql->bind_param("i", $isbn);
    $sql->execute();
    $result = $sql->get_result();
    // Validar resultados
    if ($result && $result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "isbn" => $row['isbn'],
                "titulo" => $row['titulo'],
                "portada" => $row['portada'],
                "descripcion" => $row['descripcion'],
                "estado" =>$row['estado'],
                "autor" =>$row['autor'],
                "nPaginas" =>$row['nPaginas'],
                "pagsLeidas" =>$row['pagsLeidas'],
                "editorial" =>$row['editorial'],
                "formato" =>$row['formato']
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