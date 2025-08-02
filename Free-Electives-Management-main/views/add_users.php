<?php
include '../includes/header.php';
require_once '../controllers/usersController.php';

$controller = new UsersController();
$error = "";

// Procesa el form cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    try {
        // Agrega un nuevo usuario con los datos proporcionados
        $controller->addUser($_POST['username'], $_POST['name'], $_POST['role'], $_POST['dept_id'], $_POST['password']);
        header("Location: users.php"); 
        exit();
    } catch (Exception $e) {
        // Captura el error y lo asigna a la variable
        $error = $e->getMessage();
    }
}
?>

<main>
    <div class="form-container">
        <h2 class="form-title">Add New User</h2>

        <!-- Muestra el mensaje de error -->
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Formulario para agregar un nuevo usuario -->
        <form method="post" class="styled-form">
            <label for="username">Username:</label>
            <!-- Campo para ingresar el nombre de usuario -->
            <input type="text" id="username" name="username" placeholder="Username" required>

            <label for="name">Full Name:</label>
            <!-- Campo para ingresar el nombre completo -->
            <input type="text" id="name" name="name" placeholder="Full Name" required>

            <label for="role">Role:</label>
            <!-- Lista desplegable para seleccionar el rol -->
            <select id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="chair">Chair</option>
                <option value="coordinator">Coordinator</option>
            </select>

            <label for="dept_id">Department:</label>
            <!-- Drop down para seleccionar el departamento -->
            <select id="dept_id" name="dept_id" required>
                <option value="">Select Department</option>
                <?php foreach ($controller->getDepartments() as $department): ?>
                    <option value="<?php echo htmlspecialchars($department['dept_id']); ?>">
                        <?php echo htmlspecialchars($department['dept_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="password">Password:</label>
            <!-- Campo para ingresar la contraseña -->
            <input type="password" id="password" name="password" placeholder="Password" required>

            <!-- Botones de accion -->
            <div class="form-buttons">
                <button type="submit" class="btn-submit" name="add">Add User</button>
                <button type="button" class="btn-cancel" onclick="location.href='users.php'">Back</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
