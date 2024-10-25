<?php
date_default_timezone_set('America/Lima');

class CocineroController extends Controller {
    public function __construct() {
        $this->requireLogin();
        if ($_SESSION['user']['role'] !== 'cocinero') {
            $this->redirect('index.php?controller=auth&action=login');
        }
    }

    public function index() {
        $pedidoModel = $this->loadModel('Pedido');
        $pedidos = $pedidoModel->getAllPedidos();
        $this->loadView('cocinero/pedidos', ['pedidos' => $pedidos]);
    }

    public function cambiarEstado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pedido_id = $_POST['pedido_id'];
            $estado = $_POST['estado'];

            if (empty($pedido_id) || empty($estado)) {
                echo 'Datos incompletos';
                return;
            }

            $pedidoModel = $this->loadModel('Pedido');
            $success = $pedidoModel->updateEstado($pedido_id, $estado);

            if ($success) {
                $this->redirect('index.php?controller=cocinero&action=index');
            } else {
                echo 'Error al actualizar el estado del pedido';
            }
        } else {
            echo 'Método no permitido';
        }
    }

    public function getData() {
        // Aquí obtienes los datos de los pedidos
        $pedidoModel = $this->loadModel('Pedido');
        $pedidos = $pedidoModel->getAllPedidos(); // Asegúrate de que este método devuelve los pedidos
        
        return ['pedidos' => $pedidos];
    }

    public function getPedidos() {
        // Método para devolver los pedidos como JSON
        $pedidoModel = $this->loadModel('Pedido');
        $pedidos = $pedidoModel->getAllPedidos(); // Asegúrate de que este método devuelve los pedidos

        header('Content-Type: application/json');
        echo json_encode(['pedidos' => $pedidos]);
        exit;
    }

    private function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    public function getPedidosLongPolling() {
        session_write_close();
        $pedidoModel = $this->loadModel('Pedido');

        $lastUpdated = $_GET['lastUpdated'] ?? '';

        while (true) {
            $pedidos = $pedidoModel->getAllPedidos();

            $newLastUpdated = $this->getMaxUpdatedTime($pedidos);
            if ($newLastUpdated !== $lastUpdated) {
                echo json_encode(['pedidos' => $pedidos, 'lastUpdated' => $newLastUpdated]);
                return;
            }

            usleep(500000); // Dormir por 500ms
        }
    }

    private function getMaxUpdatedTime($pedidos) {
        $maxTime = 0;
        foreach ($pedidos as $pedido) {
            $updatedAt = strtotime($pedido['updated_at']);
            if ($updatedAt > $maxTime) {
                $maxTime = $updatedAt;
            }
        }
        return $maxTime;
    }

    public function redirect($url) {
        header("Location: $url");
        exit;
    }
}
?>
