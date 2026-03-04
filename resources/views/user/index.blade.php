{{-- resources/views/manajemenPegawai.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Pegawai</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/manajemenPegawai.css') }}">
</head>

<body>

<!-- CONTENT -->
<main class="content" id="content">

  <div class="page-wrap">

    <!-- TOP BAR -->
    <div class="table-top">
      <div class="left">
        Show
        <select>
          <option>10</option>
          <option>25</option>
          <option>50</option>
        </select>
        entries
      </div>

      <div class="right">
        <a href="{{ url('resources/views/user/import') }}" class="btn gray">⬇ Import Peserta</a>
        <a href="{{ url('resources/views/user/tambah') }}" class="btn green">＋ Tambah</a>
      </div>

    </div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
          document.getElementById("btnImport")?.addEventListener("click", () => {
              window.location.href = "{{ url('project/detail/peserta/import') }}";
          });

          document.getElementById("btnTambah")?.addEventListener("click", () => {
              window.location.href = "{{ url('project/detail/peserta/tambah') }}";
          });
      });
    </script>

    <!-- CARD -->
    <div class="card-table">

      <div class="filter-bar">
        <div class="search">
          <input type="text" placeholder="Cari NIP Peserta">
          <span>🔍</span>
        </div>

        <div class="search">
          <input type="text" placeholder="Cari Peserta">
          <span>🔍</span>
        </div>

        <button class="btn reset">⟳ Reset</button>
      </div>

      <table>
        <thead>
          <tr>
            <th>NO.</th>
            <th>NIP/NPS</th>
            <th>Nama Peserta</th>
            <th>Unit Kerja</th>
            <th>Tenaga</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>nps1233521</td>
            <td>dr. Rabbinu Rangga</td>
            <td>TK Diklat</td>
            <td>Perawat</td>
          </tr>
          <tr>
            <td>2</td>
            <td>23153786129368</td>
            <td>Andi Ade Wijaya</td>
            <td>ICTEC</td>
            <td>Administrasi</td>
          </tr>
        </tbody>
      </table>

      <div class="table-footer">
        <span>Showing 1 to 1 of 1 entries</span>
        <div class="pagination">
          <button>Previous</button>
          <button class="active">1</button>
          <button>Next</button>
        </div>
      </div>

    </div>
  </div>

</main>

<script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('assets/js/manajemenPegawai.js') }}"></script>

{{-- INCLUDE LAYOUT --}}
<div data-include="{{ asset('assets/layout/LayoutSuperAdmin.html') }}"></div>

</body>
</html>
