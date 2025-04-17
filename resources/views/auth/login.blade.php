<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Aktiviti Pelajar</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                url('/api/placeholder/1200/800');
            background-size: cover;
            background-position: center;
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 40px;
            text-align: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            background-color: #022954;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }

        h1 {
            color: #022954;
            font-size: 20px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
            line-height: 1.4;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #022954;
            outline: none;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .form-check {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #022954;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: 500;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #033e7c;
        }

        .btn-link {
            color: #022954;
            text-decoration: none;
            font-size: 14px;
            margin-top: 15px;
            display: inline-block;
        }

        .copyright {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">FKP</div>
        <h1>SISTEM AKTIVITI PELAJAR<br>FAKULTI KEUSAHAWANAN DAN PERNIAGAAN</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Alamat E-mel</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Laluan</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <!-- <div class="form-group">
                    <div class="d-flex align-items-center">
                        <input class="form-check-input me-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                </div> -->


            <div class="text-align-center">

                <button type="submit" class="btn-primary">
                    Log Masuk
                </button>
            </div>
            @if (Route::has('password.request'))
            <div>
                <a class="btn-link" href="{{ route('password.request') }}">
                    Lupa Kata Laluan?
                </a>
            </div>
            @endif
        </form>

        <div class="copyright text-align-center">
            © 2025 Fakulti Keusahawanan dan Perniagaan. Hak Cipta Terpelihara.
        </div>
    </div>
</body>

</html>