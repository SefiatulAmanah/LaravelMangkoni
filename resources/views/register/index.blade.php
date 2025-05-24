<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Register - MANGKONI SINGOSARI</title>
    <style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #e3e6e8, #c3c7cc);
        height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #444;
    }

    .register-container {
        background: #f8f9fa;
        padding: 2.5rem 3rem;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        width: 380px;
    }

    h2 {
        margin-bottom: 1.5rem;
        font-weight: 700;
        text-align: center;
        color: #6c757d;
        letter-spacing: 3px;
        font-size: 1.8rem;
    }

    label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: #6c757d;
        font-size: 0.95rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 0.65rem 1rem;
        margin-bottom: 1.3rem;
        border: 1.8px solid #ced4da;
        border-radius: 10px;
        font-size: 1rem;
        color: #495057;
        background-color: #e9ecef;
        transition: border-color 0.3s ease, background-color 0.3s ease;
    }

    input:focus {
        border-color: #868e96;
        background-color: #fff;
        outline: none;
        box-shadow: 0 0 6px #adb5bd;
    }

    input[type="submit"] {
        width: 100%;
        padding: 0.75rem;
        font-weight: 700;
        font-size: 1rem;
        color: #f8f9fa;
        background-color: #6c757d;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        letter-spacing: 1.2px;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #495057;
    }

    .error-message {
        background-color: #f8d7da;
        color: #842029;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        box-shadow: inset 0 0 5px #f5c2c7;
    }

    .login-link {
        margin-top: 1.2rem;
        font-size: 0.9rem;
        text-align: center;
        color: #6c757d;
    }

    .login-link a {
        color: #495057;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .login-link a:hover {
        color: #343a40;
        text-decoration: underline;
    }

    .show-password {
        margin-top: -0.8rem;
        margin-bottom: 1.2rem;
    }

    .show-password label {
        font-size: 0.85rem;
        font-weight: normal;
        display: inline-block;
        margin-left: 6px;
    }
    </style>
</head>

<body>
    @if(session('success'))
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses!',
        text: '{{ session('
        success ') }}',
        timer: 2500,
        timerProgressBar: true,
        showConfirmButton: false
    });
    </script>
    @endif
    <div class="register-container">
        <h2>MANGKONI SINGOSARI</h2>

        @if ($errors->any())
        <div class="error-message">
            <ul style="margin: 0; padding-left: 1.2rem;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" autocomplete="off">
            @csrf
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}"
                required autofocus>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}"
                required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>

            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password"
                required>

            <div class="show-password">
                <input type="checkbox" id="show-password">
                <label for="show-password">Lihat Password</label>
            </div>

            <input type="submit" value="Daftar">
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>

    <script>
    document.getElementById('show-password').addEventListener('change', function() {
        const pw = document.getElementById('password');
        const pwConfirm = document.getElementById('password_confirmation');
        const type = this.checked ? 'text' : 'password';
        pw.type = type;
        pwConfirm.type = type;
    });
    </script>
</body>

</html>