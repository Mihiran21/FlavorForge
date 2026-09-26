<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero / Carousel Section -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" style="height: 450px;">
      <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=1200&q=80" class="d-block w-100 h-100 object-fit-cover" alt="Cooking">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
        <h1>Master the Art of Cooking</h1>
        <p>Explore thousands of delicious recipes crafted by food lovers worldwide.</p>
        <a href="#featured" class="btn btn-warning smooth-scroll">Explore Recipes</a>
      </div>
    </div>
    <div class="carousel-item" style="height: 450px;">
      <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80" class="d-block w-100 h-100 object-fit-cover" alt="Delicious Food">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
        <h1>Share Your Culinary Creations</h1>
        <p>Join our active community and save your personalized recipe book online.</p>
        <a href="auth/register.php" class="btn btn-warning">Get Started</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- Featured Section -->
<div class="container mt-5" id="featured">
    <h2 class="text-center mb-4 fw-bold">Recent Culinary Delights</h2>
    <div class="row g-4">
        <?php
        $query = "SELECT recipes.*, users.username FROM recipes JOIN users ON recipes.user_id = users.id ORDER BY recipes.id DESC LIMIT 3";
        $result = $conn->query($query);

        if($result->num_rows > 0):
            while($row = $result->fetch_assoc()):
        ?>
            <div class="col-md-4 fade-in">
                <div class="card h-100 shadow-sm recipe-card">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Recipe">
                    <div class="card-body">
                        <span class="badge bg-warning text-dark mb-2"><?php echo htmlspecialchars($row['category']); ?></span>
                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="text-muted small"><i class="fa-regular fa-clock me-1"></i><?php echo $row['cooking_time']; ?> mins | By <?php echo htmlspecialchars($row['username']); ?></p>
                        <button class="btn btn-outline-dark btn-sm w-100" data-bs-toggle="modal" data-bs-target="#recipeModal<?php echo $row['id']; ?>">View Recipe</button>
                    </div>
                </div>
            </div>

            <!-- Modal for Recipe Details -->
            <div class="modal fade" id="recipeModal<?php echo $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h6><strong>Ingredients:</strong></h6>
                            <p><?php echo nl2br(htmlspecialchars($row['ingredients'])); ?></p>
                            <h6><strong>Instructions:</strong></h6>
                            <p><?php echo nl2br(htmlspecialchars($row['instructions'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            endwhile;
        endif; 
        ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>