<x-layouts.app>
    <x-slot:title>Detail Usulan Diklat</x-slot>

    @php
        $activeTab = request('tab', 'kegiatan');
        $status = $kegiatan->latestStatus->status ?? 'draft';

        $tabBase = 'py-3 px-6 rounded-t-lg font-semibold whitespace-nowrap no-underline transition-all duration-200 border border-b-0 border-transparent cursor-pointer';
        $tabActive = 'bg-[#007a7a] text-white';
        $tabInactive = 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700';

        $statusColors = [
            'draft'     => 'bg-gray-200 text-gray-700',
            'submitted' => 'bg-blue-100 text-blue-700',
            'revision'  => 'bg-yellow-100 text-yellow-700',
            'accepted'  => 'bg-green-100 text-green-700',
        ];
        $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-600';
    @endphp

    <div class="px-8 py-6">

        {{-- HEADER --}}
        <div class="bg-[#007a7a] text-white py-6 px-8 rounded-t-xl text-2xl font-bold uppercase">
            PENGAJUAN DIKLAT - DETAIL
        </div>

        {{-- INFO CARD --}}
        <div class="bg-white p-8 rounded-b-xl shadow-sm mb-8">
            <div class="grid grid-cols-[200px_1fr] gap-y-4 text-base text-gray-700">
                <div class="font-semibold text-gray-600">Nama Kegiatan</div>
                <div class="font-medium">: {{ $kegiatan->activityName->name ?? '-' }}</div>

                <div class="font-semibold text-gray-600">Pengusul</div>
                <div class="font-medium">: {{ $kegiatan->workUnit->name ?? '-' }}</div>

                <div class="font-semibold text-gray-600">PIC Kegiatan</div>
                <div class="font-medium">: {{ $kegiatan->picUser->name ?? '-' }}</div>

                <div class="font-semibold text-gray-600">Jenis Kegiatan</div>
                <div class="font-medium">: {{ $kegiatan->activityType->name ?? '-' }}</div>

                <div class="font-semibold text-gray-600">Kategori Kegiatan</div>
                <div class="font-medium">: {{ $kegiatan->activityCategory->name ?? '-' }}</div>

                <div class="font-semibold text-gray-600">Cakupan</div>
                <div class="font-medium">: {{ $kegiatan->activityScope->name ?? '-' }}</div>

                <div class="font-semibold text-gray-600">Jenis Materi</div>
                <div class="font-medium">: {{ $kegiatan->materialType->name ?? '-' }}</div>

                <div class="font-semibold text-gray-600">Sumber Dana</div>
                <div class="font-medium">: {{ $kegiatan->fundSource->name ?? '-' }}</div>

                <div class="font-semibold text-gray-600">Kuota Peserta</div>
                <div class="font-medium">: {{ $kegiatan->quota_participant ? $kegiatan->quota_participant . ' Orang' : '-' }}</div>

                <div class="font-semibold text-gray-600">Waktu Pelaksanaan</div>
                <div class="font-medium">:
                    @if ($kegiatan->start_date && $kegiatan->end_date)
                        {{ \Carbon\Carbon::parse($kegiatan->start_date)->format('d M Y') }} s/d
                        {{ \Carbon\Carbon::parse($kegiatan->end_date)->format('d M Y') }}
                    @else
                        -
                    @endif
                </div>

                <div class="font-semibold text-gray-600">Status</div>
                <div class="font-medium">:
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold capitalize {{ $statusColor }}">
                        {{ $status }}
                    </span>
                </div>
            </div>
        </div>

        {{-- TABS NAVIGATION --}}
        <div class="flex gap-2 mb-6 flex-nowrap overflow-x-auto border-b-2 border-gray-200 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            @foreach ([
                'kegiatan'    => 'Kegiatan',
                'dokumen'     => 'Dokumen',
                'justifikasi' => 'Justifikasi',
                'sasaran'     => 'Sasaran Profesi',
                'kak'         => 'KAK',
                'materi'      => 'Materi',
                'narasumber'  => 'Narasumber',
                'peserta'     => 'Peserta',
                'input-nilai' => 'Input Nilai',
                'pengiriman'  => 'Pengiriman',
                'penilaian'   => 'Penilaian',
                'sertifikat'  => 'Sertifikat',
            ] as $tab => $label)
                <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => $tab]) }}"
                   class="{{ $tabBase }} {{ $activeTab === $tab ? $tabActive : $tabInactive }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- TAB CONTENT --}}
        <div class="bg-white rounded-xl shadow-sm min-h-[400px] p-8 [&>section]:mt-0 [&>section]:pt-4">
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

    @push('scripts')
        <script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
    @endpush
</x-layouts.app>
