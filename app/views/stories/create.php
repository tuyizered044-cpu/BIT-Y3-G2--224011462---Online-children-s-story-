<?php
// app/views/stories/create.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php'; // Using Sidebar for dashboard layout
$flash = getFlash();
?>

<div class="app-layout">
    <!-- Sidebar is rendered here -->

    <main class="app-content">
        <div style="background: var(--white); padding: 2.5rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(124, 92, 252, 0.1); border: 1px solid var(--lavender); width: 100%; max-width: 800px; margin: 0 auto;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2 style="color: var(--dark-purple); font-size: 1.8rem; margin: 0;">Create a New Story</h2>
                <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="btn btn-outline" style="padding: 0.5rem 1rem; border-radius: 12px;">&larr; Back</a>
            </div>
            
            <?php if ($flash): ?>
                <div style="padding: 1rem; margin-bottom: 1.5rem; border-radius: 12px; font-weight: 600; background: <?= $flash['type'] === 'error' ? '#FFD9E8' : '#DFF4FF' ?>; color: <?= $flash['type'] === 'error' ? '#D8000C' : '#005C97' ?>;">
                    <?= sanitize($flash['message']) ?>
                </div>
            <?php endif; ?>

            <!-- Important: enctype="multipart/form-data" is required for image uploads -->
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                
                <div class="auth-form-group">
                    <label>Story Title</label>
                    <input type="text" name="title" required placeholder="Enter a catchy title...">
                </div>

                <!-- Thumbnail Upload Field -->
                <div class="auth-form-group">
                    <label>Story Thumbnail (Cover Image)</label>
                    <div style="border: 2px dashed #D1C4E9; border-radius: 12px; padding: 1.5rem; text-align: center; background: #FAFAFF; position: relative;">
                        <input type="file" name="thumbnail" id="thumbnail-input" accept="image/jpeg, image/png, image/webp" style="width: 100%; cursor: pointer; color: var(--text-light);">
                        <small style="display: block; margin-top: 0.5rem; color: var(--text-light);">Max size: 5MB. Formats: JPG, PNG, WEBP.</small>
                        
                        <!-- Image Preview Container -->
                        <div id="image-preview" style="display: none; margin-top: 1rem;">
                            <img id="preview-img" src="" alt="Thumbnail Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="auth-form-group" style="margin-bottom: 0;">
                        <label>Category</label>
                        <select name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>"><?= sanitize($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="auth-form-group" style="margin-bottom: 0;">
                        <label>Age Group</label>
                        <select name="age_group" required>
                            <option value="">Select Age Group</option>
                            <option value="3-5">3-5 years</option>
                            <option value="6-8">6-8 years</option>
                            <option value="9-12">9-12 years</option>
                            <option value="13+">13+ years</option>
                        </select>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label>Story Content</label>
                    <textarea name="body" rows="12" required placeholder="Once upon a time..." style="width: 100%; padding: 1rem; border: 1.5px solid #E0D8F0; border-radius: 12px; font-family: inherit; font-size: 1rem; resize: vertical;"></textarea>
                </div>

                <div class="auth-form-group">
                    <label>Publish Status</label>
                    <select name="status">
                        <option value="draft">Save as Draft (Only you can see it)</option>
                        <option value="published">Publish Now (Everyone can read it)</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary auth-btn" style="padding: 1rem 2rem;">Save Story</button>
                    <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="btn btn-outline" style="padding: 1rem 2rem;">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- JavaScript for Image Preview -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('thumbnail-input');
    const previewContainer = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

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