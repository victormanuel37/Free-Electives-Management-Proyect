<?php
/*
 * edit_departments.php
 *
 * - Permite editar la información de un departamento.
 * - Muestra un mensaje de error si no se encuentra el departamento o si no se selecciona ninguno.
 */

include '../includes/header.php';
require_once '../controllers/departmentsController.php';

$controller = new DepartmentsController(); // Instancia del controlador
$error = ""; // Variable para manejar errores
$department = null; // Almacena los detalles del departamento

/*
 * Verifica si se proporciona un ID de departamento en una solicitud GET.
 * 
 */
if (isset($_GET['dept_id'])) {
    $dept_id = $_GET['dept_id'];
    $department = $controller->getDepartmentById($dept_id);
    if (!$department) {
        $error = "Department not found.";
    }
} else {
    $error = "No department selected.";
}

/*
 * Procesa el formulario para editar un departamento.
 * 
 * - Si se envía el formulario, actualiza los datos del departamento.
 * - Si ocurre un error, se guarda el mensaje en la variable $error.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller->editDepartment($_POST['old_dept_id'], $_POST['dept_id'], $_POST['dept_name']); // Actualiza el departamento
        header("Location: departments.php"); // redirect a la lista de departamentos
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<main>
    <!-- Contenedor del formulario -->
    <div class="form-container styled-form">
        <!-- Título del formulario -->
        <h2 class="form-title">Edit Department</h2>

        <!-- Muestra el mensaje de error si existe -->
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <!-- Formulario para editar el departamento -->
            <form method="post">
                <!-- Campo oculto con el ID actual del departamento -->
                <input type="hidden" name="old_dept_id" value="<?php echo htmlspecialchars($department['dept_id']); ?>">

                <label for="dept_id">Department ID:</label>
                <input type="text" id="dept_id" name="dept_id" value="<?php echo htmlspecialchars($department['dept_id']); ?>" required>

                <label for="dept_name">Department Name:</label>
                <input type="text" id="dept_name" name="dept_name" value="<?php echo htmlspecialchars($department['dept_name']); ?>" required>

                <!-- Botones de acción -->
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">Save Changes</button>
                    <button type="button" class="btn-cancel" onclick="location.href='departments.php'">Back</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
