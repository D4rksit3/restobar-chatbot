<?php
if (session_status() === PHP_SESSION_NONE) {

// Verificar si el usuario está logueado y tiene un rol específico
session_start(); // Iniciar la sesión (si no está iniciada)

// Verificar si hay una sesión activa y si el usuario tiene un rol específico
if (isset($_SESSION['user'])) {
    switch ($_SESSION['user']['role']) {
        case 'mozo':
            header('Location: index.php?controller=mozo&action=index');
            exit();
        case 'cocinero':
            header('Location: index.php?controller=cocinero&action=index');
            exit();
        case 'administrador':
            header('Location: index.php?controller=admin&action=ventas');
            exit();
        // Agregar más casos según los roles de tu aplicación
        default:
            // Si no se encuentra un rol específico, redirigir a una página por defecto
            header('Location: index.php'); // Página de inicio o cualquier otra página por defecto
            exit();
    }
}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 300px;
        }

        .logo img {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #333333;
            color: #ffffff;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #6200ee;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #3700b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="/logo.png" alt="Logo">
        </div>
        <form action="index.php?controller=auth&action=login" method="POST">
            <h2>Bienvenido al panel administrativo</h2>
            <?php if (!empty($data['error'])): ?>
                <div class="alert alert-danger"><?php echo $data['error']; ?></div>
            <?php endif; ?>
            <div class="input-group">
                <input type="text" name="username" id="username" class="form-control" placeholder="Usuario" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
