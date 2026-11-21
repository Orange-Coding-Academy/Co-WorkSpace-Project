document.addEventListener('DOMContentLoaded', function () {

    /* ------------------------------------------------------------------
     * Revenue chart
     * ------------------------------------------------------------------ */
    const chartElement = document.getElementById('revenueChart');
    const chartData = Array.isArray(window.paymentChartData) ? window.paymentChartData : [];

    if (chartElement && typeof Chart !== 'undefined') {
        new Chart(chartElement, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.date),
                datasets: [{
                    label: 'Revenue',
                    data: chartData.map(item => Number(item.amount) || 0),
                    borderColor: '#315c4d',
                    backgroundColor: 'rgba(49, 92, 77, 0.10)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' ' + Number(ctx.parsed.y).toLocaleString(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    /* ------------------------------------------------------------------
     * Filters: apply automatically when a select or date changes
     * ------------------------------------------------------------------ */
    const filterForm = document.getElementById('paymentsFilters');

    if (filterForm) {
        filterForm.querySelectorAll('select, input[type="date"]').forEach(function (field) {
            field.addEventListener('change', function () {
                filterForm.submit();
            });
        });
    }

    /* ------------------------------------------------------------------
     * Payment details side panel (no Bootstrap required)
     * ------------------------------------------------------------------ */
    const panel = document.getElementById('paymentPanel');
    const overlay = document.getElementById('paymentOverlay');
    const panelBody = document.getElementById('paymentPanelBody');
    const closeButton = document.getElementById('paymentPanelClose');

    if (!panel || !overlay || !panelBody) {
        return;
    }

    // Move to <body> so no parent (transform, overflow, grid) can affect position: fixed
    document.body.appendChild(overlay);
    document.body.appendChild(panel);

    const detailsUrl = panel.dataset.detailsUrl;
    let lastTrigger = null;
    let activeRequest = null;

    function openPanel() {
        overlay.hidden = false;
        // Force a reflow so the CSS transition runs
        void overlay.offsetWidth;

        overlay.classList.add('is-open');
        panel.classList.add('is-open');
        panel.setAttribute('aria-hidden', 'false');
        document.body.classList.add('payment-panel-open');
        panel.focus();
    }

    function closePanel() {
        if (activeRequest) {
            activeRequest.abort();
            activeRequest = null;
        }

        overlay.classList.remove('is-open');
        panel.classList.remove('is-open');
        panel.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('payment-panel-open');

        setTimeout(function () {
            if (!panel.classList.contains('is-open')) {
                overlay.hidden = true;
            }
        }, 250);

        if (lastTrigger) {
            lastTrigger.focus();
            lastTrigger = null;
        }
    }

    function showLoading() {
        panelBody.innerHTML = `
            <div class="payment-loading">
                <div class="payment-spinner" role="status" aria-label="Loading"></div>
                <p>Loading payment details...</p>
            </div>
        `;
    }

    function showError(message) {
        const box = document.createElement('div');
        box.className = 'payment-error';
        box.textContent = message;

        panelBody.innerHTML = '';
        panelBody.appendChild(box);
    }

    function enhanceContent(root) {
        const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
        const nodes = [];

        while (walker.nextNode()) {
            nodes.push(walker.currentNode);
        }

        nodes.forEach(function (node) {
            const text = node.nodeValue.trim();
            const parent = node.parentElement;

            if (!text || !parent || parent.closest('code, button, script, style, .pay-id-wrap')) {
                return;
            }

            if (/^(cs|pi|ch|evt|cus|pm|in|re|py|seti)_[A-Za-z0-9_]{8,}$/.test(text)) {
                const wrap = document.createElement('span');
                wrap.className = 'pay-id-wrap';

                const code = document.createElement('code');
                code.className = 'pay-id';
                code.textContent = text;

                const copy = document.createElement('button');
                copy.type = 'button';
                copy.className = 'pay-copy';
                copy.dataset.copy = text;
                copy.textContent = 'Copy';

                wrap.append(code, copy);
                node.replaceWith(wrap);
            } else if (text === 'N/A') {
                const span = document.createElement('span');
                span.className = 'pay-na';
                span.textContent = 'Not available';
                node.replaceWith(span);
            }
        });
    }

    async function copyText(button) {
        const value = button.dataset.copy || '';

        try {
            await navigator.clipboard.writeText(value);
        } catch (e) {
            const area = document.createElement('textarea');
            area.value = value;
            area.style.position = 'fixed';
            area.style.opacity = '0';
            document.body.appendChild(area);
            area.select();
            document.execCommand('copy');
            area.remove();
        }

        button.textContent = 'Copied';
        setTimeout(function () { button.textContent = 'Copy'; }, 1500);
    }

    async function loadPayment(paymentId) {
        if (activeRequest) {
            activeRequest.abort();
        }

        activeRequest = new AbortController();
        const request = activeRequest;

        showLoading();

        try {
            const response = await fetch(
                `${detailsUrl}/${encodeURIComponent(paymentId)}`,
                {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    },
                    credentials: 'same-origin',
                    signal: request.signal
                }
            );

            if (!response.ok) {
                throw new Error('Unable to load payment details.');
            }

            panelBody.innerHTML = await response.text();
            enhanceContent(panelBody);

        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }
            showError(error.message || 'Unable to load payment details.');
        } finally {
            if (activeRequest === request) {
                activeRequest = null;
            }
        }
    }

    panelBody.addEventListener('click', function (event) {
        const copyButton = event.target.closest('[data-copy]');

        if (copyButton) {
            copyText(copyButton);
        }
    });

    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-payment-id]');

        if (!button) {
            return;
        }

        lastTrigger = button;
        openPanel();
        loadPayment(button.dataset.paymentId);
    });

    overlay.addEventListener('click', closePanel);

    if (closeButton) {
        closeButton.addEventListener('click', closePanel);
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && panel.classList.contains('is-open')) {
            closePanel();
        }
    });
});