<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

if (!isLoggedIn()) {
    header("Location: /flavorforge/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$status = '';
$allowed_categories = ['Breakfast', 'Lunch', 'Dinner', 'Dessert'];
$default_image = 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?auto=format&fit=crop&w=600&q=80';

// Handle Recipe Creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_recipe'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $status = '<div class="alert alert-danger">Your session expired. Please try again.</div>';
    } else {
        $title        = sanitize_input($_POST['title']);
        $category     = sanitize_input($_POST['category']);
        $cooking_time = (int)$_POST['cooking_time'];
        $ingredients  = sanitize_input($_POST['ingredients']);
        $instructions = sanitize_input($_POST['instructions']);
        $image_url    = trim($_POST['image_url']);

        if (!in_array($category, $allowed_categories, true)) {
            $category = 'Dinner';
        }

        // Only accept a genuinely valid http(s) URL; otherwise fall back to
        // the default image rather than storing whatever was submitted.
        if (empty($image_url)
            || !filter_var($image_url, FILTER_VALIDATE_URL)
            || !preg_match('/^https?:\/\//i', $image_url)) {
            $image_url = $default_image;
        }

        if (empty($title) || empty($ingredients) || empty($instructions) || $cooking_time <= 0) {
            $status = '<div class="alert alert-warning">Please fill in all required fields.</div>';
        } else {
            $stmt = $conn->prepare("INSERT INTO recipes (user_id, title, category, cooking_time, ingredients, instructions, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ississs", $user_id, $title, $category, $cooking_time, $ingredients, $instructions, $image_url);

            if ($stmt->execute()) {
                $status = '<div class="alert alert-success">Recipe added successfully!</div>';
            } else {
                $status = '<div class="alert alert-danger">Failed to add recipe.</div>';
            }
            $stmt->close();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container mt-4">
    <h2>Welcome back, <span class="text-warning"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!</h2>
    <?php echo $status; ?>

    <div class="row mt-4">
        <!-- Add New Recipe Form -->
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning fw-bold">Add a New Recipe</div>
                <div class="card-body">
                    <form action="dashboard.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <div class="mb-2">
                            <label class="form-label">Recipe Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>
                                <option value="Dessert">Dessert</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Cooking Time (minutes)</label>
                            <input type="number" name="cooking_time" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Image URL (Optional)</label>
                            <input type="url" name="image_url" class="form-control" placeholder="https://">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Ingredients</label>
                            <textarea name="ingredients" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Instructions</label>
                            <textarea name="instructions" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="add_recipe" class="btn btn-dark w-100">Publish Recipe</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- User's Existing Recipes -->
        <div class="col-md-7">
            <h4>Your Published Recipes</h4>
            <div class="list-group">
                <?php
                $stmt = $conn->prepare("SELECT * FROM recipes WHERE user_id = ? ORDER BY id DESC");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $res = $stmt->get_result();

                if ($res->num_rows > 0):
                    while($my_recipe = $res->fetch_assoc()):
                ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center mb-2 shadow-sm rounded">
                        <div>
                            <h5 class="mb-1"><?php echo htmlspecialchars($my_recipe['title']); ?></h5>
                            <small class="text-muted"><?php echo htmlspecialchars($my_recipe['category']); ?> | <?php echo (int)$my_recipe['cooking_time']; ?> mins</small>
                        </div>
                        <span class="badge bg-success rounded-pill">Active</span>
                    </div>
                <?php 
                    endwhile;
                else: 
                ?>
                    <p class="text-muted">You haven't added any recipes yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>