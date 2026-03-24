<?php require_once INCLUDES . 'admin/dashboardTop.php'; ?>

<div class="row">
    <!--formulario para el album-->
    <div class="col-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Agregar Album</h6>
            </div>
            <div class="card-body">
                <form action="albums/post_agregar" method="POST">
                    <?php echo insert_inputs(); ?>

                    <div class="mb-3">
                        <label for="title" class="form-label">Titulo: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="artist" class="form-label">Artista: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="artist" name="artist" required>
                    </div>

                    <div class="mb-3">
                        <label for="genre" class="form-label">Género: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="genre" name="genre" required>
                    </div>

                    <div class="mb-3">
                        <label for="release_date" class="form-label">Fecha de publicación: <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="release_date" name="release_date" required>
                    </div>

                    <button class="btn btn-success btn-lg btn-block" type="submit">Agregar</button>
                </form>
            </div>
        </div>
    </div>

    <!--tabla de vista de canciones-->

    <div class="col-12 col-md-6 col-lg-6 col-xl-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Todos los albumes:</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="min-height: 300px;">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Id:</th>
                                <th class="text-center">Título:</th>
                                <th class="text-center">Artista:</th>
                                <th class="text-center">Género:</th>
                                <th class="text-center">Fecha de publicación:</th>
                                <th class="text-center">Creado:</th>
                                <th class="text-center">Actualizado:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($d->albums)): ?>
                                <?php foreach ($d->albums as $album): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $album->id; ?></td>
                                        <td class="text-center"><?php echo $album->title; ?></td>
                                        <td class="text-center"><?php echo $album->artist; ?></td>
                                        <td class="text-center"><?php echo $album->genre; ?></td>
                                        <td class="text-center"><?php echo $album->release_date; ?></td>
                                        <td class="text-center"><?php echo $album->created_at; ?></td>
                                        <td class="text-center"><?php echo $album->updated_at; ?></td>
                                        <td class="text-center justify-content-between">
                                            <a href="<?php echo build_url('albums/post_editar/' . $album->id); ?>"
                                                class="btn btn-primary btn-sm">
                                                Editar
                                            </a>
                                            <a href="<?php echo build_url('albums/borrar/' . $album->id); ?>"
                                                class="btn btn-danger btn-sm mt-1 confirmar">
                                                Borrar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td class="text-center">
                                        No hay álbumes registrados.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php require_once INCLUDES . 'admin/dashboardBottom.php'; ?>