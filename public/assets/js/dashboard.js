let barChartInstance = null;
let calendarInstance = null;


const content = document.getElementById("content");
const submenuUsulan = document.getElementById("submenuUsulan");
const pageArea = document.getElementById("pageArea");

function toggleSidebar(){
    sidebar.classList.toggle("hidden");
    topbar.classList.toggle("full");
    content.classList.toggle("full");
}

function toggleSubmenu(){
    submenuUsulan.style.display =
        submenuUsulan.style.display === "block" ? "none" : "block";
}

function showPage(page, el){
    document.querySelectorAll(".menu a")
        .forEach(a => a.classList.remove("active"));
    if(el) el.classList.add("active");

    // ===== DASHBOARD =====
    if(page === "dashboard"){
        pageArea.innerHTML = `
            <h2 style="color:white;">Dashboard</h2>

            <div class="cards">
                <div class="card"><i class="fa fa-file-alt"></i>Draft<br><b>7</b></div>
                <div class="card"><i class="fa fa-paper-plane"></i>Tahap Pengajuan<br><b>10</b></div>
                <div class="card"><i class="fa fa-tasks"></i>Proses Penilaian<br><b>10</b></div>
                <div class="card"><i class="fa fa-exclamation-triangle"></i>Butuh Perbaikan<br><b>1</b></div>
                <div class="card"><i class="fa fa-check-circle"></i>Telah Perbaikan<br><b>0</b></div>
                <div class="card"><i class="fa fa-thumbs-up"></i>Disetujui<br><b>4</b></div>
                <div class="card"><i class="fa fa-ban"></i>Ditolak<br><b>7</b></div>
                
            </div>

            <div class="grid">
                <div class="card-box">
                    <canvas id="barChart"></canvas>
                </div>
                <div class="card-box">
                    <div id="calendar"></div>
                </div>
            </div>
        `;

        loadChart();
        loadCalendar();
    }

    // ===== MONITORING =====
    if(page === "monitoring"){
        pageArea.innerHTML = `
            <h2 style="color:white;">Monitoring Pengajuan Diklat</h2>
            <div class="card-box">Isi tabel monitoring</div>
        `;
    }

    // ===== PENGAJUAN =====
    if(page === "pengajuan"){
        pageArea.innerHTML = `
            <h2 style="color:white;">Form Pengajuan Diklat</h2>
            <div class="card-box">Isi form pengajuan</div>
        `;
    }

    // ===== JPL =====
    if(page === "jpl"){
        pageArea.innerHTML = `
            <h2 style="color:white;">Monitoring Capaian JPL</h2>
            <div class="card-box">Grafik JPL</div>
        `;
    }

    // ===== PASSWORD PAGE =====
    if(page === "password"){
        pageArea.innerHTML = `
            <h2 style="color:white;">Ubah Password</h2>

            <div class="password-page">
                <img src="../images/logo-sipelatih.png" width="180">

                <label>Last Password</label>
                <input type="password" id="lastPassword">

                <label>New Password</label>
                <input type="password" id="newPassword">

                <button onclick="submitPassword()">Kirim</button>
            </div>
        `;
    }
}

function loadChart(){
    const barChart = document.getElementById("barChart");
    if(!barChart) return;

    if(barChartInstance) barChartInstance.destroy();

    barChartInstance = new Chart(barChart, {
        type: 'bar',
        data: {
            labels: ['Anestesi','Bedah','Anak','Mata','Jantung','Paru'],
            datasets: [{
                label: 'Jumlah Diklat',
                data: [80,65,50,45,30,20],
                backgroundColor: '#1fd1d1'
            }]
        },
        options: {
            responsive:true,
            maintainAspectRatio:false
        }
    });
}

function loadCalendar(){
    const calendarEl = document.getElementById("calendar");
    if(!calendarEl) return;

    if(calendarInstance) calendarInstance.destroy();

    calendarInstance = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: "auto",
        expandRows: true,
        events: [
            { title: 'Diklat A', start: '2026-01-10' },
            { title: 'Diklat B', start: '2026-01-15' },
            { title: 'Diklat C', start: '2026-01-22' }
        ]
    });

    setTimeout(() => calendarInstance.render(), 50);
}

function toggleProfileMenu(){
    const menu = document.getElementById("profileMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

function submitPassword(){
    const last = document.getElementById("lastPassword").value;
    const newer = document.getElementById("newPassword").value;

    if(!last || !newer){
        alert("Password harus diisi!");
        return;
    }

    alert("Password berhasil diubah (dummy)");
    showPage('dashboard');
}

function logout(){
    window.location.href = "login.html";
}

/* default */
showPage('dashboard');
