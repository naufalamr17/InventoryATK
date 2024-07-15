<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage='dashboard'></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="DASHBOARD INVENTORY ATK"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-4 col-md-6 mt-4 mb-4">
                    <div class="card z-index-2">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                            <div class="bg-white shadow-dark border-radius-lg py-3 ps-2 pe-2">
                                <div class="chart">
                                    <canvas id="pieChart" class="chart-canvas" height="170"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-0">Persediaan Barang</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-4 mb-4">
                    <div class="card z-index-2">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                            <div class="bg-white shadow-dark border-radius-lg py-3 ps-2 pe-2">
                                <div class="chart">
                                    <canvas id="monthlyInventoryChart" class="chart-canvas" height="170"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-0">Data Masuk</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-4 mb-4">
                    <div class="card z-index-2">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                            <div class="bg-white shadow-dark border-radius-lg py-3 ps-2 pe-2">
                                <div class="chart">
                                    <canvas id="monthlyDataoutChart" class="chart-canvas" height="170"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-0">Data Keluar</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-6 mt-4 mb-4">
                    <div class="card z-index-2">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                            <div class="bg-white shadow-dark border-radius-lg py-3 ps-2 pe-2">
                                <div class="table-responsive p-0" style="height: 200px;">
                                    <table id="inventoryTable" class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Code') }}</th>
                                                <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Name') }}</th>
                                                <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('QTY') }}</th>
                                                <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Location') }}</th>
                                                <th class="text-center text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Unit') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inventoryTableData as $item)
                                            <tr>
                                                <td class="text-center text-secondary text-xs opacity-7">{{ $item->code }}</td>
                                                <td class="text-center text-secondary text-xs opacity-7">{{ $item->name }}</td>
                                                <td class="text-center text-secondary text-xs opacity-7">{{ $item->qty }}</td>
                                                <td class="text-center text-secondary text-xs opacity-7">{{ $item->location }}</td>
                                                <td class="text-center text-secondary text-xs opacity-7">{{ $item->unit }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-0">Barang Tersisa Sedikit</h6>
                        </div>
                    </div>
                </div>
                <x-footers.auth></x-footers.auth>
            </div>
        </div>
    </main>
    <x-plugins></x-plugins>

    @push('js')
    <script src="{{ asset('assets') }}/js/plugins/chartjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inventoryData = @json($inventoryData);
            const monthlyInventoryData = @json($monthlyInventoryData);
            const monthlyDataoutData = @json($monthlyDataoutData);

            // Pie Chart
            const pieData = {
                labels: inventoryData.map(item => item.category),
                datasets: [{
                    data: inventoryData.map(item => item.total),
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FFC107', '#F44336', '#8E44AD', '#3498DB', '#E74C3C', '#2ECC71'
                    ].slice(0, inventoryData.length),
                }]
            };

            const pieConfig = {
                type: 'pie',
                data: pieData,
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const value = pieData.datasets[0].data[tooltipItem.dataIndex];
                                    const percentage = ((value / pieData.datasets[0].data.reduce((a, b) => a + b, 0)) * 100).toFixed(2);
                                    return `${tooltipItem.label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                const total = pieData.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(2);
                                return `${percentage}%`;
                            },
                            color: '#fff',
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            };

            const pieChart = new Chart(
                document.getElementById('pieChart'),
                pieConfig
            );

            // Monthly Inventory Chart
            const monthlyInventoryLabels = monthlyInventoryData.map(item => item.month);
            const monthlyInventoryCounts = monthlyInventoryData.map(item => item.total_qty); // Menggunakan total_qty yang sudah dihitung

            const monthlyInventoryDataConfig = {
                labels: monthlyInventoryLabels,
                datasets: [{
                    label: 'Data Masuk',
                    data: monthlyInventoryCounts,
                    fill: false,
                    backgroundColor: '#4CAF50',
                    borderColor: '#4CAF50',
                    tension: 0.1
                }]
            };

            const monthlyInventoryChartConfig = {
                type: 'line',
                data: monthlyInventoryDataConfig,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            };

            const monthlyInventoryChart = new Chart(
                document.getElementById('monthlyInventoryChart'),
                monthlyInventoryChartConfig
            );

            // Monthly Dataout Chart
            const monthlyDataoutLabels = monthlyDataoutData.map(item => item.month);
            const monthlyDataoutCounts = monthlyDataoutData.map(item => item.total_qty); // Menggunakan total_qty yang sudah dihitung

            const monthlyDataoutDataConfig = {
                labels: monthlyDataoutLabels,
                datasets: [{
                    label: 'Data Keluar',
                    data: monthlyDataoutCounts,
                    fill: false,
                    backgroundColor: '#F44336',
                    borderColor: '#F44336',
                    tension: 0.1
                }]
            };

            const monthlyDataoutChartConfig = {
                type: 'line',
                data: monthlyDataoutDataConfig,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            };

            const monthlyDataoutChart = new Chart(
                document.getElementById('monthlyDataoutChart'),
                monthlyDataoutChartConfig
            );
        });
    </script>
    @endpush
</x-layout>