function openModal(){
    document.getElementById("modal").classList.add("show");
}

function closeModal(){
    document.getElementById("modal").classList.remove("show");
}

function saveFile(){
    alert("Dokumen berhasil disimpan");
    closeModal();
}

/* TAB ACTIVE SWITCH */
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function(){

        document.querySelectorAll('.tab-btn')
            .forEach(b => b.classList.remove('active'));

        this.classList.add('active');
    });
});
