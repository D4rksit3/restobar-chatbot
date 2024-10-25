<?php
class Producto extends Model {
    public function getAllProductos() {
        $stmt = $this->db->prepare("SELECT p.*, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllCategorias() {
        $stmt = $this->db->prepare("SELECT id,nombre FROM categorias");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProducto($nombre, $precio, $categoria_id) {
        $stmt = $this->db->prepare("INSERT INTO productos (nombre, precio, categoria_id) VALUES (:nombre, :precio, :categoria_id)");
        return $stmt->execute(['nombre' => $nombre, 'precio' => $precio, 'categoria_id' => $categoria_id]);
    }

    public function addCategoria($nombre) {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
        return $stmt->execute(['nombre' => $nombre]);
    }
}
?>
