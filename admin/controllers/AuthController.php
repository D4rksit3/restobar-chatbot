<?php
class AuthController extends Controller {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->loadModel('User');
            $user = $userModel->login($_POST['username'], $_POST['password']);
            if ($user) {
                $_SESSION['user'] = $user;
                // Redireccionar según el rol del usuario
                switch ($user['role']) {
                    case 'mozo':
                        $this->redirect('index.php?controller=mozo&action=index');
                        break;
                    case 'administrador':
                        $this->redirect('index.php?controller=admin&action=ventas');
                        break;
                    case 'cocinero':
                        $this->redirect('index.php?controller=cocinero&action=index');
                        break;
                }
            } else {
                $this->loadView('login', ['error' => 'Usuario o contraseña incorrectos']);
            }
        } else {
            $this->loadView('login');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('index.php?controller=auth&action=login');
    }
}
?>
