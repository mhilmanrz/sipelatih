<x-layouts.app>
    <x-slot:title>Detail Usulan Diklat</x-slot>

    @php
        $activeTab = request('tab', 'kegiatan');
        $status = $kegiatan->latestStatus->status ?? 'draft';

        $tabs = [
            'kegiatan'    => ['label' => 'Kegiatan',        'title' => 'Informasi Kegiatan',       'icon' => 'fa-clipboard-list'],
            'dokumen'     => ['label' => 'Dokumen',         'title' => 'Dokumen Kegiatan',         'icon' => 'fa-file-alt'],
            'justifikasi' => ['label' => 'Justifikasi',     'title' => 'Tujuan & Justifikasi',     'icon' => 'fa-balance-scale'],
            'sasaran'     => ['label' => 'Sasaran Profesi', 'title' => 'Sasaran Profesi',          'icon' => 'fa-bullseye'],
            'kak'         => ['label' => 'KAK',             'title' => 'Dokumen KAK',              'icon' => 'fa-file-contract'],
            'materi'      => ['label' => 'Materi',          'title' => 'Materi Kegiatan',           'icon' => 'fa-book'],
            'narasumber'  => ['label' => 'Narasumber',      'title' => 'Narasumber / Pembicara',    'icon' => 'fa-user-tie'],
            'peserta'     => ['label' => 'Peserta',          'title' => 'Peserta Kegiatan',          'icon' => 'fa-users'],
            'input-nilai' => ['label' => 'Input Nilai',     'title' => 'Input Nilai Peserta',      'icon' => 'fa-pen-alt'],
            'pengiriman'  => ['label' => 'Pengiriman',       'title' => 'Pengiriman Usulan',         'icon' => 'fa-paper-plane'],
            'penilaian'   => ['label' => 'Penilaian',        'title' => 'Riwayat Penilaian',         'icon' => 'fa-star'],
            'sertifikat'  => ['label' => 'Sertifikat',       'title' => 'E-Sertifikat',              'icon' => 'fa-certificate'],
        ];

        $statusColors = [
            'draft'     => 'bg-white/20 text-white',
            'submitted' => 'bg-white/25 text-white',
            'revision'  => 'bg-yellow-400/90 text-yellow-900',
            'accepted'  => 'bg-green-400/90 text-green-900',
        ];
        $statusColor = $statusColors[$status] ?? 'bg-white/15 text-white';
    @endphp

    <div class="px-8 py-6">

        {{-- HEADER + INFO BANNER --}}
        <div class="bg-[#007a7a] rounded-xl shadow-sm overflow-hidden">
            <div class="px-8 py-5 flex flex-wrap items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-200 mb-1">Pengajuan Diklat</p>
                    <h1 class="text-xl font-bold text-white truncate">{{ $kegiatan->activityName->name ?? '-' }}</h1>
                </div>
                <div class="shrink-0">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-sm font-semibold capitalize {{ $statusColor }}">
                        <span class="w-2 h-2 rounded-full {{ $status === 'accepted' ? 'bg-green-900' : ($status === 'submitted' ? 'bg-white' : ($status === 'revision' ? 'bg-yellow-900' : 'bg-white/70')) }}"></span>
                        {{ ucfirst($status) }}
                    </span>
                </div>
            </div>
            <div class="bg-white px-8 py-3.5 rounded-b-xl">
                <div class="flex flex-wrap items-center gap-x-8 gap-y-2 text-sm text-gray-600">
                    <span class="inline-flex items-center gap-2">
                        <i class="fa fa-building text-[#007a7a]"></i>
                        <span class="font-medium text-gray-800">{{ $kegiatan->workUnit->name ?? '-' }}</span>
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <i class="fa fa-calendar-alt text-[#007a7a]"></i>
                        @if ($kegiatan->start_date && $kegiatan->end_date)
                            <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($kegiatan->start_date)->format('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($kegiatan->end_date)->format('d M Y') }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- SIDEBAR + CONTENT LAYOUT --}}
        <div class="flex gap-0 rounded-xl overflow-hidden shadow-sm border border-gray-200 mt-6">

            {{-- SIDEBAR NAV --}}
            <nav class="w-52 shrink-0 bg-gray-50 border-r border-gray-200">
                @foreach ($tabs as $tab => $meta)
                    <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => $tab]) }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm font-medium border-l-3 transition-colors duration-150
                              {{ $activeTab === $tab
                                  ? 'bg-[#007a7a] text-white border-l-[#004d4d]'
                                  : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800 border-l-transparent' }}">
                        <i class="fa {{ $meta['icon'] }} w-4 text-center"></i>
                        {{ $meta['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- CONTENT --}}
            <div class="flex-1 bg-white min-h-[400px] p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6">{{ $tabs[$activeTab]['title'] }}</h3>

                @if ($activeTab == 'kegiatan')
                    @include('usulan.detail.kegiatan')
                @elseif ($activeTab == 'dokumen')
                    @include('usulan.detail.dokumen')
                @elseif ($activeTab == 'justifikasi')
                    @include('usulan.detail.justifikasi')
                @elseif ($activeTab == 'kak')
                    @include('usulan.detail.kak')
                @elseif ($activeTab == 'sasaran')
                    @include('usulan.detail.sasaranprofesi')
                @elseif ($activeTab == 'materi')
                    @include('usulan.detail.materi')
                @elseif ($activeTab == 'narasumber')
                    @include('usulan.detail.narasumber')
                @elseif ($activeTab == 'peserta')
                    @include('usulan.detail.peserta')
                @elseif ($activeTab == 'input-nilai')
                    @include('usulan.detail.input_nilai')
                @elseif ($activeTab == 'pengiriman')
                    @include('usulan.detail.pengiriman')
                @elseif ($activeTab == 'penilaian')
                    @include('usulan.detail.penilaian')
                @elseif ($activeTab == 'sertifikat')
                    @include('usulan.detail.sertifikat')
                @else
                    <div class="p-8 text-center text-gray-500">
                        <h3 class="text-xl font-bold mb-2">Tab {{ ucfirst($activeTab) }}</h3>
                        <p>Bagian ini sedang dalam tahap pengembangan.</p>
                    </div>
                @endif
            </div>

        </div>

    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
    @endpush
</x-layouts.app>