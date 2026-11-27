<?php
// app/views/admin/dashboard.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php'; // Sidebar
$flash = getFlash();
?>

<div class="app-layout">
    <main class="app-content">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <p style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light); margin-bottom: 0.3rem;">Control Center</p>
                <h1 style="font-size: 2rem;">Welcome back, <?= sanitize($_SESSION['username']) ?></h1>
            </div>
            <span style="width: 50px; height: 50px; background: var(--primary-purple); color: var(--white); display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.4rem; font-weight: 700; box-shadow: 0 4px 10px rgba(124, 92, 252, 0.3);">
                <?= strtoupper(substr(sanitize($_SESSION['username']), 0, 1)) ?>
            </span>
        </header>

        <?php if ($flash): ?>
            <div style="padding: 1rem 1.5rem; margin-bottom: 2rem; border-radius: 12px; font-weight: 600; background: <?= $flash['type'] === 'error' ? '#FFD9E8' : '#DFF4FF' ?>; color: <?= $flash['type'] === 'error' ? '#D8000C' : '#005C97' ?>;">
                <?= sanitize($flash['message']) ?>
            </div>
        <?php endif; ?>

        <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <article style="background: var(--white); padding: 1.5rem; border-radius: 16px; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--lavender);">
                <span style="font-size: 1.8rem; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: var(--lavender); color: var(--primary-purple);">♙</span>
                <div><strong style="font-size: 1.8rem; line-height: 1.2; color: var(--dark-purple); display: block;"><?= $stats['users'] ?></strong><span style="font-size: 0.9rem; color: var(--text-light);">Total users</span></div>
            </article>
            <article style="background: var(--white); padding: 1.5rem; border-radius: 16px; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--lavender);">
                <span style="font-size: 1.8rem; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: var(--light-blue); color: #005C97;">▤</span>
                <div><strong style="font-size: 1.8rem; line-height: 1.2; color: var(--dark-purple); display: block;"><?= $stats['stories'] ?></strong><span style="font-size: 0.9rem; color: var(--text-light);">Total stories</span></div>
            </article>
            <article style="background: var(--white); padding: 1.5rem; border-radius: 16px; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--lavender);">
                <span style="font-size: 1.8rem; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #E8F5E9; color: #2E7D32;">✓</span>
                <div><strong style="font-size: 1.8rem; line-height: 1.2; color: var(--dark-purple); display: block;"><?= $stats['published'] ?></strong><span style="font-size: 0.9rem; color: var(--text-light);">Published</span></div>
            </article>
            <article style="background: var(--white); padding: 1.5rem; border-radius: 16px; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--lavender);">
                <span style="font-size: 1.8rem; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: var(--soft-pink); color: #D8000C;">◇</span>
                <div><strong style="font-size: 1.8rem; line-height: 1.2; color: var(--dark-purple); display: block;"><?= $stats['categories'] ?></strong><span style="font-size: 0.9rem; color: var(--text-light);">Categories</span></div>
            </article>
        </section>

        <section id="users" style="background: var(--white); border-radius: 20px; padding: 2rem; margin-bottom: 2.5rem; border: 1px solid var(--lavender);">
            <div style="margin-bottom: 1.5rem;">
                <p style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light); margin-bottom: 0.3rem;">Account Access</p>
                <h2 style="font-size: 1.5rem;">Manage Users</h2>
                <a href="<?= BASE_URL ?>/index.php?page=admin-user-create" class="btn btn-primary" style="display: inline-block; margin-top: 1rem;">Add User</a>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--lavender);">
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase;">User</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase;">Email</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase;">Role</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase;">Joined</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!$users): ?>
                        <tr><td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-light);">No users found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr style="border-bottom: 1px solid #F0F0F5;">
                                <td style="padding: 1rem; font-weight: 600; color: var(--dark-purple);"><?= sanitize($user['username']) ?></td>
                                <td style="padding: 1rem;"><?= sanitize($user['email']) ?></td>
                                <td style="padding: 1rem;">
                                    <form method="POST" action="<?= BASE_URL ?>/index.php?page=admin-user-role" style="display: flex; gap: 0.5rem;">
                                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                        <input type="hidden" name="user_id" value="<?= (int) $user['user_id'] ?>">
                                        <select name="role" style="padding: 0.4rem; border-radius: 8px; border: 1px solid #ccc;" <?= (int) $user['user_id'] === (int) $_SESSION['user_id'] ? 'disabled' : '' ?>>
                                            <?php foreach (['admin', 'writer', 'child', 'parent'] as $role): ?>
                                                <option value="<?= $role ?>" <?= $user['role'] === $role ? 'selected' : '' ?>><?= ucfirst($role) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if ((int) $user['user_id'] !== (int) $_SESSION['user_id']): ?>
                                            <button type="submit" style="background: var(--primary-purple); color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Save</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                                <td style="padding: 1rem; color: var(--text-light);"><?= sanitize(date('M j, Y', strtotime($user['created_at']))) ?></td>
                                <td style="padding: 1rem;">
                                    <?php if ((int) $user['user_id'] !== (int) $_SESSION['user_id']): ?>
                                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                                            <a href="<?= BASE_URL ?>/index.php?page=admin-user-edit&id=<?= (int) $user['user_id'] ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem;">Edit</a>
                                            <form method="POST" action="<?= BASE_URL ?>/index.php?page=admin-user-delete" onsubmit="return confirm('Delete this user account?');">
                                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                                <input type="hidden" name="user_id" value="<?= (int) $user['user_id'] ?>">
                                                <button type="submit" style="background: #FFEBEE; color: #C62828; border: none; padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Delete</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span style="font-size: 0.85rem; color: var(--text-light); font-style: italic;">Current account</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="stories" style="background: var(--white); border-radius: 20px; padding: 2rem; border: 1px solid var(--lavender);">
            <div style="margin-bottom: 1.5rem;">
                <p style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light); margin-bottom: 0.3rem;">Story Library</p>
                <h2 style="font-size: 1.5rem;">Manage Stories</h2>
                <a href="<?= BASE_URL ?>/index.php?page=admin-story-create" class="btn btn-primary" style="display: inline-block; margin-top: 1rem;">Add Story</a>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--lavender);">
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase;">Story</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase;">Author</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase;">Category</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase;">Status</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!$stories): ?>
                        <tr><td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-light);">No stories found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($stories as $story): ?>
                            <tr style="border-bottom: 1px solid #F0F0F5;">
                                <td style="padding: 1rem; font-weight: 600; color: var(--dark-purple);"><?= sanitize($story['title']) ?></td>
                                <td style="padding: 1rem;"><?= sanitize($story['author']) ?></td>
                                <td style="padding: 1rem;"><?= sanitize($story['category']) ?></td>
                                <td style="padding: 1rem;">
                                    <form method="POST" action="<?= BASE_URL ?>/index.php?page=admin-story-status">
                                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                        <input type="hidden" name="story_id" value="<?= (int) $story['story_id'] ?>">
                                        <input type="hidden" name="status" value="<?= $story['status'] === 'published' ? 'draft' : 'published' ?>">
                                        <button type="submit" style="border: none; padding: 0.4rem 0.8rem; border-radius: 20px; font-weight: 600; cursor: pointer; font-size: 0.85rem; background: <?= $story['status'] === 'published' ? '#E8F5E9' : '#FFF3E0' ?>; color: <?= $story['status'] === 'published' ? '#2E7D32' : '#E65100' ?>;">
                                            <?= $story['status'] === 'published' ? 'Published · Hide' : 'Draft · Publish' ?>
                                        </button>
                                    </form>
                                </td>
                                <td style="padding: 1rem;">
                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                        <a href="<?= BASE_URL ?>/index.php?page=story&id=<?= (int) $story['story_id'] ?>" style="padding: 0.4rem 0.8rem; border-radius: 8px; background: var(--lavender); color: var(--primary-purple); font-weight: 600; font-size: 0.85rem; text-decoration: none;">View</a>
                                        <a href="<?= BASE_URL ?>/index.php?page=admin-story-edit&id=<?= (int) $story['story_id'] ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem;">Edit</a>
                                        <form method="POST" action="<?= BASE_URL ?>/index.php?page=admin-story-delete" onsubmit="return confirm('Delete this story?');" style="margin: 0;">
                                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                            <input type="hidden" name="story_id" value="<?= (int) $story['story_id'] ?>">
                                            <button type="submit" style="background: #FFEBEE; color: #C62828; border: none; padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.85rem;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
