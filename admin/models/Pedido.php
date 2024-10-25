<?php
class Pedido extends Model {
    public function getPedidos($mozo_id) {
        $stmt = $this->db->prepare("SELECT * FROM pedidos INNER JOIN productos ON pedidos.producto_id = productos.id WHERE pedidos.mozo_id = :mozo_id AND pedidos.created_at >= NOW() - INTERVAL 8 HOUR");
        $stmt->execute(['mozo_id' => $mozo_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPedidosPorNroOrden($nro_orden) {
        $stmt = $this->db->prepare("
            SELECT pedidos.*, productos.nombre, productos.precio 
            FROM pedidos 
            INNER JOIN productos ON pedidos.producto_id = productos.id 
            WHERE pedidos.nro_orden = :nro_orden
        ");
        $stmt->execute(['nro_orden' => $nro_orden]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createVenta($data) {
        $stmt = $this->db->prepare("INSERT INTO ventas (nro_orden, total, created_at) VALUES (:nro_orden, :total, :created_at)");
        return $stmt->execute($data);
    }

    public function createPedido($data) {
        $stmt = $this->db->prepare("INSERT INTO pedidos (mozo_id, cliente, mesa, producto_id, cantidad, estado, created_at, nro_orden) VALUES (:mozo_id, :cliente, :mesa, :producto_id, :cantidad, :estado, :created_at, :nro_orden)");
        return $stmt->execute($data);
    }

    


    public function updateEstado($pedido_id, $estado) {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado = :estado, updated_at = NOW() WHERE id = :pedido_id");
        return $stmt->execute(['estado' => $estado, 'pedido_id' => $pedido_id]);
    }
    
    public function getPedidoById($pedido_id) {
        $stmt = $this->db->prepare("SELECT pedidos.*, productos.nombre FROM pedidos INNER JOIN productos ON pedidos.producto_id = productos.id WHERE pedidos.id = :pedido_id");
        $stmt->execute(['pedido_id' => $pedido_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategorias() {
        return $this->db->query("SELECT id, nombre FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductos() {
        return $this->db->query("SELECT id, nombre, precio, categoria_id FROM productos")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPedidos() {
        $stmt = $this->db->prepare("SELECT pedidos.*, productos.nombre FROM pedidos INNER JOIN productos ON pedidos.producto_id = productos.id WHERE pedidos.created_at >= NOW() - INTERVAL 8 HOUR");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaxNroOrden() {
        return $this->db->query("SELECT MAX(nro_orden) as max_nro_orden FROM pedidos")->fetch(PDO::FETCH_ASSOC)['max_nro_orden'];
    }

    public function getAllOrdenes() {
        return $this->db->query("SELECT DISTINCT nro_orden FROM pedidos")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
