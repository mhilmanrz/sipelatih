<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | siPELATIH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../public/assets/css/login.css">
</head>

<body>
<header class="slogan-bar">
    <div class="slogan-wrapper">
        <div class="slogan-text">
            TERBAIK YANG MEMBERIKAN, MENOLONG, MELESAT, PRIMA •
            TERBAIK YANG MEMBERIKAN, MENOLONG, MELESAT, PRIMA •
            TERBAIK YANG MEMBERIKAN, MENOLONG, MELESAT, PRIMA
        </div>
    </div>
</header>

<main class="page-wrapper">
    <div class="login-card">

        <img src="../public/assets/images/logo-sipelatih.png" class="logo">

        <!-- LOGIN FORM -->
        <form id="loginForm">

            <label>Email</label>
            <input type="email" id="email" placeholder="Masukkan email" required>

            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" placeholder="Masukkan password" required>
                <span class="toggle-password" onclick="togglePassword()"></span>
            </div>

            <button type="submit">Login</button>
        </form>

            </div>
</main>

<footer class="footer">
    © Tim Kerja Pendidikan dan Pelatihan (Nina, Hilman, Sandra, Saskya)
</footer>

<script src="../public/assets/js/login.js"></script>

<!-- <script>
function togglePassword() {
    const password = document.getElementById('password');
    const toggle = document.querySelector('.toggle-password');

    if (password.type === 'password') {
        password.type = 'text';
        toggle.textContent = 'Hide';
    } else {
        password.type = 'password';
        toggle.textContent = 'Show';
    }
}

function showReset(e) {
    e.preventDefault();
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('resetForm').classList.remove('hidden');
}

function showLogin(e) {
    e.preventDefault();
    document.getElementById('resetForm').classList.add('hidden');
    document.getElementById('loginForm').classList.remove('hidden');
}

document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (!email || !password) {
        alert("Email dan Password wajib diisi");
        return;
    }

    alert("Login dummy berhasil ✅");
});
</script> -->

</body>

</html>

