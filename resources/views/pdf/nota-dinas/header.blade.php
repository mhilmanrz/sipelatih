@if (isset($kopBase64))
    <img src="{{ $kopBase64 }}" style="width: 100%; display: block;">
@elseif (isset($logoBase64))
    {{-- Fallback: tampilkan header teks jika kop belum di-set --}}
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 30%;">
                <img src="{{ $logoBase64 }}" style="width: 180px;">
            </td>
            <td style="width: 70%; padding-left: 15px;">
                <div class="kemenkes-title">Kementerian Kesehatan</div>
                <div class="dirjen-title">Direktorat Jenderal Kesehatan Lanjutan</div>
                <div class="rsup-title">RSUP Nasional Dr. Cipto Mangunkusumo Jakarta</div>
            </td>
        </tr>
    </table>
@else
    <div style="color: #0d9488; font-size: 18pt; font-weight: bold;">Kementerian Kesehatan</div>
    <div style="font-weight: bold;">RSUP Nasional Dr. Cipto Mangunkusumo</div>
@endif
