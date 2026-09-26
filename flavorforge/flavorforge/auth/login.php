<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $wait = login_is_locked_out();

    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = "Your session expired. Please try again.";
    } elseif ($wait > 0) {
        $error = "Too many failed attempts. Please try again in {$wait} seconds.";
    } else {
        $email    = sanitize_input($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $error = "Please fill in all fields.";
        } else {
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->num_rows === 1 ? $result->fetch_assoc() : null;
            $stmt->close();

            // Same generic message whether the email doesn't exist or the
            // password is wrong — don't tell an attacker which one it was.
            if ($user && password_verify($password, $user['password'])) {
                login_reset_attempts();
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: /flavorforge/dashboard.php");
                exit;
            } else {
                login_record_failure();
                $error = "Invalid email or password.";
            }
        }
    }
}
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body p-4">
            <h3 class="card-title text-center mb-4">User Login</h3>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning w-100 fw-bold">Login</button>
            </form>
            <p class="text-center mt-3">Don't have an account? <a href="register.php">Sign Up</a></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>