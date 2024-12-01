@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Charts</h2>

    <div class="row">
        <!-- Line Chart -->
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Line Chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Bar Chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pie Chart -->
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pie Chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart -->
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Doughnut Chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/chart.js" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Line Chart
    var lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
            datasets: [{
                label: 'Ventas 2023',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Bar Chart
    var barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Rojo', 'Azul', 'Amarillo', 'Verde', 'Morado', 'Naranja'],
            datasets: [{
                label: 'Votos',
                data: [12, 19, 3, 5, 2, 3],
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
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Pie Chart
    var pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Rojo', 'Azul', 'Amarillo'],
            datasets: [{
                data: [300, 50, 100],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ]
            }]
        }
    });

    // Doughnut Chart
    var doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Rojo', 'Azul', 'Amarillo', 'Verde'],
            datasets: [{
                data: [300, 50, 100, 150],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)'
                ]
            }]
        }
    });
});
</script>
@endpush
@endsection
