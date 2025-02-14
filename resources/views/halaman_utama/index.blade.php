@extends('layouts.ut')

@section('title', 'Dashboard')

@section('contents')
<div class="container mt-5">

    <!-- Formulir untuk memasukkan data penjualan -->
    <form method="POST" action="{{ route('updateSalesData') }}">
        @csrf
        <div class="row">
            @foreach ($salesData as $month => $value)
            <div class="col-md-3 mb-3">
                <label for="{{ $month }}">{{ $month }}</label>
                <input type="number" name="salesData[{{ $month }}]" id="{{ $month }}" value="{{ $value }}" class="form-control" min="0">
            </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Update Data</button>
    </form>

    <!-- Chart Container -->
    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="salesPieChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="salesLineChart" width="400" height="200"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="salesChart4" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    const salesData = @json($salesData);
    const labels = Object.keys(salesData);
    const data = Object.values(salesData);

    // Fungsi untuk membuat grafik batang
    function createBarChart(ctx, label, data) {
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        display: true,
                        anchor: 'center',
                        align: 'center',
                        formatter: (value) => value,
                        color: '#000',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            display: false
                        }
                    },
                    x: {
                        ticks: {
                            display: true
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }

    // Fungsi untuk membuat grafik lingkaran
    function createPieChart(ctx, label, data) {
        return new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        display: true,
                        color: '#000',
                        formatter: (value) => value,
                    }
                }
            }
        });
    }

    // Fungsi untuk membuat grafik garis
    function createLineChart(ctx, label, data) {
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Membuat grafik
    const ctx1 = document.getElementById('salesChart').getContext('2d');
    createBarChart(ctx1, 'Total Penjualan (juta)', data);

    const ctx2 = document.getElementById('salesPieChart').getContext('2d');
    createPieChart(ctx2, 'Total Penjualan (juta) - Grafik Lingkaran', data); // Grafik lingkaran

    const ctx3 = document.getElementById('salesLineChart').getContext('2d');
    createLineChart(ctx3, 'Total Penjualan (juta) - Grafik Garis', data); // Grafik garis

    const ctx4 = document.getElementById('salesChart4').getContext('2d');
    createBarChart(ctx4, 'Total Penjualan (juta) - Grafik 4', data); // Grafik keempat
</script>
@endsection