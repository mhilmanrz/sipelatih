const modal = document.getElementById('modal');
const openBtn = document.getElementById('openModal');
const closeBtn = document.getElementById('closeModal');
const cancelBtn = document.getElementById('cancelModal');
const form = document.getElementById("nilaiForm");
const tableBody = document.getElementById("tableBody");

let editRow = null;

openBtn.onclick = () => {
editRow = null;
form.reset();
modal.classList.remove('hidden');
modal.classList.add('flex');
}

function closeModal(){
modal.classList.add('hidden');
modal.classList.remove('flex');
}

closeBtn.onclick = closeModal;
cancelBtn.onclick = closeModal;

// generate nilai
function generateNilai(id){
const select = document.getElementById(id);
if(select.options.length <= 1){
for(let i=0;i<=100;i++){
let opt=document.createElement("option");
opt.value=i;
opt.text=i;
select.appendChild(opt);
}
}
}
generateNilai("pretest");
generateNilai("posttest");
generateNilai("praktik");

form.addEventListener("submit", function(e){
e.preventDefault();

const namaVal = document.getElementById("nama").value;
const nipVal = document.getElementById("nip").value;
const unitVal = document.getElementById("unit").value;
const preVal = parseInt(document.getElementById("pretest").value);
const postVal = parseInt(document.getElementById("posttest").value);
const praktikVal = parseInt(document.getElementById("praktik").value);

if(!namaVal || !nipVal || !unitVal || isNaN(preVal) || isNaN(postVal) || isNaN(praktikVal)){
alert("Semua field harus diisi!");
return;
}

const akumulasi = Math.round((preVal + postVal + praktikVal) / 3);
const batas = 80;
const status = akumulasi >= batas ? "Lulus" : "Tidak Lulus";
const warna = akumulasi >= batas ? "bg-lulus" : "bg-gagal";

if(editRow){
const no = editRow.cells[0].innerText;
editRow.outerHTML = createRow(no);
}else{
let nomor = tableBody.rows.length + 1;
tableBody.insertAdjacentHTML("beforeend", createRow(nomor));
}

closeModal();

function createRow(no){
return `
<tr class="border-b">
<td class="py-3">${no}</td>
<td>${namaVal}</td>
<td>${nipVal}</td>
<td>${unitVal}</td>
<td>${preVal}</td>
<td>${postVal}</td>
<td>${praktikVal}</td>
<td>${akumulasi}</td>
<td>${batas}</td>
<td>
<span class="${warna} text-white px-4 py-1 rounded-pill text-xs font-semibold">
${status}
</span>
</td>
<td>
<button class="bg-editBtn text-white text-xs px-4 py-1 rounded-pill editBtn">
<i class="fa-solid fa-pen mr-1"></i>EDIT
</button>
</td>
</tr>`;
}
});

tableBody.addEventListener("click", function(e){
if(e.target.closest(".editBtn")){
editRow = e.target.closest("tr");
let cells = editRow.querySelectorAll("td");

document.getElementById("nama").value = cells[1].innerText;
document.getElementById("nip").value = cells[2].innerText;
document.getElementById("unit").value = cells[3].innerText;
document.getElementById("pretest").value = cells[4].innerText;
document.getElementById("posttest").value = cells[5].innerText;
document.getElementById("praktik").value = cells[6].innerText;

modal.classList.remove('hidden');
modal.classList.add('flex');
}
});