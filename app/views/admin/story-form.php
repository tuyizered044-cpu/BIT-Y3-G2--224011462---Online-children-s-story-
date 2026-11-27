<?php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php';
$isEditing = is_array($story);
$storyId = $isEditing ? (int) $story['story_id'] : 0;
$title = $_POST['title'] ?? ($story['title'] ?? '');
$body = $_POST['body'] ?? ($story['body'] ?? '');
$categoryId = $_POST['category_id'] ?? ($story['category_id'] ?? '');
$ageGroup = $_POST['age_group'] ?? ($story['age_group'] ?? '');
$status = $_POST['status'] ?? ($story['status'] ?? 'draft');
$flash = getFlash();
?>
<div class="app-layout">
    <main class="app-content">
        <div style="background: var(--white); padding: 2rem; border-radius: 20px; border: 1px solid var(--lavender); max-width: 800px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h1><?= $isEditing ? 'Edit Story' : 'Add Story' ?></h1>
                <a href="<?= BASE_URL ?>/index.php?page=admin-dashboard#stories" class="btn btn-outline">Back</a>
            </div>

            <?php if ($flash): ?>
                <div role="status" style="padding: 1rem; margin-bottom: 1rem; border-radius: 12px; background: <?= $flash['type'] === 'error' ? '#FFD9E8' : '#DFF4FF' ?>; color: <?= $flash['type'] === 'error' ? '#D8000C' : '#005C97' ?>;">
                    <?= sanitize($flash['message']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/index.php?page=<?= $isEditing ? 'admin-story-edit&id=' . $storyId : 'admin-story-create' ?>" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <div class="auth-form-group">
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title" maxlength="150" value="<?= sanitize($title) ?>" required>
                </div>
                <div class="auth-form-group">
                    <label for="thumbnail">Cover image</label>
                    <?php if (!empty($story['thumbnail'])): ?>
                        <p><img src="<?= BASE_URL ?>/uploads/stories/<?= rawurlencode($story['thumbnail']) ?>" alt="Current story cover" style="max-height: 140px;"></p>
                    <?php endif; ?>
                    <input id="thumbnail" type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp">
                    <small>Optional. JPG, PNG, or WEBP; max 5 MB.</small>
                </div>
                <div class="auth-form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['category_id'] ?>" <?= (string) $categoryId === (string) $category['category_id'] ? 'selected' : '' ?>><?= sanitize($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="auth-form-group">
                    <label for="age_group">Age group</label>
                    <select id="age_group" name="age_group" required>
                        <option value="">Select an age group</option>
                        <?php foreach (['3-5', '6-8', '9-12', '13+'] as $availableAgeGroup): ?>
                            <option value="<?= $availableAgeGroup ?>" <?= $ageGroup === $availableAgeGroup ? 'selected' : '' ?>><?= $availableAgeGroup ?> years</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="auth-form-group">
                    <label for="body">Story content</label>
                    <textarea id="body" name="body" rows="12" required><?= sanitize($body) ?></textarea>
                </div>
                <div class="auth-form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><?= $isEditing ? 'Save Story' : 'Create Story' ?></button>
            </form>
        </div>
    </main>
</div>
<?php require_once BASE_PATH . '/app/includes/footer.php'; ?>
