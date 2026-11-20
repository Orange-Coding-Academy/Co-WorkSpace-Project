(function () {
    var root = document.getElementById("nxDashboard");
    var toggle = document.getElementById("sidebarToggle");
    var overlay = document.getElementById("sidebarOverlay");

    // ==============================
    // SIDEBAR
    // ==============================

    if (root && toggle) {
        var KEY = "nx-sidebar-collapsed";
        var mq = window.matchMedia("(min-width: 1024px)");

        function safeGet() {
            try {
                return localStorage.getItem(KEY) === "1";
            } catch (e) {
                return false;
            }
        }

        function safeSet(v) {
            try {
                localStorage.setItem(KEY, v ? "1" : "0");
            } catch (e) {}
        }

        function setOpen(open) {
            root.classList.toggle("is-sidebar-open", open);
            toggle.setAttribute("aria-expanded", String(open));
        }

        function applyMode() {
            if (mq.matches) {
                setOpen(false);
                root.classList.toggle("is-collapsed", safeGet());

                toggle.setAttribute(
                    "aria-expanded",
                    String(!root.classList.contains("is-collapsed")),
                );
            } else {
                root.classList.remove("is-collapsed");
            }
        }

        toggle.addEventListener("click", function () {
            if (mq.matches) {
                var collapsed = !root.classList.contains("is-collapsed");

                root.classList.toggle("is-collapsed", collapsed);
                safeSet(collapsed);

                toggle.setAttribute("aria-expanded", String(!collapsed));
            } else {
                setOpen(!root.classList.contains("is-sidebar-open"));
            }
        });

        if (overlay) {
            overlay.addEventListener("click", function () {
                setOpen(false);
            });
        }

        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") setOpen(false);
        });

        mq.addEventListener
            ? mq.addEventListener("change", applyMode)
            : mq.addListener(applyMode);

        applyMode();
    }

    // ==============================
    // DASHBOARD CHARTS
    // ==============================

    function initializeCharts() {
        if (typeof Chart === "undefined") {
            console.error("Chart.js is not loaded!");
            return;
        }

        var data = window.adminDashboardData || {};

        // ------------------------------
        // REVENUE CHART
        // ------------------------------

        var revenueCanvas = document.getElementById("revenueChart");
        var revenueEmpty = document.getElementById("revenueEmpty");

        var revenue = data.revenue || {};

        if (
            revenueCanvas &&
            Array.isArray(revenue.labels) &&
            revenue.labels.length > 0
        ) {
            new Chart(revenueCanvas, {
                type: "line",

                data: {
                    labels: revenue.labels,

                    datasets: [
                        {
                            label: "Booking GMV",
                            data: revenue.bookingGMV,
                            borderColor: "#526044",
                            backgroundColor: "rgba(82, 96, 68, 0.10)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                        },
                        {
                            label: "Platform Commission",
                            data: revenue.platformCommission,
                            borderColor: "#C5A46D",
                            backgroundColor: "rgba(197, 164, 109, 0.10)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                        },
                    ],
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    interaction: {
                        intersect: false,
                        mode: "index",
                    },

                    plugins: {
                        legend: {
                            position: "top",
                        },

                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return (
                                        context.dataset.label +
                                        ": " +
                                        Number(context.raw).toFixed(2) +
                                        " JOD"
                                    );
                                },
                            },
                        },
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return value + " JOD";
                                },
                            },
                        },
                    },
                },
            });

            if (revenueEmpty) {
                revenueEmpty.style.display = "none";
            }
        } else if (revenueEmpty) {
            revenueEmpty.style.display = "flex";
        }

        // ------------------------------
        // BOOKING STATUS CHART
        // ------------------------------

        var bookingCanvas = document.getElementById("bookingChart");
        var bookingEmpty = document.getElementById("bookingEmpty");
        var bookingLegend = document.getElementById("bookingLegend");

        var statuses = data.bookingStatuses || {};

        var statusLabels = Object.keys(statuses);
        var statusValues = Object.values(statuses);

        var statusColors = {
            completed: "#526044",
            confirmed: "#8C9B7A",
            pending: "#C5A46D",
            cancelled: "#A66F5B",
        };

        if (
            bookingCanvas &&
            statusLabels.length > 0 &&
            statusValues.some((value) => Number(value) > 0)
        ) {
            var colors = statusLabels.map(function (status) {
                return statusColors[status] || "#A0A0A0";
            });

            new Chart(bookingCanvas, {
                type: "doughnut",

                data: {
                    labels: statusLabels.map(function (status) {
                        return status.charAt(0).toUpperCase() + status.slice(1);
                    }),

                    datasets: [
                        {
                            data: statusValues,
                            backgroundColor: colors,
                            borderWidth: 2,
                            hoverOffset: 6,
                        },
                    ],
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: "70%",

                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                },
            });

            // Custom Legend
            if (bookingLegend) {
                bookingLegend.innerHTML = statusLabels
                    .map(function (status, index) {
                        var label =
                            status.charAt(0).toUpperCase() + status.slice(1);

                        return `
                        <div class="nx-legend-item">
                            <span class="nx-legend-dot"
                                style="background-color: ${colors[index]}">
                            </span>

                            <span>${label}</span>

                            <strong>${statusValues[index]}</strong>
                        </div>
                    `;
                    })
                    .join("");
            }

            if (bookingEmpty) {
                bookingEmpty.style.display = "none";
            }
        } else if (bookingEmpty) {
            bookingEmpty.style.display = "flex";
        }
    }

    // ==============================
    // INITIALIZATION
    // ==============================

    initializeCharts();

    if (window.feather) {
        window.feather.replace();
    }
})();
