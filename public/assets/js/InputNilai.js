const modal = document.getElementById('modal');
const openBtn = document.getElementById('openModal'); // Tombol Tambah global (diabaikan jika tidak ada row template baru)
const closeBtn = document.getElementById('closeModal');
const cancelBtn = document.getElementById('cancelModal');
const form = document.getElementById("nilaiForm");
const tableBody = document.getElementById("tableBody");

let editRow = null;

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    modal.style.display = "none";
}

if(closeBtn) closeBtn.onclick = closeModal;
if(cancelBtn) cancelBtn.onclick = closeModal;
if(openBtn) {
    openBtn.onclick = () => {
        alert("Silakan klik Edit pada baris peserta di tabel untuk mengisi nilai.");
    }
}


form.addEventListener("submit", function(e) {
    e.preventDefault();

    if(!editRow) {
        alert("Silakan pilih peserta untuk diisi nilai.");
        closeModal();
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const kegiatanId = document.getElementById('kegiatanId').value;
    const participantId = editRow.getAttribute('data-participant-id');

    const preVal = parseInt(document.getElementById("pretest").value);
    const postVal = parseInt(document.getElementById("posttest").value);
    const praktikVal = parseInt(document.getElementById("praktik").value);

    // Disable button to prevent double submit
    const submitBtn = form.querySelector('button[type="submit"]');
    const oldText = submitBtn.innerText;
    submitBtn.innerText = "Menyimpan...";
    submitBtn.disabled = true;

    fetch(`/kegiatan/${kegiatanId}/peserta/${participantId}/score`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            pre_test_score: isNaN(preVal) ? null : preVal,
            post_test_score: isNaN(postVal) ? null : postVal,
            practice_score: isNaN(praktikVal) ? null : praktikVal
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            let cells = editRow.querySelectorAll("td");
            cells[4].innerText = isNaN(preVal) ? 0 : preVal;
            cells[5].innerText = isNaN(postVal) ? 0 : postVal;
            cells[6].innerText = isNaN(praktikVal) ? 0 : praktikVal;
            cells[7].innerText = data.data.akumulasi;
            
            cells[9].innerHTML = `<span class="${data.data.warna} text-white px-3 py-1 rounded-full text-xs">${data.data.status}</span>`;
            
            closeModal();
        } else {
            alert('Server error: gagal menyimpan laporan.');
        }
    })
    .catch(error => {
        console.error(error);
        alert('Terjadi kesalahan jaringan.');
    })
    .finally(() => {
        submitBtn.innerText = oldText;
        submitBtn.disabled = false;
    });
});

tableBody.addEventListener("click", function(e) {
    if(e.target.closest(".editBtn")){
        editRow = e.target.closest("tr");
        let cells = editRow.querySelectorAll("td");

        // Set input data (readonly display)
        const nameInput = document.getElementById("nama");
        const nipInput = document.getElementById("nip");
        const unitInput = document.getElementById("unit");
        
        nameInput.value = cells[1].innerText;
        nipInput.value = cells[2].innerText;
        unitInput.value = cells[3].innerText;
        
        nameInput.readOnly = true;
        nipInput.readOnly = true;
        unitInput.readOnly = true;
        nameInput.classList.add('bg-gray-100');
        nipInput.classList.add('bg-gray-100');
        unitInput.classList.add('bg-gray-100');

        // Set select data
        let preValue = cells[4].innerText.trim();
        let postValue = cells[5].innerText.trim();
        let praktikValue = cells[6].innerText.trim();

        document.getElementById("pretest").value = preValue === "0" ? "" : preValue;
        document.getElementById("posttest").value = postValue === "0" ? "" : postValue;
        document.getElementById("praktik").value = praktikValue === "0" ? "" : praktikValue;

        console.log("Edit button clicked!", editRow);
        
        // Memastikan edit modal terlihat baik itu dengan Tailwind display class maupun override langsung
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = "flex";
    }
});

