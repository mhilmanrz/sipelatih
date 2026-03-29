@extends('layout.LayoutPngusul')

@section('title', 'Manajemen Pegawai')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/peserta.css') }}">
@endpush

@section('content')

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
            <button onclick="location.href='{{ route('user.import') }}'" class="btn gray">
                ⬇ Import Peserta
            </button>

            <button onclick="location.href='{{ route('user.tambah') }}'" class="btn green">
                ＋ Tambah
            </button>
        </div>
    </div>

    <!-- CARD -->
    <div class="card-table">

        <!-- FILTER -->
        <div class="filter-bar">
            <div class="search">
                <input type="text" placeholder="Cari NIP Peserta">
                <span><i class="fa fa-search"></i></span>
            </div>

            <div class="search">
                <input type="text" placeholder="Cari Peserta">
                <span><i class="fa fa-search"></i></span>
            </div>

            <button class="btn reset">⟳ Reset</button>
        </div>

        <!-- TABLE -->
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
                {{-- Contoh static (nanti bisa pakai @foreach) --}}
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

        <!-- FOOTER -->
        <div class="table-footer">
            <span>Showing 1 to 2 of 2 entries</span>
            <div class="pagination">
                <button>Previous</button>
                <button class="active">1</button>
                <button>Next</button>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/peserta.js') }}"></script>
@endpush