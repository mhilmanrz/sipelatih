// Optional: klik baris untuk detail
document.querySelectorAll("tbody tr").forEach(row => {
    row.addEventListener("click", () => {
        const status = row.querySelector(".badge").innerText;
        alert("Status Penilaian: " + status);
    });
});
