<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto o Categoría</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .modal-body input, .modal-body select {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Administrar Productos y Categorías</h2>
    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#modalProducto">Agregar Producto</button>
    <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#modalCategoria">Agregar Categoría</button>

    <!-- Modal Agregar Producto -->
    <div class="modal fade" id="modalProducto" tabindex="-1" role="dialog" aria-labelledby="modalProductoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProductoLabel">Agregar Nuevo Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="index.php?controller=admin&action=agregarProducto" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombreProducto">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombreProducto" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="precioProducto">Precio del Producto</label>
                            <input type="number" class="form-control" id="precioProducto" name="precio" required>
                        </div>
                        <div class="form-group">
                            <label for="categoriaProducto">Categoría</label>
                            <select class="form-control" id="categoriaProducto" name="categoria_id" required>
                                <?php foreach ($data['categorias'] as $categoria): ?>
                                    <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Categoría -->
    <div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCategoriaLabel">Agregar Nueva Categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="index.php?controller=admin&action=agregarCategoria" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombreCategoria">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="nombreCategoria" name="nombre" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Guardar Categoría</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lista de Categorías -->
    <h3>Categorías</h3>
    <ul class="list-group mb-4">
        <?php if (!empty($data['categorias'])): ?>
            <?php foreach ($data['categorias'] as $categoria): ?>
                <li class="list-group-item"><?php echo $categoria['nombre']; ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="list-group-item">No hay categorías disponibles.</li>
        <?php endif; ?>
    </ul>

    <!-- Lista de Productos -->
    <h3>Productos</h3>
    <ul class="list-group">
        <?php if (!empty($data['productos'])): ?>
            <?php foreach ($data['productos'] as $producto): ?>
                <li class="list-group-item">
                    <?php echo $producto['nombre']; ?> - <?php echo $producto['precio']; ?> - Categoría: <?php echo $producto['categoria_nombre']; ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="list-group-item">No hay productos disponibles.</li>
        <?php endif; ?>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
