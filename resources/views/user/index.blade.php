<x-layouts.app>
    <x-slot:title>Manajemen Pegawai</x-slot>

    <div class="page-wrap">

        <!-- TOP BAR -->
        <div class="table-top" style="display:flex; justify-content:space-between; margin-bottom:15px;">
            <div class="left" style="display:flex; align-items:center; gap:8px; color:#000000; font-size:14px;">
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
                    style="background:#D6DE20; padding:8px 15px; border-radius:5px; text-decoration:none; color:black; margin-right:5px;">⬇
                    Import Peserta</a>
                <a href="{{ url('users/create') }}" class="btn green"
                    style="background:#1A5555; padding:8px 15px; border-radius:5px; text-decoration:none; color:white;">＋
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
                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST"
                                        style="margin:0;">
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
                <span>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of
                    {{ $users->total() }}
                    entries</span>
                <div class="pagination">
                    {{ $users->links('pagination::bootstrap-4') }} <!-- Keeping standard pagination links -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
        <script src="{{ asset('assets/js/manajemenPegawai.js') }}"></script>
    @endpush
</x-layouts.app>
