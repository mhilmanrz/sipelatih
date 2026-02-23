// ================================
// NARASUMBER
// ================================

function simpanNarasumber() {

    const nama = document.getElementById("namaSDM").value;
    const materi = document.getElementById("materiAjar").value;
    const table = document.getElementById("narasumberTable").getElementsByTagName("tbody")[0];

    if (nama === "" || materi === "") {
        alert("Semua field harus dipilih!");
        return;
    }

    const rowCount = table.rows.length;
    const row = table.insertRow();

    row.insertCell(0).innerHTML = rowCount + 1;
    row.insertCell(1).innerHTML = nama;
    row.insertCell(2).innerHTML = materi;
    row.insertCell(3).innerHTML =
        `<button onclick="hapusRow(this)" class="btn-danger">HAPUS</button>`;

    // Reset form
    document.getElementById("namaSDM").value = "";
    document.getElementById("materiAjar").value = "";
}


// ================================
// MODERATOR
// ================================

document.getElementById("btnSimpanModerator").addEventListener("click", function () {

    const nama = document.getElementById("namaModerator").value;
    const materi = document.getElementById("materiModerator").value;
    const table = document.getElementById("moderatorTable").getElementsByTagName("tbody")[0];

    if (nama === "" || materi === "") {
        alert("Semua field moderator harus dipilih!");
        return;
    }

    const rowCount = table.rows.length;
    const row = table.insertRow();

    row.insertCell(0).innerHTML = rowCount + 1;
    row.insertCell(1).innerHTML = "-"; // NIP dummy
    row.insertCell(2).innerHTML = nama;
    row.insertCell(3).innerHTML = materi;
    row.insertCell(4).innerHTML = "-"; // Unit kerja dummy
    row.insertCell(5).innerHTML =
        `<button onclick="hapusRow(this)" class="btn-danger">HAPUS</button>`;

    document.getElementById("namaModerator").value = "";
    document.getElementById("materiModerator").value = "";
});


// ================================
// HAPUS ROW
// ================================

function hapusRow(button) {

    const row = button.parentNode.parentNode;
    const tableBody = row.parentNode;

    tableBody.removeChild(row);

    // Reset nomor ulang
    for (let i = 0; i < tableBody.rows.length; i++) {
        tableBody.rows[i].cells[0].innerHTML = i + 1;
    }
}
