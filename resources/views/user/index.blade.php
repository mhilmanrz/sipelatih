@extends('layout.LayoutSuperAdmin')

@section('title', 'Manajemen Pegawai')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/manajemenPegawai.css') }}">
<style>
    .pagination nav ul {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 5px;
    }

    .pagination nav ul li a,
    .pagination nav ul li span {
        padding: 5px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
    }

    .pagination nav ul li.active span {
        background-color: #007A7F;
        color: white;
        border-color: #007A7F;
    }
</style>
@endpush

@section('content')
<div class="page-wrap">

    {{-- NOTIFIKASI IMPORT --}}
    <div id="import-notifications" style="display:none; margin-bottom:15px;"></div>

    {{-- STATUS BADGE: Sedang Diproses --}}
    <div id="import-processing" style="display:none; margin-bottom:15px;">
        <div style="background:#fffbeb; border-left:4px solid #f59e0b; color:#92400e; padding:14px 18px; border-radius:6px; display:flex; align-items:center; gap:12px;">
            <svg style="width:20px;height:20px;animation:spin 1s linear infinite;flex-shrink:0;" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="#f59e0b" stroke-width="3" stroke-dasharray="40" stroke-dashoffset="10" />
            </svg>
            <span><strong>⏳ Sedang Diproses...</strong> File import pegawai sedang berada dalam antrean. Halaman ini akan otomatis diperbarui setelah selesai.</span>
        </div>
    </div>
    <style>
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>


    <div class="table-top" style="display:flex; justify-content:space-between; margin-bottom:15px;">
        <div class="left" style="display:flex; align-items:center; gap:8px; color:#555; font-size:14px;">
            Show
            <select
                style="padding:6px 12px; border:1px solid #ddd; border-radius:4px; font-size:14px; outline:none; background-color:white; cursor:pointer;"
                onchange="window.location.href='?per_page='+this.value">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
            entries
        </div>

        <div class="right">
            <a href="{{ route('users.import.view') }}" class="btn gray"
                style="background:#ccc; padding:8px 15px; border-radius:5px; text-decoration:none; color:black; margin-right:5px;">⬇
                Import Peserta</a>
            <a href="{{ url('users/create') }}" class="btn green"
                style="background:#00B8A5; padding:8px 15px; border-radius:5px; text-decoration:none; color:white;">＋
                Tambah</a>
        </div>
    </div>

    <!-- CARD -->
    <div class="card-table"
        style="background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1);">
        <form method="GET" action="{{ route('users.index') }}" class="filter-bar"
            style="display:flex; gap:10px; margin-bottom:15px; align-items:center;">
            <div class="search" style="flex-grow: 1; max-width: 400px;">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Cari NIP, Nama, Unit Kerja, Jenis Tenaga..."
                    style="width: 100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <button type="submit" class="btn text-white"
                style="padding:8px 15px; background:#007A7F; border:none; border-radius:5px; cursor:pointer;">Cari</button>
            <a href="{{ route('users.index') }}" class="btn reset"
                style="padding:8px 15px; background:#f4f4f4; border:1px solid #ccc; border-radius:5px; cursor:pointer; text-decoration:none; color:black;">⟳
                Reset</a>
        </form>
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
                    <td style="padding:10px;"><a href="{{ route('users.edit', $u->id) }}"
                            style="text-decoration:none; color:#007A7F; font-weight:bold;">{{ $u->name }}</a>
                    </td>
                    <td style="padding:10px;">{{ $u->workUnit->name ?? '-' }}</td>
                    <td style="padding:10px;">{{ $u->profession->name ?? '-' }}</td>
                    <td style="padding:10px;">
                        <div style="display:flex; gap:5px; align-items:center;">
                            <a href="{{ route('users.edit', $u->id) }}"
                                style="background:#007A7F; border:none; color:white; padding:5px 10px; border-radius:5px; cursor:pointer; text-decoration:none; font-size:14px;">
                                Edit
                            </a>
                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="background:red; border:none; color:white; padding:5px 10px; border-radius:5px; cursor:pointer; font-size:14px;"
                                    onclick="return confirm('Hapus pegawai ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:20px;">Belum ada data pegawai.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="table-footer"
            style="display:flex; justify-content:space-between; margin-top:20px; align-items:center;">
            <span>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                entries</span>
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
<script>
    var notifUrl = "{{ route('users.notifications') }}";
    var notifReadUrl = "{{ route('users.notifications.read') }}";
    var importStatusUrl = "{{ route('users.import.status') }}";
    var csrfToken = "{{ csrf_token() }}";

    function fetchImportStatus() {
        fetch(importStatusUrl)
            .then(function(res) {
                return res.json();
            })
            .then(function(data) {
                var badge = document.getElementById('import-processing');
                badge.style.display = data.pending ? 'block' : 'none';
            })
            .catch(function() {});
    }

    function fetchImportNotifications() {
        fetch(notifUrl)
            .then(function(res) {
                return res.json();
            })
            .then(function(data) {
                var container = document.getElementById('import-notifications');
                if (!data.length) {
                    container.style.display = 'none';
                    return;
                }

                var html = '';
                data.forEach(function(n) {
                    var d = n.data;
                    var color = d.status === 'success' ? '#d1fae5' : '#fee2e2';
                    var border = d.status === 'success' ? '#10b981' : '#ef4444';
                    var text = d.status === 'success' ? '#065f46' : '#991b1b';
                    html += '<div style="background:' + color + ';border-left:4px solid ' + border + ';color:' + text + ';padding:14px 18px;border-radius:6px;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;">' +
                        '<span>' + d.icon + ' <strong>' + d.title + ':</strong> ' + d.message + '</span>' +
                        '<button onclick="markNotificationsRead()" style="background:none;border:none;cursor:pointer;font-size:18px;color:' + text + ';margin-left:12px;">&times;</button>' +
                        '</div>';
                });

                container.innerHTML = html;
                container.style.display = 'block';
            })
            .catch(function() {});
    }

    function markNotificationsRead() {
        fetch(notifReadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        }).then(function() {
            var c = document.getElementById('import-notifications');
            c.style.display = 'none';
            c.innerHTML = '';
        });
    }

    fetchImportNotifications();
    fetchImportStatus();
    setInterval(function() {
        fetchImportNotifications();
        fetchImportStatus();
    }, 10000);
</script>
@endpush