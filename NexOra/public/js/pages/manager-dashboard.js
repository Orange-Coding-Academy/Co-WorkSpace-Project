(function () {
    function initializeManagerCharts() {
        if (typeof Chart === "undefined") {
            console.error("Chart.js is not loaded.");
            return;
        }

        const data = window.managerDashboardData || {};

        // ==================================
        // REVENUE CHART
        // ==================================

        const revenue = data.revenue || {};

        const revenueCanvas = document.getElementById("managerRevenueChart");
        const revenueEmpty = document.getElementById("managerRevenueEmpty");

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
                            borderColor: "#2563EB",
                            backgroundColor: "rgba(37, 99, 235, 0.12)",
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                        },
                        {
                            label: "Workspace Revenue",
                            data: revenue.workspaceRevenue,
                            borderColor: "#0EA5E9",
                            backgroundColor: "rgba(14, 165, 233, 0.12)",
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
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
                        },
                    },
                },
            });

            if (revenueEmpty) revenueEmpty.style.display = "none";
        } else if (revenueEmpty) {
            revenueEmpty.style.display = "flex";
        }

        // ==================================
        // BOOKING STATUS DOUGHNUT CHART
        // ==================================

        const trend = data.bookingTrend || {};

        const bookingCanvas = document.getElementById(
            "managerBookingTrendChart",
        );

        const bookingEmpty = document.getElementById("managerBookingEmpty");

        // Latest booking status values
        const statuses = [
            {
                key: "completed",
                label: "Completed",
                color: "#22C55E",
            },
            {
                key: "confirmed",
                label: "Confirmed",
                color: "#06B6D4",
            },
            {
                key: "pending",
                label: "Pending",
                color: "#F59E0B",
            },
            {
                key: "cancelled",
                label: "Cancelled",
                color: "#F43F5E",
            },
        ];

        // Calculate total bookings from the latest available period
        const labels = trend.labels || [];
        const lastIndex = labels.length - 1;

        const statusValues = statuses.map(function (status) {
            const values = trend[status.key] || [];

            return Number(values[lastIndex] || 0);
        });

        const totalBookings = statusValues.reduce(function (sum, value) {
            return sum + value;
        }, 0);

        if (bookingCanvas && labels.length > 0 && totalBookings > 0) {
            const colors = statuses.map(function (status) {
                return status.color;
            });

            new Chart(bookingCanvas, {
                type: "doughnut",

                data: {
                    labels: statuses.map(function (status) {
                        return status.label;
                    }),

                    datasets: [
                        {
                            data: statusValues,
                            backgroundColor: colors,
                            borderWidth: 3,
                            borderColor: "#ffffff",
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
                                    const value = Number(context.raw || 0);

                                    const percentage =
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
                        id: "managerDoughnutCenterText",

                        afterDraw: function (chart) {
                            const ctx = chart.ctx;

                            const centerX =
                                (chart.chartArea.left + chart.chartArea.right) /
                                2;

                            const centerY =
                                (chart.chartArea.top + chart.chartArea.bottom) /
                                2;

                            ctx.save();

                            ctx.textAlign = "center";
                            ctx.textBaseline = "middle";

                            ctx.font = "700 28px Inter, sans-serif";
                            ctx.fillStyle = "#053631";

                            ctx.fillText(
                                totalBookings.toLocaleString(),
                                centerX,
                                centerY - 7,
                            );

                            ctx.font = "500 11px Inter, sans-serif";
                            ctx.fillStyle = "#94A3B8";

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
            const bookingLegend = document.getElementById(
                "managerBookingLegend",
            );

            if (bookingLegend) {
                bookingLegend.innerHTML = statuses
                    .map(function (status, index) {
                        const value = statusValues[index];

                        const percentage =
                            totalBookings > 0
                                ? ((value / totalBookings) * 100).toFixed(1)
                                : 0;

                        return `
                    <div class="nx-legend-item">
                        <span
                            class="nx-legend-dot"
                            style="background-color: ${status.color}">
                        </span>

                        <span>${status.label}</span>

                        <strong>
                            ${value}
                            <small>(${percentage}%)</small>
                        </strong>
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

    initializeManagerCharts();
})();
