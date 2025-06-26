<?php $cart = \Config\Services::cart(); ?>
<div class="container-fluid">
    <a href="<?= base_url('plantilla_principal') ?>"><img style="width: 100px"
            src="<?= base_url('public/assets/img/Iconos/logo.png') ?>" alt="responsive"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
        aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
        <ul class="navbar-nav nav-underline me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Ofertas y descuentos
                </a>
                <div class="dropdown-menu bg-black m-0 p-0 text-white">
                    <div
                        class="d-flex p-4 m-0 gap-1 align-items-center justify-content-center fst-italic dropdown-header bg-white text-black">
                        <h6 class="m-0">Ofertas y descuentos destacados</h6>
                        <img style="width: 5vw;" src="<?= base_url('public/assets/img/Iconos/fuego.gif') ?>" alt="">
                    </div>
                    <div class="d-flex align-items-center">
                        <img style="width: 10vw;"
                            src="<?= base_url('public/assets/img/Ropa/Remera-rider-hombre.jpg') ?>"
                            alt="Promo 1">
                        <div class="p-4">
                            <strong>Descuento del 20%</strong><br>
                            <small>En Remera rider para hombre</small>
                        </div>
                    </div>
                    <div class="m-0 p-0">
                        <a href="<?= base_url('catalogo_productos_view') ?>">
                            <img style="width: 100%" src="<?= base_url('public/assets/videos/Oferta.gif') ?>" alt="">
                        </a>
                    </div>
                </div>
            </li>
           <li class="nav-item dropdown">
    <a class="text-white nav-link" href="<?= base_url('catalogo_productos_view') ?>">Catálogo</a>
</li>

<?php if (session()->get('perfil_id') == 2): ?>
    <li class="nav-item dropdown">
        <a class="text-white nav-link" href="<?= base_url('mis_compras') ?>">Mis compras</a>
    </li>
<?php endif; ?>

<li class="nav-item dropdown">
    <a class="text-white nav-link" href="<?= base_url('quienes_somos') ?>">Quienes somos</a>
</li>
        </ul>
        <div class="nav-busqueda d-flex gap-4 m-2 justify-content-end align-items-center">
            <!-- Boton login -->
            <div class="nav-item dropdown">
                <a class="btn m-0 p-0" data-bs-toggle="dropdown" aria-expanded="false" href="#">
                    <img class="nav-img" src="<?= base_url('public/assets/img/Iconos/usuario.png') ?>" alt="Usuario">
                </a>

                <ul id="info-sesion" class="dropdown-menu dropdown-menu-end bg-black my-2 p-4">
                    <li class="d-flex justify-content-between align-items-center p-0">
                        <h4>Mi cuenta</h4>
                        <button type="button" class="btn btn-close btn-close-white" data-bs-dismiss="offcanvas"
                            aria-label="Cerrar"></button>
                    </li>
                    <hr>

                    <?php if (session()->get('logged_in')): ?>
                        <!-- Si el usuario está logueado -->
                        <li class="text-white text-center mt-3">
                            <p class="m-0 fw-bold">
                                <?= session()->get('nombre') ?>
                                <?= session()->get('apellido') ?>
                            </p>
                            <p class="m-0"><?= session()->get('email') ?></p>
                        </li>

                        
                        <li class="d-flex justify-content-center gap-4 mt-3">
                            <a href="<?= base_url('plantilla_perfil') ?>"
                                class="btn d-flex align-items-center btn-outline-light">Ver perfil</a>
                            <a href="<?= base_url('logout') ?>"
                                class="btn d-flex align-items-center btn-outline-light">Cerrar sesión</a>
                        </li>

                    <?php else: ?>
                        <!-- Si NO está logueado -->
                        <li class="d-flex justify-content-center gap-4 mt-3">
                            <a href="<?= base_url('agregarusuario_view') ?>"
                                class="btn d-flex align-items-center btn-outline-light">Registrarme</a>
                            <a href="<?= base_url('iniciarsesion_view') ?>"
                                class="btn d-flex align-items-center btn-outline-light">Iniciar sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Boton carrito -->
            <a class="btn m-0 p-0 position-relative" data-bs-toggle="offcanvas" href="#carritoOffcanvas" role="button"
                aria-controls="carritoOffcanvas">
                <img class="nav-img" src="<?= base_url('public/assets/img/Iconos/carrito-nav.png') ?>" alt="">
                <?php if (!empty($cart->contents())): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= count($cart->contents()) ?>
                        <span class="visually-hidden">productos en el carrito</span>
                    </span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</div>
</div>