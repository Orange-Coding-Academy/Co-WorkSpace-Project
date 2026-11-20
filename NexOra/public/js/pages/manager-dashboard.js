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
                            borderColor: "#526044",
                            backgroundColor: "rgba(82, 96, 68, 0.10)",
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                        },
                        {
                            label: "Workspace Revenue",
                            data: revenue.workspaceRevenue,
                            borderColor: "#C5A46D",
                            backgroundColor: "rgba(197, 164, 109, 0.10)",
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
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
        // BOOKING TREND CHART
        // ==================================

        const trend = data.bookingTrend || {};

        const bookingCanvas = document.getElementById(
            "managerBookingTrendChart",
        );
        const bookingEmpty = document.getElementById("managerBookingEmpty");

        if (
            bookingCanvas &&
            Array.isArray(trend.labels) &&
            trend.labels.length > 0
        ) {
            const statuses = [
                {
                    key: "pending",
                    label: "Pending",
                    color: "#C5A46D",
                },
                {
                    key: "confirmed",
                    label: "Confirmed",
                    color: "#8C9B7A",
                },
                {
                    key: "completed",
                    label: "Completed",
                    color: "#526044",
                },
                {
                    key: "cancelled",
                    label: "Cancelled",
                    color: "#A66F5B",
                },
            ];

            const datasets = statuses.map(function (status) {
                return {
                    label: status.label,
                    data: trend[status.key] || [],
                    borderColor: status.color,
                    backgroundColor: status.color,
                    tension: 0.35,
                    borderWidth: 2,
                };
            });

            new Chart(bookingCanvas, {
                type: "line",

                data: {
                    labels: trend.labels,
                    datasets: datasets,
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
                            position: "bottom",
                        },
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                            },
                        },
                    },
                },
            });

            if (bookingEmpty) bookingEmpty.style.display = "none";
        } else if (bookingEmpty) {
            bookingEmpty.style.display = "flex";
        }
    }

    initializeManagerCharts();
})();
