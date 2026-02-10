document.addEventListener("DOMContentLoaded", function () {

    function togglePassword() {
        const pass = document.getElementById("password");
        pass.type = pass.type === "password" ? "text" : "password";
    }

    // Supaya bisa dipanggil dari HTML onclick
    window.togglePassword = togglePassword;

    const form = document.getElementById("loginForm");

    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            if (!email || !password) {
                alert("Email dan Password wajib diisi");
                return;
            }

            // Login berhasil → redirect ke dashboard
            alert("Login dummy berhasil ✅");
            window.location.href = "dashboard.html"; // pastikan path dashboard.html benar
        });
    }

});