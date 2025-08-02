<?php
/*
 * departments.php
 *
 * - Muestra una lista de los departamentos almacenados en la base de datos.
 * - Permite a los administradores agregar, editar o eliminar departamentos.
 * - La opción de eliminar verifica si el departamento tiene cursos activos.
 */

include '../includes/header.php';
require_once '../controllers/departmentsController.php';

$controller = new DepartmentsController(); // Instancia del controlador de departamentos

/*
 * Verifica si se envió una solicitud POST para eliminar un departamento.
 * - Llama al método "deleteDepartment" del controlador.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $controller->deleteDepartment($_POST['dept_id']);
}

// Obtiene la lista de departamentos
$departments = $controller->listDepartments();
?>

<main>
    <!-- Título de la página -->
    <h2>Departments</h2>
    
    <!-- Tabla para mostrar los departamentos -->
    <table id="tblDepartments">
        <thead>
            <tr>
                <th scope="col">Department ID</th>
                <th scope="col">Department Name</th>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <th scope="col">Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <!-- Itera sobre los departamentos y muestra sus datos -->
            <?php foreach ($departments as $department): ?>
                <tr>
                    <td><?php echo htmlspecialchars($department['dept_id']); ?></td>
                    <td><?php echo htmlspecialchars($department['dept_name']); ?></td>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <td>                 
                        <!-- Botón para editar un departamento -->
                        <form method="get" action="edit_departments.php" style="display:inline;">
                            <input type="hidden" name="dept_id" value="<?php echo htmlspecialchars($department['dept_id']); ?>">
                            <button type="submit">Edit</button>
                        </form>
                        
                        <!-- Botón para eliminar un departamento -->
                        <form method="post" style="display:inline;" onsubmit="return confirmDelete();">
                            <input type="hidden" name="dept_id" value="<?php echo htmlspecialchars($department['dept_id']); ?>">
                            <button id="deleteButton" type="submit" name="delete"
                            <?php if ($controller->departmentActiveCourses($department['dept_id'])): ?>
                                disabled
                            <?php endif; ?>>Delete</button>
                        </form> 
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Botón para agregar un nuevo departamento (solo pueden usarlo los administradores) -->
    <form method="get" action="add_departments.php" style="margin-bottom:20px;">
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <button type="submit">Add New Department</button>
        <?php endif; ?>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
