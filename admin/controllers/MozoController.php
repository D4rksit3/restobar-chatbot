

<?php
date_default_timezone_set('America/Lima');
class MozoController extends Controller {
    public function __construct() {
        $this->requireLogin();
        if ($_SESSION['user']['role'] !== 'mozo') {
            $this->redirect('index.php?controller=auth&action=login');
        }
    }

    public function index() {
        $pedidoModel = $this->loadModel('Pedido');
        $pedidos = $pedidoModel->getPedidos($_SESSION['user']['id']);
        $ordenes = $pedidoModel->getAllOrdenes();
        $productos = $pedidoModel->getProductos();
        $this->loadView('mozo/pedidos', [
            'pedidos' => $pedidos,
            'ordenes' => $ordenes,
            'productos' => $productos
        ]);
    }

    public function generarBoleta() {
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nro_orden = $_POST['nro_orden'];
    
            $pedidoModel = $this->loadModel('Pedido');
            $pedidos = $pedidoModel->getPedidosPorNroOrden($nro_orden);
    
            // Calcular el total
            $total = 0;
            foreach ($pedidos as $pedido) {
                $total += $pedido['cantidad'] * $pedido['precio'];
            }
    
            // Guardar la boleta en la base de datos 'ventas'
            $ventaData = [
                'nro_orden' => $nro_orden,
                'total' => $total,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $pedidoModel->createVenta($ventaData);

            // Agregar los datos generados al array de datos para la vista
            $data['boleta'] = [
                'nro_orden' => $nro_orden,
                'total' => $total,
                'created_at' => $ventaData['created_at'],
                'pedidos' => $pedidos
            ];
        }

        // Cargar la vista para generar la boleta (formulario de selección de número de orden)
        $pedidoModel = $this->loadModel('Pedido');
        $ordenes = $pedidoModel->getAllOrdenes();
    
        $data['ordenes'] = $ordenes;
    
        $this->loadView('mozo/generarBoleta', $data);
    }

    
    

    public function fetchPedidos() {
        $pedidoModel = $this->loadModel('Pedido');
        $pedidos = $pedidoModel->getPedidos($_SESSION['user']['id']);
        echo json_encode(['pedidos' => $pedidos]);
    }

    public function enviarPedido() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pedidoModel = $this->loadModel('Pedido');
            $productos = $_POST['detalle'];

            $nro_orden = $_POST['nro_orden'];
            if (empty($nro_orden)) {
                $max_nro_orden = $pedidoModel->getMaxNroOrden();
                $nro_orden = $max_nro_orden + 1;
            }

            foreach ($productos as $producto_id) {
                $data = [
                    'producto_id' => $producto_id,
                    'mozo_id' => $_SESSION['user']['id'],
                    'cliente' => $_POST['cliente'],
                    'mesa' => $_POST['mesa'],
                    'cantidad' => 1,
                    'estado' => 'pendiente',
                    'created_at' => date('Y-m-d H:i:s'),
                    'nro_orden' => $nro_orden
                ];
                $pedidoModel->createPedido($data);
            }
            $this->redirect('index.php?controller=mozo&action=index');
        } else {
            $pedidoModel = $this->loadModel('Pedido');
            $categorias = $pedidoModel->getCategorias();
            $productos = $pedidoModel->getProductos();
            $ordenes = $pedidoModel->getAllOrdenes();

            $view = new View();
            $view->loadView('mozo/enviarPedido', [
                'categorias' => $categorias,
                'productos' => $productos,
                'ordenes' => $ordenes
            ]);
        }
    }

    public function agregarProducto() {
        $timezone = new DateTimeZone('America/Lima');
        $now = new DateTime('now', $timezone);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pedidoModel = $this->loadModel('Pedido');
            $data = [
                'producto_id' => $_POST['producto_id'],
                'mozo_id' => $_SESSION['user']['id'],
                'cliente' => '', // Puedes ajustar esto según sea necesario
                'mesa' => '', // Puedes ajustar esto según sea necesario
                'cantidad' => $_POST['cantidad'],
                'estado' => 'pendiente',
                'created_at' => $now->format('Y-m-d H:i:s'),
                'nro_orden' => $_POST['nro_orden']
            ];
            $pedidoModel->createPedido($data);
            $this->redirect('index.php?controller=mozo&action=index');
        }
    }

    public function estadoPedido() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pedido_id = $_POST['pedido_id'];
            $estado = $_POST['estado'];
            $pedidoModel = $this->loadModel('Pedido');
            $pedidoModel->updateEstado($pedido_id, $estado);
            $this->redirect('index.php?controller=mozo&action=index');
        }
    }
}
?>
