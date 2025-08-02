<?php
/*  
 *  - Este archivo maneja la lista de cursos.
 *  - Permite realizar acciones como agregar, editar, eliminar cursos y eliminar prerrequisitos.
 *  - Filtra las acciones en base al rol del usuario.
 */

include '../includes/header.php';
require_once '../controllers/coursesController.php';
require_once '../controllers/term_offeringController.php';

/*  
 *  Inicialización de controladores 
 *  
 *  Se crean instancias de los controladores para manejar cursos y términos.
 */
$controller = new CoursesController();
$controllers = new TermOfferingController();
$activeTerm = $controllers->getActiveTerm();

/*
 *  Elimina un curso si se envía la acción "delete" mediante POST.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $controller->deleteCourse($_POST['course_id']);
}

/*
 *  Elimina un prerrequisito de un curso si se envía la acción "delete_prerequisite" mediante POST.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_prerequisite'])) {
    try {
        $controller->deletePrerequisite($_POST['course_id'], $_POST['prerequisite_id']);
        header("Location: courses.php");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

/*  
 *  Obtiene la lista de cursos disponibles
 */
$courses = $controller->listCourses();
?>

<main>
    <!--  
     *  Encabezado principal
     *  
     *  Muestra el título de la página con el término activo.
     -->
    <h2>Courses (<?php echo htmlspecialchars($activeTerm['term_id']); ?>)</h2>

    <!--  
     *  Tabla de cursos
     *  
     *  - Imprime los cursos en una tabla con las acciones permitidas para cada curso.
     *  - Incluye botones de agregar, editar y eliminar cursos.
     -->
    <table id="tblCourses">
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Credits</th>
                <th>Description</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($courses as $course): ?>
            <tr>
                <!-- ID del curso -->
                <td><?php echo htmlspecialchars($course['course_id']); ?></td>

                <!-- Nombre del curso y prerrequisitos -->
                <td>
                    <?php echo htmlspecialchars($course['course_name']); ?>
                    <?php if (!empty($course['prerequisites'])): ?>
                        <br>
                        <small>
                            Pre-req:
                            <?php foreach (explode(', ', $course['prerequisites']) as $prerequisite): ?>
                                <?php echo htmlspecialchars($prerequisite); ?>
                                <!-- Botón para eliminar prerrequisitos -->
                                <form method="post" style="display:inline;" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
                                    <input type="hidden" name="prerequisite_id" value="<?php echo htmlspecialchars($prerequisite); ?>">
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <button class="btn-small" type="submit" name="delete_prerequisite">X</button>
                                    <?php endif; ?>
                                </form>
                            <?php endforeach; ?>
                        </small>
                    <?php endif; ?>
                </td>

                <!-- Créditos del curso -->
                <td><?php echo htmlspecialchars($course['course_credits']); ?></td>

                <!-- Descripción del curso -->
                <td>
                    <span id="<?php echo htmlspecialchars($course['course_id']); ?>_desc" 
                        class="course_desc" 
                        title="<?php echo htmlspecialchars($course['course_desc']); ?>">ⓘ</span>
                </td>

                <!-- Departamento del curso -->
                <td><?php echo htmlspecialchars($course['dept_name']); ?></td>

                <!-- Acciones: agregar prerrequisitos, editar o eliminar el curso -->
                <td>
                    <!-- Formulario para agregar prerrequisitos -->
                    <form method="get" action="add_prerequisites.php" style="display:inline;">
                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
                        <button type="submit"
                        <?php if ($_SESSION['role'] !== 'admin' && $course['dept_id'] !== $_SESSION['dept_id']): ?>disabled<?php endif; ?>>Add Prerequisite</button>
                    </form>

                    <!-- Formulario para editar curso -->
                    <form method="get" action="edit_courses.php" style="display:inline;">
                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
                        <button type="submit"
                        <?php if ($_SESSION['role'] !== 'admin' && $course['dept_id'] !== $_SESSION['dept_id']): ?>disabled<?php endif; ?>>Edit</button>
                    </form>

                    <!-- Formulario para eliminar curso -->
                    <form method="post" style="display:inline;" onsubmit="return confirmDelete();">
                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
                        <button id="deleteButton" type="submit" name="delete"
                        <?php if (
                                $course['linked_to_offering'] > 0 || 
                                ($_SESSION['role'] !== 'admin' && $course['dept_id'] !== $_SESSION['dept_id'])
                            ): ?>
                                disabled
                            <?php endif; ?>>Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Botón para agregar un nuevo curso -->
    <form method="get" action="add_courses.php" style="margin-bottom:20px;">
        <button type="submit">Add New Course</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
