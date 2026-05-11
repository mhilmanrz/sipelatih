<div style="page-break-before: always;"></div>

<div style="margin-top: 40px;">
    <table style="width: 100%; margin-bottom: 30px;" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 55%;"></td>
            <td style="width: 45%;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-weight: bold; white-space: nowrap; padding-bottom: 3px;">Lampiran - 1</td>
                    </tr>
                    <tr>
                        <td style="width: 80px;">Nomor</td>
                        <td style="width: 15px;">:</td>
                        <td>${nomor_naskah}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>${tanggal_naskah}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="font-weight: bold; margin-bottom: 15px;">Daftar Peserta yang terdaftar</p>

    <table class="peserta-table">
        <thead>
            <tr>
                <th style="width: 8%;">No.</th>
                <th style="width: 32%;">Nama</th>
                <th style="width: 30%;">NIP</th>
                <th style="width: 30%;">Unit Kerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peserta as $index => $p)
                <tr>
                    <td class="center">{{ $index + 1 }}.</td>
                    <td>{{ $p->user?->name ?? '-' }}</td>
                    <td>{{ $p->user?->employee_id ?? '-' }}</td>
                    <td>{{ $p->user?->workUnit?->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="center" style="font-style: italic; color: #666;">
                        Belum ada peserta terdaftar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
