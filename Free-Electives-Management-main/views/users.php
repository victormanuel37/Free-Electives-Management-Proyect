<?php
include '../includes/header.php';
require_once '../controllers/usersController.php';

$controller = new UsersController();

// Maneja la eliminación de usuarios cuando se envía el form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $controller->deleteUser($_POST['username']);
}

// Obtiene la lista de usuarios
$users = $controller->listUsers();
?>

<main>
    <h2>Users</h2>  
    <table id="tblUsers">
        <thead>
            <tr>
                <th scope="col">Department</th>
                <th scope="col">Username</th>
                <th scope="col">Name</th>
                <th scope="col">Role</th>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <th scope="col">Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <!-- Recorre y muestra la lista de usuarios -->
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['department']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>

                    <!-- Acciones solo visibles para administradores -->
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <td>
                        <!-- Form para editar un usuario -->
                        <form method="get" action="edit_users.php" style="display:inline;">
                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                            <button type="submit">Edit</button>    
                        </form>

                        <!-- Form para eliminar un usuario -->
                        <form method="post" style="display:inline;" onsubmit="return confirmDelete();">
                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                            <button id="deleteButton" type="submit" name="delete">Delete</button>    
                        </form>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Botón para agregar un nuevo usuario, visible para administradores -->
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <form method="get" action="add_users.php" style="margin-bottom:20px;">
            <button type="submit">Add New User</button>
        </form>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
