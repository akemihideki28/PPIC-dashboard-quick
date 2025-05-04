// Initialize date pickers
flatpickr(".datepicker", {
    dateFormat: "Y-m-d",
    allowInput: true
});

// Show/hide date range picker based on time range selection
function updateDatePicker() {
    const timeRange = document.getElementById('timeRangeSelect').value;
    const dateRangeContainer = document.getElementById('dateRangeContainer');
    
    if (timeRange === 'custom') {
        dateRangeContainer.style.display = 'block';
    } else {
        dateRangeContainer.style.display = 'none';
    }
}

// Reset all filters
function resetFilters() {
    window.location.href = window.location.pathname;
}

// Enable Bootstrap tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});

// Auto-refresh when data changes
let lastUpdate = '{{ $lastUpdate }}';

function checkForUpdates() {
    fetch('/check-updates?last_update=' + lastUpdate)
        .then(response => response.json())
        .then(data => {
            if (data.has_update) {
                location.reload();
                lastUpdate = data.new_last_update;
            }
        });
}

// Polling every 30 seconds
setInterval(checkForUpdates, 30000);

// Chart data
const data = @json($grafikData);
const labels = data.map(item => {
    if (@json($range) === 'bulanan') {
        return new Date(item.waktu + '-01').toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    } else if (@json($range) === 'tahunan') {
        return item.waktu;
    } else if (@json($range) === 'mingguan') {
        return 'Week ' + item.waktu.substring(4) + ', ' + item.waktu.substring(0, 4);
    } else {
        return new Date(item.waktu).toLocaleDateString('en-US', { day: 'numeric', month: 'short' });
    }
});

const availabilityData = data.map(item => parseFloat(item.availability));
const performanceData = data.map(item => parseFloat(item.performance));
const qualityData = data.map(item => parseFloat(item.ok_ratio));
const oeeData = data.map(item => parseFloat(item.oee));

// Downtime data
const downtimeReasons = @json($downtimeData);
const lostTimeDetails = @json($lostTimeDetails);

// Reasons data
const reasonsLabels = downtimeReasons.map(item => item.alasan);
const reasonsDurations = downtimeReasons.map(item => parseFloat(item.total_lost_time));
const reasonsOccurrences = downtimeReasons.map(item => parseInt(item.occurrence_count));

//pareto data
const paretoData = @json($paretoData);
const machineIds = paretoData.map(item => item.no_mesin); // Changed from nama_mesin to no_mesin
const machineOEE = paretoData.map(item => parseFloat(item.avg_oee));

// Sort data for pareto (descending order)
const sortedIndices = [...machineOEE.keys()].sort((a, b) => machineOEE[a] - machineOEE[b]); // Changed to sort from highest to lowest
const sortedMachineIds = sortedIndices.map(i => machineIds[i]);
const sortedMachineOEE = sortedIndices.map(i => machineOEE[i]);

// Calculate cumulative percentage
const totalOEE = sortedMachineOEE.reduce((a, b) => a + b, 0);
let cumulative = 0;
const cumulativePercentages = sortedMachineOEE.map(value => {
cumulative += value;
return (cumulative / totalOEE) * 100;
});

// Chart options
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true,
            max: 100,
            ticks: {
                callback: value => value + '%'
            },
            grid: {
                color: 'rgba(0, 0, 0, 0.05)'
            }
        },
        x: {
            grid: {
                display: false
            }
        }
    },
    plugins: {
        tooltip: {
            callbacks: {
                label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y.toFixed(1) + '%'
            }
        },
        legend: {
            position: 'top',
            labels: {
                boxWidth: 12,
                padding: 20,
                usePointStyle: true
            }
        },
        datalabels: {
            display: false
        }
    },
    elements: {
        line: {
            tension: 0.4
        },
        point: {
            radius: 3,
            hoverRadius: 6
        }
    }
};

// Downtime Chart
const downtimeChart = new Chart(
    document.getElementById('downtimeChart'),
    {
        type: 'bar',
        data: {
            labels: reasonsLabels,
            datasets: [{
                label: 'Lost Time (minutes)',
                data: reasonsDurations,
                backgroundColor: 'rgba(231, 76, 60, 0.7)',
                borderColor: 'rgba(231, 76, 60, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Minutes'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Downtime by Reason',
                    font: {
                        size: 16
                    }
                }
            }
        }
    }
);

// Pareto Chart
new Chart(
document.getElementById('paretoChart'),
{
type: 'bar',
data: {
    labels: sortedMachineIds,
    datasets: [
        {
            label: 'Average OEE',
            data: sortedMachineOEE,
            backgroundColor: 'rgba(52, 152, 219, 0.7)',
            borderColor: 'rgba(52, 152, 219, 1)',
            borderWidth: 1,
            yAxisID: 'y'
        },
        {
            label: 'Cumulative %',
            data: cumulativePercentages,
            type: 'line',
            borderColor: '#e74c3c',
            backgroundColor: 'rgba(231, 76, 60, 0.1)',
            borderWidth: 2,
            pointRadius: 3,
            yAxisID: 'y1'
        }
    ]
},
options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            type: 'linear',
            display: true,
            position: 'left',
            title: {
                display: true,
                text: 'OEE (%)'
            },
            max: 100,
            min: 0
        },
        y1: {
            type: 'linear',
            display: true,
            position: 'right',
            title: {
                display: true,
                text: 'Cumulative %'
            },
            max: 100,
            min: 0,
            grid: {
                drawOnChartArea: false
            }
        },
        x: {
            grid: {
                display: false
            }
        }
    },
    plugins: {
        tooltip: {
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    if (context.datasetIndex === 0) {
                        label += context.parsed.y.toFixed(1) + '%';
                    } else {
                        label += context.parsed.y.toFixed(1) + '%';
                    }
                    return label;
                }
            }
        },
        legend: {
            position: 'top'
        },
        title: {
            display: true,
            text: 'Pareto Analysis of OEE by No Machine',
            font: {
                size: 16
            }
        }
    }
}
}
);
// OEE Trend Chart
new Chart(
    document.getElementById('oeeTrendChart'),
    {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'OEE',
                    data: oeeData,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    borderWidth: 3,
                    fill: true
                },
                {
                    label: 'Availability',
                    data: availabilityData,
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false
                },
                {
                    label: 'Performance',
                    data: performanceData,
                    borderColor: '#f39c12',
                    backgroundColor: 'rgba(243, 156, 18, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false
                },
                {
                    label: 'Quality',
                    data: qualityData,
                    borderColor: '#9b59b6',
                    backgroundColor: 'rgba(155, 89, 182, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false
                }
            ]
        },
        options: {
            ...chartOptions,
            plugins: {
                ...chartOptions.plugins,
                title: {
                    display: true,
                    text: 'OEE and Components Trend',
                    font: {
                        size: 16
                    }
                }
            }
        }
    }
);

// Availability Chart
const availabilityChart = new Chart(
    document.getElementById('availabilityChart'),
    {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Availability',
                data: availabilityData,
                backgroundColor: 'rgba(46, 204, 113, 0.7)',
                borderColor: 'rgba(46, 204, 113, 1)',
                borderWidth: 1
            }]
        },
        options: {
            ...chartOptions,
            plugins: {
                ...chartOptions.plugins,
                title: {
                    display: true,
                    text: 'Availability Rate',
                    font: {
                        size: 14
                    }
                }
            }
        }
    }
);

// Performance Chart
new Chart(
    document.getElementById('performanceChart'),
    {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Performance',
                data: performanceData,
                backgroundColor: 'rgba(243, 156, 18, 0.7)',
                borderColor: 'rgba(243, 156, 18, 1)',
                borderWidth: 1
            }]
        },
        options: {
            ...chartOptions,
            plugins: {
                ...chartOptions.plugins,
                title: {
                    display: true,
                    text: 'Performance Rate',
                    font: {
                        size: 14
                    }
                }
            }
        }
    }
);

// Quality Chart
new Chart(
    document.getElementById('qualityChart'),
    {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Quality',
                data: qualityData,
                backgroundColor: 'rgba(155, 89, 182, 0.7)',
                borderColor: 'rgba(155, 89, 182, 1)',
                borderWidth: 1
            }]
        },
        options: {
            ...chartOptions,
            plugins: {
                ...chartOptions.plugins,
                title: {
                    display: true,
                    text: 'Quality',
                    font: {
                        size: 14
                    }
                }
            }
        }
    }
);

// Radar Chart
const avgAvailability = availabilityData.reduce((a, b) => a + b, 0) / availabilityData.length;
const avgPerformance = performanceData.reduce((a, b) => a + b, 0) / performanceData.length;
const avgQuality = qualityData.reduce((a, b) => a + b, 0) / qualityData.length;
const avgOEE = oeeData.reduce((a, b) => a + b, 0) / oeeData.length;

new Chart(
    document.getElementById('oeeRadarChart'),
    {
        type: 'radar',
        data: {
            labels: ['Availability', 'Performance', 'Quality', 'OEE'],
            datasets: [{
                label: 'Average Values',
                data: [avgAvailability, avgPerformance, avgQuality, avgOEE],
                backgroundColor: 'rgba(52, 152, 219, 0.2)',
                borderColor: 'rgba(52, 152, 219, 1)',
                pointBackgroundColor: 'rgba(52, 152, 219, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: {
                        display: true
                    },
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: {
                        stepSize: 20,
                        callback: value => value + '%'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'OEE Components Overview',
                    font: {
                        size: 16
                    }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': ' + ctx.parsed.r.toFixed(1) + '%'
                    }
                }
            },
            elements: {
                line: {
                    tension: 0.1
                }
            }
        }
    }
);

// Initialize modal charts variables
let reasonsChart, durationChart;

// Add click event to details button
document.getElementById('downtimeDetailsBtn').addEventListener('click', function(e) {
    e.preventDefault();
    
    // Toggle visibility of downtime analysis card
    const downtimeCard = document.getElementById('downtimeAnalysisCard');
    downtimeCard.classList.toggle('hidden-section');
    
    // If showing, scroll to it
    if (!downtimeCard.classList.contains('hidden-section')) {
        downtimeCard.scrollIntoView({ behavior: 'smooth' });
    }
    
    // Also show the modal
    showDowntimeModal();
});

// Function to show downtime modal
function showDowntimeModal() {
    // Destroy existing charts if they exist
    if (reasonsChart) {
        reasonsChart.destroy();
    }
    if (durationChart) {
        durationChart.destroy();
    }
    
    // Create charts for modal
    reasonsChart = new Chart(
        document.getElementById('reasonsChart'),
        {
            type: 'pie',
            data: {
                labels: reasonsLabels,
                datasets: [{
                    data: reasonsDurations,
                    backgroundColor: [
                        '#e74c3c', '#3498db', '#2ecc71', '#f39c12', 
                        '#9b59b6', '#1abc9c', '#d35400', '#34495e'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    title: {
                        display: true,
                        text: 'Downtime by Reason',
                        font: {
                            size: 14
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} minutes (${((context.raw / reasonsDurations.reduce((a, b) => a + b, 0)) * 100).toFixed(1)}%)`;
                            }
                        }
                    }
                }
            }
        }
    );
    
    durationChart = new Chart(
        document.getElementById('durationChart'),
        {
            type: 'bar',
            data: {
                labels: reasonsLabels,
                datasets: [{
                    label: 'Duration (minutes)',
                    data: reasonsDurations,
                    backgroundColor: 'rgba(231, 76, 60, 0.7)',
                    borderColor: 'rgba(231, 76, 60, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Minutes'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Downtime Duration by Reason',
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    );
    
    // Populate downtime table
    const tableBody = document.getElementById('downtimeTableBody');
    tableBody.innerHTML = '';
    
    lostTimeDetails.forEach(record => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${new Date(record.tanggal).toLocaleDateString()}</td>
            <td>${record.mesin}</td>
            <td>${record.shift}</td>
            <td>${record.alasan}</td>
            <td>${record.lost_time}</td>
            <td>${record.keterangan || '-'}</td>
        `;
        tableBody.appendChild(row);
    });
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('downtimeModal'));
    modal.show();
}