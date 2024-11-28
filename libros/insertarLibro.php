<?php
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo=="POST"){
    require_once "../conexion.php";
    $data = json_decode(file_get_contents('php://input'), true);
    if(isset($data['isbn']) && isset($data['titulo']) && isset($data['autor']) && isset($data['editorial']) && isset($data['numPaginas'])){
        $isbn=$data['isbn'];
        $titulo=$data['titulo'];
        $autor=$data['autor'];
        $editor=$data['editorial'];
        $numPaginas=$data['numPaginas'];
        $descripcion=$data['descripcion'];
        $formato=$data['Formato'];
        $portada=$data['portada'];
        $estado=$data['estado'];

        $stmt = $conn->prepare("SELECT isbn FROM libro WHERE isbn = ?");
        $stmt->bind_param("i", $isbn);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            echo json_encode(['status' => 'error', 'message' => 'ISBN ya creado.']);
        } else{
            $stmt = $conn->prepare("INSERT INTO libro (isbn, titulo, autor, editorial, nPaginas, formato, descripcion,portada,estado) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("issssssss",$isbn,$titulo,$autor,$editor,$numPaginas,$formato,$descripcion,$portada,$estado);
            
            if($stmt->execute()){
                echo json_encode(['status' => 'success', 'message' => 'Libro insertado correctamente.']);
            } else{
                echo json_encode(['status' => 'error', 'message' => 'Error al insertar libro.']);
            }
        }
    } else{
        echo json_encode(['status' => 'error', 'message' => 'Faltan parametros importantes.']);
    }
    $stmt->close();
    $conn->close();
}
?>