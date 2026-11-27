<?php
// app/includes/footer.php
?>
<footer class="site-footer">
    <div class="footer-container">
        
        <!-- Column 1: Brand & Newsletter -->
        <div class="footer-col brand-col">
            <div class="footer-brand">
                <span>📖</span> <?= APP_NAME ?>
            </div>
            <p class="footer-desc">A little magic in every story. Join our community of writers, readers, and dreamers.</p>
            
            <div class="newsletter-box">
                <h4>Get weekly stories! 📬</h4>
                <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing to StoryNest!');">
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>

        <!-- Column 2: Quick Links -->
        <div class="footer-col">
            <h3>Quick Links</h3>
            <ul class="footer-links">
                <li><a href="<?= BASE_URL ?>/index.php?page=stories">Explore Stories</a></li>
                <li><a href="<?= BASE_URL ?>/index.php?page=categories">Categories</a></li>
                <li><a href="<?= BASE_URL ?>/index.php?page=about">About Us</a></li>
                <li><a href="<?= BASE_URL ?>/index.php?page=register">Become a Writer</a></li>
            </ul>
        </div>

        <!-- Column 3: For Parents -->
        <div class="footer-col">
            <h3>For Parents</h3>
            <ul class="footer-links">
                <li><a href="<?= BASE_URL ?>/index.php?page=register">Create Child Account</a></li>
                <li><a href="<?= BASE_URL ?>/index.php?page=login">Parent Dashboard</a></li>
                <li><a href="<?= BASE_URL ?>/index.php?page=privacy">Privacy Policy</a></li>
            </ul>
        </div>

        <!-- Column 4: Support (Updated Links) -->
        <div class="footer-col">
            <h3>Support</h3>
            <ul class="footer-links">
                <li><a href="<?= BASE_URL ?>/index.php?page=help">Help Center</a></li>
                <li><a href="<?= BASE_URL ?>/index.php?page=contact">Contact Us</a></li>
                <li><a href="<?= BASE_URL ?>/index.php?page=terms">Terms of Service</a></li>
                <li><a href="<?= BASE_URL ?>/index.php?page=faq">FAQ</a></li>
            </ul>
            <div class="social-links">
                <a href="#" aria-label="Facebook">📘</a>
                <a href="#" aria-label="Twitter">🐦</a>
                <a href="#" aria-label="Instagram">📸</a>
                <a href="#" aria-label="YouTube">▶️</a>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
        <p>Made with 💜 for curious minds.</p>
    </div>
</footer>
</body>
</html>