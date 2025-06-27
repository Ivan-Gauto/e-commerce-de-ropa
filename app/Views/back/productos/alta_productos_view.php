<?php if (!isset($validation)) {
    $validation = \Config\Services::validation();
} ?>

<?= csrf_field() ?>

<div class="container d-flex flex-column justify-content-center align-items-center my-5">
    <form id="formulario-alta-productos" class="formulario bg-black text-white p-4 rounded shadow-lg w-100"
        style="max-width: 900px;" action="<?= site_url('alta_producto') ?>" method="POST" enctype="multipart/form-data">

        <h1 class="fw-light mb-4 text-center">Alta de Producto</h1>

        <!-- Tabs -->
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

        <!-- Contenido de pestañas -->
        <div class="tab-content d-flex justify-content-center align-items-center border p-5" id="productTabContent"
            style="height: 400px;">

            <!-- TAB 1 -->
            <div class="tab-pane fade show active" id="datos" role="tabpanel">
                <div class="row g-4">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre_prod" class="form-label">Nombre</label>
                        <input type="text" class="form-control <?= $validation->hasError('nombre_prod') ? 'is-invalid' : '' ?>"
                            id="nombre_prod" name="nombre_prod" value="<?= set_value('nombre_prod') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->showError('nombre_prod') ?>
                        </div>
                    </div>

                    <!-- Selects -->
                    <?php
                    $inputs = [
                        'categorias' => $categorias,
                        'marcas' => $marcas,
                        'talles' => $talles,
                        'generos' => $generos,
                        'edades' => $edades
                    ];

                    foreach ($inputs as $name => $list): ?>
                        <div class="col-md-6">
                            <label for="<?= $name ?>" class="form-label"><?= ucfirst($name) ?></label>
                            <select class="form-select <?= $validation->hasError($name) ? 'is-invalid' : '' ?>"
                                id="<?= $name ?>" name="<?= $name ?>">
                                <option disabled selected>Selecciona <?= strtolower($name) ?></option>
                                <?php foreach ($list as $item): ?>
                                    <?php
                                    $idKey = array_key_first($item);
                                    $valueKey = array_keys($item)[1];
                                    ?>
                                    <option value="<?= esc($item[$idKey]) ?>" <?= set_select($name, $item[$idKey]) ?>>
                                        <?= esc($item[$valueKey]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= $validation->showError($name) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- TAB 2 -->
            <div class="tab-pane fade" id="stock" role="tabpanel">
                <div class="row g-4">
                    <!-- Precio costo -->
                <div class="col-md-6">
                    <label for="precio_costo" class="form-label">Precio de costo</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01"
                            class="form-control <?= $validation->hasError('precio_costo') ? 'is-invalid' : '' ?>"
                            id="precio_costo" name="precio_costo"
                            value="<?= set_value('precio_costo') ?>">
                    </div>
                    <div class="invalid-feedback d-block">
                        <?= $validation->showError('precio_costo') ?>
                    </div>
                </div>

                    <!-- Precio venta -->
                <div class="col-md-6">
                    <label for="precio_venta" class="form-label">Precio de venta</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01"
                            class="form-control <?= $validation->hasError('precio_venta') ? 'is-invalid' : '' ?>"
                            id="precio_venta" name="precio_venta"
                            value="<?= set_value('precio_venta') ?>">
                    </div>
                    <div class="invalid-feedback d-block">
                        <?= $validation->showError('precio_venta') ?>
                    </div>
                </div>


                    <!-- Stock mínimo -->
                    <div class="col-md-6">
                        <label for="stock_min" class="form-label">Stock mínimo</label>
                        <input type="number" min="0"
                            class="form-control <?= $validation->hasError('stock_min') ? 'is-invalid' : '' ?>"
                            id="stock_min" name="stock_min"
                            value="<?= set_value('stock_min') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->showError('stock_min') ?>
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" min="0"
                            class="form-control <?= $validation->hasError('stock') ? 'is-invalid' : '' ?>"
                            id="stock" name="stock"
                            value="<?= set_value('stock') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->showError('stock') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3 -->
            <div class="tab-pane fade" id="imagen" role="tabpanel">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <label class="form-label">Imagen</label>
                    <div class="d-flex justify-content-center align-items-center mb-3"
                        style="width: 200px; height: 250px; border: 1px dashed #ccc;">
                        <img id="preview-img" src="<?= base_url('assets/img/Iconos/sin-imagen.png') ?>"
                            alt="Vista previa" style="width: 180px; height: 230px; object-fit: cover;">
                    </div>
                    <input class="form-control <?= $validation->hasError('imagen') ? 'is-invalid' : '' ?>"
                        type="file" id="imagen" name="imagen" accept="image/*">
                    <div class="invalid-feedback">
                        <?= $validation->showError('imagen') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-start gap-3 mt-4">
            <button type="submit" class="btn btn-outline-success">
                <i class="bi bi-save"></i> Guardar producto
            </button>
            <a href="<?= site_url('/crud_productos_view') ?>" class="btn btn-outline-danger">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
        </div>
    </form>
</div>
