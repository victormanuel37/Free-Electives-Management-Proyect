<?php include '../includes/header.php'; ?>

<!-- 
 * login.php
 *  
 * - Muestra el formulario de inicio de sesión para que el usuario ingrese sus credenciales.
 * - Envía los datos a "loginController.php"
 -->

<main>
    <!-- div principal del form -->
    <div class="form-container">
        <!-- Título del form -->
        <h2 class="form-title">Login</h2>

        <!-- form de inicio de sesión -->
        <form action="../controllers/loginController.php" method="POST" class="styled-form">
            <!-- Campo: Nombre de usuario -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <!-- Campo: Contraseña -->
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <!-- Botones del Form -->
            <div class="form-buttons">
                <button type="submit" class="btn-submit">Login</button>
                <button type="button" class="btn-cancel" onclick="location.href='../views/index.php'">Cancel</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
