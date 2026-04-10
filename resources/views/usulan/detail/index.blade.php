@extends('layout.LayoutSuperAdmin', ['title' => 'Detail Usulan Diklat'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    <style>
        .page-header {
            background-color: #007a7a;
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 12px 12px 0 0;
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .info-card {
            background: white;
            padding: 2rem;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 1rem 0;
            font-size: 1rem;
            color: #374151;
        }

        .info-grid .label {
            font-weight: 600;
            color: #4b5563;
        }

        .info-grid .value {
            font-weight: 500;
        }

        .tab-navigation {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: nowrap;
            /* Tahan agar tidak turun baris */
            overflow-x: auto;
            /* Munculkan scroll horizontal jika mentok */
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0px;

            /* Sembunyikan garis scrollbar agar lebih estetis (terutama di Chrome/Safari) */
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .tab-navigation::-webkit-scrollbar {
            display: none;
            /* Sembunyikan scrollbar di Chrome/Safari/Opera */
        }

        .tab-link {
            padding: 0.75rem 1.5rem;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s ease;
            background: #f3f4f6;
            border: 1px solid transparent;
            border-bottom: none;
            cursor: pointer;
            white-space: nowrap;
            /* Mencegah teks di dalam tab terpotong/turun baris */
        }

        .tab-link:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .tab-link.active {
            background: #007a7a;
            color: white;
        }

        .tab-content-area {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            min-height: 400px;
            padding: 2rem;
            /* Tambahan padding di sekeliling content/tab */
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-draft {
            background-color: #e5e7eb;
            color: #374151;
        }

        .status-submitted {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .status-revision {
            background-color: #fef08a;
            color: #a16207;
        }

        .status-accepted {
            background-color: #dcfce3;
            color: #15803d;
        }
    </style>
@endpush

@section('content')
    <div class="px-8 py-6">

        <!-- HEADER & BASIC INFO -->
        <div class="page-header">
            PENGAJUAN DIKLAT - DETAIL
        </div>

        <div class="info-card">
            <div class="info-grid">
                <div class="label">Nama Kegiatan</div>
                <div class="value">: {{ $kegiatan->activityName->name ?? '-' }}</div>

                <div class="label">Pengusul</div>
                <div class="value">: {{ $kegiatan->workUnit->name ?? '-' }}</div>

                <div class="label">PIC Kegiatan</div>
                <div class="value">: {{ $kegiatan->picUser->name ?? '-' }}</div>

                <div class="label">Jenis Kegiatan</div>
                <div class="value">: {{ $kegiatan->activityType->name ?? '-' }}</div>

                <div class="label">Kategori Kegiatan</div>
                <div class="value">: {{ $kegiatan->activityCategory->name ?? '-' }}</div>

                <div class="label">Cakupan</div>
                <div class="value">: {{ $kegiatan->activityScope->name ?? '-' }}</div>

                <div class="label">Jenis Materi</div>
                <div class="value">: {{ $kegiatan->materialType->name ?? '-' }}</div>

                <div class="label">Sumber Dana</div>
                <div class="value">: {{ $kegiatan->fundSource->name ?? '-' }}</div>

                <div class="label">Kuota Peserta</div>
                <div class="value">: {{ $kegiatan->quota_participant ? $kegiatan->quota_participant . ' Orang' : '-' }}</div>

                <div class="label">Waktu Pelaksanaan</div>
                <div class="value">:
                    @if ($kegiatan->start_date && $kegiatan->end_date)
                        {{ \Carbon\Carbon::parse($kegiatan->start_date)->format('d M Y') }} s/d
                        {{ \Carbon\Carbon::parse($kegiatan->end_date)->format('d M Y') }}
                    @else
                        -
                    @endif
                </div>

                <div class="label">Status</div>
                <div class="value">:
                    @php
                        $status = $kegiatan->latestStatus->status ?? 'draft';
                        $badgeClass = 'status-' . $status;
                    @endphp
                    <span class="status-badge {{ $badgeClass }}">{{ $status }}</span>
                </div>
            </div>
        </div>

        <!-- TABS NAVIGATION -->
        @php
            $activeTab = request('tab', 'kegiatan');
        @endphp
        <div class="tab-navigation">
            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'kegiatan']) }}"
                class="tab-link {{ $activeTab == 'kegiatan' ? 'active' : '' }}">Kegiatan</a>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'sasaran']) }}"
                class="tab-link {{ $activeTab == 'sasaran' ? 'active' : '' }}">Sasaran Profesi</a>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'kak']) }}"
                class="tab-link {{ $activeTab == 'kak' ? 'active' : '' }}">KAK</a>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'materi']) }}"
                class="tab-link {{ $activeTab == 'materi' ? 'active' : '' }}">Materi</a>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'narasumber']) }}"
                class="tab-link {{ $activeTab == 'narasumber' ? 'active' : '' }}">Narasumber</a>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'peserta']) }}"
                class="tab-link {{ $activeTab == 'peserta' ? 'active' : '' }}">Peserta</a>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'input-nilai']) }}"
                class="tab-link {{ $activeTab == 'input-nilai' ? 'active' : '' }}">Input Nilai</a>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'pengiriman']) }}"
                class="tab-link {{ $activeTab == 'pengiriman' ? 'active' : '' }}">Pengiriman</a>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'penilaian']) }}"
                class="tab-link {{ $activeTab == 'penilaian' ? 'active' : '' }}">Penilaian</a>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'sertifikat']) }}"
                class="tab-link {{ $activeTab == 'sertifikat' ? 'active' : '' }}">Sertifikat</a>
        </div>

        <!-- DYNAMIC TAB CONTENT AREA -->
        <div class="tab-content-area">
            @if ($activeTab == 'kegiatan')
                @include('usulan.detail.kegiatan')
            @elseif($activeTab == 'kak')
                @include('usulan.detail.kak')
            @elseif($activeTab == 'sasaran')
                @include('usulan.detail.sasaranprofesi')
            @elseif($activeTab == 'materi')
                @include('usulan.detail.materi')
            @elseif($activeTab == 'narasumber')
                @include('usulan.detail.narasumber')
            @elseif($activeTab == 'peserta')
                @include('usulan.detail.peserta')
            @elseif($activeTab == 'input-nilai')
                @include('usulan.detail.input_nilai')
            @elseif($activeTab == 'pengiriman')
                @include('usulan.detail.pengiriman')
            @elseif($activeTab == 'penilaian')
                @include('usulan.detail.penilaian')
            @elseif($activeTab == 'sertifikat')
                @include('usulan.detail.sertifikat')
            @else
                <div class="p-8 text-center text-gray-500">
                    <h3 class="text-xl font-bold mb-2">Tab {{ ucfirst($activeTab) }}</h3>
                    <p>Bagian ini sedang dalam tahap pengembangan (Under Construction).</p>
                </div>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
@endpush
