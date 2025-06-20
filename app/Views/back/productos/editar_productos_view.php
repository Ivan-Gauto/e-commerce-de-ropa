<?php ?>
<?= csrf_field() ?>

<div class="container d-flex flex-column justify-content-center align-items-center my-5">
    <form id="formulario-editar-productos" class="formulario bg-black text-white p-4 rounded shadow-lg w-100"
        style="max-width: 900px;" action="<?= site_url('editar_producto/' . $old['id_producto']) ?>" method="POST"
        enctype="multipart/form-data">

        <h1 class="fw-light mb-4 text-center">Editar producto</h1>

        <!-- Navegación por tabs -->
        <ul class="nav nav-tabs mb-4" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link nav-form active" id="datos-tab" data-bs-toggle="tab" data-bs-target="#datos"
                    type="button" role="tab">Datos principales</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link nav-form" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock"
                    type="button" role="tab">Stock y precios</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link nav-form" id="imagen-tab" data-bs-toggle="tab" data-bs-target="#imagen"
                    type="button" role="tab">Imagen</button>
            </li>
        </ul>

        <div class="tab-content d-flex justify-content-center align-items-center border p-5" id="productTabContent"
            style="height: 400px;">

            <!-- TAB 1: Datos -->
            <div class="tab-pane fade show active" id="datos" role="tabpanel">
                <div class="row g-4">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre_prod" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_prod" name="nombre_prod"
                            value="<?= esc($old['nombre_prod']) ?>">
                    </div>

                    <!-- Selects dinámicos con opción seleccionada -->
                    <?php
                        $campos = [
                            'categorias' => [$categorias, 'id_categoria', 'nombre', $categoriaActual['id_categoria']],
                            'marcas'     => [$marcas, 'id_marca', 'nombre', $marcaActual['id_marca']],
                            'talles'     => [$talles, 'id_talle', 'nombre', $talleActual['id_talle']],
                            'generos'    => [$generos, 'id_genero', 'nombre', $generoActual['id_genero']],
                            'edades'     => [$edades, 'id_edad', 'nombre', $edadActual['id_edad']],
                        ];

                        foreach ($campos as $nombreCampo => [$lista, $idKey, $nombreKey, $valorActual]):
                    ?>
                        <div class="col-md-6">
                            <label for="<?= $nombreCampo ?>" class="form-label"><?= ucfirst($nombreCampo) ?></label>
                            <select class="form-select" id="<?= $nombreCampo ?>" name="<?= $nombreCampo ?>">
                                <option disabled selected>Selecciona <?= $nombreCampo ?></option>
                                <?php foreach ($lista as $item): ?>
                                    <option value="<?= esc($item[$idKey]) ?>" <?= ($item[$idKey] == $valorActual) ? 'selected' : '' ?>>
                                        <?= esc($item[$nombreKey]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- TAB 2: Stock -->
            <div class="tab-pane fade" id="stock" role="tabpanel">
                <div class="row g-4">
                    <!-- Precio de costo -->
                    <div class="col-md-6">
                        <label for="precio_costo" class="form-label">Precio de costo</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" id="precio_costo" name="precio_costo"
                                value="<?= old('precio_costo') ?>">
                        </div>
                    </div>

                    <!-- Precio de venta -->
                    <div class="col-md-6">
                        <label for="precio_venta" class="form-label">Precio de venta</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" id="precio_venta" name="precio_venta"
                                value="<?= old('precio_venta') ?>">
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" min="<?= esc($old['stock_min']) ?>" class="form-control" id="stock" name="stock"
                            value="<?= esc($old['stock']) ?>">
                    </div>

                    <!-- Stock mínimo -->
                    <div class="col-md-6">
                        <label for="stock_min" class="form-label">Stock mínimo</label>
                        <input type="number" min="0" class="form-control" id="stock_min" name="stock_min"
                            value="<?= esc($old['stock_min']) ?>">
                    </div>
                </div>
            </div>

            <!-- TAB 3: Imagen -->
            <div class="tab-pane fade" id="imagen" role="tabpanel">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <label class="form-label">Imagen</label>
                    <?php if (!empty($old['imagen'])): ?>
                        <div class="d-flex justify-content-center align-items-center mb-3"
                            style="width: 200px; height: 250px; border: 1px dashed #ccc; padding: 5px;">
                            <img id="preview-img" src="<?= base_url('public/assets/uploads/' . esc($old['imagen'])) ?>"
                                alt="Imagen del producto" style="width: 180px; height: 230px; object-fit: cover;">
                        </div>
                    <?php else: ?>
                        <p>No hay imagen disponible.</p>
                    <?php endif; ?>
                    <input class="form-control" type="file" id="imagen" name="imagen" accept="image/*">
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-start gap-3 mt-4">
            <button type="submit" class="btn btn-outline-success"><i class="bi bi-save"></i> Guardar cambios</button>
            <a href="<?= site_url('/crud_productos_view') ?>" class="btn btn-outline-danger">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
        </div>
    </form>
</div>
