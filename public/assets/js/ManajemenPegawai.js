const searchNip = document.getElementById("searchNip");
const searchNama = document.getElementById("searchNama");
const tableBody = document.getElementById("tableBody");

function filterTable() {
    const nipValue = searchNip.value.toLowerCase();
    const namaValue = searchNama.value.toLowerCase();
    const rows = tableBody.getElementsByTagName("tr");

    for (let row of rows) {
        const nip = row.cells[1].innerText.toLowerCase();
        const nama = row.cells[2].innerText.toLowerCase();

        if (nip.includes(nipValue) && nama.includes(namaValue)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    }
}

searchNip.addEventListener("keyup", filterTable);
searchNama.addEventListener("keyup", filterTable);

function resetSearch() {
    searchNip.value = "";
    searchNama.value = "";
    filterTable();
}