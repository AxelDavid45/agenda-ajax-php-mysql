<?php
require_once "inc/layout/header.php";
require_once "inc/functions/functions.php";
?>
    <div class="contenedor-barra">
        <h1>Agenda de contacto</h1>
    </div>

    <div class="bg-amarillo contenedor sombra">
        <form action="#" id="contacto">
            <p>
                Añada un contacto
                <span>Todos los campos son obligatorios</span>
            </p>
            <?php require_once "inc/layout/formulario.php"; ?>
        </form>
    </div>

    <div class="bg-blanco contenedor sombra contactos">
        <div class="contenedor-contactos">
            <h2>Contactos</h2>
            <input type="text" id="buscar" class="buscador sombra" placeholder="Buscar contacto...">
            <p class="total-contactos"><span>2</span> Contactos</p>
            <div class="contenedor-tabla">
                <table id="listado-contactos" class="lista-contactos">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Empresa</th>
                        <th>Telefono</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $contactos = mostrarRegistros();
                    if ($contactos):
                        while ($contacto = $contactos->fetch_object()):?>
                            <tr>
                                <td><?= $contacto->nombre ?></td>
                                <td><?= $contacto->empresa ?></td>
                                <td><?= $contacto->telefono ?></td>
                                <td>

                                    <a class="btn btn-warning btn-editar" href="editar.php?id=<?= $contacto->id ?>">
                                        <i class="fas fa-user-edit"></i>

                                    </a>
                                    <button type="button" class="btn btn-danger btn-borrar" data-id="<?= $contacto->id ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


<?php require_once "inc/layout/footer.php"; ?>