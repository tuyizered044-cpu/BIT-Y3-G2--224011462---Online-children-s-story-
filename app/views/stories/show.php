<?php
// app/views/stories/show.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/navbar.php';
?>
<div style="max-width: 800px; margin: 3rem auto; padding: 2rem; background: var(--white); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: relative;">
    
    <div style="margin-bottom: 2rem; border-bottom: 2px solid var(--lavender); padding-bottom: 1rem;">
        <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;"><?= sanitize($story['title']) ?></h1>
        <p style="color: var(--text-light);">
            By <strong><?= sanitize($story['author']) ?></strong> | 
            Category: <?= sanitize($story['category']) ?> | 
            Age: <?= sanitize($story['age_group']) ?>
        </p>
    </div>

    <?php if (isLoggedIn() && $_SESSION['role'] === 'child'): ?>
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="font-weight: 600; color: var(--primary-purple);">Reading Progress</span>
            <span id="progress-text" style="font-weight: 600;">0%</span>
        </div>
        <div style="width: 100%; height: 12px; background: var(--lavender); border-radius: 10px; overflow: hidden;">
            <div id="progress-bar" style="width: 0%; height: 100%; background: var(--primary-purple); transition: width 0.3s;"></div>
        </div>
    </div>
    <?php endif; ?>

    <div id="story-content" style="font-size: 1.2rem; line-height: 1.8; color: var(--text-dark);">
        <?= nl2br(sanitize($story['body'])) ?>
    </div>

    <div style="margin-top: 3rem; text-align: center;">
        <a href="<?= BASE_URL ?>/index.php?page=stories" class="btn btn-outline" style="padding: 0.8rem 2rem;">&larr; Back to Stories</a>
    </div>
</div>

<?php if (isLoggedIn() && $_SESSION['role'] === 'child'): ?>
<script>
    window.storyId = <?= $story['story_id'] ?>;
</script>
<script src="<?= BASE_URL ?>/assets/js/reading-progress.js"></script>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/includes/footer.php'; ?>