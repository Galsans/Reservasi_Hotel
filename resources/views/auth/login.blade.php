<!DOCTYPE html>
<!-- Coding By CodingNepal - codingnepalweb.com -->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login | GALSANS </title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #4070f4;
        }

        .wrapper {
            position: relative;
            max-width: 430px;
            width: 100%;
            background: #fff;
            padding: 34px;
            border-radius: 6px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .wrapper h2 {
            position: relative;
            font-size: 22px;
            font-weight: 600;
            color: #333;
        }

        .wrapper h2::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 28px;
            border-radius: 12px;
            background: #4070f4;
        }

        .wrapper form {
            margin-top: 30px;
        }

        .wrapper form .input-box {
            height: 52px;
            margin: 18px 0;
        }

        form .input-box input {
            height: 100%;
            width: 100%;
            outline: none;
            padding: 0 15px;
            font-size: 17px;
            font-weight: 400;
            color: #333;
            border: 1.5px solid #C7BEBE;
            border-bottom-width: 2.5px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .input-box input:focus,
        .input-box input:valid {
            border-color: #4070f4;
        }

        form .policy {
            display: flex;
            align-items: center;
        }

        form h3 {
            color: #707070;
            font-size: 14px;
            font-weight: 500;
            margin-left: 10px;
        }

        .input-box.button input {
            color: #fff;
            letter-spacing: 1px;
            border: none;
            background: #4070f4;
            cursor: pointer;
        }

        .input-box.button input:hover {
            background: #0e4bf1;
        }

        form .text h3 {
            color: #333;
            width: 100%;
            text-align: center;
            margin-top: 10px
        }

        form .text h3 a {
            color: #4070f4;
            text-decoration: none;
        }

        form .text h3 a:hover {
            text-decoration: underline;
        }

        .social-login {
            text-align: center;
            margin-top: 15px;
        }

        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px;
            background: #fff;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            color: #333;
            text-decoration: none;
            transition: 0.3s ease-in-out;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .google-btn img {
            margin-right: 10px;
        }

        .google-btn:hover {
            background: #f5f5f5;
            border-color: #bbb;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Login</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="input-box">
                <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"
                    required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Create password" required>
            </div>
            <div class="input-box button">
                <input type="submit" value="Login Now">
            </div>
            <div class="social-login">
                <p style="text-align: center; margin: 15px 0; font-size: 14px; color: #555;">Or login with</p>
                {{-- <a href="" class="google-btn"> --}}
                <a href="{{ route('oauth.google') }}" class="google-btn">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="24" height="24"
                        alt="Google Logo">
                    <span>Continue with Google</span>
                </a>
            </div>
            <div class="text">
                <h3>Don't have an account?
                    <a href="{{ route('register') }}">Sigup now</a>
                </h3>
            </div>
        </form>
    </div>
</body>

</html>
