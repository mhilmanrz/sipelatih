@extends('layout.LayoutSuperAdmin')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
@endpush

@section('content')

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
            <h3 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Kegiatan <span id="selected-date-text">Hari Ini</span></h3>
            <div id="activity-list-container" style="max-height: 500px; overflow-y: auto;">
                <div id="loading-activities" style="display: none; text-align: center; padding: 20px;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary" style="color: #1fd1d1;"></i>
                    <p style="margin-top: 10px;">Memuat kegiatan...</p>
                </div>
                <div id="activity-list">
                    <!-- Activities will be rendered here -->
                </div>
                <div id="no-activities" style="display: none; text-align: center; padding: 20px; color: #777;">
                    <p>Tidak ada kegiatan pada tanggal ini.</p>
                </div>
            </div>
        </div>
        <div class="card-box">
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                loadCalendar();
                // Get today's date in YYYY-MM-DD format based on local timezone
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                fetchActivitiesForDate(`${year}-${month}-${day}`);
            }, 100);
        });

        function fetchActivitiesForDate(dateStr) {
            document.getElementById('loading-activities').style.display = 'block';
            document.getElementById('activity-list').style.display = 'none';
            document.getElementById('no-activities').style.display = 'none';

            const dateObj = new Date(dateStr);
            const formattedDate = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            
            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            document.getElementById('selected-date-text').textContent = dateStr === todayStr ? 'Hari Ini (' + formattedDate + ')' : formattedDate;

            fetch(`/dashboard/activities?date=${dateStr}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading-activities').style.display = 'none';
                    const listContainer = document.getElementById('activity-list');
                    listContainer.innerHTML = '';
                    
                    if (data.length === 0) {
                        document.getElementById('no-activities').style.display = 'block';
                        listContainer.style.display = 'none';
                    } else {
                        document.getElementById('no-activities').style.display = 'none';
                        listContainer.style.display = 'block';
                        
                        data.forEach(activity => {
                            const item = document.createElement('div');
                            item.style.padding = '15px';
                            item.style.borderLeft = '4px solid #1fd1d1';
                            item.style.marginBottom = '10px';
                            item.style.borderRadius = '5px';
                            item.style.backgroundColor = '#f9f9f9';
                            item.style.boxShadow = '0 1px 3px rgba(0,0,0,0.05)';
                            
                            const actName = activity.activity_name ? activity.activity_name.name : 'Kegiatan Tanpa Nama';
                            const actStart = new Date(activity.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                            const actEnd = new Date(activity.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                            
                            item.innerHTML = `
                                <h4 style="margin: 0 0 8px 0; color: #333; font-size: 1.1em;">${actName}</h4>
                                <div style="font-size: 0.9em; color: #666; display: flex; justify-content: space-between; align-items: center;">
                                    <span><i class="fa fa-calendar-alt" style="margin-right: 5px;"></i> ${actStart} - ${actEnd}</span>
                                </div>
                            `;
                            listContainer.appendChild(item);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching activities:', error);
                    document.getElementById('loading-activities').style.display = 'none';
                    document.getElementById('no-activities').style.display = 'block';
                    document.getElementById('no-activities').innerHTML = '<p style="color: #dc3545;">Terjadi kesalahan saat memuat data.</p>';
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
                dateClick: function(info) {
                    // Update visual selection (remove from others, add to clicked)
                    document.querySelectorAll('.fc-daygrid-day').forEach(el => {
                        el.style.backgroundColor = '';
                    });
                    info.dayEl.style.backgroundColor = 'rgba(31, 209, 209, 0.1)';
                    
                    fetchActivitiesForDate(info.dateStr);
                }
            });

            setTimeout(() => window.calendarInstance.render(), 50);
        }
    </script>
@endpush
