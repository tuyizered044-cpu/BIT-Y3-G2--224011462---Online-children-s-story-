<?php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php';
$isEditing = is_array($user);
$userId = $isEditing ? (int) $user['user_id'] : 0;
$username = $_POST['username'] ?? ($user['username'] ?? '');
$email = $_POST['email'] ?? ($user['email'] ?? '');
$role = $_POST['role'] ?? ($user['role'] ?? 'writer');
$flash = getFlash();
?>
<div class="app-layout">
    <main class="app-content">
        <div style="background: var(--white); padding: 2rem; border-radius: 20px; border: 1px solid var(--lavender); max-width: 700px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h1><?= $isEditing ? 'Edit User' : 'Add User' ?></h1>
                <a href="<?= BASE_URL ?>/index.php?page=admin-dashboard#users" class="btn btn-outline">Back</a>
            </div>

            <?php if ($flash): ?>
                <div role="status" style="padding: 1rem; margin-bottom: 1rem; border-radius: 12px; background: <?= $flash['type'] === 'error' ? '#FFD9E8' : '#DFF4FF' ?>; color: <?= $flash['type'] === 'error' ? '#D8000C' : '#005C97' ?>;">
                    <?= sanitize($flash['message']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/index.php?page=<?= $isEditing ? 'admin-user-edit&id=' . $userId : 'admin-user-create' ?>">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <div class="auth-form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" maxlength="50" value="<?= sanitize($username) ?>" required>
                </div>
                <div class="auth-form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" maxlength="100" value="<?= sanitize($email) ?>" required>
                </div>
                <div class="auth-form-group">
                    <label for="password"><?= $isEditing ? 'New password (optional)' : 'Password' ?></label>
                    <input id="password" type="password" name="password" minlength="6" <?= $isEditing ? '' : 'required' ?> autocomplete="new-password">
                    <?php if ($isEditing): ?><small>Leave blank to keep the current password.</small><?php endif; ?>
                </div>
                <div class="auth-form-group">
                    <label for="role">Role</label>
                    <?php if ($isEditing && $userId === (int) $_SESSION['user_id']): ?>
                        <input type="text" value="<?= sanitize(ucfirst($user['role'])) ?>" disabled>
                    <?php else: ?>
                        <select id="role" name="role" required>
                            <?php foreach (['admin', 'writer', 'child', 'parent'] as $availableRole): ?>
                                <option value="<?= $availableRole ?>" <?= $role === $availableRole ? 'selected' : '' ?>><?= ucfirst($availableRole) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary"><?= $isEditing ? 'Save User' : 'Create User' ?></button>
            </form>
        </div>
    </main>
</div>
<?php require_once BASE_PATH . '/app/includes/footer.php'; ?>
