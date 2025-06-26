<section class="container-fluid bg-white text-dark py-4" style="min-height: 600px;">
    <h1 class="text-center mb-4 fw-light">Catálogo de Productos</h1>
    <hr>

    <!-- Botón para abrir filtros -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn boton-negro" type="button" data-bs-toggle="offcanvas" data-bs-target="#filtrosOffcanvas"
            aria-controls="filtrosOffcanvas" aria-label="Abrir filtros">
            <i class="bi bi-funnel-fill"></i> Filtros
        </button>
    </div>

    <!-- Offcanvas de filtros -->
    <div class="offcanvas offcanvas-end bg-black text-white" tabindex="-1" id="filtrosOffcanvas"
        aria-labelledby="filtrosOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filtrosOffcanvasLabel">Filtros</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
            <form method="get" action="<?= site_url('catalogo_productos_view') ?>">

                <!-- Filtros dinámicos -->
                <div class="mb-4">
                    <h6>Filtrar por Edad</h6>
                    <?php foreach ($edades as $edad): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="edad[]" value="<?= $edad['id_edad'] ?>"
                                <?= in_array($edad['id_edad'], $edadSeleccionada ?? []) ? 'checked' : '' ?>>
                            <label class="form-check-label"><?= esc($edad['nombre']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mb-4">
                    <h6>Filtrar por Categoría</h6>
                    <?php foreach ($categorias as $categoria): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categoria[]"
                                value="<?= $categoria['id_categoria'] ?>" <?= in_array($categoria['id_categoria'], $categoriaSeleccionada ?? []) ? 'checked' : '' ?>>
                            <label class="form-check-label"><?= esc($categoria['nombre']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mb-4">
                    <h6>Filtrar por Marca</h6>
                    <?php foreach ($marcas as $marca): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="marca[]" value="<?= $marca['id_marca'] ?>"
                                <?= in_array($marca['id_marca'], $marcaSeleccionada ?? []) ? 'checked' : '' ?>>
                            <label class="form-check-label"><?= esc($marca['nombre']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mb-4">
                    <h6>Filtrar por Género</h6>
                    <?php foreach ($generos as $genero): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="genero[]"
                                value="<?= $genero['id_genero'] ?>" <?= in_array($genero['id_genero'], $generoSeleccionado ?? []) ? 'checked' : '' ?>>
                            <label class="form-check-label"><?= esc($genero['nombre']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn btn-primary w-100">Aplicar Filtros</button>
            </form>
        </div>
    </div>

    <!-- Productos -->
    <div class="d-flex flex-wrap gap-4 mt-3 justify-content-center">
        <?php if (empty($productos)): ?>
            <div class="col-12">
                <p class="text-center">No se encontraron productos con los filtros seleccionados.</p>
            </div>
        <?php else: ?>
            <?php foreach ($productos as $producto): ?>
                <div class="card bg-black text-white shadow" style="width: 230px; cursor: default !important;">
                    <img src="<?= base_url('public/assets/uploads/' . $producto['imagen']) ?>" class="card-img-top"
                        alt="<?= esc($producto['nombre_prod']) ?>" style="height: 250px; object-fit: cover;">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= esc($producto['nombre_prod']) ?></h5>
                        <p class="card-text mb-1 fw-bold">
                            $<?= number_format($producto['precio_venta'], 2, ',', '.') ?></p>

                        <p class="card-text mb-2">
                            <small><?= $producto['stock'] ?> unidades disponibles</small><br>
                            <?php if ($producto['stock'] > 0): ?>
                                <span class="badge bg-success">Hay stock</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Sin stock</span>
                            <?php endif; ?>
                        </p>

                        <form action="<?= site_url('carrito_view/agregar') ?>" method="post" class="mt-auto">
                            <input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">
                            <input type="hidden" name="nombre_prod" value="<?= $producto['nombre_prod'] ?>">
                            <input type="hidden" name="precio_venta" value="<?= $producto['precio_venta'] ?>">
                            <input type="hidden" name="imagen" value="<?= $producto['imagen'] ?>">

                            <div class="input-group w-100 mb-2">
                                <span class="input-group-text">Cantidad</span>
                                <input type="number" name="cantidad" min="1" max="<?= $producto['stock'] ?>" value="1"
                                    class="form-control">
                            </div>

                            <button type="submit" class="btn btn-outline-light w-100" <?= $producto['stock'] <= 0 ? 'disabled' : '' ?>>
                                Agregar al carrito
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if ($pager): ?>

    <div class="mt-5 d-flex justify-content-center">
        <nav aria-label="Paginación de productos">
            <ul class="pagination justify-content-center">
                <?= str_replace(
                    ['<li class="active">', '<li>', '</li>', '<a', '</a>', '&laquo;', '&raquo;'],
                    [
                        '<li class="page-item active"><a class="page-link"',
                        '<li class="page-item"><a class="page-link"',
                        '</a></li>',
                        '<a class="page-link mx-1"',
                        '</a>',
                        '<span class="page-link bg-black text-white border-dark">Anterior</span>',
                        '<span class="page-link bg-black text-white border-dark">Siguiente</span>',
                    ],
                    $pager->links()
                ) ?>
            </ul>
        </nav>
    </div>
<?php endif; ?>
</section>
