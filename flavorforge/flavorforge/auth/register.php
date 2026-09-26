<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = "Your session expired. Please try again.";
    } else {
        $username = sanitize_input($_POST['username']);
        $email    = sanitize_input($_POST['email']);
        $password = $_POST['password'];

        if (empty($username) || empty($email) || empty($password)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = "Registration successful! You can now <a href='login.php'>Login</a>.";
            } else {
                $error = "Username or Email already exists.";
            }
            $stmt->close();
        }
    }
}
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body p-4">
            <h3 class="card-title text-center mb-4">Create an Account</h3>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form id="registerForm" action="register.php" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="regUsername" required>
                    <div class="invalid-feedback">Username must be at least 3 characters.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="regEmail" required>
                    <div class="invalid-feedback">Please provide a valid email.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="regPassword" required>
                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                </div>
                <button type="submit" class="btn btn-warning w-100 fw-bold">Register</button>
            </form>
            <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>