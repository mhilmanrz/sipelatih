<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>


    <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

    <!-- JS Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

</head>

<body>
   
<!-- MODAL UBAH PASSWORD -->
<div class="modal" id="passwordModal">
    <div class="modal-box">
        <span class="close" onclick="closePasswordModal()">✖</span>

        <img src="../public/assets/images/logo-sipelatih.png" width="180">

        <label>Last Password</label>
        <input type="password" id="lastPassword">

        <label>New Password</label>
        <input type="password" id="newPassword">

        <button onclick="submitPassword()">Kirim</button>
    </div>
</div>

<!-- CONTENT -->
  <div class="content" id="pageArea">
      </div>
<!-- MAIN JS -->
<script src="{{ asset('assets/js/layout.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<div data-include="../resources/views/layout/layout.blade.php"></div>
</body>
</html>
