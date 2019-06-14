const FormNuevoContacto = document.querySelector('#contacto'),
    ListadoContactos = document.querySelector('#listado-contactos tbody'),
    inputBuscador = document.querySelector("#buscar");

//Inicia los eventListeners
EventListeners();

function EventListeners() {
    //Inicia los eventlissteners
    FormNuevoContacto.addEventListener("submit", LeerForm);
    if(ListadoContactos) {
    ListadoContactos.addEventListener('click', BorrarRegistro);
    }

    if(inputBuscador) {
        inputBuscador.addEventListener('input',Buscador);
    }

    contarRegistros();

}

function Buscador(e) {
    //Empezamos a crear una expresion regular
    const expresion = new RegExp(e.target.value,'i');
    //Obtenemos todos los elementos que existan en la tabla
    const elementosTable = document.querySelectorAll("tbody tr");

    elementosTable.forEach(elemento => {
       elemento.style.display = 'none';

       if(elemento.childNodes[1].textContent.replace("/\s\g"," ").search(expresion) != -1) {
           elemento.style.display = 'table-row';
       }

    });
    contarRegistros();
}

function contarRegistros() {
    //Obtenemos todas las filas que existan en el tr
    const registros = document.querySelectorAll("tbody tr"),
          contenedorNumeroTotal = document.querySelector(".total-contactos span");
    //Creamos un acumulador
    let totalRegistros = 0;

    registros.forEach(registro => {
       if(registro.style.display === '' || registro.style.display === 'table-row' ) {
           totalRegistros++;
       }
    });
    contenedorNumeroTotal.innerText = totalRegistros;
}

function LeerForm(e) {
    e.preventDefault();
    //Leer datos de los imputs
    const nombre = document.querySelector("#nombre").value,
        empresa = document.querySelector('#empresa').value,
        telefono = document.querySelector("#tel").value,
        accion = document.querySelector("#accion").value;

    if (nombre === '' || empresa === '' || telefono === '') {
        MostrarNotificacion('error', 'Hubo un error, verifica datos');
    } else {
        //Creamos el objeto fomrdata
        const DatosFormulario = new FormData();
        //Agregamos posiciones al arreglo de datos
        DatosFormulario.append('nombre', nombre);
        DatosFormulario.append('empresa', empresa);
        DatosFormulario.append('telefono', telefono);
        DatosFormulario.append('accion', accion);
        // console.log(...DatosFormulario);

        if (accion === 'crear') {
            //Creamos el contacto en la bd
            InsertarEnDb(DatosFormulario);
        } else {
            //Leemos el id que viene en el hidden
            const idRegistro = document.querySelector("#id").value;
            //Agregamos un elemento mas al JSON del DATAFORM
            DatosFormulario.append('id',idRegistro);
            //Actualizamos el registro
            actualizarRegistro(DatosFormulario);
        }
    }
}

function InsertarEnDb(formdata) {
    //Peticion AJAX
    //1. creacmos el objeto
    const xhr = new XMLHttpRequest();

    //2. Abrimos la conexion
    xhr.open('POST', 'inc/models/ContactosModel.php', true);

    // 3 - Pasamos los datos
    xhr.onload = function () {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);

            //Creamos el tr para la tabla
            const ContNuevoContacto = document.createElement('tr');

            //Insertamos los datos en el tr
            ContNuevoContacto.innerHTML = `
           <td>${response.datos.nombre}</td>
           <td>${response.datos.empresa}</td>
           <td>${response.datos.telefono}</td>
           `;

            //Creamos el contenedor de las acciones
            const contAcciones = document.createElement('td');
            //Creamos el boton de editar
            const btnEditar = document.createElement('a');
            btnEditar.classList.add('btn', 'btn-warning');
            btnEditar.href = `editar.php?id${response.datos.id}`;
            //Creo el icono
            const icoEditar = document.createElement('i');
            icoEditar.classList.add('fa', 'fa-user-edit');
            //Agregamos el icono al btn
            btnEditar.appendChild(icoEditar);
            //Agregamos el btn al contenedor
            contAcciones.appendChild(btnEditar);

            //Creamos el btn de borrar
            const btnBorrar = document.createElement('button');
            btnBorrar.classList.add('btn', 'btn-danger');
            btnBorrar.setAttribute('data-id', response.datos.id);
            //Creo icono para el btn
            const icoBorrar = document.createElement('i');
            icoBorrar.classList.add('fa', 'fa-trash-alt');
            btnBorrar.appendChild(icoBorrar);

            //Agregamos el btn borrar al contenedor
            contAcciones.appendChild(btnBorrar);

            //Agregamos el contenedor de acciones al la fila
            ContNuevoContacto.appendChild(contAcciones);
            ListadoContactos.appendChild(ContNuevoContacto);

            //Mostrar notificacion de agregado
            MostrarNotificacion('correcto', 'Contacto agregado correctamente');
            //Resetear el form
            document.querySelector('form').reset();

            //Contar registros
            contarRegistros();
        }
    }

    //4- enviamos la peticion
    xhr.send(formdata);
}

function actualizarRegistro(formdata) {
    //Peticion AJAX
    //Creamos el objeto de la peticion
    const xhr = new XMLHttpRequest();
    //Abrimos la conexion
    xhr.open('POST','inc/models/ContactosModel.php',true);
    //Manejamos los datos
    xhr.onload = function () {
        if(this.status === 200) {
            const response = JSON.parse(this.responseText);
            //Mostramos las notificaciones
            if(response.respuesta === 'correcto') {
                //Alerta correcto
                MostrarNotificacion('correcto','Contacto Modificado correctamente');
            } else {
                //Alerta de error
                MostrarNotificacion('error','Ocurrio un error...');
            }

            //Redireccion a la pagina principal
            setTimeout(() => {
                window.location.href = 'index.php';
            },1500);
        }
    }
    //Enviamos la peticion
    xhr.send(formdata);
}

function MostrarNotificacion(tipo, mensaje) {
    const notificacion = document.createElement('div');
    notificacion.classList.add(tipo, 'notificacion', 'sombra');
    notificacion.textContent = mensaje;

    FormNuevoContacto.insertBefore(notificacion, document.querySelector('form p'));
    setTimeout(() => {
        //Le agraga la visibilidad
        notificacion.classList.add('visible');
        setTimeout(() => {
            //Le quita la visibilidad
            notificacion.classList.remove('visible');
            setTimeout(() => {
                //Remueve del dom la notificacion
                notificacion.remove();
            }, 1100);
        }, 1000);
    }, 100);
}


function BorrarRegistro(e) {
    //Revisamos si contiene la clase unica de los botones de borrar
    if (e.target.parentElement.classList.contains('btn-borrar')) {
        const id = e.target.parentElement.getAttribute('data-id');
        const confirmacionEliminar = confirm('¿Estas segur(a) de eliminarlo?');
        if (confirmacionEliminar) {
            //Hacemoss la peticion AJAX para eliminar el elemento
            //Creamos el objeto
            const xhr = new XMLHttpRequest();
            //Abrimos la conexion
            xhr.open("GET", `inc/models/ContactosModel.php?id=${id}&accion=borrar`, true);
            //Pasamos los datos
            xhr.onload = function () {
                if (this.status === 200) {
                    const respuesta = JSON.parse(this.responseText);

                    if (respuesta.resultado === 'ok') {
                        //Borrar la fila de la tabla
                        e.target.parentElement.parentElement.parentElement.remove();
                        //Mostrar una notificación
                        MostrarNotificacion('correcto', 'Registro borrado exitosamente');
                    } else {
                        //Notificacion de error
                        MostrarNotificacion('error', 'Ocurrio un error interno...');
                    }
                    contarRegistros();

                }
            }
            //Enviamos la peticion
            xhr.send();
        }

    }
}