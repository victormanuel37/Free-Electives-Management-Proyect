<?php
require_once '../models/term_OfferingModel.php';

class TermOfferingController {
    private $model;

    public function __construct() {
        $this->model = new TermOfferingModel();
    }

    // Sin parametros
    // Retorna todas las ofertas de terminos disponibles
    public function listTermOfferings() {
        return $this->model->getTermOfferings();
    }

    // Sin parametros
    // Retorna los terminos activos
    public function listTermActive() {
        return $this->model->getTermActive();
    }

    // Parametros: term_id, course_id
    // Agrega una oferta de termino validando formato y estado activo
    // Excepciones: formato incorrecto de term_id o termino inactivo
    public function addTermOffering($term_id, $course_id) {
        if (!preg_match('/^[A-Z][0-9]{2}$/', $term_id)) {
            throw new Exception("Term ID must consist of an uppercase letter followed by two digits");
        }

        if (!$this->model->isTermActive($term_id)) {
            throw new Exception("You can only manage offerings for the active term");
        }

        $this->model->addTermOffering($term_id, $course_id);
    }

    // Parametros: old_term_id, old_course_id, new_term_id, new_course_id
    // Edita una oferta validando formato y estado activo
    // Excepciones: formato incorrecto de new_term_id o termino inactivo
    public function editTermOffering($old_term_id, $old_course_id, $new_term_id, $new_course_id) {
        if (!preg_match('/^[A-Z][0-9]{2}$/', $new_term_id)) {
            throw new Exception("Term ID must consist of an uppercase letter followed by two digits");
        }

        if (!$this->model->isTermActive($new_term_id)) {
            throw new Exception("You can only manage offerings for the active term");
        }

        $this->model->editTermOffering($old_term_id, $old_course_id, $new_term_id, $new_course_id);
    }

    // Parametros: term_id, course_id
    // Elimina una oferta validando estado activo y permisos
    // Excepciones: termino inactivo o acceso denegado por permisos
    public function deleteTermOffering($term_id, $course_id) {
        if (!$this->model->isTermActive($term_id)) {
            throw new Exception("You can only manage offerings for the active term");
        }

        if ($_SESSION['role'] !== 'admin') {
            $course = $this->model->getCourseById($course_id);
            if ($course['dept_id'] !== $_SESSION['dept_id']) {
                throw new Exception("Access denied You can only manage offerings for your department");
            }
        }

        $this->model->deleteTermOffering($term_id, $course_id);
    }

    // Sin parametros
    // Retorna la lista de cursos disponibles
    public function getCourses() {
        return $this->model->getCourses();
    }

    // Sin parametros
    // Retorna la lista de terminos disponibles
    public function getTerms() {
        return $this->model->getTerms();
    }

    // Sin parametros
    // Retorna el termino activo actual
    public function getActiveTerm() {
        return $this->model->getActiveTerm();
    }

    // Sin parametros
    // Retorna cursos del departamento del usuario autenticado
    // Excepciones: dept_id no definido en la sesion
    public function getCoursesForLoggedUser() {
        if (isset($_SESSION['dept_id'])) {
            return $this->model->getCoursesByDepartment($_SESSION['dept_id']);
        }
        throw new Exception("Department ID is not set in the session");
    }

    // Parametros: user_role, dept_id
    // Retorna cursos disponibles segun el rol del usuario
    // Excepciones: rol del usuario no valido
    public function getAvailableCourses($user_role, $dept_id) {
        if ($user_role === 'admin') {
            return $this->model->getAvailableCoursesAdmin();
        } elseif ($user_role === 'chair' || $user_role === 'coordinator') {
            return $this->model->getAvailableCoursesChair($dept_id);
        } else {
            throw new Exception("Invalid user role");
        }
    }
}
