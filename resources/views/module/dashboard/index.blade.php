@extends('main')
@section('title', '| Dashboard')

@section('content')

<div class="row">
    @if(Auth::user()->role === 'employee')
    <div class="col-lg-12 p-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Anda Masuk Sebagai {{ Auth::user()->name }}</h4>

                <div class="card w-100">
                    <ul class="list-group list-group-flush d-flex flex-column" style="min-height: 200px;">
                        <li class="list-group-item bg-light d-flex justify-content-center align-items-center">
                            Total Penjualan Hari Ini
                        </li>
                        <li class="list-group-item flex-grow-1 d-flex flex-column justify-content-center align-items-center" style="min-height: 100px;">
                            <b style="font-size: 2rem;">{{ $todaySalesCount }}</b>
                            <span>Data terjual hari ini:</span>
                        </li>

                        <li class="list-group-item bg-light d-flex justify-content-center align-items-center">
                            Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->role === 'admin')
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center">
                        <div>
                            <h4 class="card-title">Anda Masuk Sebagai {{ Auth::user()->name }}</h4>
                        </div>
                    </div>
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Penjualan Produk</h4>
                    <div class="chart-container">
                        <canvas id="salesPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Pie Chart
      const pieCtx = document.getElementById('salesPieChart');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: @json($labelspieChart), // Contoh: ["Baju : 50%", "Jaket : 50%"]
                datasets: [{
                    data: @json($salesDatapieChart), // Contoh: [50, 50]
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                // Ambil label asli (misal: "Baju : 50%")
                                return `${context.label}`;
                            }
                        }
                    }
                }
            }
        });
    }
        // Line Chart
        const lineCtx = document.getElementById('salesChart');
        if(lineCtx) {
            new Chart(lineCtx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Jumlah Penjualan',
                        data: @json($salesData),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }
    });
</script>

@endsection
