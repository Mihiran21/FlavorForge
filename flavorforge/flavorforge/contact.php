<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $msg = '<div class="alert alert-danger">Your session expired. Please try again.</div>';
    } else {
        $name    = sanitize_input($_POST['name']);
        $email   = sanitize_input($_POST['email']);
        $message = sanitize_input($_POST['message']);

        if (!empty($name) && !empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $message);
            if ($stmt->execute()) {
                $msg = '<div class="alert alert-success">Thank you! Your message has been saved.</div>';
            } else {
                $msg = '<div class="alert alert-danger">Database error. Please try again.</div>';
            }
            $stmt->close();
        } else {
            $msg = '<div class="alert alert-warning">Please fill in all fields with a valid email.</div>';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container mt-5" style="max-width: 600px;">
    <h2 class="text-center mb-4 fw-bold">Contact Us</h2>
    <?php echo $msg; ?>
    <form id="contactForm" action="contact.php" method="POST" class="shadow p-4 rounded bg-white" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" id="contactName" name="name" class="form-control" required>
            <div class="invalid-feedback">Name is required.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" id="contactEmail" name="email" class="form-control" required>
            <div class="invalid-feedback">Please provide a valid email.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea id="contactMessage" name="message" class="form-control" rows="4" required></textarea>
            <div class="invalid-feedback">Message cannot be empty.</div>
        </div>
        <button type="submit" class="btn btn-warning w-100 fw-bold">Send Message</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>