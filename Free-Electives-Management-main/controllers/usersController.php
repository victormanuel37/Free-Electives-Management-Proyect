<?php
require_once '../models/usersModel.php';

class UsersController {
    private $model;

    // Constructor
    // Inicializa el modelo de usuarios
    public function __construct() {
        $this->model = new UsersModel();
    }

    // Obtiene la lista de usuarios
    // Retorna una lista de usuarios
    public function listUsers() {
        return $this->model->getUsers();
    }

    // Agrega un nuevo usuario validando los datos proporcionados
    public function addUser($username, $name, $role, $dept_id, $password) {
        // Verifica si el nombre es valido en UTF-8
        if (!mb_check_encoding($name, 'UTF-8')) {
            throw new Exception("Name must be valid UTF-8.");
        }

        // Valida el formato del nombre de usuario
        if (!preg_match('/^[a-z]+\.[a-z]+[0-9]*$/', $username)) {
            throw new Exception("Username must consist of lowercase letters, digits, and periods, and follow the format name.surname.");
        }
        
        // Verifica que el nombre de usuario sea unico
        if (!$this->model->isUsernameUnique($username)) {
            throw new Exception("Username already exists.");
        }

        // Valida que el nombre contiene caracteres permitidos
        if (!preg_match('/^[\p{L}\p{M}.\' -]+$/u', $name)) {
            throw new Exception("Name can only contain letters including accents periods dashes and apostrophes.");
        }

        // Verifica que la contraseña cumple con el minimo requerido
        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }

        // Valida el rol del usuario
        if (!in_array($role, ['admin', 'chair', 'coordinator'])) {
            throw new Exception("Role must be admin chair or coordinator.");
        }

        // Valida que el id del departamento sea valido
        if (!$this->model->isValidDepartment($dept_id)) {
            throw new Exception("Invalid department ID.");
        }
        
        // Hashea la contraseña y la agrega al modelo
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->model->addUser($username, $name, $role, $dept_id, $hashedPassword);
    }

    // Obtiene la lista de departamentos
    // Retorna una lista de departamentos
    public function getDepartments() {
        return $this->model->getDepartments();
    }

    // Edita los datos de un usuario existente
    public function editUser($username, $name, $role, $dept_id) {
        $this->model->editUser($username, $name, $role, $dept_id);
    }

    // Elimina un usuario verificando permisos
    public function deleteUser($username) {
        if ($_SESSION['role'] !== 'admin') {
            throw new Exception("Access denied. Only admins can delete users.");
        }
        $this->model->deleteUser($username);
    }
}
?>
