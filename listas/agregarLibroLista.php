<?php
$metodo = $_SERVER["REQUEST_METHOD"];
if($metodo=="POST"){
    require_once "../conexion.php";
    $data = json_decode(file_get_contents('php://input'), true);
    if(isset($data['isbn']) && isset($data['lista'])){
        $isbn=$data['isbn'];
        $lista=$data['lista'];

        $stmt = $conn->prepare("SELECT fk_rel_libro_isbn FROM rel_listas_libros WHERE fk_rel_libro_isbn = ? AND fk_rel_lista_Id=?");
        $stmt->bind_param("ii", $isbn,$lista);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            echo json_encode(['status' => 'error', 'message' => 'Este  libro ya fue agregado a esta lista.']);
        } else{
            $stmt = $conn->prepare("INSERT INTO rel_listas_libros(fk_rel_lista_Id, fk_rel_libro_isbn) VALUES (?,?)");
            $stmt->bind_param("ii",$lista,$isbn);
            
            if($stmt->execute()){
                echo json_encode(['status' => 'success', 'message' => 'Libro agregad exitosamente.']);
            } else{
                echo json_encode(['status' => 'error', 'message' => 'Error al agregar el libro.']);
            }
        }
    } else{
        echo json_encode(['status' => 'error', 'message' => 'Faltan parametros importantes.']);
    }
    $stmt->close();
    $conn->close();
}
?>