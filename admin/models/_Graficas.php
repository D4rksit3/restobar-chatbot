<?php
class Graficas {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getVentasPorDia($inicio, $fin) {
        $stmt = $this->db->prepare("
            SELECT DATE(created_at) as fecha, SUM(total) as total_ventas
            FROM ventas
            WHERE created_at BETWEEN :inicio AND :fin
            GROUP BY DATE(created_at)
        ");
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentasPorHora($inicio, $fin) {
        $stmt = $this->db->prepare("
            SELECT HOUR(created_at) as hora, SUM(total) as total_ventas
            FROM ventas
            WHERE created_at BETWEEN :inicio AND :fin
            GROUP BY HOUR(created_at)
        ");
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
