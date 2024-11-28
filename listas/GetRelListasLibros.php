<?php
$metodo = $_SERVER['REQUEST_METHOD'];
if ($metodo == "GET"){
    $listaId = $_GET['lista_Id'];
    require_once '../conexion.php';
    $sql = "SELECT l.nameList AS lista, b.titulo AS titulo, b.autor AS autor, b.isbn AS isbn, 
    b.portada AS portada FROM rel_listas_libros ll INNER JOIN libro b ON ll.fk_rel_libro_isbn = b.ISBN 
    INNER JOIN listas l ON ll.fk_rel_lista_Id = l.lista_Id WHERE l.lista_Id = ?;";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $listaId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "lista" => $row['lista'],
            "titulo" => $row['titulo'],
            "autor" => $row['autor'],
            "isbn" => $row['isbn'],
            "portada" => $row['portada']
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>