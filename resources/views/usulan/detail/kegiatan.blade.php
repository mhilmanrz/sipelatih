<!-- CONTENT CARD -->
<section style="margin-top: 2rem;">
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
        <a href="{{ route('kegiatan.edit', $kegiatan->id) }}"
            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded shadow transition-colors"
            style="text-decoration: none;">
            ✎ Edit Kegiatan
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1rem 0; font-size: 1rem; color: #374151;">
        <div style="font-weight: 600; color: #4b5563;">Tanggal Usulan</div>
        <div>{{ $kegiatan->date ? \Carbon\Carbon::parse($kegiatan->date)->format('d F Y') : '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">No. Surat</div>
        <div>{{ $kegiatan->reference_number ?? '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">Nama Kegiatan</div>
        <div>{{ $kegiatan->activityName->name ?? '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">Jenis Kegiatan</div>
        <div>{{ $kegiatan->activityType->name ?? '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">Cakupan</div>
        <div>{{ $kegiatan->activityScope->name ?? '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">Jenis Materi</div>
        <div>{{ $kegiatan->materialType->name ?? '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">Metode</div>
        <div>{{ $kegiatan->activityMethod->name ?? '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">Angkatan</div>
        <div>{{ $kegiatan->batch->name ?? '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">Bentuk</div>
        <div>{{ $kegiatan->activityFormat->name ?? '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">Target Peserta</div>
        <div>{{ $kegiatan->targetParticipant->name ?? '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">Institusi Kerjasama</div>
        <div>{{ $kegiatan->collaboration_inst ?? '-' }}</div>
        <div style="font-weight: 600; color: #4b5563;">Waktu Pelaksanaan</div>
        <div>
            @if ($kegiatan->start_date && $kegiatan->end_date)
                {{ \Carbon\Carbon::parse($kegiatan->start_date)->format('d M Y') }} –
                {{ \Carbon\Carbon::parse($kegiatan->end_date)->format('d M Y') }}
            @else
                -
            @endif
        </div>
        <div style="font-weight: 600; color: #4b5563;">Jam Mulai / Selesai</div>
        <div>
            @if ($kegiatan->start_time && $kegiatan->end_time)
                {{ $kegiatan->start_time }} – {{ $kegiatan->end_time }}
            @else
                -
            @endif
        </div>
        <div style="font-weight: 600; color: #4b5563;">Anggaran</div>
        <div>Rp {{ number_format($kegiatan->budget_amount, 0, ',', '.') }}</div>
        <div style="font-weight: 600; color: #4b5563;">PIC Penyelenggara</div>
        <div>{{ $kegiatan->organizerPic->name ?? '-' }}</div>
    </div>
</section>
