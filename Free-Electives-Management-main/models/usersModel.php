<?php
require_once '../config/database.php';

class UsersModel {
    private $db;

    // Constructor
    // Inicializa la conexion con la base de datos
    public function __construct() {
        $this->db = DataBase::getDB();
    }

    // Obtiene todos los usuarios con informacion detallada de sus departamentos
    // Retorna una lista de usuarios con sus roles y departamentos
    public function getUsers() {
        $query = "
            SELECT u.username, u.name, u.role, d.dept_name AS department, u.dept_id
            FROM users u
            JOIN departments d ON u.dept_id = d.dept_id
            ORDER BY d.dept_name, u.role, u.name
        ";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    // Agrega un nuevo usuario con los datos proporcionados
    public function addUser($username, $name, $role, $dept_id, $password) {
        $query = "INSERT INTO users (username, name, role, dept_id, password) VALUES (:username, :name, :role, :dept_id, :password)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':role', $role);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->bindValue(':password', $password);
        $statement->execute();
    }

    // Actualiza la informacion de un usuario existente
    public function editUser($username, $name, $role, $dept_id) {
        $query = "UPDATE users SET name = :name, role = :role, dept_id = :dept_id WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':role', $role);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
    }

    // Elimina un usuario por su nombre de usuario
    public function deleteUser($username) {
        $query = "DELETE FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
    }

    // Verifica si un nombre de usuario es unico
    // Retorna true si el nombre de usuario no existe false de lo contrario
    public function isUsernameUnique($username) {
        $query = "SELECT COUNT(*) FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        return $statement->fetchColumn() == 0;
    }

    // Verifica si un departamento es valido
    // Retorna true si el departamento existe false de lo contrario
    public function isValidDepartment($dept_id) {
        $query = "SELECT COUNT(*) FROM departments WHERE dept_id = :dept_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }

    // Obtiene todos los departamentos
    // Retorna una lista de departamentos
    public function getDepartments() {
        $query = "SELECT dept_id, dept_name FROM departments ORDER BY dept_name";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
