<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Usulan Diklat</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/LayoutPengusul.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/usulan.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
<div class="content">

    <div class="page-header">
        <h2>Usulan Diklat</h2>

        {{-- arahkan ke route / url laravel --}}
        <a href="{{ url('../resources/views/usulan/tambahdata.html') }}" class="btn-add">➕ Tambah Data</a>
    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("btnTambah");
    if(btn){
        btn.addEventListener("click", () => {
            window.location.href = "{{ url('../resources/views/usulan/tambahdata.html') }}";
        });
    }
});
</script>

    <!-- AREA FORM -->
    <div id="pengajuanArea"></div>

    <!-- AREA TABLE -->
    <div class="card-box" id="tableArea">

        <div class="table-control">
            <div>
                Show 
                <select id="entriesSelect">
                    <option>5</option>
                    <option selected>10</option>
                    <option>25</option>
                </select>
                entries
            </div>

            <input type="text" id="searchInput" placeholder="Search...">
        </div>

      <div class="table-wrapper">
            <table id="monitorTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Aksi</th>
                        <th>Judul Kegiatan</th>
                        <th>Pengusul</th>
                        <th>JPL</th>
                        <th>Jenis Kegiatan</th>
                        <th>Waktu</th>
                        <th>Materi</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>1</td>
                        <td><button class="btn-detail">Detail</button></td>
                        <td>Workshop ICU</td>
                        <td>RSUPN Cipto</td>
                        <td>2</td>
                        <td>Teknis</td>
                        <td>11-11-2025</td>
                        <td>Kurikulum</td>
                        <td><span class="badge draft">Draft</span></td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td><button class="btn-detail">Detail</button></td>
                        <td>Workshop Manajerial</td>
                        <td>RSUPN Cipto</td>
                        <td>3</td>
                        <td>Manajerial</td>
                        <td>25-11-2025</td>
                        <td>Teknis</td>
                        <td><span class="badge ok">Disetujui</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>

<script src="{{ asset('assets/js/usulan.js') }}"></script>
<script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
</body>
</html>
