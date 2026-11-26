
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bookingFiltersForm');

    if (!form) return;

    const filters = form.querySelectorAll('select, input[type="date"]');

    filters.forEach(function (filter) {
        filter.addEventListener('change', function () {
            form.requestSubmit();
        });
    });
});
