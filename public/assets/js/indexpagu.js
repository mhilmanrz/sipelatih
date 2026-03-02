const modal = document.getElementById("modalPagu");
const closeBtn = document.getElementById("closeModal");

/* Tutup modal */
closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
});
/* Buka modal dari tombol */
const openBtn = document.getElementById("openPagu");
if (openBtn) {
    openBtn.addEventListener("click", () => {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
    });
}
/* Klik luar modal = tidak menutup (sesuai tampilan) */
document.body.style.pointerEvents = "auto";
