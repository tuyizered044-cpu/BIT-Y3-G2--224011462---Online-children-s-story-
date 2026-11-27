<?php
// app/includes/navbar.php
$isParent = isLoggedIn() && $_SESSION['role'] === 'parent';
?>
<nav class="navbar">
    <div class="nav-left">
        <a href="<?= BASE_URL ?>/index.php" class="navbar-brand">
            <span style="font-size: 1.5rem;">📖</span> <?= APP_NAME ?>
        </a>
        
        <ul class="nav-links">
            <li><a href="<?= BASE_URL ?>/index.php">Home</a></li>
            <!-- Hide Stories link for parents -->
            <?php if (!$isParent): ?>
                <li><a href="<?= BASE_URL ?>/index.php?page=stories">Stories</a></li>
            <?php endif; ?>
            <li><a href="<?= BASE_URL ?>/index.php?page=categories">Categories</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=about">About</a></li>
        </ul>
    </div>

    <div class="nav-right">
        <!-- Hide Search bar for parents -->
        <?php if (!$isParent): ?>
            <form class="navbar-search" method="GET" action="<?= BASE_URL ?>/index.php" role="search">
                <input type="hidden" name="page" value="stories">
                <input type="search" name="search" placeholder="Search..." class="nav-search-input">
                <button class="btn btn-primary" type="submit" style="padding: 0.5rem 1rem; border-radius: 20px;">Search</button>
            </form>
        <?php endif; ?>

        <div class="nav-actions">
            <?php if (isLoggedIn()): ?>
                <span class="nav-welcome">Hi, <?= sanitize($_SESSION['username']) ?></span>
                <a href="<?= BASE_URL ?>/index.php?page=profile" class="btn-nav-outline">Profile</a>
                <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="btn-nav-outline">Dashboard</a>
                
                <form method="POST" action="<?= BASE_URL ?>/index.php?page=logout" style="display: inline; margin: 0;">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <button type="submit" class="btn-nav-primary">Logout</button>
                </form>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/index.php?page=login" class="btn-nav-outline">Login</a>
                <a href="<?= BASE_URL ?>/index.php?page=register" class="btn-nav-primary">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>