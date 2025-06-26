<div class="container-fluid">
    <!-- Enlace al logo con la redirección a la página principal -->
    <a href="<?= base_url('crud_productos_view') ?>"><img style="width: 100px"
            src="<?= base_url('public/assets/img/Iconos/logo.png') ?>" alt="responsive"></a>

    <!-- Botón para mostrar el menú en dispositivos pequeños -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
        aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menú de navegación desplegable -->
    <div class="collapse navbar-collapse" id="navbarScroll">
        <ul class="navbar-nav nav-underline me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
            <!-- Enlaces de navegación a las distintas secciones del sitio -->
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= base_url('/crud_productos_view') ?>">CRUD Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= base_url('/crud_usuarios_view') ?>">CRUD Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= base_url('ventas_admin') ?>">Ventas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= base_url('/consultas_view') ?>">Consultas</a>
            </li>
        </ul>

        <!-- Menú de usuario (icono y opciones del perfil) -->
        <div class="d-flex gap-4 align-items-center justify-content-end">
            <div class="nav-item dropdown">
                <!-- Icono de usuario para desplegar el menú -->
                <a class="btn m-0 p-0" data-bs-toggle="dropdown" aria-expanded="false" href="#">
                    <img class="nav-img" src="<?= base_url('public/assets/img/Iconos/usuario.png') ?>" alt="Usuario">
                </a>
                <!-- Opciones del menú del usuario -->
                <ul id="info-sesion" class="dropdown-menu dropdown-menu-end bg-black my-2 p-4"
                    style="min-width: 250px;">
                    <li class="d-flex justify-content-between align-items-center p-0">
                        <h4 class="m-0">Mi cuenta</h4>
                        <button type="button" class="btn btn-close btn-close-white" data-bs-dismiss="dropdown"
                            aria-label="Cerrar"></button>
                    </li>
                    <hr>
                    <li class="text-white text-center mt-3">
                        <!-- Información del usuario -->
                        <p class="m-0 fw-bold">
                            <?= session()->get('nombre') ?>
                            <?= session()->get('apellido') ?>
                        </p>
                        <p class="m-0"><?= session()->get('email') ?></p>
                    </li>
                    <li class="d-flex justify-content-center gap-3 mt-3">
                        <a href="<?= base_url('logout') ?>" class="btn btn-outline-light">
                            Cerrar sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
