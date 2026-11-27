<?php
// app/views/auth/login.php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/navbar.php';
$flash = getFlash();
?>
<div class="auth-container">
    <h2>Login to StoryNest</h2>
    
    <?php if ($flash): ?>
        <div class="auth-alert <?= $flash['type'] === 'error' ? 'error' : 'success' ?>" role="status">
            <?= sanitize($flash['message']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        
        <div class="auth-form-group">
            <label>Email Address</label>
            <input type="email" name="email" required placeholder="Enter your email">
        </div>

        <div class="auth-form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter your password">
        </div>

        <button type="submit" class="btn btn-primary auth-btn">Login</button>
    </form>

    <p class="auth-footer-text">
        Don't have an account? <a href="<?= BASE_URL ?>/index.php?page=register">Sign Up</a>
    </p>
</div>
<?php require_once BASE_PATH . '/app/includes/footer.php'; ?>