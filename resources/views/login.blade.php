<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login | siPELATIH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>

<body>
    <header class="slogan-bar">
        <div class="slogan-wrapper">
            <div class="slogan-text">
                MEMBERIKAN YANG TERBAIK, MENOLONG, MELESAT, PRIMA •&nbsp;&nbsp;&nbsp;
                MEMBERIKAN YANG TERBAIK, MENOLONG, MELESAT, PRIMA •&nbsp;&nbsp;&nbsp;
                MEMBERIKAN YANG TERBAIK, MENOLONG, MELESAT, PRIMA •
            </div>
        </div>
    </header>

    <main class="page-wrapper">
        <div class="login-card">

            <img src="{{ asset('assets/images/logo-sipelatih.png') }}" class="logo">

            <!-- LOGIN FORM -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label>Email</label>
                <input type="email" name="email" id="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                @error('email')
                <div style="color: red; font-size: 0.8rem; margin-top: -5px; margin-bottom: 10px;">{{ $message }}</div>
                @enderror

                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                    <span class="toggle-password" onclick="togglePassword()"></span>
                </div>

                <button type="submit">Login</button>
            </form>

        </div>
    </main>

    <footer class="footer">
        © Tim Kerja Pendidikan dan Pelatihan (Nina, Hilman, Sandra, Saskya)
    </footer>

    <script src="{{ asset('assets/js/login.js') }}"></script>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const toggle = document.querySelector('.toggle-password');

            if (password.type === 'password') {
                password.type = 'text';
                toggle.classList.add('hide'); // Requires slight css adjustment usually, just logic toggle is fine
            } else {
                password.type = 'password';
                toggle.classList.remove('hide');
            }
        }
    </script>

</body>

</html>