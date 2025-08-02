<?php
include '../includes/header.php';
require_once '../controllers/term_offeringController.php';

$controller = new TermOfferingController();

// Variables de control
$error = "";
$user_role = $_SESSION['role'];
$dept_id = $_SESSION['dept_id'];

// Procesa el form cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    try {
        // Agrega una nueva oferta de termino
        $controller->addTermOffering($_POST['term_id'], $_POST['course_id']);
        header("Location: term_offering.php"); 
        exit();
    } catch (Exception $e) {
        // Captura errores y los asigna a la variable
        $error = $e->getMessage();
    }
}

// Obtiene los cursos disponibles segun el rol y el departamento
$courses = $controller->getAvailableCourses($user_role, $dept_id);

// Obtiene el termino activo
$activeTerm = $controller-> getActiveTerm();

// Obtiene la lista de terminos
$terms = $controller->getTerms();
?>

<main>
    <div class="form-container">
        <h2 class="form-title">Add Term Offering</h2>

        <!-- Muestra el mensaje de error si existe -->
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Formulario para agregar una nueva oferta de termino -->
        <form method="post" class="styled-form">
            <label for="term_id">Active Term:</label>
            <!-- Campo de solo lectura con el termino activo -->
            <input type="text" id="term_id" name="term_id" value="<?php echo htmlspecialchars($activeTerm['term_id']); ?>" readonly>

            <label for="course_id">Course:</label>
            <!-- Drop down para seleccionar un curso -->
            <select id="course_id" name="course_id" required>
                <option value="">Select Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo htmlspecialchars($course['course_id']); ?>">
                        <?php echo htmlspecialchars($course['course_id'] . ' - ' . $course['course_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Botones de accion -->
            <div class="form-buttons">
                <button type="submit" class="btn-submit" name="add">Add Offering</button>
                <button type="button" class="btn-cancel" onclick="location.href='term_offering.php'">Back</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
