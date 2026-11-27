<?php
// app/views/parent/dashboard.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php'; // Load Sidebar
?>

<div class="app-layout">
    <!-- Sidebar is rendered here -->

    <main class="app-content">
        <div style="margin-bottom: 2.5rem;">
            <h1 style="margin-bottom: 0.5rem; font-size: 2rem;">Parent Dashboard</h1>
            <p style="color: var(--text-light); font-size: 1.1rem;">Monitor your child's reading journey.</p>
        </div>

        <!-- Link Child Form -->
        <div style="background: var(--white); padding: 1.5rem; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid var(--lavender); margin-bottom: 2.5rem;">
            <h3 style="margin-bottom: 1rem; font-size: 1.2rem;">Link a Child Account</h3>
            
            <form method="POST" action="<?= BASE_URL ?>/index.php?page=api-link-child" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                
                <select name="child_id" required style="flex: 1; padding: 0.8rem; border: 1.5px solid #E0D8F0; border-radius: 12px; font-family: inherit; min-width: 200px;">
                    <option value="">Select a child account</option>
                    <?php foreach ($allChildren as $c): ?>
                        <option value="<?= $c['user_id'] ?>">
                            <?= sanitize($c['username']) ?> (<?= sanitize($c['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="btn btn-primary" style="padding: 0.8rem 1.5rem; border-radius: 12px;">Link Child</button>
            </form>
        </div>

        <?php if (empty($children)): ?>
            <div style="text-align: center; padding: 3rem; background: var(--white); border-radius: 20px; border: 1px dashed var(--lavender);">
                <p style="color: var(--text-light); font-size: 1.1rem;">No children linked yet. Use the form above to connect a child account.</p>
            </div>
        <?php else: ?>
            <!-- Child Selection -->
            <div style="margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <span style="font-weight: 700; color: var(--dark-purple);">Viewing data for:</span>
                <div style="display: flex; gap: 0.5rem;">
                    <?php foreach ($children as $child): ?>
                        <a href="<?= BASE_URL ?>/index.php?page=dashboard&child_id=<?= $child['user_id'] ?>" 
                           class="btn <?= $selectedChildId == $child['user_id'] ? 'btn-primary' : 'btn-outline' ?>" 
                           style="padding: 0.5rem 1rem; border-radius: 20px;">
                            <?= sanitize($child['username']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($selectedChildId): ?>
                <!-- Stats -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
                    <div style="background: var(--lavender); padding: 1.5rem; border-radius: 16px;">
                        <h3 style="font-size: 2.5rem; color: var(--dark-purple); margin-bottom: 0.2rem;"><?= count($readings) ?></h3>
                        <p style="color: var(--text-light); font-weight: 600;">Stories Started</p>
                    </div>
                    <div style="background: var(--light-blue); padding: 1.5rem; border-radius: 16px;">
                        <h3 style="font-size: 2.5rem; color: var(--dark-purple); margin-bottom: 0.2rem;"><?= $completed ?></h3>
                        <p style="color: var(--text-light); font-weight: 600;">Stories Completed</p>
                    </div>
                    <div style="background: var(--soft-pink); padding: 1.5rem; border-radius: 16px;">
                        <h3 style="font-size: 2.5rem; color: var(--dark-purple); margin-bottom: 0.2rem;"><?= count($readings) - $completed ?></h3>
                        <p style="color: var(--text-light); font-weight: 600;">In Progress</p>
                    </div>
                </div>

                <!-- Reading History Table -->
                <div style="background: var(--white); border-radius: 20px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid var(--lavender);">
                    <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem;">Reading History</h2>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--lavender);">
                                    <th style="padding: 1rem; color: var(--text-light); text-transform: uppercase; font-size: 0.85rem;">Story</th>
                                    <th style="padding: 1rem; color: var(--text-light); text-transform: uppercase; font-size: 0.85rem;">Category</th>
                                    <th style="padding: 1rem; color: var(--text-light); text-transform: uppercase; font-size: 0.85rem;">Progress</th>
                                    <th style="padding: 1rem; color: var(--text-light); text-transform: uppercase; font-size: 0.85rem;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($readings)): ?>
                                    <tr><td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-light);">No reading activity yet.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($readings as $r): ?>
                                        <tr style="border-bottom: 1px solid #F0F0F5;">
                                            <td style="padding: 1rem; font-weight: 600; color: var(--dark-purple);"><?= sanitize($r['title']) ?></td>
                                            <td style="padding: 1rem;"><?= sanitize($r['category']) ?></td>
                                            <td style="padding: 1rem;">
                                                <div style="width: 120px; height: 8px; background: var(--lavender); border-radius: 5px; display: inline-block; vertical-align: middle;">
                                                    <div style="width: <?= $r['progress_pct'] ?>%; height: 100%; background: var(--primary-purple); border-radius: 5px;"></div>
                                                </div>
                                                <span style="margin-left: 0.5rem; font-size: 0.85rem; font-weight: 600;"><?= $r['progress_pct'] ?>%</span>
                                            </td>
                                            <td style="padding: 1rem;">
                                                <?php if ($r['progress_pct'] >= 100): ?>
                                                    <span style="padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; background: #E8F5E9; color: #2E7D32;">✅ Completed</span>
                                                <?php else: ?>
                                                    <span style="padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; background: #FFF3E0; color: #E65100;">📖 In Progress</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</div>

</body>
</html>