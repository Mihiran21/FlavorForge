<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Cleans up raw input for storage. Does NOT escape HTML — escaping is an
 * output concern, not an input concern. Always call htmlspecialchars()
 * (or nl2br(htmlspecialchars())) when you print a value back into HTML,
 * even if it already passed through this function.
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Returns the current CSRF token, generating one if none exists yet.
 * Call this when rendering a form: 
 *   <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifies a submitted CSRF token against the one stored in the session.
 * Call this at the top of every POST handler:
 *   if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { ... reject ... }
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token'])
        && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Very lightweight session-based login throttle. Good enough to slow down
 * casual brute-forcing on a small site; for anything higher-stakes, track
 * attempts per-IP/per-account in the database instead of in the session,
 * since a session resets whenever the attacker clears cookies.
 */
function login_is_locked_out($max_attempts = 5, $lockout_seconds = 60) {
    $attempts = $_SESSION['login_attempts'] ?? 0;
    $last     = $_SESSION['login_last_attempt'] ?? 0;

    if ($attempts >= $max_attempts && (time() - $last) < $lockout_seconds) {
        return $lockout_seconds - (time() - $last); // seconds remaining
    }
    if ($attempts >= $max_attempts) {
        // lockout window has passed — reset
        $_SESSION['login_attempts'] = 0;
    }
    return 0;
}

function login_record_failure() {
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    $_SESSION['login_last_attempt'] = time();
}

function login_reset_attempts() {
    unset($_SESSION['login_attempts'], $_SESSION['login_last_attempt']);
}
?>