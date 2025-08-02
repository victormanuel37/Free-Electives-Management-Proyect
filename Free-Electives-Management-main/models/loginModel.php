<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Autentica a un usuario verificando sus credenciales
     * 
     * - Busca el usuario en la base de datos usando su username.
     * - Verifica si la contraseña proporcionada coincide con la contraseña almacenada en la base de datos.
     * - Devuelve los datos del usuario si la autenticación es exitosa o "false" si falla.
     */
    public function authenticate($username, $password) {
        $query = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();
        $user = $query->fetch();

        // Verifica si el usuario existe y si la contraseña es correcta
        if ($user && password_verify($password, $user['password'])) {
            return $user; 
        }
        return false;
    }

    /**
     * Obtiene una lista de todos los usuarios registrados.
     * 
     * - Devuelve una lista ordenada de usuarios, mostrando su departamento, nombre de usuario, nombre real y rol.
     */
    public function getAllUsers() {
        $query = $this->db->prepare(
            "SELECT dept_id, username, name, role 
             FROM users 
             ORDER BY dept_id ASC, role ASC"
        );
        $query->execute();
        return $query->fetchAll();
    }
}
