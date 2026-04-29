@extends('layout.dashboard', ['title' => 'Monitoring Kegiatan'])

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="h3 mb-0 text-gray-800">{{ $title ?? 'Monitoring Kegiatan' }}</h3>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                    <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                        <tr>
                            <th class="text-center border border-white py-3 px-4 font-semibold" width="5%">No</th>
                            <th>Judul Kegiatan</th>
                            <th>Pengusul</th>
                            <th>JPL</th>
                            <th>Jenis Kegiatan</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Jenis Materi</th>
                            <th>No Registrasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataKegiatan as $index => $kegiatan)
                            <tr>
                                <td class="text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                                <td>{{ $kegiatan['nama'] }}</td>
                                <td>{{ $kegiatan['unit_pengusul'] }}</td>
                                <td>{{ $kegiatan['jpl'] }}</td>
                                <td>{{ $kegiatan['jenis'] }}</td>
                                <td>{{ $kegiatan['tgl_mulai'] }}</td>
                                <td>{{ $kegiatan['tgl_selesai'] }}</td>
                                <td>{{ $kegiatan['materi'] }}</td>
                                <td>{{ $kegiatan['no_registrasi'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted border border-gray-200 py-3 px-4">
                                    Data kegiatan belum tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
