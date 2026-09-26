<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mt-4">
    <h2 class="text-center mb-4 fw-bold">Recipe Collection</h2>

    <!-- Dynamic JavaScript Search & Filter Controls -->
    <div class="row mb-4">
        <div class="col-md-6 mb-2">
            <input type="text" id="recipeSearch" class="form-control" placeholder="Search recipes by title or ingredients...">
        </div>
        <div class="col-md-6 mb-2">
            <select id="categoryFilter" class="form-select">
                <option value="All">All Categories</option>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
                <option value="Dessert">Dessert</option>
            </select>
        </div>
    </div>

    <!-- Recipe Display Grid -->
    <div class="row g-4" id="recipeContainer">
        <?php
        $sql = "SELECT recipes.*, users.username FROM recipes JOIN users ON recipes.user_id = users.id ORDER BY id DESC";
        $res = $conn->query($sql);

        while($row = $res->fetch_assoc()):
        ?>
            <div class="col-md-4 recipe-item" data-category="<?php echo htmlspecialchars($row['category']); ?>">
                <div class="card h-100 shadow-sm recipe-card">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Recipe">
                    <div class="card-body">
                        <span class="badge bg-warning text-dark mb-2"><?php echo htmlspecialchars($row['category']); ?></span>
                        <h5 class="card-title recipe-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="text-muted small"><i class="fa-regular fa-clock me-1"></i><?php echo $row['cooking_time']; ?> mins | By <?php echo htmlspecialchars($row['username']); ?></p>
                        <p class="recipe-ingredients d-none"><?php echo htmlspecialchars($row['ingredients']); ?></p>
                        
                        <button class="btn btn-outline-dark btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id']; ?>">View Details</button>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modal<?php echo $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h6><strong>Ingredients:</strong></h6>
                            <p><?php echo nl2br(htmlspecialchars($row['ingredients'])); ?></p>
                            <hr>
                            <h6><strong>Instructions:</strong></h6>
                            <p><?php echo nl2br(htmlspecialchars($row['instructions'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>