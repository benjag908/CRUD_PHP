<?php

session_start();

require '../config/database.php';

$sqlPeliculas = "SELECT p.id, p.nombre, p.descripcion, g.nombre AS genero FROM pelicula AS p 
INNER JOIN genero AS g ON p.id_genero=g.id";
$peliculas = $conn->query($sqlPeliculas);

$dir = "posters/";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Modal</title>

    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-3">

        <h2 class="text-center">Peliculas</h2>

        <hr>

        <?php if (isset($_SESSION['msg'])) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php
            unset($_SESSION['msg']);
        } ?>

        <div class="row justify-content-end">
            <div class="col-auto">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#nuevoModal"><i class="fa-solid fa-circle-plus"></i>Nuevo Registro</a>
            </div>
        </div>
    
    <div class="table-responsive">
    <table class="table table-sm table-striped table-hover mt-4">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Género</th>
                <th>Poster</th>
                <th>Acción</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($row_pelicula = $peliculas->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row_pelicula['id']; ?></td>
                    <td><?= $row_pelicula['nombre']; ?></td>
                    <td><?= $row_pelicula['descripcion']; ?></td>
                    <td><?= $row_pelicula['genero']; ?></td>
                    <td><img src="<?= $dir . $row_pelicula['id'] . '.jpg'; ?>" width="100"></td>


                    <td>

                        <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editaModal" data-bs-id="<?= $row_pelicula['id']; ?>">
                            <i class="fa-solid fa-pen-to-square"></i> Editar</a>


                        <a href="#" class="btn btn-sm btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#eliminaModal"
                            data-bs-id="<?= $row_pelicula['id']; ?>">
                            <i class="fa-solid fa-trash-can">
                            </i> Eliminar</a>
                    </td>
                </tr>
            <?php } ?>

        </tbody>



    </table>
    </div>
</div>



    <?php
    $sqlGenero = "SELECT id, nombre FROM  genero";
    $generos = $conn->query($sqlGenero);
    ?>

    <?php include 'nuevoModal.php'; ?>

    <?php $generos->data_seek(0); ?>

    <?php include 'editaModal.php'; ?>

    <?php include 'eliminaModal.php'; ?>

    <script>

        let nuevoModalModal = document.getElementById('nuevoModal');
        let editaModal = document.getElementById('editaModal');
        let eliminaModal = document.getElementById('eliminaModal');

        nuevoModalModal.addEventListener('shown.bs.modal', event => {
            nuevoModal.querySelector('.modal-body #nombre').focus()
        })

        nuevoModalModal.addEventListener('hide.bs.modal', event => {
            nuevoModal.querySelector('.modal-body #nombre'). value =""
            nuevoModal.querySelector('.modal-body #descripcion'). value =""
            nuevoModal.querySelector('.modal-body #genero'). value =""
            nuevoModal.querySelector('.modal-body #poster'). value =""
         
        })

        editaModal.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget;
            let id = button.getAttribute('data-bs-id');

            let inputId = editaModal.querySelector('.modal-body #id');
            let inputNombre = editaModal.querySelector('.modal-body #nombre');
            let inputDescripcion = editaModal.querySelector('.modal-body #descripcion');
            let inputGenero = editaModal.querySelector('.modal-body #genero');
            let poster = editaModal.querySelector('.modal-body #img_poster');

            


            let url = "getPelicula.php";
            let formData = new FormData();
            formData.append('id', id);

            fetch(url, {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    inputId.value = data.id;
                    inputNombre.value = data.nombre;
                    inputDescripcion.value = data.descripcion;
                    inputGenero.value = data.id_genero;
                    poster.src = '<?= $dir ?>' + data.id + '.jpg';

                })
                .catch(err => console.log(err));
        });

        eliminaModal.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            eliminaModal.querySelector('.modal-footer #id').value = id
        })
    </script>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>


</body>

</html>