<?php
include '../includes/header.php';
require_once '../controllers/coursesController.php';

$controller = new CoursesController();
$courses = $controller->getAllCourses(); 
$error = "";


$course_id = $_GET['course_id'] ?? null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_prerequisite'])) {
    try {
        $controller->addPrerequisite($_POST['course_id'], $_POST['prerequisite_id']);
        header("Location: courses.php"); 
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<main>
    <h2 class="form-title">Add Prerequisite</h2>
    <div class="form-container">
        <form method="post" class="styled-form" accept-charset="UTF-8">
            <label for="course_id">Course ID:</label>
            <input type="text" id="course_id" name="course_id" placeholder="Enter the Course ID" value="<?php echo htmlspecialchars($_GET['course_id'] ?? ''); ?>" readonly>

            <label for="prerequisite_id">Prerequisite ID:</label>
            <input type="text" id="prerequisite_id" name="prerequisite_id" placeholder="Enter Prerequisite ID (e.g., ADMI1234)" 
                pattern="^[A-Z]{4}[0-9]{4}$" title="Course ID must be 4 uppercase letters followed by 4 digits" required>

            <div class="form-buttons">
                <button type="submit" name="add_prerequisite" class="btn-submit">Add Prerequisite</button>
                <button type="button" class="btn-cancel" onclick="location.href='courses.php'">Back</button>
            </div>
        </form>
    </div>
</main>


<?php include '../includes/footer.php'; ?>
