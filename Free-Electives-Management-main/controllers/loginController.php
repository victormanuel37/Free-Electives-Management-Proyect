<?php
session_start();
require_once '../config/database.php';
require_once '../models/loginModel.php';

/**
 * Maneja el inicio de sesión de un usuario.
 * 
 * Verifica las credenciales enviadas por el formulario POST.
 * Si el inicio de sesión es exitoso, guarda las variables en la sesion.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        die('ERROR---All fields are necessary.');
    }

    try {
        $db = DataBase::getDB();
        $userModel = new User($db);
        $user = $userModel->authenticate($username, $password);
        
        if ($user) {
            // Establece las variables de sesión tras autenticación exitosa
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['dept_id'] = $user['dept_id'];

            header("Location: ../views/courses.php");
            exit;
        } else {
            die('ERROR---Username or password are incorrect.');
        }
    } catch (PDOException $e) {
        die("ERORR---Data base did not load: ". $e->getMessage());
    }
}

/**
 * Maneja el cierre de sesión del usuario.
 * 
 * Destruye la sesión y redirige al usuario a la página de inicio.
 */
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: ../views/index.php");
    exit;
}

/**
 * Muestra todos los usuarios si el rol de sesión es admin.
 * 
 * Solo los administradores pueden acceder a esta funcionalidad. 
 * Los datos se cargan desde la base de datos y se muestran en la vista correspondiente.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    try {
        $db = DataBase::getDB();
        $userModel = new User($db);
        $users = $userModel->getAllUsers(); 
        
        require_once '../views/users.php';

    } catch (PDOException $e) {
        die("ERORR---Data base did not load: " . $e->getMessage());
    }
} elseif (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('ERROR---Access denied. Only admins can view this page.');
}

