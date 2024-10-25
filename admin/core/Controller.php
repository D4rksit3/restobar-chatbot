<?php
class Controller {
    public function loadModel($model) {
        require_once '../models/' . $model . '.php';
        return new $model();
    }

    public function loadView($view, $data = []) {
        require_once '../views/layouts/header.php';
        require_once '../views/' . $view . '.php';
        require_once '../views/layouts/footer.php';
    }

    public function redirect($url) {
        header('Location: ' . $url);
        exit();
    }

    public function isLoggedIn() {
        
        return isset($_SESSION['user']);
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            
        
            $this->redirect('index.php?controller=auth&action=login');
        }
    }
}
?>
