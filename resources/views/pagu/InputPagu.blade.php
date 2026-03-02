<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Pagu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inputpagu.css') }}">
</head>

<body>

<div class="layout">
    <main class="content input-page">

        <h1>INPUT PAGU</h1>

        <div class="table-wrapper">

            <div class="header">
                <a href="../resources/views/pagu/index.html" class="btn-add">＋ Tambah Pagu</a>
            </div>

            <table id="paguTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. RKAKL</th>
                        <th>Kategori Pagu</th>
                        <th>Submark</th>
                        <th>Pagu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="paguBody"></tbody>
            </table>

            <div class="total">
                <span>Total Pagu</span>
                <span id="totalPagu">0</span>
            </div>

        </div>

    </main>
</div>

{{-- LAYOUT LOAD --}}
<div data-include="{{ asset('layout/LayoutSuperAdmin.html') }}"></div>
<script src="{{ asset('assets/JS/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('assets/JS/InputPagu.js') }}"></script>

</body>
</html>
