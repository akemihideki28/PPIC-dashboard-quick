<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Grafik OEE Harian</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #343a40;
        }

        .chart-container {
            width: 90%;
            max-width: 1000px;
            margin: auto;
            background-color: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <h2>Grafik OEE Harian</h2>
    <div class="chart-container">
        <canvas id="oeeChart"></canvas>
    </div>

    <script>
        const grafikData = @json($grafikData);

        const labels = grafikData.map(item => item.tanggal);
        const availability = grafikData.map(item => item.availability);
        const performance = grafikData.map(item => item.performance);
        const oee = grafikData.map(item => item.oee);

        const ctx = document.getElementById('oeeChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Availability (%)',
                        data: availability,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Performance (%)',
                        data: performance,
                        backgroundColor: 'rgba(255, 205, 86, 0.7)',
                        borderColor: 'rgba(255, 205, 86, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'OEE (%)',
                        data: oee,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + "%";
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y}%`;
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>
