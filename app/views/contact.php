<?php
require_once BASE_PATH . '/app/includes/header.php';
require_once BASE_PATH . '/app/includes/navbar.php';
?>
<section style="padding: 5rem 10%; min-height: 60vh;">
    <div style="max-width: 600px; margin: 0 auto; text-align: center;">
        <h1 style="font-size: 3rem; color: var(--dark-purple); margin-bottom: 1rem;">Contact Us</h1>
        <p style="font-size: 1.2rem; color: var(--text-light); margin-bottom: 2rem;">
            Have a question or feedback? Send us a message!
        </p>
        <form style="background: var(--white); padding: 2.5rem; border-radius: 20px; box-shadow: 0 8px 24px rgba(124, 92, 252, 0.08); border: 1px solid var(--lavender); text-align: left;" onsubmit="event.preventDefault(); alert('Message sent successfully!');">
            <div class="auth-form-group">
                <label>Your Name</label>
                <input type="text" required placeholder="John Doe">
            </div>
            <div class="auth-form-group">
                <label>Email Address</label>
                <input type="email" required placeholder="john@example.com">
            </div>
            <div class="auth-form-group">
                <label>Message</label>
                <textarea rows="5" required placeholder="How can we help?" style="width: 100%; padding: 0.9rem; border: 1.5px solid #E0D8F0; border-radius: 12px; font-family: inherit;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; border-radius: 12px;">Send Message</button>
        </form>
    </div>
</section>
<?php require_once BASE_PATH . '/app/includes/footer.php'; ?>s