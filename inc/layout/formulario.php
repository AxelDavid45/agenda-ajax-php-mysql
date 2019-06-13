<?php
require_once "inc/functions/functions.php";
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if (!$id) {
        die('NO es un entero');
    }
    $contacto = obtenerUsuario($id);
    $resultado = $contacto->fetch_object();

}
?>

<div class="campos">
    <div class="campo">
        <label for="nombre">Nombre:</label>
        <input type="text"
               placeholder="Nombre"
               id="nombre"
               value="<?= isset($resultado->nombre) ? $resultado->nombre : ''; ?>"
        >
    </div>
    <div class="campo">
        <label for="empresa">Empresa:</label>
        <input
                type="text"
                placeholder="Nombre Empresa"
                id="empresa"
                value="<?= isset($resultado->empresa) ? $resultado->empresa : ''; ?>"
        >
    </div>
    <div class="campo">
        <label for="tel">Telefono:</label>
        <input
                type="tel"
                placeholder="-- --- --"
                id="tel"
                value="<?= isset($resultado->telefono) ? $resultado->telefono : ''; ?>"

        >
    </div>
</div>
<div class="campo enviar">
    <input type="hidden" value="<?=isset($resultado) ? 'editar' : 'crear'?>" id="accion">
    <?php if (isset($_GET['id'])): ?>
        <input type="hidden" id="id" value="<?= isset($resultado) ? $resultado->id : ''?>">
    <?php endif;  ?>
    <input type="submit" value="<?= isset($_GET['id']) ? 'Editar' : 'Añadir'; ?>">
</div>