<?php
// app/views/admin/users.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
$flash = getFlash();
?>
<div style="padding: 2rem 5%;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Manage Users</h1>
        <a href="<?= BASE_URL ?>/index.php?page=admin-dashboard" class="btn btn-outline">&larr; Back to Dashboard</a>
    </div>

    <?php if ($flash): ?>
        <div style="padding: 1rem; margin-bottom: 1rem; border-radius: 8px; background: <?= $flash['type'] === 'error' ? '#FFD9E8' : '#DFF4FF' ?>; color: <?= $flash['type'] === 'error' ? '#D8000C' : '#005C97' ?>;">
            <?= $flash['message'] ?>
        </div>
    <?php endif; ?>

    <div style="background: var(--white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--lavender);">
                    <th style="padding: 1rem;">ID</th>
                    <th style="padding: 1rem;">Username</th>
                    <th style="padding: 1rem;">Email</th>
                    <th style="padding: 1rem;">Role</th>
                    <th style="padding: 1rem;">Joined</th>
                    <th style="padding: 1rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1rem;"><?= $u['user_id'] ?></td>
                        <td style="padding: 1rem; font-weight: 600;"><?= sanitize($u['username']) ?></td>
                        <td style="padding: 1rem;"><?= sanitize($u['email']) ?></td>
                        <td style="padding: 1rem;">
                            <form method="POST" action="<?= BASE_URL ?>/index.php?page=admin-update-role" style="display: flex; gap: 0.5rem;">
                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                                <select name="role" style="padding: 0.3rem; border-radius: 8px; border: 1px solid #ccc;" <?= $u['user_id'] == $_SESSION['user_id'] ? 'disabled' : '' ?>>
                                    <?php foreach (['admin', 'writer', 'child', 'parent'] as $r): ?>
                                        <option value="<?= $r ?>" <?= $u['role'] === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($u['user_id'] != $_SESSION['user_id']): ?>
                                    <button type="submit" class="btn btn-outline" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">Update</button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td style="padding: 1rem; color: var(--text-light);"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                        <td style="padding: 1rem;">
                            <?php if ($u['user_id'] != $_SESSION['user_id']): ?>
                                <a href="<?= BASE_URL ?>/index.php?page=admin-delete-user&id=<?= $u['user_id'] ?>" class="btn" style="padding: 0.3rem 0.8rem; font-size: 0.8rem; background: #ffcccc; color: #cc0000;" onclick="return confirm('Delete this user? All their stories and readings will be deleted.');">Delete</a>
                            <?php else: ?>
                                <span style="color: var(--text-light); font-size: 0.85rem;">You</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>