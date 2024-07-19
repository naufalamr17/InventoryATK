<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage='dashboard'></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="DASHBOARD INVENTORY ATK"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mt-4 mb-4">
                        <div class="card">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                    <i class="material-icons opacity-10">payments</i>
                                </div>
                                <div class="text-end mt-6" style="height: 7.5rem;">
                                    <p class="text-2xl mb-0 text-capitalize">Total Pengeluaran</p>
                                    <hr>
                                    <div class="total-price">
                                        <h4>Total : Rp.{{ number_format($totalPriceMasuk, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                            <hr class="dark horizontal my-0">
                            <div class="card-footer p-3">
                                <div class="d-flex">
                                    <input type="month" id="monthFilterMasuk" name="monthMasuk" class="form-control form-control-sm" value="{{ $selectedMonthMasuk }}"> ||
                                    <input type="number" id="yearFilterMasuk" name="yearMasuk" class="form-control form-control-sm ms-2" placeholder="Input Year" value="{{ $selectedYearMasuk }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mt-4 mb-4">
                        <div class="card z-index-2">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                                <div class="bg-white shadow-dark border-radius-lg py-3 ps-2 pe-2">
                                    <div class="chart">
                                        <canvas id="pieChart" class="chart-canvas" height="190"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-0">Persediaan Barang</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mt-4 mb-4">
                        <div class="card z-index-2">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                                <div class="bg-success shadow-dark border-radius-lg py-3 ps-2 pe-2">
                                    <div class="chart">
                                        <canvas id="monthlyInventoryChart" class="chart-canvas" height="190"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-0">Data Masuk</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mt-4 mb-4">
                        <div class="card z-index-2">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                                <div class="bg-danger shadow-dark border-radius-lg py-3 ps-2 pe-2">
                                    <div class="chart">
                                        <canvas id="monthlyDataoutChart" class="chart-canvas" height="190"></canvas>
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
                                    <div class="table-responsive p-0" style="height: 300px;">
                                        <table id="inventoryTable" class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center text-secondary text-base font-weight-bolder opacity-7">{{ __('Code') }}</th>
                                                    <th class="text-center text-secondary text-base font-weight-bolder opacity-7">{{ __('Name') }}</th>
                                                    <th class="text-center text-secondary text-base font-weight-bolder opacity-7">{{ __('QTY') }}</th>
                                                    <th class="text-center text-secondary text-base font-weight-bolder opacity-7">{{ __('Location') }}</th>
                                                    <th class="text-center text-secondary text-base font-weight-bolder opacity-7">{{ __('Unit') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($inventoryTableData as $item)
                                                <tr>
                                                    <td class="text-center text-secondary opacity-7" style="font-size: large;">{{ $item->code }}</td>
                                                    <td class="text-center text-secondary opacity-7" style="font-size: large;">{{ $item->name }}</td>
                                                    <td class="text-center text-secondary opacity-7" style="font-size: large;">{{ $item->qty }}</td>
                                                    <td class="text-center text-secondary opacity-7" style="font-size: large;">{{ $item->location }}</td>
                                                    <td class="text-center text-secondary opacity-7" style="font-size: large;">{{ $item->unit }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-secondary text-xs opacity-7">Persediaan Barang Aman</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body d-flex justify-start align-items-center">
                                <h6 class="mb-0">Barang Tersisa Sedikit</h6>
                                @if($inventoryTableData->isNotEmpty())
                                <span class="badge bg-danger ms-2" style="animation: breathe 2s infinite;">
                                    !
                                </span>
                                <div class="ms-auto mb-2">
                                    <button id="exportExcelButton" class="btn bg-gradient-dark mb-0">
                                        <i class="material-icons text-sm">file_download</i>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <x-footers.auth></x-footers.auth>
                </div>
            </form>
        </div>
    </main>
    <x-plugins></x-plugins>

    @push('js')
    <script src="{{ asset('assets') }}/js/plugins/chartjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <script>
        document.getElementById('yearFilterMasuk').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('monthFilterMasuk').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>

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
                    backgroundColor: '#FFFFFF', // Warna putih untuk latar belakang titik data
                    borderColor: '#FFFFFF', // Warna putih untuk garis
                    tension: 0.1
                }]
            };

            const monthlyInventoryChartConfig = {
                type: 'line',
                data: monthlyInventoryDataConfig,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#FFFFFF' // Warna putih untuk label legenda
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#FFFFFF' // Warna putih untuk label sumbu X
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)' // Warna putih dengan transparansi untuk garis grid
                            }
                        },
                        y: {
                            ticks: {
                                color: '#FFFFFF' // Warna putih untuk label sumbu Y
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)' // Warna putih dengan transparansi untuk garis grid
                            }
                        }
                    }
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
                    backgroundColor: '#FFFFFF', // Warna putih untuk latar belakang titik data
                    borderColor: '#FFFFFF', // Warna putih untuk garis
                    tension: 0.1
                }]
            };

            const monthlyDataoutChartConfig = {
                type: 'line',
                data: monthlyDataoutDataConfig,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#FFFFFF' // Warna putih untuk label legenda
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#FFFFFF' // Warna putih untuk label sumbu X
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)' // Warna putih dengan transparansi untuk garis grid
                            }
                        },
                        y: {
                            ticks: {
                                color: '#FFFFFF' // Warna putih untuk label sumbu Y
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)' // Warna putih dengan transparansi untuk garis grid
                            }
                        }
                    }
                }
            };

            const monthlyDataoutChart = new Chart(
                document.getElementById('monthlyDataoutChart'),
                monthlyDataoutChartConfig
            );
        });

        // Export to Excel functionality
        $('#exportExcelButton').on('click', function() {
            const sheetName = 'Report';
            const fileName = 'report_inventory';

            const table = document.getElementById('inventoryTable');

            // Memastikan tabel ditemukan sebelum melanjutkan
            if (!table) {
                console.error('Tabel tidak ditemukan.');
                return;
            }

            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.table_to_sheet(table);

            const range = XLSX.utils.decode_range(ws['!ref']);

            // Kolom yang ingin diexport (indeks kolom dimulai dari 0)
            const columnsToExport = [0, 1, 2, 3, 4];

            const filteredData = [];
            for (let R = range.s.r; R <= range.e.r; ++R) {
                const row = [];
                for (let C = 0; C < columnsToExport.length; ++C) {
                    const colIndex = columnsToExport[C];
                    const cellAddress = XLSX.utils.encode_cell({
                        r: R,
                        c: colIndex
                    });
                    if (!ws[cellAddress]) continue;
                    row.push(ws[cellAddress].v);
                }
                filteredData.push(row);
            }

            // Buat sheet baru dengan data yang difilter
            const newWs = XLSX.utils.aoa_to_sheet(filteredData);

            const newRange = XLSX.utils.decode_range(newWs['!ref']);

            // Autofit width untuk setiap kolom
            const colWidths = [];
            for (let C = newRange.s.c; C <= newRange.e.c; ++C) {
                let maxWidth = 0;
                for (let R = newRange.s.r; R <= newRange.e.r; ++R) {
                    const cellAddress = XLSX.utils.encode_cell({
                        r: R,
                        c: C
                    });
                    if (!newWs[cellAddress]) continue;
                    const cellTextLength = XLSX.utils.format_cell(newWs[cellAddress]).length;
                    maxWidth = Math.max(maxWidth, cellTextLength);
                }
                colWidths[C] = {
                    wch: maxWidth + 2
                };
            }
            newWs['!cols'] = colWidths;

            XLSX.utils.book_append_sheet(wb, newWs, sheetName);

            XLSX.writeFile(wb, fileName + '.xlsx');
        });
    </script>
    @endpush
</x-layout>