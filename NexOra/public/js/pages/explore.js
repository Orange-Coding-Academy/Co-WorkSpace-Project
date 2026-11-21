/* NexOra Explore — AJAX filtering, pagination and image gallery.
 * Falls back to normal Laravel navigation if fetch/HTML replacement fails.
 */
(function () {
    'use strict';

    window.changeImage = function (button, direction) {
        if (!button || !Number.isFinite(Number(direction))) return;
        const gallery = button.closest('.gallery');
        if (!gallery) return;
        const images = Array.from(gallery.querySelectorAll('img'));
        if (images.length < 2) return;

        let current = images.findIndex(function (image) {
            return image.classList.contains('active');
        });
        if (current < 0) current = 0;

        images[current].classList.remove('active');
        current = (current + Number(direction) + images.length) % images.length;
        images[current].classList.add('active');

        const counter = gallery.querySelector('.current');
        if (counter) counter.textContent = String(current + 1);
    };

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('explore-filters');
        const results = document.getElementById('explore-results');
        if (!form || !results || !window.fetch || !window.DOMParser) return;

        let activeRequest = null;

        async function loadResults(url, updateHistory) {
            if (activeRequest) activeRequest.abort();
            activeRequest = new AbortController();

            results.classList.add('is-loading');
            const pagination = results.querySelector('.nx-explore__pagination');
            if (pagination) pagination.classList.add('is-loading');

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    },
                    signal: activeRequest.signal
                });
                if (!response.ok) throw new Error('Could not load rooms.');

                const html = await response.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const nextResults = doc.querySelector('#explore-results');
                if (!nextResults) throw new Error('Results section not found.');

                results.innerHTML = nextResults.innerHTML;
                results.classList.remove('is-loading');

                if (updateHistory) {
                    window.history.pushState({ explore: true }, '', response.url || url);
                }
            } catch (error) {
                if (error.name === 'AbortError') return;
                // Preserve reliable navigation if the AJAX response cannot be parsed.
                window.location.href = url;
            } finally {
                results.classList.remove('is-loading');
                activeRequest = null;
            }
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const url = new URL(form.action, window.location.origin);
            const data = new FormData(form);
            data.forEach(function (value, key) {
                if (String(value).trim() !== '') url.searchParams.set(key, value);
                else url.searchParams.delete(key);
            });
            url.searchParams.delete('page');
            loadResults(url.toString(), true);
        });

        form.addEventListener('click', function (event) {
            const resetLink = event.target.closest('a[href]');
            if (!resetLink || !resetLink.classList.contains('btn-light')) return;
            event.preventDefault();
            form.reset();
            loadResults(resetLink.href, true);
        });

        results.addEventListener('click', function (event) {
            const link = event.target.closest('.nx-explore__pagination a');
            if (!link || link.origin !== window.location.origin) return;
            event.preventDefault();
            loadResults(link.href, true);
        });

        window.addEventListener('popstate', function () {
            loadResults(window.location.href, false);
        });
    });
})();
