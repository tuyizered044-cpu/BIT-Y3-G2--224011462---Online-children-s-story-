<?php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/navbar.php';
?>
<section style="padding: 5rem 10%; min-height: 60vh; max-width: 800px; margin: 0 auto;">
    <h1 style="font-size: 2.5rem; color: var(--dark-purple); margin-bottom: 2rem;">Terms of Service</h1>
    <div style="color: var(--text-dark); line-height: 1.8;">
        <p>Last updated: <?= date('F d, Y') ?></p>
        
        <h3 style="color: var(--primary-purple); margin-top: 2rem;">1. Acceptance of Terms</h3>
        <p>By accessing or using StoryNest, you agree to be bound by these Terms. If you disagree with any part of the terms, you may not access the service.</p>
        
        <h3 style="color: var(--primary-purple); margin-top: 2rem;">2. User Accounts</h3>
        <p>When you create an account with us, you must provide accurate, complete, and current information. You are responsible for safeguarding the password that you use to access the Service.</p>
        
        <h3 style="color: var(--primary-purple); margin-top: 2rem;">3. Content</h3>
        <p>Our Service allows you to post, link, store, share and otherwise make available certain information, text, graphics, or other material. You are responsible for the content you post.</p>
    </div>
</section>
<?php require_once BASE_PATH . '/app/includes/footer.php'; ?>