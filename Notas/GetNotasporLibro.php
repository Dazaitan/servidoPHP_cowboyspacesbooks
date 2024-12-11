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
    if ($_GET["isbn"]!=""){
        $isbn = $_GET["isbn"];
    // Consulta a la base de datos
        $sql = $conn->prepare("SELECT l.ISBN AS isbn, l.titulo AS titulo, l.autor AS autor, l.portada AS imagenUrl, n.tipoNota AS tipoNota, n.fecha AS fecha, n.pag_inicio AS pag_inicio, n.descripcion AS descripcion, n.pag_fin AS pag_final FROM libro l INNER JOIN notas n ON l.ISBN = n.fk_isbn_libro WHERE l.ISBN = ? ORDER BY n.fecha DESC;");
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
                    "imagenUrl" => $row['imagenUrl'],
                    "autor" => $row['autor'],
                    "descripcion" => $row['descripcion'],
                    "fecha" => $row['fecha'],
                    "pagInicio" => $row['pag_inicio'],
                    "pagFinal" => $row['pag_final'],
                    "tipoNota" => $row['tipoNota']
                ];
            }
            echo json_encode($data);
        } else {
            echo json_encode(["message" => "No se encontraron resultados"]);
        }

        $conn->close();
    }
    
} else {
    echo json_encode(["error" => "Método no permitido"]);
}
?>