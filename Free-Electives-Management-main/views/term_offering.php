<?php
include '../includes/header.php';
require_once '../controllers/term_offeringController.php';

$controller = new TermOfferingController();
$activeTerm = $controller->getActiveTerm();

// Maneja la eliminacion de una oferta de termino
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    try {
        // Elimina una oferta de termino segun term_id y course_id
        $controller->deleteTermOffering($_POST['term_id'], $_POST['course_id']);
    } catch (Exception $e) {
        // Captura el error y lo asigna a la variable
        $error = $e->getMessage();
    }
}

// Obtiene la lista de ofertas activas del termino
$offerings = $controller->listTermActive();
?>

<main>
    <h2>Term Offerings (<?php echo htmlspecialchars($activeTerm['term_id']); ?>)</h2>
    <table>
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Recorre y muestra cada oferta de termino -->
            <?php foreach ($offerings as $offering): ?>
                <tr>
                    <td><?php echo htmlspecialchars($offering['course_id']); ?></td>
                    <td><?php echo htmlspecialchars($offering['course_name']); ?></td>
                    <td>
                        <!-- Form para eliminar una oferta de curso -->
                        <form method="post" style="display:inline;" onsubmit="return confirmDelete();">
                            <input type="hidden" name="term_id" value="<?php echo htmlspecialchars($offering['term_id']); ?>">
                            <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($offering['course_id']); ?>">

                            <!-- Boton de eliminar, deshabilitado si no tiene permisos -->
                            <button id="deleteButton" type="submit" name="delete"
                            <?php if ($_SESSION['role'] !== 'admin' && $offering['dept_id'] !== $_SESSION['dept_id']): ?>
                                disabled
                            <?php endif; ?>>
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Boton para agregar una nueva oferta -->
    <form method="get" action="add_term_offering.php" style="margin-bottom:20px;">
        <button type="submit">Add New Offering</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
