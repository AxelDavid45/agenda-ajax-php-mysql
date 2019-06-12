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