@extends('layouts.vertical', ['title' => 'Arus Listrik Data Mini Excavator'])

@section('css')
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@endsection

@section('content')

<div class="row">
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">
                        Arus Data
                    </h4>
                </div>
                <div id="chart-container" style="overflow-x: auto; white-space: nowrap;">
                    <div id="voltageChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div id="table-gridjs"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-bottom')
@vite(['resources/js/components/table-gridjs.js'])
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let voltageValues = [];  // Data untuk nilai tegangan
    let createdAtLabels = [];  // Data untuk tanggal/waktu
    let tableData = [];  // Data untuk tabel

    // Fungsi untuk memuat data
    function fetchData() {
        fetch('/proxy-api')  // Ganti dengan endpoint yang sesuai
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    const today = new Date().toISOString().split('T')[0]; // Format YYYY-MM-DD

                    // Filter data hanya untuk hari ini (untuk grafik)
                    const filteredDataForChart = data.filter(item => {
                        const createdAtDate = item.created_at.split(' ')[0]; // Ambil tanggal dari created_at
                        return createdAtDate === today;
                    });

                    // Ambil nilai voltage dan waktu untuk grafik
                    voltageValues = filteredDataForChart.map(item => parseFloat(item.voltage));
                    createdAtLabels = filteredDataForChart.map(item => item.created_at);

                    // Ambil semua data untuk tabel
                    tableData = data.map((item, index) => [index + 1, item.created_at, item.voltage]);

                    // Perbarui grafik dan tabel
                    updateChart();
                    updateTable();
                } else {
                    console.error("No valid data received", data);
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // Fungsi untuk memperbarui grafik menggunakan ApexCharts
    function updateChart() {
        const options = {
            chart: {
                type: 'line',
                height: 400,
                zoom: { enabled: true, type: 'x' },
                toolbar: { show: true }
            },
            series: [{ name: 'Arus', data: voltageValues }],
            xaxis: {
                categories: createdAtLabels,
                title: { text: 'Date' },
                labels: {
                    rotate: -45,
                    formatter: function(value) {
                        const date = new Date(value);
                        const day = date.getDate().toString().padStart(2, '0');
                        const month = date.toLocaleString('default', { month: 'long' });
                        const year = date.getFullYear().toString().slice(-2);
                        const hours = date.getHours().toString().padStart(2, '0');
                        const minutes = date.getMinutes().toString().padStart(2, '0');
                        return `${day} ${month} ${year}, ${hours}:${minutes}`;
                    }
                }
            },
            yaxis: {
                title: { text: 'Arus' },
                min: 0,
                max: Math.max(...voltageValues, 10) + 2,
                tickAmount: 5
            },
            title: { text: 'Arus Data', align: 'center' },
            stroke: { curve: 'smooth' },
            markers: { size: 5 },
            dataLabels: { enabled: false },
            grid: { borderColor: '#ccc', strokeDashArray: 5 }
        };

        const chartElement = document.querySelector("#voltageChart");
        const existingChart = ApexCharts.getChartByID("voltageChart");

        if (existingChart) {
            existingChart.updateOptions(options);
        } else {
            const chart = new ApexCharts(chartElement, options);
            chart.render();
        }
    }

    // Fungsi untuk memperbarui tabel Grid.js
    function updateTable() {
        if (document.getElementById("table-gridjs")) {
            document.getElementById("table-gridjs").innerHTML = ""; // Reset tabel sebelum render ulang

            new gridjs.Grid({
                columns: [
                    { name: 'ID' },
                    { name: 'Tanggal' },
                    { name: 'Arus Data' }
                ],
                pagination: { limit: 15 },
                sort: true,
                search: true,
                data: tableData
            }).render(document.getElementById("table-gridjs"));
        }
    }

    // Memuat data pertama kali saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        fetchData();
    });

    // Menyegarkan data setiap 10 menit (600000 ms)
    setInterval(() => {
        fetchData();
    }, 600000);
</script>

@endsection
