<?php include_once '../views/layouts/header.php'; ?>
<div class="container">
    <form action="index.php?controller=mozo&action=enviarPedido" method="post">
        <div class="form-group">
            <label for="mesa">Mesa:</label>
            <select class="form-control" id="mesa" name="mesa" required>
                <option value="Mesa 1">Mesa 1</option>
                <option value="Mesa 2">Mesa 2</option>
                <option value="Mesa 3">Mesa 3</option>
                <option value="Mesa 4">Mesa 4</option>
                <option value="Mesa 5">Mesa 5</option>
                <option value="Delivery">Delivery</option>
            </select>
        </div>
        <div class="form-group">
            <label for="cliente">Cliente:</label>
            <input type="text" class="form-control" id="cliente" name="cliente" required>
        </div>
        
        <div class="form-group">
            <label for="nro_orden">Número de Orden (opcional):</label>
            <select class="form-control" id="nro_orden" name="nro_orden">
                <option value="">Nueva Orden</option>
                <?php foreach ($ordenes as $orden): ?>
                    <option value="<?php echo $orden['nro_orden']; ?>"><?php echo $orden['nro_orden']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="pedido-items">
            <div class="form-group pedido-item">
                <label for="categoria">Categoría:</label>
                <select class="form-control categoria" name="categoria[]" required>
                    <option value="">Seleccione una categoría</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="detalle">Detalle del Pedido:</label>
                <select class="form-control detalle" name="detalle[]" required>
                    <option value="">Seleccione un producto</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-secondary" id="add-item">Agregar otro plato</button>
        <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorias = <?php echo json_encode($categorias); ?>;
        const productos = <?php echo json_encode($productos); ?>;

        function createPedidoItem() {
            const itemHTML = `
                <div class="form-group pedido-item">
                    <label for="categoria">Categoría:</label>
                    <select class="form-control categoria" name="categoria[]" required>
                        <option value="">Seleccione una categoría</option>
                        ${categorias.map(categoria => `<option value="${categoria.id}">${categoria.nombre}</option>`).join('')}
                    </select>
                    <label for="detalle">Detalle del Pedido:</label>
                    <select class="form-control detalle" name="detalle[]" required>
                        <option value="">Seleccione un producto</option>
                    </select>
                </div>`;
            const newItem = document.createElement('div');
            newItem.innerHTML = itemHTML.trim();
            return newItem.firstChild;
        }

        function agregarItemPedido() {
            const newItem = createPedidoItem();
            const categoriaSelect = newItem.querySelector('.categoria');
            categoriaSelect.addEventListener('change', updateDetalle);
            document.getElementById('pedido-items').appendChild(newItem);
        }

        document.getElementById('add-item').addEventListener('click', agregarItemPedido);

        function updateDetalle(event) {
            const categoriaId = event.target.value;
            const detalleSelect = event.target.closest('.pedido-item').querySelector('.detalle');
            if (!detalleSelect) {
                console.error('Detalle no encontrado');
                return;
            }
            detalleSelect.innerHTML = '<option value="">Seleccione un producto</option>';
            
            productos.filter(producto => producto.categoria_id == categoriaId).forEach(function (producto) {
                const option = document.createElement('option');
                option.value = producto.id;
                option.textContent = producto.nombre;
                detalleSelect.appendChild(option);
            });
        }

        document.querySelectorAll('.categoria').forEach(function (element) {
            element.addEventListener('change', updateDetalle);
        });
    });
    </script>
</div>
<?php include_once '../views/layouts/footer.php'; ?>
