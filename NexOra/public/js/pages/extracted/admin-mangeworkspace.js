
        const searchInput = document.querySelector('#workspace-search');
        const statusFilter = document.querySelector('#workspace-status');
        const resetFiltersButton = document.querySelector('#reset-workspace-filters');
        const emptyRow = document.querySelector('#workspace-empty');
        const workspaceRows = document.querySelectorAll('[data-workspace-status]');
        const pagination = document.querySelector('#workspace-pagination');
        const paginationSummary = document.querySelector('#workspace-pagination-summary');
        const paginationControls = document.querySelector('#workspace-pagination-controls');
        const pageSize = 5;
        let currentPage = 1;

        function filterWorkspaces(resetPage = true) {
            if (resetPage) currentPage = 1;

            const search = searchInput.value.trim().toLocaleLowerCase();
            const status = statusFilter.value;
            const matchingRows = [];

            for (const row of workspaceRows) {
                const workspaceSearchFields = [row.querySelector('.workspace-details-link span span').textContent, row.cells[1].textContent, row.cells[3].textContent];
                const matchesSearch = workspaceSearchFields.some(value =>
                    value.toLocaleLowerCase().includes(search)
                );
                const matchesStatus = !status || row.dataset.workspaceStatus === status;

                if (matchesSearch && matchesStatus) matchingRows.push(row);
            }

            const totalPages = Math.max(1, Math.ceil(matchingRows.length / pageSize));
            currentPage = Math.min(currentPage, totalPages);
            const firstIndex = (currentPage - 1) * pageSize;
            const visibleRows = matchingRows.slice(firstIndex, firstIndex + pageSize);

            for (const row of workspaceRows) {
                row.hidden = !visibleRows.includes(row);
            }

            emptyRow.hidden = matchingRows.length > 0;
            renderPagination(matchingRows.length, totalPages, firstIndex);
        }

        function renderPagination(totalResults, totalPages, firstIndex) {
            paginationControls.replaceChildren();
            pagination.hidden = totalResults === 0;

            if (totalResults === 0) return;

            const lastResult = Math.min(firstIndex + pageSize, totalResults);
            paginationSummary.textContent = `Showing ${firstIndex + 1} to ${lastResult} of ${totalResults} results`;

            const addButton = (label, page, disabled = false, current = false) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `page-button${current ? ' is-current' : ''}`;
                button.textContent = label;
                button.disabled = disabled;
                button.setAttribute('aria-label', label === '‹' ? 'Previous page' : label === '›' ? 'Next page' : `Page ${label}`);
                button.addEventListener('click', () => {
                    currentPage = page;
                    filterWorkspaces(false);
                });
                paginationControls.append(button);
            };

            addButton('‹', currentPage - 1, currentPage === 1);

            for (let page = 1; page <= totalPages; page++) {
                addButton(String(page), page, false, page === currentPage);
            }

            addButton('›', currentPage + 1, currentPage === totalPages);
        }

        resetFiltersButton.addEventListener('click', () => {
            searchInput.value = '';
            statusFilter.value = '';
            filterWorkspaces();
        });
        searchInput.addEventListener('input', () => filterWorkspaces());
        statusFilter.addEventListener('change', () => filterWorkspaces());
        filterWorkspaces();
    