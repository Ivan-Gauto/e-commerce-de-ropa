<div class="container my-5" style="min-height: 400px;">
    <h2 class="mb-4 text-center">Detalle de la compra</h2>

    <!-- Muestra un mensaje si no hay datos de la compra -->
    <?php if (empty($detalles)): ?>
        <div class="alert alert-warning text-center">
            No se encontró la información de la compra.
        </div>
    <?php else: ?>
        <!-- Tabla con los detalles de la compra -->
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th class="bg-black text-white">Producto</th>
                    <th class="bg-black text-white">Imagen</th>
                    <th class="bg-black text-white">Cantidad</th>
                    <th class="bg-black text-white">Precio Unitario</th>
                    <th class="bg-black text-white">Subtotal</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php $total = 0; ?>
                <!-- Itera sobre los productos comprados -->
                <?php foreach ($detalles as $detalle): ?>
                    <?php $subtotal = $detalle['cantidad'] * $detalle['precio']; ?>
                    <tr>
                        <td><?= esc($detalle['producto_nombre']) ?></td>
                        <td>
                            <img src="<?= base_url('public/assets/uploads/' . $detalle['producto_imagen']) ?>" width="80">
                        </td>
                        <td><?= esc($detalle['cantidad']) ?></td>
                        <td>$<?= number_format($detalle['precio'], 2, ',', '.') ?></td>
                        <td>$<?= number_format($subtotal, 2, ',', '.') ?></td>
                    </tr>
                    <?php $total += $subtotal; ?>
                <?php endforeach; ?>
            </tbody>
            <!-- Muestra el total general de la compra -->
            <tfoot>
                <tr class="table-secondary text-end">
                    <th colspan="4">Total:</th>
                    <th>$<?= number_format($total, 2, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
</div>