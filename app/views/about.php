<?php
// app/views/about.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>
<section style="padding: 5rem 10%; text-align: center; min-height: 60vh;">
    <h1 style="font-size: 3rem; margin-bottom: 1rem;">About StoryNest</h1>
    <p style="font-size: 1.2rem; color: var(--text-light); max-width: 800px; margin: 0 auto 2rem;">
        StoryNest is a magical digital library where writers share their imagination, children discover new worlds, and parents stay connected to their child's reading journey. Every story is a new adventure waiting to be explored.
    </p>
    <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
        <div style="background: var(--lavender); padding: 2rem; border-radius: 16px; width: 250px;">
            <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">✍️ Writers</h3>
            <p style="color: var(--text-light);">Create and publish your magical stories.</p>
        </div>
        <div style="background: var(--light-blue); padding: 2rem; border-radius: 16px; width: 250px;">
            <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">🧒 Children</h3>
            <p style="color: var(--text-light);">Read, learn, and track your progress.</p>
        </div>
        <div style="background: var(--soft-pink); padding: 2rem; border-radius: 16px; width: 250px;">
            <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">👨‍👩‍👧 Parents</h3>
            <p style="color: var(--text-light);">Monitor your child's reading journey.</p>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>