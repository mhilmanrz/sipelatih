document.addEventListener("DOMContentLoaded", () => {
    const angkaSelect = document.getElementById("angka");

    for (let i = 1; i <= 10; i++) {
        let opt = document.createElement("option");
        opt.value = i;
        opt.textContent = "Angkatan " + i;
        angkaSelect.appendChild(opt);
    }

    document.getElementById("btnSave").addEventListener("click", () => {
        alert("Data berhasil disimpan!");
    });

    document.getElementById("btnCancel").addEventListener("click", () => {
        location.reload();
    });
});
