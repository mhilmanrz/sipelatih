<x-layouts.app>
    <x-slot:title>Tambah Peserta</x-slot>

    @push('styles')
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .custom-pane {
                border: 2px solid #e5e7eb;
                border-radius: 12px;
                background: white;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                height: 600px;
            }

            .pane-header {
                padding: 1rem 1.5rem;
                font-weight: bold;
                font-size: 1.125rem;
                color: #007a7a;
                border-bottom: 2px solid #e5e7eb;
                display: flex;
                align-items: center;
            }

            .pane-body {
                padding: 1.5rem;
                flex-grow: 1;
                overflow-y: auto;
                display: flex;
                flex-direction: column;
            }

            .search-container {
                position: relative;
                margin-bottom: 1rem;
            }

            .search-input {
                width: 100%;
                border: 2px solid #007a7a;
                border-radius: 9999px;
                padding: 0.75rem 1rem 0.75rem 3rem;
                font-size: 0.875rem;
                outline: none;
                color: #374151;
            }

            .search-icon {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: #007a7a;
                font-weight: bold;
            }

            .list-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 0;
                cursor: pointer;
                transition: background 0.2s;
                border-bottom: 1px solid #f3f4f6;
            }

            .list-item:hover {
                background: #f9fafb;
            }

            .user-info {
                font-weight: 500;
                color: #111827;
                font-size: 0.9rem;
            }

            .action-btn {
                background: none;
                border: none;
                cursor: pointer;
                font-size: 1.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-add {
                color: #007a7a;
            }

            .btn-remove {
                color: #ef4444;
            }

            .pagination-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: auto;
                padding-top: 1rem;
                border-top: 1px solid #e5e7eb;
                font-size: 0.875rem;
            }
        </style>
        <!-- FontAwesome for search icon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endpush

    <div class="p-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white uppercase" style="color: white; font-size: 1.5rem; background-color: #14b8a6; padding: 1rem 2rem; border-radius: 8px; display: inline-block;">TAMBAH PESERTA</h1>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'peserta']) }}" class="text-gray-600 hover:text-gray-900 font-medium bg-white px-4 py-2 rounded shadow">
                ← Kembali ke Detail
            </a>
        </div>

        <form id="pesertaForm" action="{{ route('kegiatan.peserta.store', $kegiatan->id) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-6 rounded-xl shadow">

                <!-- KIRI: PESERTA TERPILIH -->
                <div class="custom-pane">
                    <div class="pane-header">
                        <span id="selectedCount" class="text-teal-600 mr-2">0</span> Peserta Terpilih
                    </div>
                    <div class="pane-body">
                        <div class="search-container">
                            <i class="fa-solid fa-magnifying-glass search-icon"></i>
                            <input type="text" id="searchSelected" class="search-input" placeholder="Cari Berdasarkan NIP atau Nama">
                        </div>

                        <!-- Tempat menampung list selected -->
                        <div id="selectedListContainer" class="flex-grow overflow-y-auto">
                            <!-- List JS Generated -->
                        </div>

                        <!-- Tempat menampung hidden inputs -->
                        <div id="hiddenInputsContainer"></div>
                    </div>
                </div>

                <!-- KANAN: DAFTAR PESERTA -->
                <div class="custom-pane">
                    <div class="pane-header" style="color: #6b7280;"> <!-- Abu-abu sedikit menurut desain -->
                        Daftar Peserta
                    </div>
                    <div class="pane-body">
                        <div class="search-container">
                            <i class="fa-solid fa-magnifying-glass search-icon"></i>
                            <input type="text" id="searchAvailable" class="search-input" placeholder="Cari Berdasarkan NIP atau Nama">
                        </div>

                        <!-- Tempat menampung list available -->
                        <div id="availableListContainer" class="flex-grow overflow-y-auto relative">
                            <div id="loadingIndicator" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center hidden">
                                <span class="text-teal-600 font-bold">Sedang memuat data...</span>
                            </div>
                            <!-- List JS Generated -->
                        </div>

                        <!-- Pagination Container -->
                        <div class="pagination-container">
                            <span id="totalItemsText" class="text-teal-600 font-medium">Total 0 Items</span>
                            <div class="flex gap-1" id="paginationControls">
                                <!-- Pagination Generated -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- BUTTONS -->
            <div class="flex justify-end gap-4 mt-6">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-8 rounded-full shadow flex items-center gap-2">
                    <i class="fa-regular fa-floppy-disk"></i> Simpan
                </button>
                <button type="button" id="btnReset" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-8 rounded-full shadow flex items-center gap-2">
                    <i class="fa-solid fa-arrow-rotate-right"></i> Reset
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kegiatanId = "{{ $kegiatan->id }}";
            const apiUrlBase = `/kegiatan/${kegiatanId}/peserta/available-users`;

            // State variables
            let availableUsers = []; // Datang dari API
            let selectedUsers = []; // Koleksi lokal yang dicentang

            let currentPage = 1;
            let lastPage = 1;
            let totalItems = 0;
            let searchQuery = '';

            // Element references
            const searchInputAvailable = document.getElementById('searchAvailable');
            const searchInputSelected = document.getElementById('searchSelected');
            const availableContainer = document.getElementById('availableListContainer');
            const selectedContainer = document.getElementById('selectedListContainer');
            const hiddenInputsContainer = document.getElementById('hiddenInputsContainer');
            const selectedCountText = document.getElementById('selectedCount');
            const totalItemsText = document.getElementById('totalItemsText');
            const paginationControls = document.getElementById('paginationControls');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const btnReset = document.getElementById('btnReset');

            // Initial Data Fetch
            fetchData();

            // Caching debounce timer
            let typingTimer;
            const doneTypingInterval = 500;

            // Search Available (Backend)
            searchInputAvailable.addEventListener('keyup', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    searchQuery = searchInputAvailable.value;
                    currentPage = 1; // Reset to page 1 on new search
                    fetchData();
                }, doneTypingInterval);
            });

            // Search Selected (Frontend filter only)
            searchInputSelected.addEventListener('input', () => {
                renderSelected();
            });

            // Reset Button
            btnReset.addEventListener('click', () => {
                if(confirm("Apakah Anda yakin ingin mereset pilihan?")) {
                    selectedUsers = [];
                    searchInputSelected.value = '';
                    renderSelected();
                }
            });

            function fetchData() {
                loadingIndicator.classList.remove('hidden');

                // Build URL parameters
                const params = new URLSearchParams({
                    page: currentPage,
                    search: searchQuery
                });

                fetch(`${apiUrlBase}?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update state
                        availableUsers = data.data; // Array of items
                        currentPage = data.current_page;
                        lastPage = data.last_page;
                        totalItems = data.total;

                        renderAvailable();
                        renderPagination();
                    })
                    .catch(err => {
                        console.error("Gagal mengambil data:", err);
                        availableContainer.innerHTML = '<div class="text-center text-red-500 py-4">Gagal memuat data.</div>';
                    })
                    .finally(() => {
                        loadingIndicator.classList.add('hidden');
                    });
            }

            // Action: Pindah Kanan -> Kiri
            window.selectParticipant = function(userId) {
                // Temukan di available list
                const user = availableUsers.find(u => u.id === userId);

                if (user) {
                    // Pastikan belum ada di selectedUsers
                    if (!selectedUsers.some(u => u.id === userId)) {
                        selectedUsers.push(user);
                        renderSelected();
                        renderAvailable(); // rerender to hide it from available view
                    }
                }
            };

            // Action: Pindah Kiri -> Kanan (Hapus dari selected)
            window.removeParticipant = function(userId) {
                selectedUsers = selectedUsers.filter(u => u.id !== userId);
                renderSelected();
                renderAvailable(); // rerender to show it back in available view
            };

            function renderAvailable() {
                availableContainer.innerHTML = '';

                // Filter out users that are already in selected array so they don't appear twice
                const filteredAvailable = availableUsers.filter(u => !selectedUsers.some(sel => sel.id === u.id));

                if (filteredAvailable.length === 0) {
                    availableContainer.innerHTML = '<div class="text-center text-gray-500 py-4 font-style-italic">Kosong / Semua terpilih</div>';
                    return;
                }

                filteredAvailable.forEach(user => {
                    const nipText = user.nip ? `${user.nip}` : '-';
                    const nameText = user.name;

                    // Construct inner list border UI matching screenshot
                    const htmlStr = `
                        <div class="list-item border border-teal-600 rounded-md mb-2 px-3 hover:bg-teal-50" onclick="selectParticipant(${user.id})">
                            <div class="flex items-center w-full">
                                <span class="action-btn btn-add mr-3">
                                    <i class="fa-regular fa-square-check"></i>
                                </span>
                                <span class="user-info w-full text-center">${nameText} - ${nipText}</span>
                            </div>
                        </div>
                    `;
                    availableContainer.insertAdjacentHTML('beforeend', htmlStr);
                });
            }

            function renderSelected() {
                selectedContainer.innerHTML = '';
                hiddenInputsContainer.innerHTML = '';

                // Apply frontend filter
                const filterKeyword = searchInputSelected.value.toLowerCase();
                const filteredSelected = selectedUsers.filter(u => {
                    const fullName = `${u.name} ${u.nip}`.toLowerCase();
                    return fullName.includes(filterKeyword);
                });

                // Update Counts
                selectedCountText.textContent = selectedUsers.length;

                if (filteredSelected.length === 0 && selectedUsers.length > 0) {
                     selectedContainer.innerHTML = '<div class="text-center text-gray-400 py-4">Tidak cocok dengan pencarian.</div>';
                } else if (filteredSelected.length === 0) {
                    selectedContainer.innerHTML = '<div class="text-center text-gray-400 py-4">Belum ada peserta yang dipilih.</div>';
                }

                // Render list
                filteredSelected.forEach(user => {
                    const nipText = user.nip ? `${user.nip}` : '-';
                    const nameText = user.name;

                    const htmlStr = `
                        <div class="list-item pe-3 border-b-0 hover:bg-gray-100 rounded">
                            <div class="flex items-center w-full">
                                <span class="action-btn btn-remove mr-3 font-bold" onclick="removeParticipant(${user.id})">
                                    ✕
                                </span>
                                <span class="user-info font-bold">${nameText} - ${nipText}</span>
                            </div>
                        </div>
                    `;
                    selectedContainer.insertAdjacentHTML('beforeend', htmlStr);
                });

                // Bikin hidden inputs untuk form submit
                selectedUsers.forEach(user => {
                   hiddenInputsContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="user_ids[]" value="${user.id}">`);
                });
            }

            function renderPagination() {
                totalItemsText.textContent = `Total ${totalItems} Items`;
                paginationControls.innerHTML = '';

                if (lastPage <= 1) return; // tidak perlu pagination

                const createButton = (text, isCurrent, isDisabled, targetPage) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = text;

                    let classes = "px-3 py-1 border rounded text-xs font-semibold ";

                    if (isDisabled) {
                        classes += "border-gray-300 text-gray-400 cursor-not-allowed";
                        btn.disabled = true;
                    } else if (isCurrent) {
                        classes += "bg-teal-600 text-white border-teal-600";
                    } else {
                        classes += "bg-white text-teal-600 border-teal-600 hover:bg-teal-50";
                        btn.onclick = () => {
                            currentPage = targetPage;
                            fetchData();
                        };
                    }

                    btn.className = classes;
                    return btn;
                };

                // Previous button
                paginationControls.appendChild(createButton('Previous', false, currentPage === 1, currentPage - 1));

                // Generate page numbers
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(lastPage, currentPage + 2);

                for (let i = startPage; i <= endPage; i++) {
                    paginationControls.appendChild(createButton(i.toString(), i === currentPage, false, i));
                }

                // Next Button
                paginationControls.appendChild(createButton('Next', false, currentPage === lastPage, currentPage + 1));
            }
        });
    </script>
    @endpush
</x-layouts.app>
