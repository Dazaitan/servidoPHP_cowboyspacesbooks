<?php
$metodo = $_SERVER["REQUEST_METHOD"];

switch($metodo){
    case 'GET':
        if(isset($_GET['email']) && isset($_GET['clave'])){
            //credenciales  id=1234567 & clave=123456
            inicioSesion($_GET['email'],$_GET['clave']);
        } else if(isset($_GET['email'])){
            getUsuario($_GET['email']);
        }
        break;
    case 'POST':
        header('Content-Type: application/json');
        crearUsuario();
        break;
    case 'PUT':
        actualizarUsuario();
        break;
    case 'DELETE':
        eliminarUsuario($_GET['id']);
        break;
    
}

function getUsuario($id){
    require_once 'conexion.php';
    if($id!=""){
        $sql = "SELECT nombre, apellido,email FROM usuario where identificacion=$id";
    }else{
        $sql = "SELECT nombre, apellido,email FROM usuario";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
            $data[]=array(
                "nombre"=>$row['nombre'],
                "apellido"=>$row['apellido'],
                "email"=>$row['email']
            );
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
    echo "0 results";
    }
    $conn->close();
}
function crearUsuario(){
    require_once 'conexion.php';
    $data = json_decode(file_get_contents('php://input'), true);
    if(isset($data['id']) && isset($data['nombre']) && isset($data['apellido']) && isset($data['email']) && isset($data['contrasena'])){
        $id=$data['id'];
        $nombre=$data['nombre'];
        $apellido=$data['apellido'];
        $email=$data['email'];
        $contrasena=$data['contrasena'];

        $stmt = $conn->prepare("SELECT email,identificacion FROM usuario WHERE identificacion = ? OR email = ?");
        $stmt->bind_param("is", $id, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            echo json_encode(['status' => 'error', 'message' => 'Usuario ya creado.']);
        } else{
            $stmt = $conn->prepare("INSERT INTO usuario (identificacion, nombre, apellido, email, contrasena) VALUES (?,?,?,?,?)");
            $stmt->bind_param("issss",$id,$nombre,$apellido,$email,$contrasena);
            
            if($stmt->execute()){
                echo json_encode(['status' => 'success', 'message' => 'Usuario creado exitosamente.']);
            } else{
                echo json_encode(['status' => 'error', 'message' => 'Error al crear el usuario.']);
            }
        }
    } else{
        echo json_encode(['status' => 'error', 'message' => 'Faltan parametros.']);
    }
    $stmt->close();
    $conn->close();
}
function eliminarUsuario($id){
    
}
function actualizarUsuario(){
}
function inicioSesion($email,$clave){
    require_once 'conexion.php';
    $sql = "SELECT email,nombre FROM usuario where email='$email' and contrasena='$clave'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data =$result->fetch_assoc();
        header('Conten-Type: application/json');
        echo json_encode(array("estado" => "encontrado","datos" => $data));
        }
     else {
    echo "Inicio de sesion incorrecto";
    }
    $conn->close();
}
?>