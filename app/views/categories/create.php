<?php
// app/views/categories/create.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php'; // Load Sidebar
$flash = getFlash();
?>

<div class="app-layout">
    <!-- Sidebar is rendered here -->

    <main class="app-content">
        <div style="margin-bottom: 2.5rem;">
            <h1 style="font-size: 2rem; color: var(--dark-purple);">Manage Categories</h1>
            <p style="color: var(--text-light);">Add, edit, or remove story categories.</p>
        </div>

        <?php if ($flash): ?>
            <div style="padding: 1rem 1.5rem; margin-bottom: 2rem; border-radius: 12px; font-weight: 600; background: <?= $flash['type'] === 'error' ? '#FFD9E8' : '#DFF4FF' ?>; color: <?= $flash['type'] === 'error' ? '#D8000C' : '#005C97' ?>;">
                <?= sanitize($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            
            <!-- Create Form -->
            <div style="background: var(--white); padding: 2rem; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid var(--lavender);">
                <h2 style="margin-bottom: 1.5rem; font-size: 1.2rem;">Add New Category</h2>
                <form method="POST" action="<?= BASE_URL ?>/index.php?page=create-category">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    
                    <div class="auth-form-group">
                        <label>Category Name</label>
                        <input type="text" name="name" required placeholder="e.g. Mystery, Science">
                    </div>
                    
                    <div class="auth-form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" style="width: 100%; padding: 0.9rem; border: 1.5px solid #E0D8F0; border-radius: 12px; font-family: inherit;" placeholder="What is this category about?"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem; border-radius: 12px;">Create Category</button>
                </form>
            </div>

            <!-- Category List -->
            <div style="background: var(--white); padding: 2rem; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid var(--lavender);">
                <h2 style="margin-bottom: 1.5rem; font-size: 1.2rem;">Existing Categories</h2>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--lavender);">
                                <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem;">Name</th>
                                <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem;">Description</th>
                                <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr style="border-bottom: 1px solid #F0F0F5;">
                                    <td style="padding: 1rem; font-weight: 600; color: var(--dark-purple);"><?= sanitize($cat['name']) ?></td>
                                    <td style="padding: 1rem; color: var(--text-light); font-size: 0.9rem;"><?= sanitize($cat['description']) ?></td>
                                    <td style="padding: 1rem;">
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="<?= BASE_URL ?>/index.php?page=edit-category&id=<?= $cat['category_id'] ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; border-radius: 8px;">Edit</a>
                                            <form method="POST" action="<?= BASE_URL ?>/index.php?page=delete-category" onsubmit="return confirm('Delete this category?');">
                                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                                <input type="hidden" name="category_id" value="<?= (int) $cat['category_id'] ?>">
                                                <button type="submit" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; border-radius: 8px; background: #FFEBEE; color: #C62828;">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>

</body>
</html>