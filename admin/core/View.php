<?php
class View {
    public function loadView($view, $data = []) {
        extract($data);
        require_once "../views/{$view}.php";
    }
    
}
?>
