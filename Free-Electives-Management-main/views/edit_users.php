<?php
include '../includes/header.php';
require_once '../controllers/usersController.php';

$controller = new UsersController();

// Verifica que se proporcione el nombre de usuario
if (!isset($_GET['username'])) {
    die('Username is required.');
}

$username = $_GET['username'];
$user = null;

// Busca el usuario por el nombre proporcionado
foreach ($controller->listUsers() as $u) {
    if ($u['username'] === $username) {
        $user = $u;
        break;
    }
}

// Si el usuario no existe, termina la ejecucion
if (!$user) {
    die('User not found.');
}

// Maneja la actualizacion del usuario cuando se envía el form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    // Actualiza la informacion del usuario
    $controller->editUser($username, $_POST['name'], $_POST['role'], $_POST['dept_id']);
    header("Location: users.php");
    exit();
}
?>

<main>
    <div class="form-container">
        <h2 class="form-title">Edit User</h2>

        <!-- Form para editar la informacion del usuario -->
        <form method="post" class="styled-form">
            <label for="name">Full Name:</label>
            <!-- Campo para editar el nombre completo -->
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label for="role">Role:</label>
            <!-- Lista desplegable para seleccionar el rol -->
            <select id="role" name="role" required>
                <option value="<?php echo htmlspecialchars($user['role']); ?>"><?php echo htmlspecialchars($user['role']); ?></option>
                <option value="admin">admin</option>            
                <option value="chair">chair</option>
                <option value="coordinator">coordinator</option>
            </select>

            <label for="dept_id">Department:</label>
            <!-- Lista desplegable para seleccionar el departamento -->
            <select id="dept_id" name="dept_id" required>
                <option value="">Select Department</option>
                <?php foreach ($controller->getDepartments() as $department): ?>
                    <option value="<?php echo htmlspecialchars($department['dept_id']); ?>">
                        <?php echo htmlspecialchars($department['dept_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Botones de accion -->
            <div class="form-buttons">
                <button type="submit" class="btn-submit" name="edit">Save Changes</button>
                <button type="button" class="btn-cancel" onclick="location.href='users.php'">Back</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
