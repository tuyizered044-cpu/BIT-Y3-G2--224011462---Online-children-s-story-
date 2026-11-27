// public/assets/js/stories.js
(() => {
    const form = document.querySelector('.story-filters');
    const results = document.querySelector('[data-story-results]');
    if (!form || !results) return;

    const search = form.querySelector('[data-story-search]');
    const filters = Array.from(form.querySelectorAll('[data-story-filter]'));
    const status = document.querySelector('[data-search-status]');
    const emptyState = document.querySelector('[data-no-stories]');
    const pagination = document.querySelector('[data-story-pagination]');
    let timer;
    let requestId = 0;

    const updateResults = async () => {
        const currentRequest = ++requestId;
        const params = new URLSearchParams(new FormData(form));
        params.delete('page');
        params.set('page_num', '1');
        status.textContent = 'Searching stories…';

        try {
            const response = await fetch(`${results.dataset.searchUrl}&${params.toString()}`, {
                headers: { Accept: 'application/json' }
            });
            if (!response.ok) throw new Error(`Search failed (${response.status})`);
            const data = await response.json();
            if (currentRequest !== requestId) return;

            results.replaceChildren();
            data.stories.forEach((story) => {
                const article = document.createElement('article');
                article.className = 'story-card';

                const art = document.createElement('div');
                art.className = 'story-card-img story-art';
                art.setAttribute('aria-hidden', 'true');
                art.textContent = story.illustration;

                const content = document.createElement('div');
                content.className = 'story-card-content';

                const category = document.createElement('span');
                category.className = 'story-category';
                category.textContent = story.category;

                const title = document.createElement('h2');
                title.textContent = story.title;

                const description = document.createElement('p');
                description.className = 'story-description';
                description.textContent = story.excerpt;

                const meta = document.createElement('div');
                meta.className = 'story-meta';
                const age = document.createElement('span');
                age.textContent = `Ages ${story.age_group}`;
                const link = document.createElement('a');
                link.className = 'btn btn-primary';
                link.href = story.url;
                link.textContent = 'Read';

                meta.append(age, link);
                content.append(category, title, description, meta);
                article.append(art, content);
                results.append(article);
            });

            const count = data.stories.length;
            status.textContent = `${data.total} ${data.total === 1 ? 'story' : 'stories'} found`;
            emptyState.hidden = count !== 0;
            results.dataset.storyPage = String(data.page);
            pagination.replaceChildren();
            const visiblePages = new Set([1, data.pages]);
            for (let page = Math.max(1, data.page - 2); page <= Math.min(data.pages, data.page + 2); page += 1) {
                visiblePages.add(page);
            }
            const pageNumbers = Array.from(visiblePages).sort((a, b) => a - b);
            let previousPage = 0;
            pageNumbers.forEach((page) => {
                if (previousPage && page > previousPage + 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.setAttribute('aria-hidden', 'true');
                    ellipsis.textContent = '…';
                    pagination.append(ellipsis);
                }
                const link = document.createElement('a');
                link.className = `btn ${page === data.page ? 'btn-primary' : 'btn-outline'}`;
                const pageParams = new URLSearchParams(new FormData(form));
                pageParams.set('page', 'stories');
                pageParams.set('page_num', String(page));
                link.href = `${form.action}?${pageParams.toString()}`;
                link.textContent = String(page);
                if (page === data.page) link.setAttribute('aria-current', 'page');
                pagination.append(link);
                previousPage = page;
            });
        } catch (error) {
            if (currentRequest !== requestId) return;
            status.textContent = 'Could not load stories. Use Search to try again.';
            console.error(error);
        }
    };

    search.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(updateResults, 250);
    });
    filters.forEach((filter) => filter.addEventListener('change', updateResults));
})();
