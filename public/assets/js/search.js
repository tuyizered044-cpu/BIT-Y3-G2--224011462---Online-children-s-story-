// public/assets/js/search.js
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('live-search');
    const ageFilter = document.getElementById('filter-age');
    const resultsContainer = document.getElementById('story-results');

    if (!searchInput || !resultsContainer) return;

    let debounceTimer;

    function fetchStories() {
        const search = searchInput.value.trim();
        const age = ageFilter ? ageFilter.value : '';
        
        resultsContainer.innerHTML = '<p style="text-align:center; width:100%; color: var(--text-light);">Searching...</p>';

        // FETCH VIA INDEX.PHP ROUTER
        const url = `${BASE_URL}/index.php?page=api-stories-search&search=${encodeURIComponent(search)}&age_group=${encodeURIComponent(age)}`;

        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    if (data.data.length === 0) {
                        resultsContainer.innerHTML = '<p style="text-align:center; width:100%; color: var(--text-light);">No stories found.</p>';
                        return;
                    }
                    resultsContainer.innerHTML = data.data.map((story, index) => `
                        <div class="story-card">
                            <div class="story-card-img">
                                <div style="width:100%; height:100%; background: ${['linear-gradient(to bottom, #87CEEB, #90EE90)', 'linear-gradient(to bottom, #191970, #483D8B)', 'linear-gradient(to bottom, #228B22, #556B2F)'][index % 3]};"></div>
                                <span class="age-badge">${story.age_group}</span>
                            </div>
                            <div class="story-card-content">
                                <h3>${story.title}</h3>
                                <p style="color: var(--text-light); font-size: 0.9rem;">By ${story.author}</p>
                                <div class="story-meta">
                                    <span>📖 ${story.category}</span>
                                    <a href="${BASE_URL}/index.php?page=story&id=${story.story_id}" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.9rem;">Read Story &rarr;</a>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    resultsContainer.innerHTML = `<p style="text-align:center; width:100%; color: red;">${data.message || 'Error fetching stories.'}</p>`;
                }
            })
            .catch((err) => {
                console.error(err);
                resultsContainer.innerHTML = '<p style="text-align:center; width:100%; color: red;">Error fetching stories.</p>';
            });
    }

    // Only trigger fetch on user input, not immediately on page load
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchStories, 300);
    });

    if (ageFilter) {
        ageFilter.addEventListener('change', fetchStories);
    }
});