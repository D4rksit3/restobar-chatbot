<div class="container">
    <h2>Gráficas de Ventas y Productos más Vendidos</h2>
    <form action="index.php?controller=admin&action=graficas" method="POST">
        <div class="form-group">
            <label for="inicio">Fecha Inicio</label>
            <input type="date" name="inicio" id="inicio" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="fin">Fecha Fin</label>
            <input type="date" name="fin" id="fin" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Generar Gráficas</button>
    </form>

    <!-- Gráfica de Ventas por Fecha -->
    <?php if (!empty($data['ventasPorFecha'])): ?>
        <div id="graficaFechaContainer">
            <canvas id="graficaFecha"></canvas>
        </div>
        <script>
            const ctxFecha = document.getElementById('graficaFecha').getContext('2d');
            const chartFecha = new Chart(ctxFecha, {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_column($data['ventasPorFecha'], 'fecha')); ?>,
                    datasets: [{
                        label: 'Ventas por Fecha S/',
                        data: <?= json_encode(array_column($data['ventasPorFecha'], 'total_ventas')); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>

    <!-- Gráfica de Ventas por Hora -->
    <?php if (!empty($data['ventasPorHora'])): ?>
        <div id="graficaHoraContainer">
            <canvas id="graficaHora"></canvas>
        </div>
        <script>
            const ctxHora = document.getElementById('graficaHora').getContext('2d');
            const chartHora = new Chart(ctxHora, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($data['ventasPorHora'], 'hora')); ?>,
                    datasets: [{
                        label: 'Ventas por Hora S/',
                        data: <?= json_encode(array_column($data['ventasPorHora'], 'total_ventas')); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>

    <!-- Gráfica de Productos más Vendidos por Fecha -->
    <?php if (!empty($data['productosMasVendidosFecha'])): ?>
        <div id="graficaProductosFechaContainer">
            <canvas id="graficaProductosFecha"></canvas>
        </div>
        <script>
            const ctxProductosFecha = document.getElementById('graficaProductosFecha').getContext('2d');
            const chartProductosFecha = new Chart(ctxProductosFecha, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($data['productosMasVendidosFecha'], 'producto')); ?>,
                    datasets: [{
                        label: 'Productos más vendidos por Fecha',
                        data: <?= json_encode(array_column($data['productosMasVendidosFecha'], 'cantidad_vendida')); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>

    <!-- Gráfica de Productos más Vendidos por Hora -->
    <?php if (!empty($data['productosMasVendidosHora'])): ?>
        <div id="graficaProductosHoraContainer">
            <canvas id="graficaProductosHora"></canvas>
        </div>
        <script>
            const ctxProductosHora = document.getElementById('graficaProductosHora').getContext('2d');
            const chartProductosHora = new Chart(ctxProductosHora, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($data['productosMasVendidosHora'], 'producto')); ?>,
                    datasets: [{
                        label: 'Productos más vendidos por Hora',
                        data: <?= json_encode(array_column($data['productosMasVendidosHora'], 'cantidad_vendida')); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>
</div>
