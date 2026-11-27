<?php
// app/views/users/profile.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/sidebar.php'; // Load Sidebar
$flash = getFlash();
?>

<div class="app-layout">
    <!-- Sidebar is rendered here -->

    <main class="app-content">
        
        <div style="margin-bottom: 2.5rem;">
            <h1 style="font-size: 2rem; color: var(--dark-purple); margin-bottom: 0.5rem;">My Account</h1>
            <p style="color: var(--text-light);">Manage your profile settings and security preferences.</p>
        </div>

        <?php if ($flash): ?>
            <div style="padding: 1rem; margin-bottom: 2rem; border-radius: 12px; font-weight: 600; background: <?= $flash['type'] === 'error' ? '#FFD9E8' : '#DFF4FF' ?>; color: <?= $flash['type'] === 'error' ? '#D8000C' : '#005C97' ?>;">
                <?= sanitize($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            
            <!-- Left Sidebar Summary -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- Profile Card -->
                <div style="background: var(--white); padding: 2rem; border-radius: 20px; box-shadow: 0 8px 24px rgba(124, 92, 252, 0.08); border: 1px solid var(--lavender); text-align: center;">
                    <div style="width: 100px; height: 100px; background: var(--primary-purple); color: white; font-size: 3rem; font-weight: 800; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 1.5rem; box-shadow: 0 8px 20px rgba(124, 92, 252, 0.3);">
                        <?= strtoupper(substr(sanitize($user['username']), 0, 1)) ?>
                    </div>
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.3rem;"><?= sanitize($user['username']) ?></h2>
                    <span style="display: inline-block; padding: 0.3rem 1rem; background: var(--lavender); color: var(--primary-purple); border-radius: 20px; font-weight: 700; font-size: 0.85rem; text-transform: uppercase;">
                        <?= sanitize($user['role']) ?>
                    </span>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 1rem;">Member since <?= date('M Y', strtotime($user['created_at'])) ?></p>
                </div>

                <!-- Quick Stats -->
                <div style="background: var(--white); padding: 1.5rem; border-radius: 20px; box-shadow: 0 8px 24px rgba(124, 92, 252, 0.08); border: 1px solid var(--lavender);">
                    <h3 style="font-size: 1.1rem; margin-bottom: 1rem; color: var(--dark-purple);">Account Activity</h3>
                    <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid #eee;">
                        <span style="color: var(--text-light);">Account ID</span>
                        <span style="font-weight: 700;">#<?= $user['user_id'] ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 0.8rem 0;">
                        <span style="color: var(--text-light);">Security Status</span>
                        <span style="font-weight: 700; color: #2E7D32;">✅ Protected</span>
                    </div>
                </div>
            </div>

            <!-- Right Forms -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                
                <!-- Profile Info Form -->
                <div style="background: var(--white); padding: 2.5rem; border-radius: 20px; box-shadow: 0 8px 24px rgba(124, 92, 252, 0.08); border: 1px solid var(--lavender);">
                    <h2 style="margin-bottom: 1.5rem; color: var(--dark-purple); font-size: 1.4rem;">Personal Information</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        <input type="hidden" name="update_profile" value="1">
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div class="auth-form-group" style="margin-bottom: 0;">
                                <label>Username</label>
                                <input type="text" name="username" value="<?= sanitize($user['username']) ?>" required>
                            </div>
                            <div class="auth-form-group" style="margin-bottom: 0;">
                                <label>Email Address</label>
                                <input type="email" name="email" value="<?= sanitize($user['email']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="auth-form-group">
                            <label>Role (Cannot be changed)</label>
                            <input type="text" value="<?= ucfirst(sanitize($user['role'])) ?>" disabled style="background: #f5f5f5; cursor: not-allowed;">
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem; border-radius: 12px;">Save Changes</button>
                    </form>
                </div>

                <!-- Password Form -->
                <div style="background: var(--white); padding: 2.5rem; border-radius: 20px; box-shadow: 0 8px 24px rgba(124, 92, 252, 0.08); border: 1px solid var(--lavender);">
                    <h2 style="margin-bottom: 1.5rem; color: var(--dark-purple); font-size: 1.4rem;">Security Settings</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        <input type="hidden" name="update_password" value="1">
                        
                        <div class="auth-form-group">
                            <label>Current Password</label>
                            <input type="password" name="current_password" required placeholder="Enter current password">
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div class="auth-form-group" style="margin-bottom: 0;">
                                <label>New Password</label>
                                <input type="password" name="new_password" required placeholder="Min 6 characters">
                            </div>
                            <div class="auth-form-group" style="margin-bottom: 0;">
                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" required placeholder="Confirm new password">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem; border-radius: 12px; background: #4B328F;">Update Password</button>
                    </form>
                </div>

                <!-- Danger Zone -->
                <div style="background: #FFF5F5; padding: 2rem; border-radius: 20px; border: 1px solid #FFCDD2;">
                    <h2 style="margin-bottom: 0.5rem; color: #C62828; font-size: 1.2rem;">Danger Zone</h2>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 1.5rem;">Once you delete your account, there is no going back. All your data will be permanently removed.</p>
                    <button type="button" onclick="if(confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.')) { alert('Account deletion API must be implemented here.'); }" style="background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; padding: 0.6rem 1.5rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
                        Delete My Account
                    </button>
                </div>

            </div>
        </div>
    </main>
</div>

</body>
</html>