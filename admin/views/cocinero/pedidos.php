<div class="container">
    <h2>Pedidos a Preparar</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nro Orden</th>
                <th>Cliente</th>
                <th>Mesa</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="pedidos-tbody">
            <?php foreach ($data['pedidos'] as $pedido): ?>
                <tr>
                
                    <td><?= $pedido['nro_orden']; ?></td>
                    <td><?= $pedido['cliente']; ?></td>
                    <td><?= $pedido['mesa']; ?></td>
                    <td><?= $pedido['nombre']; ?></td>
                    <td><?= $pedido['cantidad']; ?></td>
                    <td class="estado-pedido">
                        <?php if ($pedido['estado'] === 'pendiente'): ?>
                            <span class="text-danger">Pendiente</span>
                        <?php elseif ($pedido['estado'] === 'en proceso'): ?>
                            <span class="text-warning">En Proceso</span>
                        <?php elseif ($pedido['estado'] === 'realizado'): ?>
                            <span class="text-success">Realizado</span>
                        <?php else: ?>
                            <span class="text-muted">Desconocido</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="index.php?controller=cocinero&action=cambiarEstado" method="POST">
                            <input type="hidden" name="pedido_id" value="<?= $pedido['id']; ?>">
                            <select name="estado" class="form-control">
                                <option value="pendiente" <?= $pedido['estado'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="en proceso" <?= $pedido['estado'] === 'en proceso' ? 'selected' : ''; ?>>En Proceso</option>
                                <option value="realizado" <?= $pedido['estado'] === 'realizado' ? 'selected' : ''; ?>>Realizado</option>
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">Actualizar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
