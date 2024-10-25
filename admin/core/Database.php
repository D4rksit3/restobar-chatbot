<?php
class Database {
    public static function connect() {
        try {
        $db = new PDO('mysql:host=localhost;dbname=restobar', 'jroque', '123456');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Error al conectar a la base de datos: " . $e->getMessage());
    }
    }
}
?>
    
