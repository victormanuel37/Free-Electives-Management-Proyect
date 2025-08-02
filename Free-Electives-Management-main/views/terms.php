<?php
include '../includes/header.php';
require_once '../controllers/termsController.php';

$controller = new TermsController();
$error = $controller->handleRequest();
$terms = $controller->getAllTerms();
?>

<main>
    <h2>Academic Terms</h2>

    <!-- Muestra un mensaje de error si existe -->
    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Term ID</th>
                <th>Description</th>
                <th>Active</th>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <!-- Recorre y muestra cada termino -->
            <?php foreach ($terms as $term): ?>
                <tr>
                    <td><?php echo htmlspecialchars($term['term_id']); ?></td>
                    <td><?php echo htmlspecialchars($term['term_desc']); ?></td>
                    <td><?php echo $term['term_is_active'] ? 'Yes' : 'No'; ?></td>

                    <!-- Acciones disponibles solo si el rol es administrador -->
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <td>
                            <!-- Boton para activar un termino si no esta activo -->
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="term_id" value="<?php echo htmlspecialchars($term['term_id']); ?>">
                                <button type="submit" name="set_active" 
                                    <?php echo $term['term_is_active'] ? 'disabled' : ''; ?>>
                                    Set Active
                                </button>
                            </form>

                            <!-- Boton para editar un termino -->
                            <form method="get" action="edit_term.php" style="display: inline;">
                                <input type="hidden" name="term_id" value="<?php echo htmlspecialchars($term['term_id']); ?>">
                                <button type="submit">Edit</button>
                            </form>

                            <!-- Boton para eliminar un termino, deshabilitado si esta activo -->
                            <form method="post" style="display: inline;" onsubmit="return confirmDelete();">
                                <input type="hidden" name="term_id" value="<?php echo htmlspecialchars($term['term_id']); ?>">
                                <button id="deleteButton" type="submit" name="delete" 
                                    <?php echo $term['term_is_active'] ? 'disabled' : ''; ?>>
                                    Delete
                                </button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table> 

    <!-- Boton para agregar un nuevo termino, solo administradores -->
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <form method="get" action="add_term.php" style="margin-bottom: 20px;">
            <button type="submit">Add New Term</button>
        </form>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
