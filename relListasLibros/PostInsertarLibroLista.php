<?php
header('Content-Type: application/json');
require_once "../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $listaId = $_GET['listaId'] ?? null;
    $isbn = $_GET['isbn'] ?? null;

    if ($listaId && $isbn) {
        error_log("Insertando listaId: $listaId con ISBN: $isbn");
        $stmt = $conn->prepare("INSERT INTO rel_listas_libros (fk_rel_lista_Id, fk_rel_libro_isbn) VALUES (?, ?) ON DUPLICATE KEY UPDATE fechaAgregacion = CURRENT_TIMESTAMP");
        $stmt->bind_param("is", $listaId, $isbn);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Datos insertados correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al insertar los datos.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Parámetros inválidos.']);
    }
}
$conn->close();
?>
