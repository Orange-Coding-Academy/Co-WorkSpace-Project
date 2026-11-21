
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('explore-filters');
    const results = document.getElementById('explore-results');

    if (!form || !results) {
        console.error('Explore form or results section not found.');
        return;
    }

    let controller = null;
    let requestNumber = 0;
    let filterTimer = null;

    // =====================================
    // LOAD RESULTS WITHOUT PAGE REFRESH
    // =====================================

    async function loadResults(url, addHistory = true) {

        if (controller) {
            controller.abort();
        }

        controller = new AbortController();

        const currentRequest = ++requestNumber;

        results.classList.add('is-loading');

        try {

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                },
                signal: controller.signal
            });

            if (!response.ok) {
                throw new Error('Request failed: ' + response.status);
            }

            const html = await response.text();

            const parser = new DOMParser();
            const documentHTML = parser.parseFromString(html, 'text/html');

            const newResults = documentHTML.getElementById('explore-results');

            if (!newResults) {
                throw new Error('Could not find explore-results in response.');
            }

            // Ignore outdated responses
            if (currentRequest !== requestNumber) return;

            // IMPORTANT:
            // Keep the original results element.
            // Replace its content only.
            results.innerHTML = newResults.innerHTML;

            results.classList.remove('is-loading');

            if (addHistory) {
                history.pushState({}, '', url);
            }

            // Keep the results section in view
            results.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

        } catch (error) {

            if (error.name === 'AbortError') return;

            console.error('Explore loading error:', error);

            results.classList.remove('is-loading');

        } finally {

            if (currentRequest === requestNumber) {
                controller = null;
            }

        }
    }

    // =====================================
    // BUILD FILTER URL
    // =====================================

    function getFilterUrl() {

        const url = new URL(form.action, window.location.origin);
        const formData = new FormData(form);

        url.search = '';

        for (const [key, value] of formData.entries()) {

            if (value.trim() !== '') {
                url.searchParams.set(key, value);
            }

        }

        return url.toString();
    }

    // =====================================
    // FILTER SUBMISSION
    // =====================================

    form.addEventListener('submit', function (event) {

        event.preventDefault();

        clearTimeout(filterTimer);

        loadResults(getFilterUrl());

    });

    // Select filters
    form.querySelectorAll('select').forEach(function (select) {

        select.addEventListener('change', function () {
            form.requestSubmit();
        });

    });

    // Number filters
    form.querySelectorAll('input[type="number"]').forEach(function (input) {

        input.addEventListener('input', function () {

            clearTimeout(filterTimer);

            filterTimer = setTimeout(function () {
                form.requestSubmit();
            }, 700);

        });

        input.addEventListener('change', function () {

            clearTimeout(filterTimer);
            form.requestSubmit();

        });

    });

    // =====================================
    // PAGINATION
    // =====================================

    document.addEventListener('click', function (event) {

        const link = event.target.closest(
            '#explore-results .nx-page-number'
        );

        if (!link) return;

        event.preventDefault();

        loadResults(link.href);

    });

    // =====================================
    // IMAGE GALLERY
    // =====================================

    document.addEventListener('click', function (event) {

        const prev = event.target.closest('[data-gallery-prev]');
        const next = event.target.closest('[data-gallery-next]');

        if (!prev && !next) return;

        const button = prev || next;
        const gallery = button.closest('[data-gallery]');

        if (!gallery) return;

        const images = gallery.querySelectorAll('.nx-room-card__image');

        if (!images.length) return;

        let index = Array.from(images).findIndex(function (image) {
            return image.classList.contains('is-active');
        });

        if (index === -1) index = 0;

        if (prev) {
            index = (index - 1 + images.length) % images.length;
        } else {
            index = (index + 1) % images.length;
        }

        images.forEach(function (image, i) {
            image.classList.toggle('is-active', i === index);
        });

        const counter = gallery.querySelector('[data-gallery-current]');

        if (counter) {
            counter.textContent = index + 1;
        }

    });

    // =====================================
    // BROWSER BACK / FORWARD
    // =====================================

    window.addEventListener('popstate', function () {

        clearTimeout(filterTimer);

        loadResults(window.location.href, false);

    });

});
