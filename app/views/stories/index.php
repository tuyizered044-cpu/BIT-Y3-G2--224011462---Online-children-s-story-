<?php
// app/views/stories/index.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/navbar.php';

$isParent = isLoggedIn() && $_SESSION['role'] === 'parent';
?>

<section class="stories-section">
    <h1 class="section-title">All Stories</h1>

    <!-- Search and Filters (Hidden for Parents) -->
    <?php if (!$isParent): ?>
    <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 2rem; flex-wrap: wrap;">
        <input type="text" id="live-search" placeholder="Search stories..." style="padding: 0.8rem; border-radius: 20px; border: 1px solid #ccc; width: 300px;">
        <select id="filter-age" style="padding: 0.8rem; border-radius: 20px; border: 1px solid #ccc;">
            <option value="">All Ages</option>
            <option value="3-5">3-5 years</option>
            <option value="6-8">6-8 years</option>
            <option value="9-12">9-12 years</option>
            <option value="13+">13+ years</option>
        </select>
    </div>
    <?php endif; ?>

    <div id="story-results" class="story-grid">
        <?php if (empty($stories)): ?>
            <p style="text-align: center; width: 100%; color: var(--text-light);">No stories found.</p>
        <?php else: ?>
            <?php foreach ($stories as $index => $story): ?>
                <div class="story-card">
                    <div class="story-card-img">
                        <!-- Check if thumbnail exists, otherwise fallback to gradient -->
                        <?php if (!empty($story['thumbnail'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/stories/<?= sanitize($story['thumbnail']) ?>" alt="<?= sanitize($story['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width:100%; height:100%; background: <?= ['linear-gradient(to bottom, #87CEEB, #90EE90)', 'linear-gradient(to bottom, #191970, #483D8B)', 'linear-gradient(to bottom, #228B22, #556B2F)'][$index % 3] ?>;"></div>
                        <?php endif; ?>
                        <span class="age-badge"><?= sanitize($story['age_group']) ?></span>
                    </div>
                    <div class="story-card-content">
                        <h3><?= sanitize($story['title']) ?></h3>
                        <p style="color: var(--text-light); font-size: 0.9rem;">By <?= sanitize($story['author']) ?></p>
                        <div class="story-meta">
                            <span>📖 <?= sanitize($story['category']) ?></span>
                            
                            <!-- Button changes for parents -->
                            <?php if ($isParent): ?>
                                <button class="btn" style="padding: 0.4rem 1rem; font-size: 0.9rem; background: #eee; color: #888; cursor: not-allowed;" disabled title="Log in as a child to read">
                                    Child Only
                                </button>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/index.php?page=story&id=<?= $story['story_id'] ?>" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.9rem;">Read Story &rarr;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php if (!$isParent): ?>
<script src="<?= BASE_URL ?>/assets/js/search.js"></script>
<?php endif; ?>
<?php require_once BASE_PATH . '/app/includes/footer.php'; ?>