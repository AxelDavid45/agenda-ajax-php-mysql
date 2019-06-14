<?php

if(isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    //Importamos la conexion
    require_once "../functions/database.php";

    //Limpiamos las variables
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $empresa = filter_var($_POST['empresa'], FILTER_SANITIZE_STRING);
    $telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_STRING);

    if(empty($nombre) ||empty($empresa) || empty($telefono)) {
        //Enviamos error
        $respuesta = array("error" => 'Los campos deben estar rellenados');
    } else {
        //Hacemos el statement para mas seguridad
        $query_stmt = "INSERT INTO contactos (nombre, empresa, telefono) VALUES(?,?,?)";
        $stmt = $conexion->prepare($query_stmt);
        $stmt->bind_param('sss',$nombre,$empresa,$telefono);
        $stmt->execute();

        if($stmt->affected_rows == 1) {
            //Si se afecta una fila enviamos el array como respuesta con los datos
            $respuesta = array(
                "respuesta" => 'correcto',
                "datos" => array(
                    "id" => $stmt->insert_id,
                    "nombre" => $nombre,
                    "empresa" => $empresa,
                    "telefono" => $telefono
                )
            );
        }
        //Cerramos conexiones
        $stmt->close();
        $conexion->close();

    }
    echo json_encode($respuesta);
}

if(isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    //Importamos la conexion
    require_once "../functions/database.php";

    //Limpiamos las variables
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $empresa = filter_var($_POST['empresa'], FILTER_SANITIZE_STRING);
    $telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_STRING);
    $id = filter_var($_POST['id'],FILTER_VALIDATE_INT);

    if(empty($nombre) ||empty($empresa) || empty($telefono)) {
        //Enviamos error
        $respuesta = array("respuesta" => 'error');
    } else {
        //Hacemos el statement para mas seguridad
        $query_stmt = "UPDATE contactos SET nombre = ?, empresa = ?, telefono = ? WHERE id = ?";
        $stmt = $conexion->prepare($query_stmt);
        $stmt->bind_param('sssi',$nombre,$empresa,$telefono,$id);
        $stmt->execute();

        if($stmt->affected_rows == 1) {
            //Si se afecta una fila enviamos el array como respuesta con los datos
            $respuesta = array(
                "respuesta" => 'correcto'
            );
        }
        //Cerramos conexiones
        $stmt->close();
        $conexion->close();

    }
    echo json_encode($respuesta);
}


if(isset($_GET['accion']) && $_GET['accion'] == 'borrar') {
    //Sanitiza las variables
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    if($id) {
        //Importamos la conexion
        require_once "../functions/database.php";
        $sql = "DELETE FROM contactos WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        //Reviso la query
        if($stmt->affected_rows == 1) {
            $respuesta = array(
                "resultado" => 'ok'
            );
        } else {
            $respuesta = array(
                "resultado" => 'error'
            );
        }
        $stmt->close();
        $conexion->close();
    } else {
        $respuesta = array(
            "resultado" => 'error'
        );
    }

    echo json_encode($respuesta);
}
