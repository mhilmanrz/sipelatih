<x-layouts.app>
    @section('title', 'Dashboard')

    <h2 class="text-white text-2xl font-semibold mb-6">Dashboard</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 text-center shadow-sm">
            <i class="fa fa-file-alt text-3xl text-teal-500 mb-2 block"></i>
            <span class="text-gray-600 text-sm">Draft</span><br>
            <b class="text-lg text-gray-800">{{ $draftCount }}</b>
        </div>
        <div class="bg-white rounded-xl p-5 text-center shadow-sm">
            <i class="fa fa-paper-plane text-3xl text-teal-500 mb-2 block"></i>
            <span class="text-gray-600 text-sm">Tahap Pengajuan</span><br>
            <b class="text-lg text-gray-800">{{ $submittedCount }}</b>
        </div>
        <div class="bg-white rounded-xl p-5 text-center shadow-sm">
            <i class="fa fa-tasks text-3xl text-teal-500 mb-2 block"></i>
            <span class="text-gray-600 text-sm">Proses Penilaian</span><br>
            <b class="text-lg text-gray-800">10</b>
        </div>
        <div class="bg-white rounded-xl p-5 text-center shadow-sm">
            <i class="fa fa-exclamation-triangle text-3xl text-teal-500 mb-2 block"></i>
            <span class="text-gray-600 text-sm">Butuh Perbaikan</span><br>
            <b class="text-lg text-gray-800">{{ $revisionCount }}</b>
        </div>
        <div class="bg-white rounded-xl p-5 text-center shadow-sm">
            <i class="fa fa-check-circle text-3xl text-teal-500 mb-2 block"></i>
            <span class="text-gray-600 text-sm">Telah Perbaikan</span><br>
            <b class="text-lg text-gray-800">0</b>
        </div>
        <div class="bg-white rounded-xl p-5 text-center shadow-sm">
            <i class="fa fa-thumbs-up text-3xl text-teal-500 mb-2 block"></i>
            <span class="text-gray-600 text-sm">Disetujui</span><br>
            <b class="text-lg text-gray-800">{{ $acceptedCount }}</b>
        </div>
        <div class="bg-white rounded-xl p-5 text-center shadow-sm">
            <i class="fa fa-ban text-3xl text-teal-500 mb-2 block"></i>
            <span class="text-gray-600 text-sm">Ditolak</span><br>
            <b class="text-lg text-gray-800">7</b>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <div class="lg:col-span-2 bg-white rounded-xl p-5 shadow-sm min-h-[320px]">
            <h3 class="mb-4 border-b border-gray-100 pb-3 text-lg font-semibold text-gray-800">Kegiatan <span id="selected-date-text">Hari Ini</span></h3>
            
            <div id="activity-list-container" class="max-h-[500px] overflow-y-auto">
                <div id="loading-activities" class="text-center py-6 hidden">
                    <i class="fa fa-spinner fa-spin text-3xl text-teal-500"></i>
                    <p class="mt-3 text-gray-500">Memuat kegiatan...</p>
                </div>
                
                <div id="activity-list">
                    <!-- Activities will be rendered here -->
                </div>
                
                <div id="no-activities" class="text-center py-6 text-gray-500 hidden">
                    <p>Tidak ada kegiatan pada tanggal ini.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm min-h-[320px]">
            <div id="calendar" class="w-full"></div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
        <style>
            /* Essential FullCalendar Overrides for styling */
            .fc .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 700; color: #1f2937; }
            .fc .fc-button { padding: 0.25rem 0.5rem !important; background-color: #6b7280; border-color: #6b7280; }
            .fc .fc-button:hover { background-color: #4b5563; }
            .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background-color: #374151; border-color: #374151; }
            .fc-scrollgrid { border-radius: 0.5rem; overflow: hidden; border-color: #e5e7eb; }
            .fc-theme-standard td, .fc-theme-standard th { border-color: #e5e7eb; }
            .fc-col-header-cell-cushion { color: #4b5563; padding: 0.5rem !important; text-decoration: none; }
            .fc-daygrid-day-number { color: #4b5563; padding: 0.25rem !important; text-decoration: none; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
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
                const loadingEl = document.getElementById('loading-activities');
                const listEl = document.getElementById('activity-list');
                const noActEl = document.getElementById('no-activities');
                
                loadingEl.classList.remove('hidden');
                listEl.classList.add('hidden');
                noActEl.classList.add('hidden');

                const dateObj = new Date(dateStr);
                const formattedDate = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                
                const today = new Date();
                const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
                document.getElementById('selected-date-text').textContent = dateStr === todayStr ? 'Hari Ini (' + formattedDate + ')' : formattedDate;

                fetch(`/dashboard/activities?date=${dateStr}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingEl.classList.add('hidden');
                        listEl.innerHTML = '';
                        
                        if (data.length === 0) {
                            noActEl.classList.remove('hidden');
                            listEl.classList.add('hidden');
                        } else {
                            noActEl.classList.add('hidden');
                            listEl.classList.remove('hidden');
                            
                            data.forEach(activity => {
                                const item = document.createElement('div');
                                item.className = 'p-4 border-l-4 border-teal-500 mb-3 rounded-md bg-gray-50 shadow-sm';
                                
                                const actName = activity.activity_name ? activity.activity_name.name : 'Kegiatan Tanpa Nama';
                                const actStart = new Date(activity.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                                const actEnd = new Date(activity.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                                
                                item.innerHTML = `
                                    <h4 class="m-0 mb-2 text-gray-800 text-base font-semibold">${actName}</h4>
                                    <div class="text-sm text-gray-500 flex justify-between items-center">
                                        <span><i class="fa fa-calendar-alt mr-1"></i> ${actStart} - ${actEnd}</span>
                                    </div>
                                `;
                                listEl.appendChild(item);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching activities:', error);
                        loadingEl.classList.add('hidden');
                        noActEl.classList.remove('hidden');
                        noActEl.innerHTML = '<p class="text-red-500">Terjadi kesalahan saat memuat data.</p>';
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
                        // Update visual selection
                        document.querySelectorAll('.fc-daygrid-day').forEach(el => {
                            el.style.backgroundColor = '';
                        });
                        info.dayEl.style.backgroundColor = '#ccfbf1'; // Tailwind teal-100
                        
                        fetchActivitiesForDate(info.dateStr);
                    }
                });

                setTimeout(() => window.calendarInstance.render(), 50);
            }
        </script>
    @endpush
</x-layouts.app>
