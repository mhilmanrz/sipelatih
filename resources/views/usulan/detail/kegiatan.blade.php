<section>
    <div class="flex justify-end mb-6">
        <a href="{{ route('kegiatan.edit', $kegiatan->id) }}"
            class="inline-flex items-center gap-1.5 bg-[#007a7a] hover:bg-[#005f5f] text-white px-4 py-2 rounded-lg text-sm font-semibold transition no-underline">
            <i class="fa fa-pen-to-square text-xs"></i>
            Edit
        </a>
    </div>

    <x-detail-section title="Identitas Kegiatan" icon="fa-clipboard-list">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1">
            <x-detail-row label="Nama Kegiatan">{{ $kegiatan->activityName->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Kategori">{{ $kegiatan->activityCategory->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Jenis">{{ $kegiatan->activityType->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Cakupan">{{ $kegiatan->activityScope->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Metode">{{ $kegiatan->activityMethod->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Bentuk">{{ $kegiatan->activityFormat->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Angkatan">{{ $kegiatan->batch->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Jenis Materi">{{ $kegiatan->materialType->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Tujuan">{{ $kegiatan->tujuan ?? '-' }}</x-detail-row>
        </div>
    </x-detail-section>

    <x-detail-section title="Peserta & Penyelenggara" icon="fa-users">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1">
            <x-detail-row label="Target Peserta">{{ $kegiatan->profession->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Kuota Peserta">{{ $kegiatan->quota_participant ? $kegiatan->quota_participant . ' Orang' : '-' }}</x-detail-row>
            <x-detail-row label="Pengusul">{{ $kegiatan->workUnit->name ?? '-' }}</x-detail-row>
            <x-detail-row label="PIC Kegiatan">{{ $kegiatan->picUser->name ?? '-' }}</x-detail-row>
            <x-detail-row label="PIC Penyelenggara">{{ $kegiatan->organizerPic->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Institusi Kerjasama">{{ $kegiatan->collaboration_inst ?? '-' }}</x-detail-row>
        </div>
    </x-detail-section>

    <x-detail-section title="Pelaksanaan & Keuangan" icon="fa-calendar-alt">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1">
            <x-detail-row label="Tanggal Usulan">{{ $kegiatan->date ? \Carbon\Carbon::parse($kegiatan->date)->format('d M Y') : '-' }}</x-detail-row>
            <x-detail-row label="No. Surat">{{ $kegiatan->reference_number ?? '-' }}</x-detail-row>
            <x-detail-row label="Waktu Pelaksanaan">
                @if ($kegiatan->start_date && $kegiatan->end_date)
                    {{ \Carbon\Carbon::parse($kegiatan->start_date)->format('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($kegiatan->end_date)->format('d M Y') }}
                @else
                    -
                @endif
            </x-detail-row>
            <x-detail-row label="Jam">
                @if ($kegiatan->start_time && $kegiatan->end_time)
                    {{ $kegiatan->start_time }} &ndash; {{ $kegiatan->end_time }}
                @else
                    -
                @endif
            </x-detail-row>
            <x-detail-row label="Tempat">{{ $kegiatan->tempat ?? '-' }}</x-detail-row>
            <x-detail-row label="Sumber Dana">{{ $kegiatan->fundSource->name ?? '-' }}</x-detail-row>
            <x-detail-row label="Anggaran Ajuan">Rp {{ number_format($kegiatan->budget_amount, 0, ',', '.') }}</x-detail-row>

            <x-detail-row label="Anggaran Diterima">
                @if (auth()->user()->stageRole() === 'perencanaan' && $kegiatan->currentStage() === 'perencanaan')
                    <form action="{{ route('kegiatan.budget_diterima', $kegiatan->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="number" step="0.01" min="0" name="budget_diterima"
                            value="{{ old('budget_diterima', $kegiatan->budget_diterima) }}"
                            class="w-40 rounded-lg border-gray-300 text-sm focus:border-[#007a7a] focus:ring-[#007a7a]">
                        <button type="submit" class="text-xs font-semibold text-[#007a7a] hover:text-[#005f5f]">Simpan</button>
                    </form>
                @else
                    {{ $kegiatan->budget_diterima !== null ? 'Rp ' . number_format($kegiatan->budget_diterima, 0, ',', '.') : '-' }}
                @endif
            </x-detail-row>

            <x-detail-row label="Anggaran Diserap">
                @if (auth()->user()->stageRole() === 'penyelenggara' && $kegiatan->currentStage() === 'penyelenggara')
                    <form action="{{ route('kegiatan.budget_diserap', $kegiatan->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="number" step="0.01" min="0" name="budget_diserap"
                            value="{{ old('budget_diserap', $kegiatan->budget_diserap) }}"
                            class="w-40 rounded-lg border-gray-300 text-sm focus:border-[#007a7a] focus:ring-[#007a7a]">
                        <button type="submit" class="text-xs font-semibold text-[#007a7a] hover:text-[#005f5f]">Simpan</button>
                    </form>
                @else
                    {{ $kegiatan->budget_diserap !== null ? 'Rp ' . number_format($kegiatan->budget_diserap, 0, ',', '.') : '-' }}
                @endif
            </x-detail-row>
        </div>
    </x-detail-section>
</section>