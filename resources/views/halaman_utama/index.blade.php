<!DOCTYPE html>
<html>
<head>
    <title>Dashboard OEE</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='50' height='100' fill='%23005BAA'/><rect x='50' width='50' height='100' fill='%23E31937'/><text x='50' y='60' font-family='Arial' font-size='40' text-anchor='middle' fill='white'>D+M</text></svg>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            padding: 20px;
            color: #333;
        }
        
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: var(--dark-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
            padding: 12px 20px;
        }
        
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }
        
        .chart-container {
            position: relative;
            height: 100%;
            min-height: 300px;
            padding: 15px;
        }
        
        .summary-card {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
        }
        
        .summary-card .value {
            font-size: 2.2rem;
            margin: 10px 0;
        }
        
        .summary-card .label {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        #oeeSummary { background: linear-gradient(135deg, #3498db, #2c3e50); }
        #availabilitySummary { background: linear-gradient(135deg, #2ecc71, #27ae60); }
        #performanceSummary { background: linear-gradient(135deg, #f39c12, #e67e22); }
        #qualitySummary { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
        
        .time-period {
            font-size: 1.2rem;
            color: var(--dark-color);
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .date-range-selector {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .date-range-selector .form-control {
            max-width: 200px;
        }
        
        .calendar-icon {
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            background: #f0f0f0;
        }
        
        .calendar-icon:hover {
            background: #e0e0e0;
        }
        
        /* Pareto chart specific styles */
        .pareto-line {
            border-color: #e74c3c !important;
        }
        
        .machine-bar {
            transition: all 0.3s ease;
        }
        
        .machine-bar:hover {
            opacity: 0.8;
        }
        
        /* Modal styles */
        .modal-title {
            color: var(--dark-color);
            font-weight: bold;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-card {
            background: #f5f7fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .reason-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }
        
        .downtime-table th {
            background-color: var(--dark-color);
            color: white;
        }
        
        .clickable-chart {
            cursor: pointer;
        }
        
        /* Added styles for hidden sections */
        .hidden-section {
            display: none;
        }
        
        .details-btn {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .details-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
<button type="button" class="rollback-btn" onclick="window.location.href='{{ route('combo') }}'">← Back</button>
    <div class="dashboard-container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Overall Equipment Effectiveness (OEE) Dashboard</h2>
            <p class="text-muted">Monitor your equipment performance metrics</p>
        </div>
        
        <form method="GET" class="filters">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Time Range:</label>
                    <select name="range" class="form-select" id="timeRangeSelect" onchange="updateDatePicker()">
                        <option value="harian" {{ $range == 'harian' ? 'selected' : '' }}>Daily</option>
                        <option value="mingguan" {{ $range == 'mingguan' ? 'selected' : '' }}>Weekly</option>
                        <option value="bulanan" {{ $range == 'bulanan' ? 'selected' : '' }}>Monthly</option>
                        <option value="tahunan" {{ $range == 'tahunan' ? 'selected' : '' }}>Yearly</option>
                        <option value="custom" {{ $range == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Shift:</label>
                    <select name="shift" class="form-select">
                        <option value="" {{ $shift == '' ? 'selected' : '' }}>All Shifts</option>
                        <option value="1" {{ $shift == '1' ? 'selected' : '' }}>Shift 1</option>
                        <option value="2" {{ $shift == '2' ? 'selected' : '' }}>Shift 2</option>
                        <option value="3" {{ $shift == '3' ? 'selected' : '' }}>Shift 3</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Machine:</label>
                    <select name="machine" class="form-select">
                        <option value="">All Machines</option>
                        @foreach($machines as $m)
                            <option value="{{ $m }}" {{ $machine == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3" id="dateRangeContainer" style="{{ $range != 'custom' ? 'display:none;' : '' }}">
                    <label class="form-label">Date Range:</label>
                    <div class="date-range-selector">
                        <input type="text" class="form-control datepicker" id="startDate" name="start_date" 
                               placeholder="Start Date" value="{{ $request->input('start_date') }}">
                        <span>to</span>
                        <input type="text" class="form-control datepicker" id="endDate" name="end_date" 
                               placeholder="End Date" value="{{ $request->input('end_date') }}">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">Reset</button>
                </div>
            </div>
        </form>
        
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="summary-card" id="oeeSummary">
                    <div class="label">OEE</div>
                    <div class="value">{{ round($grafikData->avg('oee'), 1) }}%</div>
                    <div class="label">Overall Equipment Effectiveness</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card" id="availabilitySummary">
                    <div class="label">Availability</div>
                    <div class="value">{{ round($grafikData->avg('availability'), 1) }}%</div>
                    <div class="label">Uptime Percentage</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card" id="performanceSummary">
                    <div class="label">Performance</div>
                    <div class="value">{{ round($grafikData->avg('performance'), 1) }}%</div>
                    <div class="label">Speed Efficiency</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card" id="qualitySummary">
                    <div class="label">Quality</div>
                    <div class="value">{{ round($grafikData->avg('ok_ratio'), 1) }}%</div>
                    <div class="label">Good Product Rate</div>
                </div>
            </div>
        </div>
        
        <!-- Time Period Display -->
        <div class="time-period">
            @if($range == 'custom' && $request->input('start_date') && $request->input('end_date'))
                Showing data from {{ \Carbon\Carbon::parse($request->input('start_date'))->format('M d, Y') }} to {{ \Carbon\Carbon::parse($request->input('end_date'))->format('M d, Y') }}
                @if($machine) for machine {{ $machine }} @endif
            @elseif($range == 'harian')
                Showing daily data @if($machine) for machine {{ $machine }} @endif
            @elseif($range == 'mingguan')
                Showing weekly data @if($machine) for machine {{ $machine }} @endif
            @elseif($range == 'bulanan')
                Showing monthly data @if($machine) for machine {{ $machine }} @endif
            @elseif($range == 'tahunan')
                Showing yearly data @if($machine) for machine {{ $machine }} @endif
            @endif
        </div>
        
        <!-- Pareto OEE by Machine -->
        <div class="card">
            <div class="card-header">
                Pareto OEE by Machine
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="paretoChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Main OEE Trend Chart -->
        <div class="card">
            <div class="card-header">
                OEE Trend
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="oeeTrendChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Component Charts -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header" style="background-color: #2ecc71;">
                        Availability Trend
                        <button class="float-end btn btn-sm btn-light details-btn" id="downtimeDetailsBtn">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="availabilityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header" style="background-color: #f39c12;">
                        Performance Trend
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header" style="background-color: #9b59b6;">
                        Quality Trend
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="qualityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Radar Chart -->
        <div class="card">
            <div class="card-header">
                OEE Components Analysis
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="oeeRadarChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Downtime Analysis Card - Initially Hidden -->
        <div class="card hidden-section" id="downtimeAnalysisCard">
            <div class="card-header">
                Downtime Analysis
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="downtimeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Downtime Details Modal -->
    <div class="modal fade" id="downtimeModal" tabindex="-1" aria-labelledby="downtimeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="downtimeModalLabel">Downtime Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    Downtime Reasons
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="reasonsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    Downtime Duration
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="durationChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            Detailed Downtime Records
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover downtime-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Machine</th>
                                            <th>Shift</th>
                                            <th>Reason</th>
                                            <th>Duration (minutes)</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody id="downtimeTableBody">
                                        <!-- Will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
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
        
        // Pareto chart data
        const paretoData = @json($paretoData);
        const machineNames = paretoData.map(item => item.nama_mesin);
        const machineOEE = paretoData.map(item => parseFloat(item.avg_oee));
        
        // Sort data for pareto (descending order)
        const sortedIndices = [...machineOEE.keys()].sort((a, b) => machineOEE[b] - machineOEE[a]);
        const sortedMachineNames = sortedIndices.map(i => machineNames[i]);
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
                    labels: sortedMachineNames,
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
                            text: 'Pareto Analysis of OEE by Machine',
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
    </script>
</body>
</html>