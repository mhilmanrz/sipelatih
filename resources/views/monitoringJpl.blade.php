<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Monitoring JPL</title>

<!-- FONT -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- ICON -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Tailwind -->
 <link rel="stylesheet" href="../CSS/LayoutPengusul.css">
<link rel="stylesheet" href="../CSS/monitoringJpl.css">
<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        primary: '#0DBBCB'
      }
    }
  }
}
</script>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Layout CSS -->
<link rel="stylesheet" href="{{ asset('CSS/LayoutPengusul.css') }}">
<link rel="stylesheet" href="{{ asset('CSS/monitoringJpl.css') }}">
</head>

<body class="bg-[#0DBBCB]">

<div class="wrapper">



  <!-- MAIN -->
  <div class="main">

    

    <!-- CONTENT -->
    <div class="content p-6 space-y-6">

      <!-- TITLE -->
      <h2 class="text-2xl font-bold text-[#F6FCFC] text-left">
        MONITORING CAPAIAN JPL
      </h2>

      <!-- FILTER -->
      <div class="bg-white p-4 rounded shadow flex flex-col md:flex-row gap-4 md:items-center">
        <input
          type="text"
          placeholder="Cari NIP Peserta"
          class="border rounded px-4 py-2 w-full md:w-64 focus:outline-none focus:ring focus:ring-primary/40"
        >

        <input
          type="text"
          placeholder="Cari Peserta"
          class="border rounded px-4 py-2 w-full md:w-64 focus:outline-none focus:ring focus:ring-primary/40"
        >

        <button
          class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded w-full md:w-auto">
          Reset
        </button>
      </div>

      <!-- TABLE 1 -->
     <div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
    <thead class="bg-[#007A7F] text-white">
      <tr>
        <th class="px-3 py-2">No</th>
        <th class="px-3 py-2">Nama Pegawai</th>
        <th class="px-3 py-2">NIP</th>
        <th class="px-3 py-2">Unit Kerja</th>
        <th class="px-3 py-2">Target</th>
        <th class="px-3 py-2">Capaian</th>
        <th class="px-3 py-2">Keterangan</th>
      </tr>
    </thead>

          <tbody>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-3 py-2 text-center">1</td>
              <td class="px-3 py-2">Nina Persada</td>
              <td class="px-3 py-2">11164825545824</td>
              <td class="px-3 py-2">TK Diklat</td>
              <td class="px-3 py-2 text-center">24</td>
              <td class="px-3 py-2 text-center">50</td>
              <td class="px-3 py-2 text-center">
                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">
                  Tercapai
                </span>
              </td>
            </tr>

            <tr class="hover:bg-gray-50">
              <td class="px-3 py-2 text-center">2</td>
              <td class="px-3 py-2">Saskya Gok</td>
              <td class="px-3 py-2">11164825545284</td>
              <td class="px-3 py-2">TK Diklat</td>
              <td class="px-3 py-2 text-center">24</td>
              <td class="px-3 py-2 text-center">20</td>
              <td class="px-3 py-2 text-center">
                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs">
                  Belum Tercapai
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="bg-white rounded-2xl shadow overflow-x-auto">

    <table class="w-full text-sm text-gray-700">
        <thead class="bg-teal-700 text-white">
            <tr>
                <th class="px-4 py-3">No.</th>
                <th class="px-4 py-3">Nama Pegawai</th>
                <th class="px-4 py-3">NIP</th>
                <th class="px-4 py-3">Unit Kerja</th>
                <th class="px-4 py-3">Nama Kegiatan</th>
                <th class="px-4 py-3">Waktu Kegiatan</th>
                <th class="px-4 py-3">Cakupan Kegiatan</th>
                <th class="px-4 py-3">Jabatan</th>
                <th class="px-4 py-3">Tenaga</th>
                <th class="px-4 py-3">4 Besar</th>
                <th class="px-4 py-3">Target</th>
                <th class="px-4 py-3">Capaian</th>
                <th class="px-4 py-3">Keterangan</th>
            </tr>
        </thead>

        <tbody class="bg-gray-50">
            <tr class="border-b">
                <td class="px-4 py-3 text-center">1</td>
                <td class="px-4 py-3 text-teal-700 font-medium">Nina Persik</td>
                <td class="px-4 py-3">1116482655488234</td>
                <td class="px-4 py-3">TK Diklat</td>
                <td class="px-4 py-3">Resertifikasi BHD</td>
                <td class="px-4 py-3">1-10 Desember 2025</td>
                <td class="px-4 py-3">Teknis</td>
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3">Administrasi</td>
                <td class="px-4 py-3">Non Kes</td>
                <td class="px-4 py-3 text-center">24</td>
                <td class="px-4 py-3 text-center">50</td>
                <td class="px-4 py-3 text-center">
                    <span class="bg-green-500 text-white px-4 py-1 rounded-full text-xs">
                        Tercapai
                    </span>
                </td>
            </tr>

            <tr>
                <td class="px-4 py-3 text-center">2</td>
                <td class="px-4 py-3 text-teal-700 font-medium">Saskya Gotik</td>
                <td class="px-4 py-3">1116482655462584</td>
                <td class="px-4 py-3">TK Diklat</td>
                <td class="px-4 py-3">Resertifikasi BHD</td>
                <td class="px-4 py-3">1-10 Desember 2025</td>
                <td class="px-4 py-3">Teknis</td>
                <td class="px-4 py-3"></td>
                <td class="px-4 py-3">Administrasi</td>
                <td class="px-4 py-3">Non Kes</td>
                <td class="px-4 py-3 text-center">24</td>
                <td class="px-4 py-3 text-center">20</td>
                <td class="px-4 py-3 text-center">
                    <span class="bg-red-500 text-white px-4 py-1 rounded-full text-xs">
                        Belum Tercapai
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

</div>


      <!-- CHART -->
      <div class="bg-white rounded shadow p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">
          Grafik Capaian JPL
        </h3>
        <canvas id="jplChart"></canvas>
      </div>

    </div>
  </div>
</div>

<script>
const ctx = document.getElementById('jplChart');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Nina Persada', 'Saskya Gok'],
    datasets: [{
      label: 'Capaian JPL',
      data: [50, 20],
      backgroundColor: '#11b9c6'
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false }
    }
  }
});
</script>

<script src="{{ asset('JS/LayoutPengusul.js') }}"></script>
<script src="{{ asset('JS/monitoringJpl.js') }}"></script>
 <!-- SIDEBAR -->
@include('layout.LayoutPengusul')
</body>