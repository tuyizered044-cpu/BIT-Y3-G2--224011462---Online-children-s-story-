<?php
// app/views/auth/register.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/navbar.php';
$flash = getFlash();
?>
<div class="auth-container">
    <h2>Join StoryNest</h2>
    
    <?php if ($flash): ?>
        <div class="auth-alert <?= $flash['type'] === 'error' ? 'error' : 'success' ?>" role="status">
            <?= sanitize($flash['message']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        
        <div class="auth-form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Choose a username">
        </div>

        <div class="auth-form-group">
            <label>Email Address</label>
            <input type="email" name="email" required placeholder="Enter your email">
        </div>

        <div class="auth-form-group">
            <label>I am a:</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="writer">Writer</option>
                <option value="child">Child</option>
                <option value="parent">Parent</option>
            </select>
        </div>

        <div class="auth-form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Create a password">
        </div>

        <div class="auth-form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required placeholder="Confirm your password">
        </div>

        <button type="submit" class="btn btn-primary auth-btn">Sign Up</button>
    </form>

    <p class="auth-footer-text">
        Already have an account? <a href="<?= BASE_URL ?>/index.php?page=login">Login</a>
    </p>
</div>
<?php require_once BASE_PATH . '/app/includes/footer.php'; ?>