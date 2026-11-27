<?php
// app/includes/sidebar.php
$role = strtolower(trim((string) ($_SESSION['role'] ?? 'guest')));
?>
<aside class="app-sidebar">
    <div class="sidebar-brand">
        <span>📖</span> <?= APP_NAME ?>
    </div>
    
    <ul class="sidebar-nav">
        <!-- Common Link to Home -->
        <li><a href="<?= BASE_URL ?>/index.php"><span>🏠</span> Back to Site</a></li>
        <hr style="border: none; border-top: 1px solid var(--lavender); margin: 0.5rem 0;">

        <!-- Role-Specific Links -->
        <?php if ($role === 'admin'): ?>
            <li><a href="<?= BASE_URL ?>/index.php?page=admin-dashboard"><span>▦</span> Admin Overview</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=admin-dashboard#users"><span>♙</span> Manage Users</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=admin-dashboard#stories"><span>▤</span> Manage Stories</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=manage-categories"><span>◇</span> Categories</a></li>

        <?php elseif ($role === 'writer'): ?>
            <li><a href="<?= BASE_URL ?>/index.php?page=dashboard"><span>▦</span> Writer Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=create-story"><span>✍️</span> Write New Story</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=stories"><span>📚</span> Public Library</a></li>

        <?php elseif ($role === 'child'): ?>
            <li><a href="<?= BASE_URL ?>/index.php?page=dashboard"><span>🏠</span> My Home</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=stories"><span>📚</span> Read Stories</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=categories"><span>🏷️</span> Explore Topics</a></li>

        <?php elseif ($role === 'parent'): ?>
            <li><a href="<?= BASE_URL ?>/index.php?page=dashboard"><span>📊</span> Overview</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=stories"><span>📚</span> Story Library</a></li>
        <?php endif; ?>

        <hr style="border: none; border-top: 1px solid var(--lavender); margin: 0.5rem 0;">
        
        <!-- Profile & Logout -->
        <li><a href="<?= BASE_URL ?>/index.php?page=profile"><span>⚙️</span> My Profile</a></li>
        <li>
            <form method="POST" action="<?= BASE_URL ?>/index.php?page=logout" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <button type="submit" class="sidebar-logout"><span>🚪</span> Logout</button>
            </form>
        </li>
    </ul>
</aside>