<?php
header('Content-Type: application/json');
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'DELETE') {
    require_once '../conexion.php';

    if (isset($_GET['isbn'])) {
        $isbn = $_GET['isbn'];

        $stmt = $conn->prepare("DELETE FROM libro WHERE isbn = ?");
        $stmt->bind_param("s", $isbn);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Libro eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el libro.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Falta el parámetro ISBN.']);
    }

    $conn->close();
}
?>