<?php
// app/views/stories/edit.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php';
$flash = getFlash();
?>
<div class="app-layout">
    <main class="app-content">
        <div style="background: var(--white); padding: 2.5rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(124, 92, 252, 0.1); border: 1px solid var(--lavender); width: 100%; max-width: 800px; margin: 0 auto;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2 style="color: var(--dark-purple); font-size: 1.8rem; margin: 0;">Edit Story</h2>
                <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="btn btn-outline" style="padding: 0.5rem 1rem; border-radius: 12px;">&larr; Back</a>
            </div>
            
            <?php if ($flash): ?>
                <div style="padding: 1rem; margin-bottom: 1.5rem; border-radius: 12px; font-weight: 600; background: <?= $flash['type'] === 'error' ? '#FFD9E8' : '#DFF4FF' ?>; color: <?= $flash['type'] === 'error' ? '#D8000C' : '#005C97' ?>;">
                    <?= sanitize($flash['message']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                
                <div class="auth-form-group">
                    <label>Story Title</label>
                    <input type="text" name="title" value="<?= sanitize($story['title']) ?>" required>
                </div>

                <!-- Thumbnail Management -->
                <div class="auth-form-group">
                    <label>Story Thumbnail</label>
                    <div style="border: 2px dashed #D1C4E9; border-radius: 12px; padding: 1.5rem; background: #FAFAFF;">
                        
                        <?php if (!empty($story['thumbnail'])): ?>
                            <div style="margin-bottom: 1rem;">
                                <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 0.5rem;">Current Cover:</p>
                                <img src="<?= BASE_URL ?>/uploads/stories/<?= sanitize($story['thumbnail']) ?>" alt="Cover" style="max-height: 150px; border-radius: 8px;">
                            </div>
                        <?php endif; ?>

                        <input type="file" name="thumbnail" id="edit-thumbnail-input" accept="image/jpeg, image/png, image/webp" style="width: 100%; cursor: pointer; color: var(--text-light);">
                        <small style="display: block; margin-top: 0.5rem; color: var(--text-light);">Leave blank to keep the current image. Max 5MB.</small>
                        
                        <div id="edit-image-preview" style="display: none; margin-top: 1rem;">
                            <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 0.5rem;">New Cover Preview:</p>
                            <img id="edit-preview-img" src="" alt="Preview" style="max-height: 150px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="auth-form-group" style="margin-bottom: 0;">
                        <label>Category</label>
                        <select name="category_id" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $story['category_id'] ? 'selected' : '' ?>><?= sanitize($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="auth-form-group" style="margin-bottom: 0;">
                        <label>Age Group</label>
                        <select name="age_group" required>
                            <option value="3-5" <?= $story['age_group'] === '3-5' ? 'selected' : '' ?>>3-5 years</option>
                            <option value="6-8" <?= $story['age_group'] === '6-8' ? 'selected' : '' ?>>6-8 years</option>
                            <option value="9-12" <?= $story['age_group'] === '9-12' ? 'selected' : '' ?>>9-12 years</option>
                            <option value="13+" <?= $story['age_group'] === '13+' ? 'selected' : '' ?>>13+ years</option>
                        </select>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label>Story Content</label>
                    <textarea name="body" rows="12" required style="width: 100%; padding: 1rem; border: 1.5px solid #E0D8F0; border-radius: 12px; font-family: inherit; font-size: 1rem; resize: vertical;"><?= sanitize($story['body']) ?></textarea>
                </div>

                <div class="auth-form-group">
                    <label>Publish Status</label>
                    <select name="status">
                        <option value="draft" <?= $story['status'] === 'draft' ? 'selected' : '' ?>>Save as Draft</option>
                        <option value="published" <?= $story['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem; border-radius: 12px;">Update Story</button>
                    <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="btn btn-outline" style="padding: 1rem 2rem; border-radius: 12px;">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('edit-thumbnail-input');
    const previewContainer = document.getElementById('edit-image-preview');
    const previewImg = document.getElementById('edit-preview-img');

    input.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewImg.src = '';
            previewContainer.style.display = 'none';
        }
    });
});
</script>
</body>
</html>