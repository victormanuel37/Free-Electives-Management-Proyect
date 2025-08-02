<?php
/*
 * add_departments.php
 *
 * - Permite agregar un nuevo departamento a través de un formulario.
 * - Muestra errores si hay problemas al agregar el departamento.
 */

include '../includes/header.php';
require_once '../controllers/departmentsController.php';

$controller = new DepartmentsController(); // Instancia del controlador de departamentos
$error = ""; // Variable para manejar errores

/*
 * Verifica si se envió el formulario con metodo POST y agrega un departamento.
 * 
 * - Si ocurre un error, se guarda el mensaje en la variable $error.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    try {
        $controller->addDepartment($_POST['dept_id'], $_POST['dept_name']); // Agrega el departamento
        header("Location: departments.php"); // Redirect a la lista de departamentos
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage(); // Captura y guarda el mensaje de error
    }
}
?>

<main>
    <!-- div del formulario -->
    <div class="form-container styled-form">
        <!-- Título del form -->
        <h2 class="form-title">Add Department</h2>

        <!-- Muestra el mensaje de error si hay alguno -->
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Formulario para agregar un nuevo departamento -->
        <form method="post" action="add_departments.php">
            <label for="dept_id">Department ID:</label>
            <input type="text" id="dept_id" name="dept_id" placeholder="Enter Department ID" required>

            <label for="dept_name">Department Name:</label>
            <input type="text" id="dept_name" name="dept_name" placeholder="Enter Department Name" required>

            <!-- Botones de acción -->
            <div class="form-buttons">
                <button type="submit" name="add" class="btn-submit">Add Department</button>
                <button type="button" class="btn-cancel" onclick="location.href='departments.php'">Back</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
