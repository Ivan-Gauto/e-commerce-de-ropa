<?php if (empty($ventas)): ?>
    <!-- Vista si no hay ventas registradas -->
    <div class="container mt-5" style="min-height: 400px;">
        <div class="alert alert-dark text-center" role="alert">
            <h4 class="alert-heading">No se registraron ventas todavía</h4>
            <p>Cuando se registre una venta, aparecerá aquí.</p>
        </div>
    </div>
<?php else: ?>
    <!-- Vista con historial de ventas -->
    <div class="container my-5" style="min-height: 400px;">
        <div class="text-center mb-4">
            <h2 class="fw-light">Historial de Ventas</h2>
        </div>

        <!-- Scroll para escritorio -->
        <div class="d-none d-md-block bg-white border rounded shadow-sm overflow-auto" style="max-height: 500px;">
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

        <!-- Versión móvil en tarjetas -->
        <div class="d-md-none d-flex flex-column gap-3 mt-4">
            <?php foreach ($ventas as $venta): ?>
                <div class="card w-100 shadow-sm border">
                    <div class="card-body">
                        <p><strong>ID Venta:</strong> <?= esc($venta['id']) ?></p>
                        <p><strong>Cliente:</strong> <?= esc($venta['nombre']) . ' ' . esc($venta['apellido']) ?></p>
                        <p><strong>Total:</strong> $<?= number_format($venta['total_venta'], 2, ',', '.') ?></p>
                        <p><strong>Fecha:</strong> <?= esc($venta['fecha']) ?></p>
                        <a href="<?= base_url('ver_factura/' . $venta['id']) ?>" class="btn btn-sm btn-outline-primary">
                            Ver Detalle
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
<?php endif; ?>
