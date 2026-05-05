<x-layouts.app>
    @section('title', 'Dashboard')

    <x-page-title>Dashboard</x-page-title>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-[15px] mb-6">
        <div class="bg-white rounded-xl p-[18px] text-center shadow-[0_2px_6px_rgba(0,0,0,0.12)]">
            <i class="fa fa-file-alt text-[26px] text-[#00B8A5] mb-1.5 block"></i>
            <span class="text-gray-800 text-sm">Draft</span><br>
            <b class="text-base text-gray-900">{{ $draftCount }}</b>
        </div>
        <div class="bg-white rounded-xl p-[18px] text-center shadow-[0_2px_6px_rgba(0,0,0,0.12)]">
            <i class="fa fa-paper-plane text-[26px] text-[#00B8A5] mb-1.5 block"></i>
            <span class="text-gray-800 text-sm">Tahap Pengajuan</span><br>
            <b class="text-base text-gray-900">{{ $submittedCount }}</b>
        </div>
        <div class="bg-white rounded-xl p-[18px] text-center shadow-[0_2px_6px_rgba(0,0,0,0.12)]">
            <i class="fa fa-tasks text-[26px] text-[#00B8A5] mb-1.5 block"></i>
            <span class="text-gray-800 text-sm">Proses Penilaian</span><br>
            <b class="text-base text-gray-900">10</b>
        </div>
        <div class="bg-white rounded-xl p-[18px] text-center shadow-[0_2px_6px_rgba(0,0,0,0.12)]">
            <i class="fa fa-exclamation-triangle text-[26px] text-[#00B8A5] mb-1.5 block"></i>
            <span class="text-gray-800 text-sm">Butuh Perbaikan</span><br>
            <b class="text-base text-gray-900">{{ $revisionCount }}</b>
        </div>
        <div class="bg-white rounded-xl p-[18px] text-center shadow-[0_2px_6px_rgba(0,0,0,0.12)]">
            <i class="fa fa-check-circle text-[26px] text-[#00B8A5] mb-1.5 block"></i>
            <span class="text-gray-800 text-sm">Telah Perbaikan</span><br>
            <b class="text-base text-gray-900">0</b>
        </div>
        <div class="bg-white rounded-xl p-[18px] text-center shadow-[0_2px_6px_rgba(0,0,0,0.12)]">
            <i class="fa fa-thumbs-up text-[26px] text-[#00B8A5] mb-1.5 block"></i>
            <span class="text-gray-800 text-sm">Disetujui</span><br>
            <b class="text-base text-gray-900">{{ $acceptedCount }}</b>
        </div>
        <div class="bg-white rounded-xl p-[18px] text-center shadow-[0_2px_6px_rgba(0,0,0,0.12)]">
            <i class="fa fa-ban text-[26px] text-[#00B8A5] mb-1.5 block"></i>
            <span class="text-gray-800 text-sm">Ditolak</span><br>
            <b class="text-base text-gray-900">7</b>
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
