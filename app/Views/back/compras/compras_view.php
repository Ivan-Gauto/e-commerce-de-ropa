<?php $session = session(); ?>

<div class="container py-5" style="min-height: 400px;">
    <h1 class="text-center mb-4 fw-light">Mis Compras</h1>

    <!-- Mensaje flash -->
    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('mensaje') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <!-- Sin compras registradas -->
    <?php if (empty($ventas)): ?>
        <div class="alert alert-dark text-center mt-5" role="alert">
            <h4 class="alert-heading">No tenés compras registradas</h4>
            <p>Podés explorar nuestro catálogo y realizar tu primera compra.</p>
            <a class="btn btn-warning mt-3" href="<?= base_url('catalogo_productos_view') ?>">Ir al Catálogo</a>
        </div>

    <?php else: ?>
        <!-- Contenedor scroll para escritorio -->
        <div class="d-none d-md-block bg-white border rounded shadow-sm overflow-auto" style="max-height: 400px;">
            <table class="table table-bordered table-striped text-center align-middle mb-0" style="min-width: 800px;">
                <thead class="bg-black text-white sticky-top" style="top: 0; z-index: 1;">
                    <tr>
                        <th class="bg-black text-white">N° Orden</th>
                        <th class="bg-black text-white">Fecha</th>
                        <th class="bg-black text-white">Total</th>
                        <th class="bg-black text-white">Ver Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?= $venta['id'] ?></td>
                            <td><?= $venta['fecha'] ?></td>
                            <td>$<?= number_format($venta['total_venta'], 2, ',', '.') ?></td>
                            <td>
                                <a href="<?= site_url('ver_factura/' . $venta['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    Ver factura
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Vista móvil: tarjetas full width -->
        <div class="d-md-none d-flex flex-column gap-3 mt-4">
            <?php foreach ($ventas as $venta): ?>
                <div class="card shadow-sm border w-100" style="cursor: default;">
                    <div class="card-body">
                        <p><strong>N° Orden:</strong> <?= $venta['id'] ?></p>
                        <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>
                        <p><strong>Total:</strong> $<?= number_format($venta['total_venta'], 2, ',', '.') ?></p>
                        <a href="<?= site_url('ver_factura/' . $venta['id']) ?>" class="btn btn-sm btn-outline-primary">
                            Ver factura
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Mensaje de agradecimiento -->
        <div class="text-center mt-4">
            <p class="h5 text-success">Gracias por tu compra</p>
        </div>
    <?php endif; ?>
</div>
