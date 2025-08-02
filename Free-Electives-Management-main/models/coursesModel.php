<?php
require_once '../config/database.php';

class CoursesModel {
    private $db;

    /*
     * Constructor de la clase
     *
     * - Recibe la conexión a la base de datos y la guarda en una variable.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Obtiene todos los cursos junto con el nombre del departamento.
     */
    public function getAllCoursesWithDepartment() {
        $query = $this->db->query("
            SELECT c.course_id, c.course_name, c.course_credits, c.course_desc, d.dept_name
            FROM courses c
            INNER JOIN departments d ON c.dept_id = d.dept_id
            ORDER BY d.dept_name ASC, c.course_name ASC
        ");
        return $query->fetchAll();
    }

    /**
     * Obtiene los cursos de un departamento junto con el nombre del departamento.
     *
     * parametro $dept_id - ID del departamento.
     */
    public function getCoursesByDepartmentWithDepartmentName($dept_id) {
        $query = "
            SELECT c.course_id, c.course_name, c.course_credits, c.course_desc, d.dept_name
            FROM courses c
            JOIN departments d ON c.dept_id = d.dept_id
            WHERE c.dept_id = :dept_id
            ORDER BY c.course_name ASC
        ";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':dept_id', $dept_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Agrega un curso a la base de datos.
     */
    public function addCourse($course_id, $course_name, $course_credits, $course_desc, $dept_id) {
        $query = "
            INSERT INTO courses (course_id, course_name, course_credits, course_desc, dept_id, updated_by)
            VALUES (:course_id, :course_name, :course_credits, :course_desc, :dept_id, 'admin')
        ";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->bindValue(':course_name', $course_name);
        $statement->bindValue(':course_credits', $course_credits);
        $statement->bindValue(':course_desc', $course_desc);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
    }

    /**
     * Edita un curso en la base de datos.
     */
    public function editCourse($course_id, $course_name, $course_credits, $course_desc, $dept_id) {
        $query = "
            UPDATE courses
            SET course_name = :course_name, course_credits = :course_credits, 
                course_desc = :course_desc, dept_id = :dept_id, updated_by = 'admin'
            WHERE course_id = :course_id
        ";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->bindValue(':course_name', $course_name);
        $statement->bindValue(':course_credits', $course_credits);
        $statement->bindValue(':course_desc', $course_desc);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
    }

    /**
     * Elimina un curso de la base de datos.
     */
    public function deleteCourse($course_id) {
        $query = "DELETE FROM courses WHERE course_id = :course_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
    }

    /**
     * Obtiene todos los cursos con información adicional como prerrequisitos y vínculos a term offerings.
     */
    public function getCourses() {
        $query = "
            SELECT c.course_id, c.course_name, c.course_credits, c.course_desc, d.dept_name, d.dept_id,
                   COALESCE(GROUP_CONCAT(p.prerequisite SEPARATOR ', '), '') AS prerequisites,
                   COALESCE(
                       (SELECT COUNT(*) 
                        FROM term_offering t 
                        JOIN terms ON t.term_id = terms.term_id 
                        WHERE t.course_id = c.course_id AND terms.term_is_active = 1), 0
                   ) AS linked_to_offering
            FROM courses c
            JOIN departments d ON c.dept_id = d.dept_id
            LEFT JOIN prerequisites p ON c.course_id = p.course_id
            GROUP BY c.course_id
            ORDER BY d.dept_name, c.course_name
        ";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica si un curso está vinculado a ofertas activas.
     */
    public function getActive($course_id) {
        $query = "
            SELECT COUNT(*)
            FROM term_offering t
            JOIN terms tm ON t.term_id = tm.term_id
            WHERE t.course_id = :course_id AND tm.term_is_active = 1
        ";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }

    /**
     * Obtiene todos los departamentos.
     */
    public function getDepartments() {
        $query = "SELECT dept_id, dept_name FROM departments ORDER BY dept_name";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Agrega un prerrequisito a un curso.
     */
    public function addPrerequisite($course_id, $prerequisite_id) {
        $query = "SELECT COUNT(*) FROM prerequisites WHERE course_id = :course_id AND prerequisite = :prerequisite_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->bindValue(':prerequisite_id', $prerequisite_id);
        $statement->execute();

        if ($statement->fetchColumn() > 0) {
            throw new Exception("Prerequisite already exists.");
        }

        $query = "INSERT INTO prerequisites (course_id, prerequisite) VALUES (:course_id, :prerequisite_id)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->bindValue(':prerequisite_id', $prerequisite_id);
        $statement->execute();
    }

    /**
     * Obtiene todos los cursos de la base de datos.
     */
    public function getAllCourses() {
        $query = "SELECT course_id, course_name, course_credits, dept_id, course_desc FROM courses ORDER BY course_id";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el nombre de un departamento por su ID.
     */
    public function getDepartmentName($dept_id) {
        $query = "SELECT dept_name FROM departments WHERE dept_id = :dept_id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':dept_id', $dept_id);
        $statement->execute();
        return $statement->fetchColumn();
    }

    /**
     * Elimina un prerrequisito de un curso.
     */
    public function deletePrerequisite($course_id, $prerequisite_id) {
        $query = "DELETE FROM prerequisites WHERE course_id = :course_id AND prerequisite = :prerequisite_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->bindValue(':prerequisite_id', $prerequisite_id);
        $statement->execute();
    }

    /**
     * Obtiene los prerrequisitos de un curso por su ID.
     */
    public function getPrerequisitesByCourseId($course_id) {
        $query = "
            SELECT prerequisite 
            FROM prerequisites 
            WHERE course_id = :course_id
        ";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Elimina todos los prerrequisitos asociados a un curso.
     */
    public function deletePrerequisitesByCourse($course_id) {
        $query = "DELETE FROM prerequisites WHERE course_id = :course_id OR prerequisite = :course_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
    }
}
