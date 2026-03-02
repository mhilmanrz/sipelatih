@extends('layout.LayoutSuperAdmin')

@section('title', 'Manajemen Pegawai')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/manajemenPegawai.css') }}">
@endpush

@section('content')
  <div class="page-wrap">

    <!-- TOP BAR -->
    <div class="table-top" style="display:flex; justify-content:space-between; margin-bottom:15px;">
      <div class="left">
        Show
        <select style="padding:5px;">
          <option>10</option>
          <option>25</option>
          <option>50</option>
        </select>
        entries
      </div>

      <div class="right">
        <a href="#" class="btn gray" style="background:#ccc; padding:8px 15px; border-radius:5px; text-decoration:none; color:black; margin-right:5px;">⬇ Import Peserta</a>
        <a href="{{ url('users/create') }}" class="btn green" style="background:#00B8A5; padding:8px 15px; border-radius:5px; text-decoration:none; color:white;">＋ Tambah</a>
      </div>
    </div>

    <!-- CARD -->
    <div class="card-table" style="background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1);">
      <div class="filter-bar" style="display:flex; gap:10px; margin-bottom:15px;">
        <div class="search">
          <input type="text" placeholder="Cari NIP Pegawai" style="padding:8px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <div class="search">
          <input type="text" placeholder="Cari Nama Pegawai" style="padding:8px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <button class="btn reset" style="padding:8px 15px; background:#f4f4f4; border:1px solid #ccc; border-radius:5px; cursor:pointer;">⟳ Reset</button>
      </div>

      <table style="width:100%; border-collapse:collapse;">
        <thead>
          <tr style="background:#007A7F; color:white; text-align:left;">
            <th style="padding:10px;">NO.</th>
            <th style="padding:10px;">NIP/NPS</th>
            <th style="padding:10px;">Nama Pegawai</th>
            <th style="padding:10px;">Unit Kerja</th>
            <th style="padding:10px;">Tenaga</th>
            <th style="padding:10px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $index => $u)
          <tr style="border-bottom:1px solid #ddd;">
            <td style="padding:10px;">{{ $users->firstItem() + $index }}</td>
            <td style="padding:10px;">{{ $u->employee_id ?? '-' }}</td>
            <td style="padding:10px;"><a href="{{ route('users.edit', $u->id) }}" style="text-decoration:none; color:#007A7F; font-weight:bold;">{{ $u->name }}</a></td>
            <td style="padding:10px;">{{ $u->workUnit->name ?? '-' }}</td>
            <td style="padding:10px;">{{ $u->profession->name ?? '-' }}</td>
            <td style="padding:10px;">
                <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:red; border:none; color:white; padding:5px 10px; border-radius:5px; cursor:pointer;" onclick="return confirm('Hapus pegawai ini?')">
                        Hapus
                    </button>
                </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align:center; padding:20px;">Belum ada data pegawai.</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <div class="table-footer" style="display:flex; justify-content:space-between; margin-top:20px; align-items:center;">
        <span>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries</span>
        <div class="pagination">
          {{ $users->links('pagination::bootstrap-4') }} <!-- Keeping standard pagination links -->
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('assets/js/manajemenPegawai.js') }}"></script>
@endpush
