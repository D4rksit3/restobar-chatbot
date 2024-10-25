<div class="container">
    <h2>Ventas</h2>
    <form action="index.php?controller=admin&action=ventas" method="POST">
        <div class="form-group">
            <label for="inicio">Fecha Inicio</label>
            <input type="date" name="inicio" id="inicio" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="fin">Fecha Fin</label>
            <input type="date" name="fin" id="fin" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>

    <?php if (!empty($data['ventas'])): ?>
        <div class="alert alert-info mt-3">
            <strong>Total de ventas: <?php echo number_format($data['totalVentas'], 2); ?> </strong>
        </div>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nro de Orden</th>
                  
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['ventas'] as $venta): ?>
                    <tr>
                        <td><?php echo $venta['nro_orden']; ?></td>
                        <td><?php echo $venta['total']; ?></td>
                        <td><?php echo $venta['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning mt-3">
            No se encontraron ventas en el rango de fechas seleccionado.
        </div>
    <?php endif; ?>
</div>
