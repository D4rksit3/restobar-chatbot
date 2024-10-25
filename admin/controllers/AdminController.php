<?php
date_default_timezone_set('America/Lima');
class AdminController extends Controller {
    public function __construct() {
        $this->requireLogin();
        if ($_SESSION['user']['role'] !== 'administrador') {
            $this->redirect('index.php?controller=auth&action=login');
        }
    }
// parte inicial
    public function ventas() {
        $ventaModel = $this->loadModel('Venta');
        $totalVentas = 0;
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
        } else {
            $inicio = date('Y-m-d 00:00:00');
            $fin = date('Y-m-d 23:59:59');
        }
    
        $ventas = $ventaModel->getVentas($inicio, $fin);
    
        // Calcular el total de ventas
        foreach ($ventas as $venta) {
            $totalVentas += $venta['total'];
        }
    
        $this->loadView('admin/ventas', ['ventas' => $ventas, 'totalVentas' => $totalVentas]);
    }
    

    public function administrarProductosCategorias() {
        $productoModel = $this->loadModel('Producto');

        // Obtener todos los productos y categorías para mostrar en la vista
        $productos = $productoModel->getAllProductos();
        $categorias = $productoModel->getAllCategorias();
 // Imprimir los datos para depuración
        
       /*  $productos=print_r($productos);
        $categorias=print_r($categorias); */
    

       // Pasar los datos a la vista
        $data['categorias'] = $categorias;
        $data['productos'] = $productos;
       
        /* 
         */

        // Cargar la vista con los datos de productos y categorías
        $this->loadView('admin/agregar', $data);
        }

    public function agregarProducto() {
        $productoModel = $this->loadModel('Producto');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productoModel->addProducto($_POST['nombre'], $_POST['precio'], $_POST['categoria_id']);
            $this->redirect('index.php?controller=admin&action=administrarProductosCategorias');
        }
    }

    public function agregarCategoria() {
        $productoModel = $this->loadModel('Producto');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productoModel->addCategoria($_POST['nombre']);
            $this->redirect('index.php?controller=admin&action=administrarProductosCategorias');
        }
    }


    
    public function graficas() {
        // Cargar el modelo Venta
        $ventaModel = $this->loadModel('Venta');
    
        // Lógica para obtener datos para las gráficas
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
    
            // Obtener datos para las gráficas desde el modelo Venta
            $ventasPorFecha = $ventaModel->getVentasPorFecha($inicio, $fin);
            $ventasPorHora = $ventaModel->getVentasPorHora($inicio, $fin);
            $productosMasVendidosFecha = $ventaModel->getProductosMasVendidosPorFecha($inicio, $fin);
            $productosMasVendidosHora = $ventaModel->getProductosMasVendidosPorHora($inicio, $fin);
    
            // Cargar la vista de gráficas con los datos
            $this->loadView('admin/graficas', [
                'ventasPorFecha' => $ventasPorFecha,
                'ventasPorHora' => $ventasPorHora,
                'productosMasVendidosFecha' => $productosMasVendidosFecha,
                'productosMasVendidosHora' => $productosMasVendidosHora,
            ]);
        } else {
            // Cargar la vista de gráficas sin datos (primera carga)
            $this->loadView('admin/graficas');
        }
    }


}
?>
