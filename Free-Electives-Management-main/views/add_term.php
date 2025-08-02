<?php
include '../includes/header.php';
require_once '../controllers/termsController.php';

$controller = new TermsController();
$error = "";

// Procesa el form cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    try {
        // Agrega un nuevo termino con los datos dados
        $controller->addTerm($_POST['term_id'], $_POST['term_desc'], $_POST['is_active'] ?? 0);
        header("Location: terms.php"); 
        exit();
    } catch (Exception $e) {
        // asigna error a la variable
        $error = $e->getMessage();
    }
}
?>

<main>
    <div class="form-container">
        <h2 class="form-title">Add Term</h2>

        <!-- Muestra el mensaje de error -->
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Formulario para agregar un nuevo termino -->
        <form method="post" class="styled-form">
            <label for="term_id">Term ID:</label>
            <!-- ingresar el ID del termino -->
            <input type="text" id="term_id" name="term_id" placeholder="Enter Term ID (B91)" required>

            <label for="term_desc">Term Description:</label>
            <!-- ingresar la descripcion del termino -->
            <input type="text" id="term_desc" name="term_desc" placeholder="Enter Term Description" required>

            <!-- Botones de accion -->
            <div class="form-buttons">
                <button type="submit" class="btn-submit" name="add">Add Term</button>
                <button type="button" class="btn-cancel" onclick="location.href='terms.php'">Back</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
