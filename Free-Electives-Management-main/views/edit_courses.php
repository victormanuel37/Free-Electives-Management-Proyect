<?php
/*
 *  edit_courses.php
 *
 *  - Este archivo permite editar los datos de un curso.
 *  - Solo los administradores o responsables del departamento pueden realizar esta acción.
 *  - Muestra un formulario con la información actual del curso.
 */

include '../includes/header.php';
require_once '../controllers/coursesController.php';

$controller = new CoursesController(); // Controlador para manejar los cursos
$error = ""; // Variable para almacenar mensajes de error

/*
 *  Verifica si se recibe el parámetro "course_id"
 *
 *  - Si no existe, el programa termina y muestra un mensaje.
 */
if (!isset($_GET['course_id'])) {
    die('Course ID is required.');
}

$course_id = $_GET['course_id']; // Guarda el ID del curso
$courses = $controller->getCoursesForLoggedUser(); // Obtiene los cursos asignados al usuario actual

/*
 *  Busca el curso que corresponde al ID enviado
 *
 *  - Se recorre la lista de cursos.
 *  - Si se encuentra el curso, se guarda en la variable $course.
 */
$course = null;
foreach ($courses as $c) {
    if ($c['course_id'] === $course_id) {
        $course = $c;
        break;
    }
}

/*
 *  Verifica si el curso existe
 *
 *  - Si no se encuentra el curso, el programa termina y muestra un mensaje.
 */
if (!$course) {
    die('Course not found.');
}

/*
 *  Obtiene el departamento del usuario
 *
 *  - Si hay un error al obtenerlo, se guarda en la variable $error.
 */
try {
    $userDepartment = $controller->getUserDepartment();
} catch (Exception $e) {
    $error = $e->getMessage();
}

/*
 *  Procesa el formulario de edición
 *
 *  - Verifica si se envía una solicitud POST con el botón "edit".
 *  - Intenta actualizar los datos del curso.
 *  - Si todo está correcto, redirige a la lista de cursos.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    try {
        $controller->editCourse(
            $_POST['course_id'],
            $_POST['course_name'],
            $_POST['course_credits'],
            $_POST['course_desc'],
            $_SESSION['dept_id']
        );
        header("Location: courses.php"); // Redirige a la lista de cursos
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage(); // Guarda el mensaje de error
    }
}
?>

<main>
    <!-- Título de la página -->
    <h2 class="form-title">Edit Course</h2>

    <!-- div del formulario -->
    <div class="form-container">
        <!-- Muestra mensajes de error si hay alguno -->
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Formulario para editar el curso -->
        <form method="post" class="styled-form" accept-charset="UTF-8">
            <!-- Campo: ID del curso (Readonly) -->
            <label for="course_id">Course ID:</label>
            <input type="text" id="course_id" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>" readonly>

            <!-- Campo: Nombre del curso -->
            <label for="course_name">Course Name:</label>
            <input type="text" id="course_name" name="course_name" placeholder="Enter Course Name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>

            <!-- Campo: Créditos del curso -->
            <label for="course_credits">Credits:</label>
            <input type="number" id="course_credits" name="course_credits" placeholder="Enter Credits" value="<?php echo htmlspecialchars($course['course_credits']); ?>" required>

            <!-- Campo: Descripción del curso -->
            <label for="course_desc">Description:</label>
            <textarea id="course_desc" name="course_desc" placeholder="Enter Course Description" rows="4" required><?php echo htmlspecialchars($course['course_desc']); ?></textarea>

            <!-- Campo: Departamento del usuario (readonly) -->
            <label for="dept_id">Department:</label>
            <input type="text" id="dept_name" value="<?php echo htmlspecialchars($userDepartment); ?>" readonly>

            <!-- Botones para guardar o cancelar -->
            <div class="form-buttons">
                <button type="submit" name="edit" class="btn-submit">Save Changes</button>
                <button type="button" class="btn-cancel" onclick="location.href='courses.php'">Back</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
