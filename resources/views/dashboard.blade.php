@extends('layout.LayoutSuperAdmin')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
@endpush

@section('content')
    <h2 style="color:white;">Dashboard</h2>

    <div class="cards">
        <div class="card"><i class="fa fa-file-alt"></i>Draft<br><b>{{ $draftCount }}</b></div>
        <div class="card"><i class="fa fa-paper-plane"></i>Tahap Pengajuan<br><b>{{ $submittedCount }}</b></div>
        <div class="card"><i class="fa fa-tasks"></i>Proses Penilaian<br><b>10</b></div>
        <div class="card"><i class="fa fa-exclamation-triangle"></i>Butuh Perbaikan<br><b>{{ $revisionCount }}</b></div>
        <div class="card"><i class="fa fa-check-circle"></i>Telah Perbaikan<br><b>0</b></div>
        <div class="card"><i class="fa fa-thumbs-up"></i>Disetujui<br><b>{{ $acceptedCount }}</b></div>
        <div class="card"><i class="fa fa-ban"></i>Ditolak<br><b>7</b></div>

    </div>

    <div class="grid">
        <div class="card-box">
            <canvas id="barChart"></canvas>
        </div>
        <div class="card-box">
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script>
        window.dashboardData = {
            draftCount: {{ $draftCount ?? 0 }},
            submittedCount: {{ $submittedCount ?? 0 }},
            revisionCount: {{ $revisionCount ?? 0 }},
            acceptedCount: {{ $acceptedCount ?? 0 }},
            chartLabels: {!! json_encode(array_keys($chartData ?? [])) !!},
            chartData: {!! json_encode(array_values($chartData ?? [])) !!}
        };

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                loadChart();
                loadCalendar();
            }, 100);
        });

        function loadChart() {
            const barChart = document.getElementById("barChart");
            if (!barChart) return;
            if (window.barChartInstance) window.barChartInstance.destroy();

            const chartData = window.dashboardData || {};
            const labels = chartData.chartLabels || [];
            const dataCounts = chartData.chartData || [];

            window.barChartInstance = new Chart(barChart, {
                type: 'bar',
                data: {
                    labels: labels.length > 0 ? labels : ['Belum Ada Data'],
                    datasets: [{
                        label: 'Jumlah Kegiatan per Unit Kerja',
                        data: dataCounts.length > 0 ? dataCounts : [0],
                        backgroundColor: '#1fd1d1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function loadCalendar() {
            const calendarEl = document.getElementById("calendar");
            if (!calendarEl) return;

            if (window.calendarInstance) window.calendarInstance.destroy();

            window.calendarInstance = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: "auto",
                expandRows: true,
                events: [{
                        title: 'Diklat A',
                        start: '2026-01-10'
                    },
                    {
                        title: 'Diklat B',
                        start: '2026-01-15'
                    },
                    {
                        title: 'Diklat C',
                        start: '2026-01-22'
                    }
                ]
            });

            setTimeout(() => window.calendarInstance.render(), 50);
        }
    </script>
@endpush
