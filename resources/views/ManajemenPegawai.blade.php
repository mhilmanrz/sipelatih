<!DOCTYPE html>
<html lang="id">
  <head>
  <meta charset="UTF-8">
  <title>siPELATIH</title>

  <!-- FONT -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- ICON -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
</head>
<body>
<div class="wrapper">

  <!-- SIDEBAR -->
  <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="{{ asset('assets/images/logo-sipelatih.png') }}" width="180">

      <small>RSUPN Dr. Cipto Mangunkusumo</small>
    </div>

    <div class="menu">
      <a href="#" onclick="showPage('dashboard',this);return false;">
        <i class="fa fa-home"></i> Dashboard
      </a>

      <a href="#" onclick="showPage('UsulanDiklat',this);return false;">
        <i class="fa fa-folder"></i> Usulan Diklat</a>

        <a href="#" onclick="showPage('ManagementPegawai',this);return false;">
        <i class="fa-solid fa-id-card"></i>Manajemen Pegawai</a>
        
      <a href="#" onclick="showPage('MonitoringJPL',this);return false;">
      <i class="fa fa-chart-line"></i> Monitoring JPL</a>
      <a href="#" onclick="showPage('MonitoringJPL',this);return false;">
        <i class="fa fa-chart-line"></i> Pagu
      </a>
      <a href="#" onclick="showPage('MonitoringJPL',this);return false;">
        <i class="fa fa-chart-line"></i> Input Nilai
      </a>
      <a href="#" onclick="showPage('MonitoringJPL',this);return false;">
        <i class="fa fa-chart-line"></i> Laporan Kegiatan
      </a>
      <a href="#" onclick="showPage('MonitoringJPL',this);return false;">
        <i class="fa fa-chart-line"></i> Evaluasi I
      </a>
        <a href="#" onclick="showPage('MonitoringJPL',this);return false;">
            <i class="fa fa-chart-line"></i> Evaluasi II
        </a>
        <a href="#" onclick="showPage('MonitoringJPL',this);return false;">
        <i class="fa fa-chart-line"></i> Evaluasi III
      </a>
       <a href="#" onclick="showPage('ManagementSasaranProfesi',this);return false;">
        <i class="fa-solid fa-briefcase"></i>Bank Data
      </a>
    </div>
  </div>

  <!-- MAIN -->
  <div class="main">

    <!-- CONTENT -->
    <div class="content" id="pageArea">
       <div class="app">
    </div>

  </div>

</div>
<footer class="footer">
    © 2026 — Saskya • Nina • Sandra • Hilman
</footer>

 <script src="{{ asset('assets/js/layout.js') }}"></script>
@include('layout.LayoutSuperAdmin') 
</body>
</html>

