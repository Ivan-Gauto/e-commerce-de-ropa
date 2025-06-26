<?php
$readonly = $readonly ?? true;
$validation = $validation ?? \Config\Services::validation();
?>
<form action="<?= site_url('editar_usuario/' . $usuario['id']) ?>" enctype="multipart/form-data" method="POST">
    <?= csrf_field() ?>
    <h1 class="m-5 fw-light text-center">Mi información</h1>
    <div class="d-flex mb-5 justify-content-center">
        <div class="mx-5 w-75 rounded shadow-lg bg-black text-white p-5">

            <!-- Nombre -->
            <label for="nombre" class="form-label">Nombre</label>
            <div class="input-group">
                <input type="text"
                    class="form-control bg-black text-white border-0 <?= $validation->hasError('nombre') ? 'is-invalid' : '' ?>"
                    id="nombre" name="nombre" value="<?= old('nombre', esc($usuario['nombre'])) ?>" <?= $readonly ? 'readonly' : '' ?> data-original="<?= esc($usuario['nombre']) ?>">
                <button type="button" class="edit-btn" onclick="habilitarCampo('nombre')">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
            <div style="height:1vh;">
                <?php if ($validation->getError('nombre')) { ?>
                    <p class="text-danger"><?= $validation->getError('nombre') ?></p>
                <?php } ?>
            </div>
            <hr class="mt-4">

            <!-- Apellido -->
            <label for="apellido" class="form-label">Apellido</label>
            <div class="input-group">
                <input type="text"
                    class="form-control bg-black text-white border-0 <?= $validation->hasError('apellido') ? 'is-invalid' : '' ?>"
                    id="apellido" name="apellido" value="<?= old('apellido', esc($usuario['apellido'])) ?>" <?= $readonly ? 'readonly' : '' ?> data-original="<?= esc($usuario['apellido']) ?>">
                <button type="button" class="edit-btn" onclick="habilitarCampo('apellido')">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
            <div style="height:1vh;">
                <?php if ($validation->getError('apellido')) { ?>
                    <p class="text-danger"><?= $validation->getError('apellido') ?></p>
                <?php } ?>
            </div>
            <hr class="mt-4">

            <!-- Email -->
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
                <input type="email"
                    class="form-control bg-black text-white border-0 <?= $validation->hasError('email') ? 'is-invalid' : '' ?>"
                    id="email" name="email" value="<?= old('email', esc($usuario['email'])) ?>" <?= $readonly ? 'readonly' : '' ?> data-original="<?= esc($usuario['email']) ?>">
                <button type="button" class="edit-btn" onclick="habilitarCampo('email')">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
            <div style="height:1vh;">
                <?php if ($validation->getError('email')) { ?>
                    <p class="text-danger"><?= $validation->getError('email') ?></p>
                <?php } ?>
            </div>
            <hr class="mt-4">

            <!-- Usuario -->
            <label for="usuario" class="form-label">Usuario</label>
            <div class="input-group">
                <input type="text"
                    class="form-control bg-black text-white border-0 <?= $validation->hasError('usuario') ? 'is-invalid' : '' ?>"
                    id="usuario" name="usuario" value="<?= old('usuario', esc($usuario['usuario'])) ?>" <?= $readonly ? 'readonly' : '' ?> data-original="<?= esc($usuario['usuario']) ?>">
                <button type="button" class="edit-btn" onclick="habilitarCampo('usuario')">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
            <div style="height:1vh;">
                <?php if ($validation->getError('usuario')) { ?>
                    <p class="text-danger"><?= $validation->getError('usuario') ?></p>
                <?php } ?>
            </div>
            <hr class="mt-4">

            <!-- Contraseña -->
            <label for="pass" class="form-label">Contraseña</label><br>
            <small class="text-secondary">(dejar en blanco para no cambiar)</small>
            <div class="input-group">
                <input type="password"
                    class="form-control bg-black text-white border-0 <?= $validation->hasError('pass') ? 'is-invalid' : '' ?>"
                    id="pass" name="pass" value="" <?= $readonly ? 'readonly' : '' ?> data-original="">
                <button type="button" class="edit-btn" onclick="habilitarCampo('pass')">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
            <div style="height:1vh;">
                <?php if ($validation->getError('pass')) { ?>
                    <p class="text-danger"><?= $validation->getError('pass') ?></p>
                <?php } ?>
            </div>
            <hr class="mt-4">

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-5">
                <button type="submit" name="accion" value="guardar" class="btn btn-success" id="guardarBtn"
                    disabled>Guardar cambios</button>
            </div>
        </div>
    </div>
</form>