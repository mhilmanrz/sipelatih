document.addEventListener("DOMContentLoaded", () => {
  const searchNip = document.getElementById("searchNip");
  const searchNama = document.getElementById("searchNama");
  const tableBody = document.getElementById("tableBody");

  function filterTable() {
    const nipValue = searchNip.value.toLowerCase();
    const namaValue = searchNama.value.toLowerCase();

    tableBody.querySelectorAll("tr").forEach(row => {
      const nip = row.cells[1].textContent.toLowerCase();
      const nama = row.cells[2].textContent.toLowerCase();

      row.style.display =
        nip.includes(nipValue) && nama.includes(namaValue)
          ? ""
          : "none";
    });
  }

  searchNip.addEventListener("input", filterTable);
  searchNama.addEventListener("input", filterTable);

  window.resetSearch = () => {
    searchNip.value = "";
    searchNama.value = "";
    filterTable();
  };
});
