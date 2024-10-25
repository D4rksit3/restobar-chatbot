<!-- mozo/generarBoleta.php -->
<div class="container">
    <h2>Generar Boleta</h2>
    <form action="index.php?controller=mozo&action=generarBoleta" method="POST">
        <div class="form-group">
            <label for="nro_orden">Número de Orden:</label>
            <select name="nro_orden" id="nro_orden" class="form-control">
                <?php foreach ($data['ordenes'] as $orden): ?>
                    <option value="<?= $orden['nro_orden']; ?>"><?= $orden['nro_orden']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Generar Boleta</button>
    </form>

    <?php if (isset($data['boleta'])): ?>
        <div id="boleta" class="boleta mt-4" style="border: 1px solid #000; padding: 20px; max-width: 600px; margin: auto;">
            <h3 style="text-align: center;">Boleta Generada</h3>
            <p><strong>Número de Orden:</strong> <?= $data['boleta']['nro_orden']; ?></p>
            <p><strong>Fecha de Creación:</strong> <?= $data['boleta']['created_at']; ?></p>
            <h4>Detalles del Pedido:</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #000; padding: 8px;">Producto</th>
                        <th style="border: 1px solid #000; padding: 8px;">Cantidad</th>
                        <th style="border: 1px solid #000; padding: 8px;">Precio Unitario</th>
                        <th style="border: 1px solid #000; padding: 8px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['boleta']['pedidos'] as $pedido): ?>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px;"><?= $pedido['nombre']; ?></td>
                            <td style="border: 1px solid #000; padding: 8px; text-align: center;"><?= $pedido['cantidad']; ?></td>
                            <td style="border: 1px solid #000; padding: 8px; text-align: right;"><?= number_format($pedido['precio'], 2); ?></td>
                            <td style="border: 1px solid #000; padding: 8px; text-align: right;"><?= number_format($pedido['cantidad'] * $pedido['precio'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="border: 1px solid #000; padding: 8px; text-align: right;"><strong>Total de la Boleta:</strong></td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right;"><strong><?= number_format($data['boleta']['total'], 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
            <div style="text-align: center; margin-top: 20px;">
                <button onclick="printBoleta()" class="btn btn-secondary">Imprimir Boleta</button>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function printBoleta() {
        var printContents = document.getElementById('boleta').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
