<?php include_once '../views/layouts/header.php'; ?>

<div class="container mt-5">
    <h2>Mis Pedidos</h2>
    <table id="pedidos-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Orden</th>
                <th>Producto</th>
                <th>Mesa</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Creado En</th>
                <th>Cliente</th>
            </tr>
        </thead>
        <tbody id="pedidos-body">
            <?php foreach ($data['pedidos'] as $pedido): ?>
                <tr>
                    <td><?= $pedido['nro_orden']; ?></td>
                    <td><?= $pedido['nombre']; ?></td>
                    <td><?= $pedido['mesa']; ?></td>
                    <td><?= $pedido['cantidad']; ?></td>
                    <td style="color: <?php
                        if ($pedido['estado'] == 'pendiente') {
                            echo 'red';
                        } elseif ($pedido['estado'] == 'en proceso') {
                            echo 'orange';
                        } elseif ($pedido['estado'] == 'realizado') {
                            echo 'green';
                        } else {
                            echo 'black';
                        }
                    ?>;">
                        <?= $pedido['estado']; ?>
                    </td>
                    <td><?= $pedido['created_at']; ?></td>
                    <td><?= $pedido['cliente']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#pedidos-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
            }
        });
    });
</script>

<?php include_once '../views/layouts/footer.php'; ?>
