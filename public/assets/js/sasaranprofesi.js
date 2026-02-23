const modal = document.getElementById('modal');
const openBtn = document.getElementById('openModal');
const closeBtn = document.getElementById('closeModal');
const cancelBtn = document.getElementById('cancelBtn');
const saveBtn = document.getElementById('saveBtn');
const profesiSelect = document.getElementById('profesiSelect');
const tableBody = document.getElementById('profesiTableBody');

// Buka modal
openBtn.addEventListener('click', () => {
    modal.style.display = 'flex';
});

// Tutup modal
function closeModal() {
    modal.style.display = 'none';
}

closeBtn.addEventListener('click', closeModal);
cancelBtn.addEventListener('click', closeModal);

// Klik luar modal tutup
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeModal();
    }
});

// Simpan
saveBtn.addEventListener('click', () => {

    const profesi = profesiSelect.value;

    if (profesi === "") {
        alert("Silakan pilih sasaran profesi terlebih dahulu!");
        return;
    }

    const rowCount = tableBody.querySelectorAll('tr').length + 1;

    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td>${rowCount}</td>
        <td>${profesi}</td>
        <td>
            <button class="btn-delete hapusBtn">HAPUS</button>
        </td>
    `;

    tableBody.appendChild(newRow);

    profesiSelect.value = "";
    closeModal();
});

// Hapus
tableBody.addEventListener('click', function(e) {
    if (e.target.classList.contains('hapusBtn')) {
        e.target.closest('tr').remove();

        const rows = tableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.querySelector('td').textContent = index + 1;
        });
    }
});
