<?php
require_once '../models/departmentsModel.php';

class DepartmentsController {
    private $model;

    /*
     * Constructor de la clase
     * 
     * - Inicializa el modelo de departamentos para interactuar con la base de datos.
     */
    public function __construct() {
        $this->model = new DepartmentsModel();
    }

    /**
     * Valida si el usuario tiene rol de administrador
     * 
     * - Lanza una excepción si el usuario no tiene permisos.
     */
    private function validateAdminRole() {
        if ($_SESSION['role'] !== 'admin') {
            throw new Exception("Access denied. Only admins can perform this action.");
        }
    }

    /**
     * Valida que el ID del departamento tenga el formato correcto
     *  
     * - El ID debe consistir de 4 letras mayúsculas.
     */ 
    private function validateDepartmentId($dept_id) {
        if (!preg_match('/^[A-Z]{4}$/', $dept_id)) {
            throw new Exception("Department ID must consist of 4 uppercase letters.");
        }
    }

    /**
     * Obtiene la lista de todos los departamentos.
     * 
     * - Devuelve un arreglo con todos los departamentos registrados.
     */
    public function listDepartments() {
        return $this->model->getDepartments();
    }

    /**
     * Agrega un nuevo departamento.
     * 
     * - Valida que el usuario sea administrador.
     * - Verifica el formato del ID y que no exista ya en la base de datos.
     * - Lanza una excepción si el nombre del departamento está vacío.
     */
    public function addDepartment($dept_id, $dept_name) {
        $this->validateAdminRole(); // Verifica permisos de administrador
        $this->validateDepartmentId($dept_id); // Valida el formato del ID

        if (empty($dept_name)) {
            throw new Exception("Department Name is required.");
        }

        if ($this->model->isDeptCodeExists($dept_id)) {
            throw new Exception("Department ID already exists.");
        }

        $this->model->addDepartment($dept_id, $dept_name); // Agrega el departamento
    }

    /**
     * Edita un departamento existente.
     * 
     * - Valida el formato del nuevo ID del departamento.
     * - Verifica que el nuevo ID no esté duplicado.
     */
    public function editDepartment($old_dept_id, $new_dept_id, $dept_name) {
        $this->validateDepartmentId($new_dept_id); // Valida el formato del nuevo ID

        // Verifica que el nuevo ID no esté duplicado si es diferente al antiguo
        if ($old_dept_id !== $new_dept_id && $this->model->isDeptCodeExists($new_dept_id)) {
            throw new Exception("The new department code already exists.");
        }

        $this->model->updateDepartment($old_dept_id, $new_dept_id, $dept_name); // Actualiza el departamento
    }

    /**
     * Obtiene un departamento utilizando su ID.
     * 
     */
    public function getDepartmentById($dept_id) {
        return $this->model->getDepartmentById($dept_id);
    }

    /**
     * Elimina un departamento.
     * 
     * - Valida que el usuario sea administrador.
     * - Verifica que el departamento no tenga cursos activos antes de eliminarlo.
     */
    public function deleteDepartment($dept_id) {
        $this->validateAdminRole(); // Verifica permisos de administrador

        // Verifica si el departamento tiene cursos activos
        if ($this->model->departmentWithActiveCourses($dept_id)) {
            throw new Exception("Cannot delete a department with active courses.");
        }

        $this->model->deleteDepartment($dept_id); // Elimina el departamento
    }

    /**
     * Verifica si un departamento tiene cursos activos.
     * 
     * - Devuelve "true" si el departamento tiene cursos activos, si no, devuelve "false".
     */
    public function departmentActiveCourses($dept_id) {
        return $this->model->departmentWithActiveCourses($dept_id);
    }
}
?>
