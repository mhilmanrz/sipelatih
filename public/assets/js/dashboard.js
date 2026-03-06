let barChartInstance = null;
let calendarInstance = null;


const content = document.getElementById("content");
const submenuUsulan = document.getElementById("submenuUsulan");
const pageArea = document.getElementById("pageArea");

function toggleSidebar() {
    sidebar.classList.toggle("hidden");
    topbar.classList.toggle("full");
    content.classList.toggle("full");
}

function toggleSubmenu() {
    submenuUsulan.style.display =
        submenuUsulan.style.display === "block" ? "none" : "block";
}

function showPage(page, el) {
    document.querySelectorAll(".menu a")
        .forEach(a => a.classList.remove("active"));
    if (el) el.classList.add("active");

    // ===== MONITORING =====
    if (page === "monitoring") {
        pageArea.innerHTML = `
            <h2 style="color:white;">Monitoring Pengajuan Diklat</h2>
            <div class="card-box">Isi tabel monitoring</div>
        `;
    }

    // ===== PENGAJUAN =====
    if (page === "pengajuan") {
        pageArea.innerHTML = `
            <h2 style="color:white;">Form Pengajuan Diklat</h2>
            <div class="card-box">Isi form pengajuan</div>
        `;
    }

    // ===== JPL =====
    if (page === "jpl") {
        pageArea.innerHTML = `
            <h2 style="color:white;">Monitoring Capaian JPL</h2>
            <div class="card-box">Grafik JPL</div>
        `;
    }

    // ===== PASSWORD PAGE =====
    if (page === "password") {
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

function toggleProfileMenu() {
    const menu = document.getElementById("profileMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

function submitPassword() {
    const last = document.getElementById("lastPassword").value;
    const newer = document.getElementById("newPassword").value;

    if (!last || !newer) {
        alert("Password harus diisi!");
        return;
    }

    alert("Password berhasil diubah (dummy)");
    showPage('dashboard');
}

function logout() {
    window.location.href = "login.html";
}

/* default */
showPage('dashboard');
