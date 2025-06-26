<?php if (empty($ventas)): ?>
    <div class="container mt-5" style="min-height: 400px;">
        <div class="alert alert-dark text-center" role="alert">
            <h4 class="alert-heading">No se registraron ventas todavía</h4>
            <p>Cuando se registre una venta, aparecerá aquí.</p>
        </div>
    </div>
<?php else: ?>
    <div class="container my-5" style="min-height: 400px;">
        <div class="text-center mb-4">
            <h2 class="fw-light">Historial de Ventas</h2>
        </div>

        <!-- Vista escritorio con scroll -->
        <div class="d-none d-md-block bg-white border rounded shadow-sm overflow-auto" style="max-height: 400px;">
            <table class="table table-bordered table-striped table-hover align-middle mb-0" style="min-width: 900px;">
                <thead class="bg-black text-white sticky-top" style="top: 0; z-index: 1;">
                    <tr>
                        <th>ID Venta</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Opción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?= esc($venta['id']) ?></td>
                            <td><?= esc($venta['nombre']) . ' ' . esc($venta['apellido']) ?></td>
                            <td>$<?= number_format($venta['total_venta'], 2, ',', '.') ?></td>
                            <td><?= esc($venta['fecha']) ?></td>
                            <td>
                                <a href="<?= base_url('ver_factura/' . $venta['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Vista móvil con scroll vertical -->
        <div class="d-md-none overflow-auto mt-3 bg-white border rounded shadow-sm p-3" style="max-height: 500px;">
            <?php foreach ($ventas as $venta): ?>
                <div class="card mb-3 shadow-sm w-100">
                    <div class="card-body">
                        <p class="mb-1"><strong>ID Venta:</strong> <?= esc($venta['id']) ?></p>
                        <p class="mb-1"><strong>Cliente:</strong> <?= esc($venta['nombre']) . ' ' . esc($venta['apellido']) ?></p>
                        <p class="mb-1"><strong>Total:</strong> $<?= number_format($venta['total_venta'], 2, ',', '.') ?></p>
                        <p class="mb-2"><strong>Fecha:</strong> <?= esc($venta['fecha']) ?></p>
                        <a href="<?= base_url('ver_factura/' . $venta['id']) ?>" class="btn btn-sm btn-outline-primary w-100">
                            Ver Detalle
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
