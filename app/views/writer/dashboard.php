<?php
// app/views/writer/dashboard.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php';
$flash = getFlash();
?>

<div class="app-layout">
    <main class="app-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
            <div>
                <h1 style="margin-bottom: 0.5rem; font-size: 2rem;">Writer Dashboard</h1>
                <p style="color: var(--text-light); font-size: 1.1rem;">Welcome back, <?= sanitize($_SESSION['username']) ?>. Manage your library.</p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=create-story" class="btn btn-primary" style="padding: 0.8rem 1.5rem; font-size: 1.1rem; display: flex; align-items: center; gap: 8px; border-radius: 12px;">
                <span>+</span> Create New Story
            </a>
        </div>

        <?php if ($flash): ?>
            <div style="padding: 1rem 1.5rem; margin-bottom: 2rem; border-radius: 12px; font-weight: 600; background: <?= $flash['type'] === 'error' ? '#FFD9E8' : '#DFF4FF' ?>; color: <?= $flash['type'] === 'error' ? '#D8000C' : '#005C97' ?>;">
                <?= sanitize($flash['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <div style="background: var(--white); padding: 1.5rem; border-radius: 16px; border: 1px solid var(--lavender); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <h3 style="font-size: 2.5rem; color: var(--dark-purple); margin-bottom: 0.2rem;"><?= $total ?></h3>
                <p style="color: var(--text-light); font-weight: 600;">Total Stories</p>
            </div>
            <div style="background: var(--white); padding: 1.5rem; border-radius: 16px; border: 1px solid var(--lavender); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <h3 style="font-size: 2.5rem; color: #2E7D32; margin-bottom: 0.2rem;"><?= $published ?></h3>
                <p style="color: var(--text-light); font-weight: 600;">Published</p>
            </div>
            <div style="background: var(--white); padding: 1.5rem; border-radius: 16px; border: 1px solid var(--lavender); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <h3 style="font-size: 2.5rem; color: #E65100; margin-bottom: 0.2rem;"><?= $drafts ?></h3>
                <p style="color: var(--text-light); font-weight: 600;">Drafts</p>
            </div>
        </div>

        <!-- Stories Table -->
        <div style="background: var(--white); border-radius: 20px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid var(--lavender);">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem;">My Stories</h2>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--lavender);">
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem;">Title</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem;">Category</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem;">Status</th>
                            <th style="padding: 1rem; color: var(--text-light); font-size: 0.85rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stories)): ?>
                            <tr>
                                <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-light);">
                                    No stories yet.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($stories as $s): ?>
                                <tr style="border-bottom: 1px solid #F0F0F5;">
                                    <td style="padding: 1rem; font-weight: 600; color: var(--dark-purple);">
                                        <?= sanitize($s['title']) ?>
                                        <?php if ($s['thumbnail']): ?>
                                            <span title="Has Cover Image" style="margin-left: 5px;">🖼️</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 1rem;"><?= sanitize($s['category']) ?></td>
                                    <td style="padding: 1rem;">
                                        <span style="padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; background: <?= $s['status'] === 'published' ? '#E8F5E9' : '#FFF3E0' ?>; color: <?= $s['status'] === 'published' ? '#2E7D32' : '#E65100' ?>;">
                                            <?= ucfirst($s['status']) ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                            <!-- View Button -->
                                            <a href="<?= BASE_URL ?>/index.php?page=story&id=<?= $s['story_id'] ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; border-radius: 8px;">View</a>
                                            
                                            <!-- Edit Button (Always Available) -->
                                            <a href="<?= BASE_URL ?>/index.php?page=edit-story&id=<?= $s['story_id'] ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; border-radius: 8px;">Edit</a>
                                            
                                            <!-- Publish Button (Only for Drafts) -->
                                            <?php if ($s['status'] === 'draft'): ?>
                                                <a href="<?= BASE_URL ?>/index.php?page=publish-story&id=<?= $s['story_id'] ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; border-radius: 8px;">Publish</a>
                                            <?php endif; ?>
                                            
                                            <!-- Delete Button (Always Available) -->
                                            <a href="<?= BASE_URL ?>/index.php?page=delete-story&id=<?= $s['story_id'] ?>" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; border-radius: 8px; background: #FFEBEE; color: #C62828;" onclick="return confirm('Are you sure you want to delete this story permanently?');">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>