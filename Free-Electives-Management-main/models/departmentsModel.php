<?php
require_once '../config/database.php';

class DepartmentsModel {
    private $db;

    /*
     * Constructor de la clase
     * 
     * - Se conecta a la base de datos usando la configuración existente.
     */
    public function __construct() {
        $this->db = DataBase::getDB();
    }

    /**
     * Obtiene todos los departamentos, ordenados por nombre.
     */
    public function getDepartments() {
        $query = "SELECT dept_id, dept_name FROM departments ORDER BY dept_name";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Agrega un nuevo departamento a la base de datos.
     */
    public function addDepartment($dept_id, $dept_name) {
        $query = "INSERT INTO departments (dept_id, dept_name) VALUES (:dept_id, :dept_name)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->bindValue(':dept_name', $dept_name);
        $statement->execute();
    }

    /**
     * Elimina un departamento de la base de datos basado en su ID.
     */
    public function deleteDepartment($dept_id) {
        $query = "DELETE FROM departments WHERE dept_id = :dept_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
    }

    /**
     * Verifica si un código de departamento ya existe en la base de datos.
     */
    public function isDeptCodeExists($dept_id) {
        $query = "SELECT COUNT(*) FROM departments WHERE dept_id = :dept_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }

    /**
     * Actualiza el ID y el nombre de un departamento.
     */
    public function updateDepartment($old_dept_id, $new_dept_id, $dept_name) {
        $query = "
            UPDATE departments
            SET dept_id = :new_dept_id, dept_name = :dept_name
            WHERE dept_id = :old_dept_id
        ";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':new_dept_id', $new_dept_id);
        $statement->bindValue(':dept_name', $dept_name);
        $statement->bindValue(':old_dept_id', $old_dept_id);
        $statement->execute();
    }

    /**
     * Obtiene los detalles de un departamento basado en su ID.
     */
    public function getDepartmentById($dept_id) {
        $query = "SELECT * FROM departments WHERE dept_id = :dept_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
        return $statement->fetch();
    }

    /**
     * Verifica si un departamento tiene cursos ofrecidos en el término activo.
     */
    public function departmentWithActiveCourses($dept_id) {
        $query = "
            SELECT COUNT(*) 
            FROM courses c
            JOIN term_offering t ON c.course_id = t.course_id
            JOIN terms tm ON t.term_id = tm.term_id
            WHERE c.dept_id = :dept_id AND tm.term_is_active = 1
        ";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }
}
?>
