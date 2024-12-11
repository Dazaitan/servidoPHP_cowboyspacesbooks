<?php
header('Content-Type: application/json');
$metodo = $_SERVER['REQUEST_METHOD'];
if ($metodo=='PUT') {
    require_once "../conexion.php";
    $data = json_decode(file_get_contents('php://input'), true);
    if(isset($data['isbn']) && isset($data['titulo']) && isset($data['autor']) && isset($data['editorial']) && isset($data['nPaginas'])){
        $isbnEdit=(int)$data['isbnEdit'];
        $isbn=(int)$data['isbn'];
        $titulo=$data['titulo'];
        $autor=$data['autor'];
        $editor=$data['editorial'];
        $numPaginas=(int)$data['nPaginas'];
        $pagsLeidas=(int)$data['pagsLeidas'];
        $descripcion=$data['descripcion'];
        $formato=$data['formato'];
        $portada=$data['portada'];
        $estado=$data['estado'];

        $stmt = $conn->prepare("UPDATE libro SET isbn= ?,titulo= ?,autor= ?, editorial= ?, portada= ?,nPaginas= ?, pagsLeidas= ?, formato= ?,descripcion= ?,Estado= ? WHERE isbn =?");
        $stmt->bind_param("issssiisssi",$isbn,$titulo,$autor,$editor,$portada,$numPaginas,$pagsLeidas,$formato,$descripcion,$estado,$isbnEdit);
        
        if($stmt->execute()){
            echo json_encode(['status' => 'success', 'message' => 'Libro actualizado correctamente.']);
        } else{
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar libro.']);
        }
    } else{
        echo json_encode(['status' => 'error', 'message' => 'Faltan parametros importantes.']);
    }
    $stmt->close();
    $conn->close();
}
?>