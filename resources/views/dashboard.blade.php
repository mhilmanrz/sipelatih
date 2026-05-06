<x-layouts.app>
    @section('title', 'Dashboard')

    <x-page-title>Dashboard</x-page-title>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:shadow-md transition duration-200">
            <div class="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center mb-3">
                <i class="fa fa-file-alt text-2xl text-teal-600"></i>
            </div>
            <span class="text-gray-500 text-xs font-medium uppercase tracking-wider mb-1">Draft</span>
            <b class="text-xl font-bold text-gray-800">{{ $draftCount }}</b>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:shadow-md transition duration-200">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mb-3">
                <i class="fa fa-paper-plane text-2xl text-blue-600"></i>
            </div>
            <span class="text-gray-500 text-xs font-medium uppercase tracking-wider mb-1">Pengajuan</span>
            <b class="text-xl font-bold text-gray-800">{{ $submittedCount }}</b>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:shadow-md transition duration-200">
            <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center mb-3">
                <i class="fa fa-tasks text-2xl text-amber-600"></i>
            </div>
            <span class="text-gray-500 text-xs font-medium uppercase tracking-wider mb-1">Penilaian</span>
            <b class="text-xl font-bold text-gray-800">10</b>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:shadow-md transition duration-200">
            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center mb-3">
                <i class="fa fa-exclamation-triangle text-2xl text-orange-600"></i>
            </div>
            <span class="text-gray-500 text-xs font-medium uppercase tracking-wider mb-1">Perbaikan</span>
            <b class="text-xl font-bold text-gray-800">{{ $revisionCount }}</b>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:shadow-md transition duration-200">
            <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center mb-3">
                <i class="fa fa-check-circle text-2xl text-indigo-600"></i>
            </div>
            <span class="text-gray-500 text-xs font-medium uppercase tracking-wider mb-1">Diperbaiki</span>
            <b class="text-xl font-bold text-gray-800">0</b>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:shadow-md transition duration-200">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-3">
                <i class="fa fa-thumbs-up text-2xl text-green-600"></i>
            </div>
            <span class="text-gray-500 text-xs font-medium uppercase tracking-wider mb-1">Disetujui</span>
            <b class="text-xl font-bold text-gray-800">{{ $acceptedCount }}</b>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:shadow-md transition duration-200">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mb-3">
                <i class="fa fa-ban text-2xl text-red-600"></i>
            </div>
            <span class="text-gray-500 text-xs font-medium uppercase tracking-wider mb-1">Ditolak</span>
            <b class="text-xl font-bold text-gray-800">7</b>
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
        .fc .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 700;
            color: #1A5555;
        }

        /* Styling dasar semua tombol (termasuk < dan >) */
        .fc .fc-button {
            padding: 0.4rem 0.75rem !important;
            background-color: #1A5555 !important;
            border-color: #1A5555 !important;
            border-radius: 0.5rem !important;
            /* Membuat tombol agak membulat */
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            text-transform: capitalize !important;
        }

        .fc .fc-button:hover {
            background-color: #134040 !important;
            border-color: #134040 !important;
        }

        /* Styling khusus tombol "today" */
        .fc .fc-today-button {
            background-color: #1A5555 !important;
            /* Warna saat tidak disabled */
            border-color: #1A5555 !important;
            margin-right: 1rem !important;
            /* Jarak antara tombol today dan panah */
        }

        /* Tombol "today" ketika sedang di bulan saat ini (disabled) */
        .fc .fc-today-button:disabled {
            background-color: #1A5555 !important;
            /* Warna abu-abu yang lebih modern */
            border-color: #1A5555 !important;
            opacity: 1 !important;
        }

        /* Merenggangkan tombol panah < dan > yang tadinya tergabung (button-group) */
        .fc .fc-button-group {
            gap: 0.5rem;
            /* Memberikan jarak antar tombol di dalam group */
        }

        .fc .fc-button-group>.fc-button {
            flex: 0 1 auto;
        }

        /* Menghilangkan efek gabung bawaan dari button-group */
        .fc-direction-ltr .fc-button-group>.fc-button:not(:first-child),
        .fc-direction-ltr .fc-button-group>.fc-button:not(:last-child) {
            border-radius: 0.5rem !important;
            margin-left: 0 !important;
        }

        .fc-scrollgrid {
            border-radius: 0.5rem;
            overflow: hidden;
            border-color: #e5e7eb;
        }

        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #e5e7eb;
        }

        /* Style background untuk tabel hari (Senin, Selasa, dst) */
        .fc-col-header-cell {
            background-color: #007a7a !important;
            /* Sama dengan warna header tabel aplikasi */
        }

        .fc-col-header-cell-cushion {
            color: #ffffff !important;
            /* Warna teks putih */
            padding: 0.75rem 0.5rem !important;
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
        }

        .fc-daygrid-day-number {
            color: #4b5563;
            padding: 0.25rem !important;
            text-decoration: none;
        }

        /* Override warna kotak HARI INI (Today) di Kalender */
        .fc .fc-daygrid-day.fc-day-today {
            background-color: #e6fffa !important;
            /* Ganti warna ini (contoh: teal-50) */
        }
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
            const formattedDate = dateObj.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

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
                            const actStart = new Date(activity.start_date).toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                            });
                            const actEnd = new Date(activity.end_date).toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                            });

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