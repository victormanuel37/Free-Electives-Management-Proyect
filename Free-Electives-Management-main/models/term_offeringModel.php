<?php
require_once '../config/database.php';

class TermOfferingModel {
    private $db;

    // Constructor
    // Inicializa la conexion con la base de datos
    public function __construct() {
        $this->db = DataBase::getDB();
    }

    // Obtiene todas las ofertas de terminos con sus cursos correspondientes
    public function getTermOfferings() {
        $query = "
            SELECT t.term_id, t.term_desc, c.course_id, c.course_name
            FROM term_offering o
            JOIN terms t ON o.term_id = t.term_id
            JOIN courses c ON o.course_id = c.course_id
            ORDER BY t.term_id, c.course_name
        ";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    // Obtiene terminos activos con sus cursos y departamentos
    public function getTermActive($term_id = null) {
        $query = "
            SELECT t.term_id, c.course_id, c.course_name, c.course_credits, c.course_desc, d.dept_name, d.dept_id
            FROM term_offering t
            JOIN courses c ON t.course_id = c.course_id
            JOIN departments d ON c.dept_id = d.dept_id
            JOIN terms tm ON t.term_id = tm.term_id
            WHERE tm.term_is_active = 1
            ORDER BY d.dept_name, c.course_name
        ";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retorna el primer termino activo encontrado
    public function getActiveTerm() {
        $query = "SELECT term_id FROM terms WHERE term_is_active = 1 LIMIT 1";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Agrega una oferta de termino nueva
    public function addTermOffering($term_id, $course_id) {
        $query = "INSERT INTO term_offering (term_id, course_id) VALUES (:term_id, :course_id)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':term_id', $term_id);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
    }

    // Edita una oferta de termino existente
    public function editTermOffering($old_term_id, $old_course_id, $new_term_id, $new_course_id) {
        $query = "
            UPDATE term_offering
            SET term_id = :new_term_id, course_id = :new_course_id
            WHERE term_id = :old_term_id AND course_id = :old_course_id
        ";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':old_term_id', $old_term_id);
        $statement->bindValue(':old_course_id', $old_course_id);
        $statement->bindValue(':new_term_id', $new_term_id);
        $statement->bindValue(':new_course_id', $new_course_id);
        $statement->execute();
    }

    // Elimina una oferta de termino
    public function deleteTermOffering($term_id, $course_id) {
        $query = "DELETE FROM term_offering WHERE term_id = :term_id AND course_id = :course_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':term_id', $term_id);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
    }

    // Verifica si un termino esta activo
    public function isTermActive($term_id) {
        $query = "SELECT term_is_active FROM terms WHERE term_id = :term_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':term_id', $term_id);
        $statement->execute();
        return $statement->fetchColumn() == 1;
    }

    // Retorna la lista de cursos disponibles
    public function getCourses() {
        $query = "SELECT course_id, course_name FROM courses ORDER BY course_name";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    // Retorna informacion del curso usando su id
    public function getCourseById($course_id) {
        $query = "SELECT dept_id FROM courses WHERE course_id = :course_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Retorna la lista de terminos disponibles
    public function getTerms() {
        $query = "SELECT term_id, term_desc FROM terms ORDER BY term_id";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    // Retorna cursos asociados a un departamento especifico
    public function getCoursesByDepartment($dept_id) {
        $query = "
            SELECT course_id, course_name 
            FROM courses 
            WHERE dept_id = :dept_id 
            ORDER BY course_name ASC
        ";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':dept_id', $dept_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retorna cursos no asignados a terminos activos para administradores
    public function getAvailableCoursesAdmin() {
        $query = "
            SELECT c.course_id, c.course_name
            FROM courses c
            WHERE c.course_id NOT IN (
                SELECT t.course_id
                FROM term_offering t
                INNER JOIN terms tm ON t.term_id = tm.term_id
                WHERE tm.term_is_active = 1
            )
            ORDER BY c.course_name
        ";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retorna cursos no asignados a terminos activos para departamentos
    public function getAvailableCoursesChair($dept_id) {
        $query = "
            SELECT c.course_id, c.course_name 
            FROM courses c
            WHERE c.dept_id = :dept_id 
            AND c.course_id NOT IN (
                SELECT t.course_id
                FROM term_offering t
                JOIN terms tm ON t.term_id = tm.term_id
                WHERE tm.term_is_active = 1
            )
            ORDER BY c.course_name ASC
        ";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':dept_id', $dept_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
