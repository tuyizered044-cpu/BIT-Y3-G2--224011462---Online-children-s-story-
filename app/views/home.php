<?php
// app/views/home.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/navbar.php';

$db = getDB();

// 1. Fetch Featured Stories
$stmt = $db->query("SELECT s.*, u.username as author, c.name as category FROM stories s JOIN users u ON s.author_id = u.user_id JOIN categories c ON s.category_id = c.category_id WHERE s.status = 'published' ORDER BY s.published_at DESC LIMIT 4");
$featuredStories = $stmt->fetchAll();

// 2. Fetch Categories
$stmt = $db->query("SELECT * FROM categories LIMIT 6");
$categories = $stmt->fetchAll();

// 3. Fetch Top Authors
$stmt = $db->query("SELECT u.username, COUNT(s.story_id) as story_count FROM users u JOIN stories s ON u.user_id = s.author_id WHERE s.status = 'published' GROUP BY u.user_id ORDER BY story_count DESC LIMIT 4");
$topAuthors = $stmt->fetchAll();

// 4. Get "Story of the Day"
$storyOfTheDay = !empty($featuredStories) ? $featuredStories[0] : null;
?>

<!-- Hero Section -->
<section class="hero-professional">
    <div class="hero-content">
        <h1>Where Little<br>Imaginations Grow</h1>
        <p>Discover magical stories, explore new worlds,<br>and let your imagination fly.</p>
        <div class="hero-buttons">
            <a href="<?= BASE_URL ?>/index.php?page=stories" class="btn-hero-primary">📖 Explore Stories &rarr;</a>
            <a href="<?= BASE_URL ?>/index.php?page=register" class="btn-hero-secondary">⭐ Join StoryNest</a>
        </div>
    </div>
    
    <!-- Interactive Story of the Day -->
    <?php if ($storyOfTheDay): ?>
    <div class="hero-interactive">
        <div class="floating-book-container">
            <div class="floating-book">
                <?php if (!empty($storyOfTheDay['thumbnail'])): ?>
                    <img src="<?= BASE_URL ?>/uploads/stories/<?= sanitize($storyOfTheDay['thumbnail']) ?>" alt="Cover" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px 20px 20px 8px;">
                <?php else: ?>
                    <span class="book-emoji">📖</span>
                <?php endif; ?>
                <div class="book-glare"></div>
            </div>
            <div class="story-of-day-card">
                <span class="sotd-badge">✨ Story of the Day</span>
                <h3><?= sanitize($storyOfTheDay['title']) ?></h3>
                <p>By <?= sanitize($storyOfTheDay['author']) ?></p>
                <a href="<?= BASE_URL ?>/index.php?page=story&id=<?= $storyOfTheDay['story_id'] ?>" class="btn-read-now">Read Now →</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>

<!-- Featured Stories -->
<section class="featured-section">
    <h2 class="section-divider"><span>⭐ Featured Stories</span></h2>
    <div class="story-grid-4">
        <?php if (empty($featuredStories)): ?>
            <p style="text-align: center; width: 100%; color: var(--text-light);">No stories published yet.</p>
        <?php else: ?>
            <?php foreach ($featuredStories as $index => $story): ?>
                <div class="story-card-modern">
                    <div class="card-image">
                        <!-- Check if thumbnail exists, otherwise fallback to gradient -->
                        <?php if (!empty($story['thumbnail'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/stories/<?= sanitize($story['thumbnail']) ?>" alt="<?= sanitize($story['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width:100%; height:100%; background: <?= ['#f4a261', '#e76f51', '#2a9d8f', '#e9c46a'][$index % 4] ?>;"></div>
                        <?php endif; ?>
                        <span class="age-badge"><?= sanitize($story['age_group']) ?> yrs</span>
                    </div>
                    <div class="card-content">
                        <h3><?= sanitize($story['title']) ?></h3>
                        <div class="card-footer">
                            <span class="category-tag">📖 <?= sanitize($story['category']) ?></span>
                            <a href="<?= BASE_URL ?>/index.php?page=story&id=<?= $story['story_id'] ?>" class="btn-read">Read Story &rarr;</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Browse by Category -->
<section class="categories-section-modern">
    <h2 class="section-divider"><span>⭐ Browse by Category</span></h2>
    <div class="category-grid-modern">
        <?php 
        $colors = ['#E8E0FF', '#FFD9E8', '#FFF3E0', '#E8F5E9', '#DFF4FF', '#F3E5F5'];
        $icons = ['🏰', '🐾', '🚀', '❤️', '🌱', '🌙'];
        foreach ($categories as $index => $cat): 
        ?>
            <a href="<?= BASE_URL ?>/index.php?page=stories&category=<?= $cat['category_id'] ?>" 
               class="category-card" 
               style="background: <?= $colors[$index % 6] ?>;">
                <span class="cat-icon"><?= $icons[$index % 6] ?></span>
                <span class="cat-name"><?= sanitize($cat['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Gamification Section -->
<section class="gamification-section">
    <h2 class="section-divider"><span>🏆 Reading Adventures</span></h2>
    <div class="gamification-grid">
        <div class="gamification-card">
            <div class="badge-icon">🔥</div>
            <h3>Reading Streak</h3>
            <p>Read a story every day to keep your streak alive!</p>
            <div class="streak-dots">
                <span class="active"></span><span class="active"></span><span class="active"></span><span></span><span></span><span></span><span></span>
            </div>
            <small>3 Days in a row!</small>
        </div>
        <div class="gamification-card">
            <div class="badge-icon">🦉</div>
            <h3>Night Owl</h3>
            <p>Read 3 stories after sunset.</p>
            <div class="badge-progress"><div class="progress-fill" style="width: 66%;"></div></div>
            <small>2 / 3 Completed</small>
        </div>
        <div class="gamification-card">
            <div class="badge-icon">🚀</div>
            <h3>Space Explorer</h3>
            <p>Read 5 stories in the Adventure category.</p>
            <div class="badge-progress"><div class="progress-fill" style="width: 20%;"></div></div>
            <small>1 / 5 Completed</small>
        </div>
    </div>
</section>

<!-- Meet Our Authors -->
<section class="authors-section">
    <h2 class="section-divider"><span>✍️ Meet Our Authors</span></h2>
    <div class="authors-grid">
        <?php if (empty($topAuthors)): ?>
            <p style="text-align: center; width: 100%; color: var(--text-light);">No authors yet.</p>
        <?php else: ?>
            <?php foreach ($topAuthors as $index => $author): ?>
                <div class="author-card">
                    <div class="author-avatar" style="background: <?= ['#FFD9E8', '#DFF4FF', '#E8E0FF', '#FFF3E0'][$index % 4] ?>;">
                        <?= strtoupper(substr(sanitize($author['username']), 0, 1)) ?>
                    </div>
                    <h4><?= sanitize($author['username']) ?></h4>
                    <p><?= $author['story_count'] ?> Published Stories</p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Features / Why StoryNest -->
<section class="features-section">
    <div class="features-grid">
        <div class="feature-box">
            <span class="feature-icon">📖</span>
            <h3>Wonderful Stories</h3>
            <p>Discover exciting stories for different ages.</p>
        </div>
        <div class="feature-box">
            <span class="feature-icon">🧠</span>
            <h3>Learn While Reading</h3>
            <p>Develop imagination, creativity and good values.</p>
        </div>
        <div class="feature-box">
            <span class="feature-icon">🌈</span>
            <h3>Stories for Every Age</h3>
            <p>Find stories suitable for each child's age.</p>
        </div>
        <div class="feature-box">
            <span class="feature-icon">👨‍👩‍👧‍👦</span>
            <h3>Parents' Corner</h3>
            <p>Parents can follow their children's reading journey.</p>
        </div>
    </div>
</section>

<!-- Age Selection & CTA -->
<section class="age-cta-section">
    <div class="age-selection">
        <h2 class="section-divider"><span>✨ Choose Your Age</span></h2>
        <div class="age-pills">
            <a href="<?= BASE_URL ?>/index.php?page=stories&age_group=3-5" class="age-pill bg-pink">3-5<br><small>Years</small></a>
            <a href="<?= BASE_URL ?>/index.php?page=stories&age_group=6-8" class="age-pill bg-blue">6-8<br><small>Years</small></a>
            <a href="<?= BASE_URL ?>/index.php?page=stories&age_group=9-12" class="age-pill bg-green">9-12<br><small>Years</small></a>
            <a href="<?= BASE_URL ?>/index.php?page=stories&age_group=13+" class="age-pill bg-orange">13-15<br><small>Years</small></a>
        </div>
    </div>
    
    <div class="cta-writer">
        <div class="cta-text">
            <h3>Have a story to tell?</h3>
            <p>Your imagination could become a child's favorite adventure.</p>
            <a href="<?= BASE_URL ?>/index.php?page=register" class="btn-start-writing">Start Writing ✨</a>
        </div>
        <div class="cta-illustration">✍️</div>
    </div>
</section>

<?php require_once BASE_PATH . '/app/includes/footer.php'; ?>