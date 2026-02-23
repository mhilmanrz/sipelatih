const tabs = document.querySelectorAll('.tab');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
    });
});


const btnKirim = document.querySelector('.btn-kirim'); // tombol KIRIM utama
const modal = document.getElementById('modalKirim');
const closeModal = document.getElementById('closeModal');
const btnBatal = document.getElementById('btnBatal');
const btnConfirm = document.querySelector('.btn-confirm');

// Buka modal
btnKirim.addEventListener('click', () => {
    modal.style.display = 'flex';
});

// Tutup modal
function hideModal() {
    modal.style.display = 'none';
}

closeModal.addEventListener('click', hideModal);
btnBatal.addEventListener('click', hideModal);

// Klik overlay untuk tutup
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        hideModal();
    }
});

// Aksi KIRIM
btnConfirm.addEventListener('click', () => {
    hideModal();
    alert('Pengajuan berhasil dikirim!');
    // Nanti di Laravel bisa ganti submit form / AJAX
});