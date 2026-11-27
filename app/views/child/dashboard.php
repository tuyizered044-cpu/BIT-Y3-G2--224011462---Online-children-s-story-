<?php
// app/views/child/dashboard.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php'; // Load Sidebar
?>

<div class="app-layout">
    <!-- Sidebar is rendered here -->

    <main class="app-content">
        <div style="margin-bottom: 2.5rem;">
            <h1 style="margin-bottom: 0.5rem; font-size: 2rem;">Hello, <?= sanitize($_SESSION['username']) ?>! 👋</h1>
            <p style="color: var(--text-light); font-size: 1.1rem;">Ready for a new adventure today?</p>
        </div>

        <!-- Stats -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 3rem;">
            <div style="background: var(--light-blue); padding: 2rem; border-radius: 20px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <h3 style="font-size: 3rem; color: var(--dark-purple); margin-bottom: 0.2rem;"><?= count($readings) ?></h3>
                <p style="color: var(--text-light); font-weight: 600;">Stories Started</p>
            </div>
            <div style="background: var(--soft-pink); padding: 2rem; border-radius: 20px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <h3 style="font-size: 3rem; color: var(--dark-purple); margin-bottom: 0.2rem;"><?= $completed ?></h3>
                <p style="color: var(--text-light); font-weight: 600;">Stories Completed</p>
            </div>
        </div>

        <!-- Recently Read -->
        <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem;">📚 Recently Read</h2>
        <div class="story-grid-4" style="margin-bottom: 3rem;">
            <?php if (empty($readings)): ?>
                <p style="color: var(--text-light); grid-column: 1 / -1;">You haven't read any stories yet. <a href="<?= BASE_URL ?>/index.php?page=stories" style="color: var(--primary-purple); font-weight: 700;">Explore stories now!</a></p>
            <?php else: ?>
                <?php foreach (array_slice($readings, 0, 4) as $r): ?>
                    <div class="story-card-modern">
                        <div class="card-content">
                            <h3><?= sanitize($r['title']) ?></h3>
                            <div style="margin: 1rem 0;">
                                <div style="width: 100%; height: 8px; background: var(--lavender); border-radius: 5px;">
                                    <div style="width: <?= $r['progress_pct'] ?>%; height: 100%; background: var(--primary-purple); border-radius: 5px;"></div>
                                </div>
                                <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.5rem; font-weight: 600;"><?= $r['progress_pct'] ?>% complete</p>
                            </div>
                            <a href="<?= BASE_URL ?>/index.php?page=story&id=<?= $r['story_id'] ?>" class="btn-read" style="display: block; text-align: center; width: 100%;">Continue Reading</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Recommended -->
        <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem;">✨ Recommended for You</h2>
        <div class="story-grid-4">
            <?php foreach (array_slice($recommended, 0, 4) as $index => $story): ?>
                <div class="story-card-modern">
                    <div class="card-image" style="background: <?= ['#f4a261', '#e76f51', '#2a9d8f', '#e9c46a'][$index % 4] ?>;">
                        <span class="age-badge"><?= sanitize($story['age_group']) ?> yrs</span>
                    </div>
                    <div class="card-content">
                        <h3><?= sanitize($story['title']) ?></h3>
                        <div class="card-footer">
                            <span class="category-tag">📖 <?= sanitize($story['category']) ?></span>
                            <a href="<?= BASE_URL ?>/index.php?page=story&id=<?= $story['story_id'] ?>" class="btn-read">Read</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

</body>
</html>