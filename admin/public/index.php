<?php
session_start();

require '../core/Controller.php';
require '../core/Model.php';
require '../core/View.php';
require '../core/Database.php';

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

$controller = ucfirst($controller) . 'Controller';
$controllerFile = '../controllers/' . $controller . '.php';

if (file_exists($controllerFile)) {
    require $controllerFile;

    if (class_exists($controller)) {
        $controllerInstance = new $controller();

        if (method_exists($controllerInstance, $action)) {
            $controllerInstance->$action();
        } else {
            // Acción no encontrada
            echo "Error: Acción '{$action}' no encontrada en el controlador '{$controller}'.";
        }
    } else {
        // Clase del controlador no encontrada
        echo "Error: Clase del controlador '{$controller}' no encontrada.";
    }
} else {
    // Archivo del controlador no encontrado
    echo "Error: Archivo del controlador '{$controllerFile}' no encontrado.";
}
?>
