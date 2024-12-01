@extends('dashboard.layouts.main')

@section('content')
<div class="dashboard-wrapper">
    <!-- Stats Row -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users fa-2x text-primary"></i>
                        <h3 class="ml-3">1,524</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Revenue</h5>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                        <h3 class="ml-3">$45,820</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shopping-cart fa-2x text-warning"></i>
                        <h3 class="ml-3">458</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Growth</h5>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line fa-2x text-info"></i>
                        <h3 class="ml-3">+ 15%</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Revenue Statistics</h5>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Traffic Sources</h5>
                    <canvas id="trafficChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recent Activity</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Activity</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>Created new order</td>
                                    <td>2 minutes ago</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>Updated profile</td>
                                    <td>15 minutes ago</td>
                                    <td><span class="badge badge-info">In Progress</span></td>
                                </tr>
                                <tr>
                                    <td>Mike Johnson</td>
                                    <td>Submitted report</td>
                                    <td>1 hour ago</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#007bff',
                tension: 0.1
            }]
        }
    });

    // Traffic Chart
    const trafficCtx = document.getElementById('trafficChart').getContext('2d');
    new Chart(trafficCtx, {
        type: 'doughnut',
        data: {
            labels: ['Direct', 'Social', 'Referral'],
            datasets: [{
                data: [300, 50, 100],
                backgroundColor: ['#007bff', '#28a745', '#ffc107']
            }]
        }
    });
</script>
@endpush
@endsection
