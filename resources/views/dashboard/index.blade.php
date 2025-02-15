@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h1>Dashboard</h1>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="row mt-4">
            <!-- Total Stock Quantity by Branch -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Total Stock Quantity by Branch
                    </div>
                    <div class="card-body">
                        <canvas id="stockByBranchChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Stock Expiry Overview -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Stock Expiry Overview
                    </div>
                    <div class="card-body">
                        <canvas id="expiryChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <!-- Overstock and Less Stock Distribution -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Overstock and Less Stock Distribution
                    </div>
                    <div class="card-body">
                        <canvas id="stockDistributionChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function() {
            // Total Stock Quantity by Branch Chart
            var stockByBranchCtx = document.getElementById('stockByBranchChart').getContext('2d');
            var stockByBranchChart = new Chart(stockByBranchCtx, {
                type: 'bar',
                data: {
                    labels: @json($branchNames),
                    datasets: [{
                        label: 'Total Stock Quantity',
                        data: @json($totalStockByBranch),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
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

            // Stock Expiry Overview Chart
            var expiryCtx = document.getElementById('expiryChart').getContext('2d');
            var expiryChart = new Chart(expiryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Expiring Soon', 'Not Expiring Soon'],
                    datasets: [{
                        label: 'Stock Expiry Overview',
                        data: [{{ $expiryStocks->count() }}, {{ $stocks->count() - $expiryStocks->count() }}],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });

            // Overstock and Less Stock Distribution Chart
            var stockDistributionCtx = document.getElementById('stockDistributionChart').getContext('2d');
            var stockDistributionChart = new Chart(stockDistributionCtx, {
                type: 'pie',
                data: {
                    labels: ['Overstock', 'Less Stock', 'Normal'],
                    datasets: [{
                        label: 'Stock Distribution',
                        data: [{{ $overstockCount }}, {{ $lessStockCount }}, {{ $stocks->count() - $overstockCount - $lessStockCount }}],
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });
        });
    </script>
@endsection