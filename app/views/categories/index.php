<?php
// app/views/categories/index.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
?>
<section class="categories-section" style="min-height: 60vh;">
    <h1 class="section-title">Browse by Category</h1>
    <div class="category-list">
        <?php 
        $catClasses = ['cat-adventure', 'cat-animals', 'cat-fairy', 'cat-friendship'];
        $catIcons = ['🏔️', '🐾', '👑', '🤝'];
        foreach ($categories as $index => $cat): 
            $isAnimalCategory = stripos($cat['name'], 'animal') !== false;
            $catClass = $isAnimalCategory ? 'cat-animals' : $catClasses[$index % 4];
        ?>
            <a href="<?= BASE_URL ?>/index.php?page=stories&category=<?= (int) $cat['category_id'] ?>" class="category-pill <?= $catClass ?> category-browse-pill">
                <?php if ($isAnimalCategory): ?>
                    <span class="category-animal-pictures" aria-hidden="true">
                        <span>🐶</span><span>🐱</span><span>🐰</span><span>🦊</span>
                    </span>
                <?php else: ?>
                    <span class="category-browse-icon" aria-hidden="true"><?= $catIcons[$index % 4] ?></span>
                <?php endif; ?>
                <span><?= sanitize($cat['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>