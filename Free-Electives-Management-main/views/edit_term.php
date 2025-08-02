<?php
include '../includes/header.php';
require_once '../controllers/termsController.php';

$controller = new TermsController();
$error = "";
$term = null;

// Obtiene el termino basado en term_id desde GET
if (isset($_GET['term_id'])) {
    $term_id = $_GET['term_id'];
    $term = $controller->getTermById($term_id);

    if (!$term) {
        $error = "Term not found.";
    }
} else {
    $error = "No term selected.";
}

// Maneja la actualizacion del termino cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $term_desc = $_POST['term_desc'];

        // Actualiza la descripcion del termino
        $controller->editTerm($term['term_id'], $term_desc, $term['term_is_active']);
        header("Location: terms.php");
        exit();
    } catch (Exception $e) {
        // Captura errores y los asigna a la variable
        $error = $e->getMessage();
    }
}
?>

<main>
    <div class="form-container styled-form">
        <h2 class="form-title">Edit Term</h2>

        <!-- Muestra el mensaje de error si existe -->
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <!-- Form para editar el termino -->
            <form method="post">
                <label for="term_id">Term ID:</label>
                <!-- Campo de solo lectura para el id del termino -->
                <input type="text" id="term_id" name="term_id" value="<?php echo htmlspecialchars($term['term_id']); ?>" readonly>

                <label for="term_desc">Description:</label>
                <!-- Campo de texto para editar la descripcion -->
                <textarea id="term_desc" name="term_desc" rows="4" required><?php echo htmlspecialchars($term['term_desc']); ?></textarea>

                <label for="term_is_active">Status:</label>
                <!-- Campo de solo lectura para mostrar el estado actual -->
                <input type="text" id="term_is_active" value="<?php echo $term['term_is_active'] ? 'Active' : 'Inactive'; ?>" readonly>

                <!-- Botones de accion -->
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">Save Changes</button>
                    <button type="button" class="btn-cancel" onclick="location.href='terms.php'">Back</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
