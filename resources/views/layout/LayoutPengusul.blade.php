<!DOCTYPE html>
<html lang="id">
  <head>
  <meta charset="UTF-8">
  <title>@yield('title', 'siPELATIH')</title>

  <!-- FONT -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- ICON -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/LayoutPengusul.css') }}">
  @stack('styles')
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
      <a href="{{ url('/') }}">
        <i class="fa fa-home"></i> Dashboard
      </a>

      <a href="{{ url('/usulan-diklat') }}">
        <i class="fa fa-folder"></i> Usulan Diklat
      </a>

      <a href="{{ url('/monitoring-jpl') }}">
        <i class="fa fa-chart-line"></i> Monitoring JPL
      </a>

       <a href="{{ route('users.index') }}">
        <i class="fa-solid fa-id-card"></i>Management Pegawai
      </a>

       <a href="{{ url('/manajemen-sasaran-profesi') }}">
        <i class="fa-solid fa-briefcase"></i>Management Sasaran profesi
      </a>
    </div>
  </div>

  <!-- MAIN -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar" id="topbar">
      <i class="fa fa-bars" onclick="toggleSidebar()"></i>

      <div class="profile-area">
        <i class="fa fa-bell"></i>

        <span class="user-badge" onclick="toggleProfileMenu()">
          <i class="fa fa-user"></i> Diklat
        </span>

        <div class="profile-menu" id="profileMenu">
          <a href="#" onclick="showPage('password');return false;">🔑 Ubah Password</a>
          <a href="#" onclick="logout();return false;">🚪 Log Out</a>
        </div>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="content" id="pageArea">
       @yield('content')
    </div>

  </div>

</div>
<footer class="footer">
    © 2026 — Saskya • Nina • Sandra • Hilman
</footer>

 <script src="{{ asset('assets/js/LayoutPengusul.js') }}"></script>
 @stack('scripts')
 </body>
</html>