<?php

function mostrarRegistros() {
    include_once "database.php";
    $contactos = $conexion->query("SELECT  * FROM contactos");
    if($contactos->num_rows >= 1) {
        return $contactos;
    } else {
        return false;
    }
}

function obtenerUsuario($id) {
    include_once "database.php";
    $contacto = $conexion->query("SELECT  * FROM contactos WHERE id = $id");
    if($contacto->num_rows >= 1) {
        return $contacto;
    } else {
        return false;
    }

}