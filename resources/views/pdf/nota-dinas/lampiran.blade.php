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

    <p style="font-weight: bold; margin-bottom: 10px;">Daftar Undangan:</p>

    <ol style="margin: 0; padding-left: 20px; line-height: 1.8;">
        @forelse ($daftarUndangan as $unitKerja)
            <li>{{ $unitKerja }}</li>
        @empty
            <li style="color: #666; font-style: italic;">Belum ada data peserta terdaftar.</li>
        @endforelse
    </ol>
</div>
