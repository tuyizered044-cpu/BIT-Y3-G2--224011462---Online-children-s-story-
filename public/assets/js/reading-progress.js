// public/assets/js/reading-progress.js
document.addEventListener('DOMContentLoaded', () => {
    const storyContent = document.getElementById('story-content');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');

    if (!storyContent || !progressBar || !progressText || !window.storyId) return;

    let lastSaved = 0;
    let saveTimeout;

    function updateProgress() {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        let progress = docHeight > 0 ? Math.min(100, Math.round((scrollTop / docHeight) * 100)) : 100;

        progressBar.style.width = progress + '%';
        progressText.textContent = progress + '%';

        // Save progress if it changed by at least 5% or reached 100%
        if (Math.abs(progress - lastSaved) >= 5 || progress === 100) {
            lastSaved = progress;
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                fetch(`${BASE_URL}/api/reading-progress.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        story_id: window.storyId,
                        progress: progress,
                        csrf_token: csrfToken
                    })
                });
            }, 500);
        }
    }

    window.addEventListener('scroll', updateProgress);
    updateProgress();
});