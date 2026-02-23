document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll("[data-include]").forEach(el => {
        fetch(el.getAttribute("data-include"))
            .then(res => res.text())
            .then(html => {
                el.innerHTML = html;
                initPage();
            });
    });

    initPage();
});

function initPage() {
    const btnAdd = document.getElementById("btnAdd");
    const pengajuanArea = document.getElementById("pengajuanArea");
    const tableArea = document.getElementById("tableArea");

    if (!btnAdd || !pengajuanArea || !tableArea) return;

    btnAdd.onclick = () => {
        tableArea.style.display = "none";

        fetch("tambahdata.html")
            .then(res => res.text())
            .then(html => {
                pengajuanArea.innerHTML = html;
                loadTambahDataJS();
            });
    };
}

function loadTambahDataJS() {
    if (document.getElementById("tambahdata-js")) return;

    const script = document.createElement("script");
    script.src = "tambahdata.js";
    script.id = "tambahdata-js";
    document.body.appendChild(script);
}
