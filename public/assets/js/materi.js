const modal = document.getElementById("modal");
const openModal = document.getElementById("openModal");
const closeModal = document.getElementById("closeModal");
const cancelBtn = document.getElementById("cancelBtn");
const saveBtn = document.getElementById("saveBtn");
const tableBody = document.getElementById("tableBody");

openModal.onclick = () => modal.classList.add("show");
closeModal.onclick = () => modal.classList.remove("show");
cancelBtn.onclick = () => modal.classList.remove("show");

saveBtn.onclick = () => {
    const materi = document.getElementById("materiInput").value;
    const jpl = document.getElementById("jplInput").value;

    if(!materi || !jpl){
        alert("Isi semua field!");
        return;
    }

    const rowCount = tableBody.children.length + 1;

    const row = document.createElement("tr");
    row.innerHTML = `
        <td>${rowCount}</td>
        <td>${materi}</td>
        <td>${jpl}</td>
        <td><button class="btn-delete">Hapus</button></td>
    `;

    tableBody.appendChild(row);

    document.getElementById("materiInput").value="";
    document.getElementById("jplInput").value="";
    modal.classList.remove("show");
};

tableBody.addEventListener("click",function(e){
    if(e.target.classList.contains("btn-delete")){
        e.target.closest("tr").remove();

        [...tableBody.children].forEach((row,i)=>{
            row.children[0].textContent = i+1;
        });
    }
});
