<?php
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlavorForge - Digital Recipe Book</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/flavorforge/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-warning" href="/flavorforge/index.php">
        <i class="fa-solid fa-utensils me-2"></i>FlavorForge
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/flavorforge/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/flavorforge/recipes.php">Explore Recipes</a></li>
        <li class="nav-item"><a class="nav-link" href="/flavorforge/contact.php">Contact Us</a></li>
      </ul>
      <div class="d-flex align-items-center">
        <?php if(isLoggedIn()): ?>
            <a href="/flavorforge/dashboard.php" class="btn btn-outline-warning btn-sm me-2">
                <i class="fa-solid fa-user me-1"></i> Dashboard (<?php echo htmlspecialchars($_SESSION['username']); ?>)
            </a>
            <a href="/flavorforge/auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
        <?php else: ?>
            <a href="/flavorforge/auth/login.php" class="btn btn-outline-light btn-sm me-2">Login</a>
            <a href="/flavorforge/auth/register.php" class="btn btn-warning btn-sm">Sign Up</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>