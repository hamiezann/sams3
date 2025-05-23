<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Activity Managemnet System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95)),
                url('/api/placeholder/1200/800');
            background-size: cover;
            background-position: center;
        }

        .container {
            background-color: white;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            justify-content: center;
            /* display: flex; */
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
            letter-spacing: 1px;
        }

        h1 {
            color: #022954;
            font-size: 22px;
            margin-bottom: 10px;
            font-weight: 700;
            text-transform: uppercase;
            font-family: 'Georgia', serif;
        }

        .subheading {
            font-size: 15px;
            color: #555;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #fdfdfd;
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

        .btn-primary {
            background-color: #022954;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
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

        .btn-link:hover {
            text-decoration: underline;
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
        <h1>Student Activity Management System</h1>
        <div class="subheading">Faculty of Entrepreneurship and Business</div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror" name="password" required
                    autocomplete="current-password">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button type="submit" class="btn-primary" style="text-align: center; justify-content:center;display:flex;">
                Login
            </button>

            @if (Route::has('password.request'))
            <div>
                <a class="btn-link" href="{{ route('password.request') }}">
                    Forgot Password?
                </a>
            </div>
            @endif
        </form>

        <div class="copyright">
            © 2025 Faculty of Entrepreneurship and Business, University. All rights reserved.
        </div>
    </div>
</body>

</html>