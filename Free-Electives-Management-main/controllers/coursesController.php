<?php
require_once '../config/database.php';
require_once '../models/coursesModel.php';

class CoursesController {
    private $courseModel;

    /*
     * Constructor de la clase
     * 
     * - Inicializa la conexión con la base de datos.
     */
    public function __construct() {
        $db = DataBase::getDB();
        $this->courseModel = new CoursesModel($db);
    }

    /**
     * Obtiene la lista de todos los cursos disponibles.
     */
    public function listCourses() {
        return $this->courseModel->getCourses();
    }
        
    /**
     * Agrega un nuevo curso a la base de datos.
     * 
     * Valida el formato del ID del curso y los créditos antes de agregarlo.
     */
    public function addCourse($course_id, $course_name, $course_credits, $course_desc, $dept_id) {
        if (!preg_match('/^[A-Z]{4}[0-9]{4}$/', $course_id)) {
            throw new Exception("Course ID must consist of 4 uppercase letters followed by 4 digits.");
        }
    
        if (!filter_var($course_credits, FILTER_VALIDATE_INT) || $course_credits <= 0) {
            throw new Exception("Credits must be a positive integer.");
        }
        $this->courseModel->addCourse($course_id, $course_name, $course_credits, $course_desc, $dept_id);
    }

    /**
     * Edita un curso existente en la base de datos.
     * 
     * Valida el formato del ID del curso y los créditos antes de realizar los cambios.
     */
    public function editCourse($course_id, $course_name, $course_credits, $course_desc, $dept_id) {
        if (!preg_match('/^[A-Z]{4}[0-9]{4}$/', $course_id)) {
            throw new Exception("Course ID must consist of 4 uppercase letters followed by 4 digits.");
        }
    
        if (!filter_var($course_credits, FILTER_VALIDATE_INT) || $course_credits <= 0) {
            throw new Exception("Credits must be a positive integer.");
        }
        $this->courseModel->editCourse($course_id, $course_name, $course_credits, $course_desc, $dept_id);
    }

    /**
     * Elimina un curso de la base de datos.
     * 
     * Verifica si el curso está vinculado a una oferta activa antes de eliminarlo.
     */
    public function deleteCourse($course_id) {
        if ($this->courseModel->getActive($course_id)) {
            throw new Exception("Cannot delete a course linked to active offerings.");
        }
        $this->courseModel->deletePrerequisitesByCourse($course_id);
        $this->courseModel->deleteCourse($course_id);
    }
    
    /**
     * Obtiene la lista de departamentos disponibles.
     */
    public function getDepartments() {
        return $this->courseModel->getDepartments();
    }

    /**
     * Agrega un prerrequisito a un curso.
     * 
     * Valida el formato de los ID de los cursos antes de agregarlos.
     */
    public function addPrerequisite($course_id, $prerequisite_id) {
        if (!preg_match('/^[A-Z]{4}[0-9]{4}$/', $course_id) || !preg_match('/^[A-Z]{4}[0-9]{4}$/', $prerequisite_id)) {
            throw new Exception("Invalid course ID or prerequisite ID format.");
        }
        $this->courseModel->addPrerequisite($course_id, $prerequisite_id);
    }

    /**
     * Obtiene una lista completa de todos los cursos.
     */
    public function getAllCourses() {
        return $this->courseModel->getAllCourses();
    }

    /**
     * Elimina un prerrequisito de un curso.
     * 
     * Valida el formato de los ID de los cursos antes de eliminar el prerrequisito.
     */
    public function deletePrerequisite($course_id, $prerequisite_id) {
        if (!preg_match('/^[A-Z]{4}[0-9]{4}$/', $course_id) || !preg_match('/^[A-Z]{4}[0-9]{4}$/', $prerequisite_id)) {
            throw new Exception("Invalid course ID or prerequisite ID format.");
        }
        $this->courseModel->deletePrerequisite($course_id, $prerequisite_id);
    }

    /**
     * Obtiene los cursos asociados al usuario actualmente autenticado.
     * 
     * Devuelve todos los cursos si el usuario es administrador o los cursos de su departamento si es usuario regular.
     */
    public function getCoursesForLoggedUser() {
        if ($_SESSION['role'] === 'admin') {
            return $this->courseModel->getAllCoursesWithDepartment();
        } elseif (isset($_SESSION['dept_id'])) {
            return $this->courseModel->getCoursesByDepartmentWithDepartmentName($_SESSION['dept_id']);
        } else {
            throw new Exception('Invalid user role or session.');
        }
    }

    /**
     * Obtiene el departamento del usuario actualmente autenticado.
     * 
     * Lanza una excepción si no se encuentra el ID del departamento en la sesión.
     */
    public function getUserDepartment() {
        if (isset($_SESSION['dept_id'])) {
            return $this->courseModel->getDepartmentName($_SESSION['dept_id']);
        }
        throw new Exception('Department ID is not set in the session.');
    }

    /**
     * Obtiene los prerrequisitos de un curso en un formato de string.
     * 
     * Los devuelve con un formato de comma-separated-value.
     */
    public function getPrerequisitesForCourse($course_id) {
        $prerequisites = $this->courseModel->getPrerequisitesByCourseId($course_id);
        return implode(', ', array_column($prerequisites, 'prerequisite'));
    }
}
