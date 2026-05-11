<div class="main-text">
    <p>Dalam rangka {{ $kegiatan->tujuan }}, akan dilaksanakan kegiatan <strong>{{ $hal }}</strong>
        secara {{ $kegiatan->metode }} pada:
    </p>

    <table class="event-detail-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 100px;">tanggal</td>
            <td style="width: 20px;">:</td>
            <td>{{ $hariTanggalAcara }}</td>
        </tr>
        <tr>
            <td>waktu</td>
            <td>:</td>
            <td>{{ $waktuAcara }}</td>
        </tr>
        <tr>
            <td>tempat</td>
            <td>:</td>
            <td>{{ $tempat }}</td>
        </tr>
    </table>

    <p>Demikian hal ini kami sampaikan, mohon Kepala Unit dapat menugaskan peserta terlampir
untuk mengikuti kegiatan sesuai dengan jadwal yang telah ditetapkan.
        Informasi lebih lanjut dapat menghubungi contact person Tim Kerja {{ $picUnitName }} :
        {{ $picName }} ({{ $picPhone }}).
    </p>
    <p>Atas perhatian dan kerjasama Bapak/Ibu diucapkan terima kasih.</p>
</div>
