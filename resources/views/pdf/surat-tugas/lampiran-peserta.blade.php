<div style="page-break-before: always;"></div>

<table style="width: 100%; margin-bottom: 15px;">
    <tr>
        <td style="width: 60%;"></td>
        <td style="width: 40%;">
            <div style="font-weight: bold; margin-bottom: 5px;">Lampiran - 1</div>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 60px;">Nomor</td>
                    <td style="width: 10px;">:</td>
                    <td>{{ $nomorSurat }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>{{ $tanggalSuratFormatted }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="font-weight: bold; margin-bottom: 10px;">Daftar Peserta yang ditugaskan</div>

<table class="peserta-table">
    <thead>
        <tr>
            <th style="width: 5%;">No.</th>
            <th style="width: 25%;">Nama</th>
            <th style="width: 20%;">NIP/NPS</th>
            <th style="width: 20%;">Pangkat/ Golongan</th>
            <th style="width: 30%;">Jabatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($peserta as $index => $p)
            <tr>
                <td class="center">{{ $loop->iteration }}.</td>
                <td>{{ $p->user->name ?? '-' }}</td>
                <td>{{ $p->user->employee_id ?? '-' }}</td>
                <td>{{ $p->user->rank->name ?? '-' }}</td>
                <td>{{ $p->user->position->name ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="center" style="font-style: italic; color: #666;">Belum ada peserta yang terdaftar.</td>
            </tr>
        @endforelse
    </tbody>
</table>
