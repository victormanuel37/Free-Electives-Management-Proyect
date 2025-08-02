<?php
/*
 *  index.php
 *
 * - Muestra a los usuarios la lista de cursos activos para el término actual.
 * - Los datos se obtienen a través de los controladores de term_offeringController y coursesController.
 */

include '../includes/indexHeader.php';
require_once '../controllers/term_offeringController.php';
require_once '../controllers/coursesController.php';

// Instancias de los controladores
$termOfferingController = new TermOfferingController();
$coursesController = new CoursesController();

// Obtiene la lista de cursos activos y el término activo
$activeCourses = $termOfferingController->listTermActive();
$activeTerm = $termOfferingController->getActiveTerm();

// Añade los prerrequisitos a cada curso activo que tenga prerrequisitos
$coursesWithPrerequisites = [];
foreach ($activeCourses as $course) {
    $prerequisites = $coursesController->getPrerequisitesForCourse($course['course_id']);
    $course['prerequisites'] = $prerequisites;
    $coursesWithPrerequisites[] = $course;
}
?>

<main>
    <!-- Título de la página con el término activo -->
    <h2>Cursos Disponibles (<?php echo htmlspecialchars($activeTerm['term_id']); ?>)</h2>

    <!-- Tabla para mostrar la lista de cursos -->
    <table id="tblCourses">
        <thead>
            <tr>
                <th>ID del Curso</th>
                <th>Nombre</th>
                <th>Créditos</th>
                <th>Descripción</th>
                <th>Departamento</th>
            </tr>
        </thead>
        <tbody>
            <!-- Itera sobre los cursos y los imprime en la tabla -->
            <?php foreach ($coursesWithPrerequisites as $course): ?>
                <tr>
                    <!-- ID del curso -->
                    <td><?= htmlspecialchars($course['course_id']); ?></td>

                    <!-- Nombre del curso con sus prerrequisitos si estos existen -->
                    <td>
                        <?= htmlspecialchars($course['course_name']); ?>
                        <?php if (!empty($course['prerequisites'])): ?>
                            <br><small class="prereq-text">Pre-req: <?= htmlspecialchars($course['prerequisites']); ?></small>
                        <?php endif; ?>
                    </td>

                    <!-- Créditos del curso -->
                    <td><?= htmlspecialchars($course['course_credits']); ?></td>

                    <!-- Descripción del curso -->
                    <td>
                        <span id="<?= htmlspecialchars($course['course_id']); ?>_desc" 
                                class="course_desc" 
                                title="<?= htmlspecialchars($course['course_desc']); ?>">ⓘ
                        </span>
                    </td>

                    <!-- Departamento al que pertenece el curso -->
                    <td><?= htmlspecialchars($course['dept_name']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>


<?php include '../includes/footer.php'; ?>
</body>
</html>
