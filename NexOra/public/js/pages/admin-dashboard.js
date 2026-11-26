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

    // Read colours/fonts from the CSS design tokens so charts always match the UI
    function token(name, fallback) {
        var v = getComputedStyle(document.documentElement)
            .getPropertyValue(name)
            .trim();
        return v || fallback;
    }

    function withAlpha(hex, alpha) {
        var m = /^#?([0-9a-f]{6})$/i.exec(hex);
        if (!m) return hex;
        var n = parseInt(m[1], 16);
        return (
            "rgba(" +
            ((n >> 16) & 255) +
            ", " +
            ((n >> 8) & 255) +
            ", " +
            (n & 255) +
            ", " +
            alpha +
            ")"
        );
    }

    function initializeCharts() {
        if (typeof Chart === "undefined") {
            console.error("Chart.js is not loaded!");
            ["revenueEmpty", "bookingEmpty"].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.style.display = "flex";
            });
            return;
        }

        var data = window.adminDashboardData || {};

        var cGmv = token("--nx-chart-gmv", "#27865B");
        var cCommission = token("--nx-chart-commission", "#D9A441");

        Chart.defaults.font.family = token(
            "--nx-font-body",
            "Inter, sans-serif",
        );
        Chart.defaults.font.size = 12;
        Chart.defaults.color = token("--nx-text-secondary", "#737373");

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
                            borderColor: cGmv,
                            backgroundColor: withAlpha("#202020", 0.08),
                            borderWidth: 3,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: "#ffffff",
                            pointBorderWidth: 2,
                            fill: true,
                            tension: 0.4,
                        },
                        {
                            label: "Platform Commission",
                            data: revenue.platformCommission,
                            borderColor: cCommission,
                            backgroundColor: withAlpha(cCommission, 0.1),
                            borderWidth: 3,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: "#ffffff",
                            pointBorderWidth: 2,
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
                            align: "end",
                            labels: { usePointStyle: true, boxWidth: 8 },
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
                        x: {
                            grid: { display: false },
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: token("--nx-divider", "#e9eee9") },
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
            completed: token("--nx-chart-completed", "#27865B"),
            confirmed: token("--nx-chart-confirmed", "#71A184"),
            pending: token("--color-warning", "#D9A441"),
            cancelled: token("--color-danger", "#B91C1C"),
        };

        var fallbackColor = token("--color-text-muted", "#737373");

        if (
            bookingCanvas &&
            statusLabels.length > 0 &&
            statusValues.some((value) => Number(value) > 0)
        ) {
            var colors = statusLabels.map(function (status) {
                return statusColors[status] || fallbackColor;
            });

            var totalBookings = statusValues.reduce(function (sum, value) {
                return sum + Number(value || 0);
            }, 0);

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
                            borderWidth: 3,
                            borderColor: token("--nx-card", "#ffffff"),
                            hoverOffset: 10,
                            borderRadius: 5,
                            spacing: 3,
                        },
                    ],
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: "75%",

                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1000,
                    },

                    plugins: {
                        legend: {
                            display: false,
                        },

                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var value = Number(context.raw || 0);

                                    var percentage =
                                        totalBookings > 0
                                            ? (
                                                  (value / totalBookings) *
                                                  100
                                              ).toFixed(1)
                                            : 0;

                                    return (
                                        context.label +
                                        ": " +
                                        value +
                                        " (" +
                                        percentage +
                                        "%)"
                                    );
                                },
                            },
                        },
                    },
                },

                plugins: [
                    {
                        id: "doughnutCenterText",

                        afterDraw: function (chart) {
                            var ctx = chart.ctx;
                            var centerX =
                                (chart.chartArea.left + chart.chartArea.right) /
                                2;
                            var centerY =
                                (chart.chartArea.top + chart.chartArea.bottom) /
                                2;

                            ctx.save();

                            ctx.textAlign = "center";
                            ctx.textBaseline = "middle";

                            ctx.font = "700 28px Inter, sans-serif";
                            ctx.fillStyle = token("--color-text", "#171717");

                            ctx.fillText(
                                totalBookings.toLocaleString(),
                                centerX,
                                centerY - 7,
                            );

                            ctx.font = "500 11px Inter, sans-serif";
                            ctx.fillStyle = token("--color-text-muted", "#737373");

                            ctx.fillText(
                                "Total Bookings",
                                centerX,
                                centerY + 18,
                            );

                            ctx.restore();
                        },
                    },
                ],
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
