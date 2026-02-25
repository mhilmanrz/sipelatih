console.log("Evaluasi I Loaded");

document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("btnDownload");

    btn.addEventListener("click", function () {
        window.open("laporan/evaluasi-penyelenggaraan.pdf", "_blank");
    });
});
