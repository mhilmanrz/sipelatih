const paguData = [
    { noRKAKL: "29107", kategori: "Studi Banding Pegawai Internal", submark: "7958.CCB.001.051.A.525119-16", pagu: 200000000 },
    { noRKAKL: "29108", kategori: "Pelatihan Teknis Bidang Kesehatan", submark: "8724289478274892", pagu: 1000000 },
    { noRKAKL: "29109", kategori: "Kegiatan Pemenuhan Kompetensi Pegawai", submark: "w48932748927", pagu: 15000000 },
    { noRKAKL: "29110", kategori: "Studi Banding Diklat Eksternal", submark: "3789273891237091", pagu: 20000000 },
    { noRKAKL: "29111", kategori: "Praktek Kerja Lapangan Mahasiswa", submark: "72312738912738917", pagu: 25000000 },
    { noRKAKL: "29112", kategori: "Kegiatan Lembaga Sertifikasi Profesi", submark: "34783742897489237489", pagu: 30000000 },
    { noRKAKL: "29114", kategori: "Bantuan Dana Pendidikan Pegawai", submark: "6273461263891268396", pagu: 25000000 },
    { noRKAKL: "29115", kategori: "Orientasi Pegawai", submark: "7874728973489217489127", pagu: 40000000 },
    { noRKAKL: "29116", kategori: "Pelatihan Manajerial Sosial Kultural (Umum)", submark: "7487971289734812748126874", pagu: 40000000 },
    { noRKAKL: "29117", kategori: "Pelatihan Teknis Kepegawaian", submark: "7958.CCB.00", pagu: 45000000 },
];

const paguBody = document.getElementById("paguBody");

function renderTable() {
    paguBody.innerHTML = "";
    let total = 0;

    paguData.forEach((item, index) => {
        total += item.pagu;

        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${item.noRKAKL}</td>
            <td>${item.kategori}</td>
            <td>${item.submark}</td>
            <td class="pagu">${item.pagu.toLocaleString("id-ID")}</td>
            <td class="action">
                <a href="../project/detail/pagu/index.html" class="btn-edit">EDIT</a>
                <button class="btn-delete">HAPUS</button>
            </td>
        `;

        paguBody.appendChild(row);
    });

    document.getElementById("totalPagu").textContent =
        total.toLocaleString("id-ID");
}

renderTable();

paguBody.addEventListener("click", (e) => {
    const editBtn = e.target.closest(".btn-edit");
    const deleteBtn = e.target.closest(".btn-delete");
    const row = e.target.closest("tr");

    if (!row) return;

    const index = Array.from(paguBody.children).indexOf(row);

    if (editBtn) {
    e.preventDefault();
    const id = paguData[index].noRKAKL;
    window.location.href = `../project/detail/pagu/index.html?id=${id}`;
}

    /*if (editBtn) {
        e.preventDefault();
        alert("Edit baris: " + (index + 1));
        console.log(paguData[index]);
    }*/

    if (deleteBtn) {
        if (confirm("Hapus baris ini?")) {
            paguData.splice(index, 1);
            renderTable();
        }
    }
});
