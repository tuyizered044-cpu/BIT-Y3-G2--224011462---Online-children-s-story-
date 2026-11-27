<?php
// app/views/categories/edit.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php';
$flash = getFlash();
?>
<div class="app-layout">
    <main class="app-content">
        <div style="max-width: 600px; margin: 0 auto; background: var(--white); padding: 2.5rem; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid var(--lavender);">
            <h2 style="margin-bottom: 1.5rem; color: var(--dark-purple);">Edit Category</h2>
            
            <?php if ($flash): ?>
                <div style="padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; background: #FFD9E8; color: #D8000C; font-weight: 600;">
                    <?= sanitize($flash['message']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <div class="auth-form-group">
                    <label>Category Name</label>
                    <input type="text" name="name" value="<?= sanitize($category['name']) ?>" required>
                </div>
                <div class="auth-form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" style="width: 100%; padding: 0.9rem; border: 1.5px solid #E0D8F0; border-radius: 12px;"><?= sanitize($category['description']) ?></textarea>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 0.8rem; border-radius: 12px;">Update Category</button>
                    <a href="<?= BASE_URL ?>/index.php?page=manage-categories" class="btn btn-outline" style="flex: 1; padding: 0.8rem; border-radius: 12px; text-align: center; text-decoration: none;">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>