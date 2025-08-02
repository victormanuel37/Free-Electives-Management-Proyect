<?php
/*
 *  add_courses.php
 *  
 *  - Este archivo permite agregar un nuevo curso.
 *  - Se muestra un formulario donde se ingresan los datos del curso.
 *  - Solo los admin pueden seleccionar el departamento, los demás usuarios verán su departamento por default.
 */

include '../includes/header.php';
require_once '../controllers/coursesController.php';

$controller = new CoursesController(); // Controlador para manejar cursos
$error = ""; // Variable para almacenar mensajes de error

/*
 *  Obtiene el departamento del usuario
 *
 *  - Intenta obtener el departamento asignado al usuario actual.
 *  - Si hay un error, guarda el mensaje en la variable $error.
 */
try {
    $userDepartment = $controller->getUserDepartment();
} catch (Exception $e) {
    $error = $e->getMessage();
}

/*
 *  Procesa el formulario para agregar un curso
 *
 *  - Verifica que se haya enviado una solicitud POST con el botón "add".
 *  - Intenta agregar un curso con los datos enviados en el formulario.
 *  - Si ocurre un error, muestra un mensaje en pantalla.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    try {
        $controller->addCourse(
            $_POST['course_id'],
            $_POST['course_name'],
            $_POST['course_credits'],
            $_POST['course_desc'],
            $_SESSION['dept_id']
        );
        header("Location: courses.php"); // Redirige a la página principal
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<main>
    <!-- Título principal -->
    <h2 class="form-title">Add New Course</h2>
    
    <!-- div del formulario -->
    <div class="form-container">
        <!-- Mensaje de error, en caso de haberlo -->
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Formulario para agregar un nuevo curso -->
        <form method="post" accept-charset="UTF-8" class="styled-form">
            <label for="course_id">Course ID:</label>
            <input type="text" id="course_id" name="course_id" placeholder="Ex: CSCI1001" required>

            <label for="course_name">Course Name:</label>
            <input type="text" id="course_name" name="course_name" placeholder="Course Name" required>

            <label for="course_credits">Credits:</label>
            <input type="number" id="course_credits" name="course_credits" placeholder="Credits" required>

            <label for="course_desc">Description:</label>
            <textarea id="course_desc" name="course_desc" placeholder="Enter course description..." required></textarea>

            <label for="dept_name">Department:</label>

            <!-- Verifica si el usuario es administrador -->
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <!-- Muestra un menú desplegable con todos los departamentos -->
            <select id="dept_id" name="dept_id" required>
                <option value="">Select Department</option>
                <?php foreach ($controller->getDepartments() as $department): ?>
                    <option value="<?php echo htmlspecialchars($department['dept_id']); ?>">
                        <?php echo htmlspecialchars($department['dept_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php else: ?>
            <!-- Si no es administrador, muestra solo el departamento del usuario -->
            <input type="text" id="dept_name" value="<?php echo htmlspecialchars($userDepartment); ?>" readonly>
            <?php endif; ?>            

            <!-- Botones para enviar o cancelar -->
            <div class="form-buttons">
                <button type="submit" name="add" class="btn-submit">Add Course</button>
                <button type="button" onclick="location.href='courses.php'" class="btn-cancel">Back</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
