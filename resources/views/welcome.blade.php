<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(45deg, rgba(92, 129, 244, 0.3), rgba(131, 136, 247, 0.3));
            overflow: hidden;
            perspective: 1000px;
        }

        .background-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(92, 129, 244, 0.2);
            background-image: radial-gradient(circle, rgba(76, 110, 245, 0.6) 0%, rgba(46, 75, 221, 0.6) 100%);
            z-index: -1;
            filter: blur(10px);
            animation: backgroundMove 15s ease-in-out infinite alternate;
        }

        .parallax-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://source.unsplash.com/random/1600x900');
            background-size: cover;
            background-position: center;
            animation: parallax 18s infinite linear;
            filter: blur(6px);
            z-index: -2;
        }

        @keyframes parallax {
            0% { transform: translateX(0); }
            100% { transform: translateX(30px); }
        }

        @keyframes backgroundMove {
            0% { transform: scale(1.1); }
            50% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        .login-container {
            position: relative;
            background: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0px 30px 45px rgba(0, 0, 0, 0.25);
            z-index: 1;
            transform: perspective(800px) rotateY(0deg);
            transition: all 0.5s ease;
            width: 350px;
            box-sizing: border-box;
        }

        .login-container:hover {
            transform: perspective(800px) rotateY(5deg) scale(1.05);
            box-shadow: 0px 35px 55px rgba(0, 0, 0, 0.4);
        }

        .login-header {
            text-align: center;
            font-size: 2.2em;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        .input-field {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            background: rgba(248, 248, 248, 0.9);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.4);
            transform: scale(1.05);
        }

        .login-button {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .login-button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #4CAF50;
        }

        /* Adding 3D tilt effect */
        .login-container:hover .input-field {
            transform: rotate3d(1, 1, 0, 5deg);
        }

        .login-container:hover .login-button {
            transform: translateY(-5px);
        }

        /* Multi-Layered Parallax Effects */
        .parallax-layer:nth-child(1) {
            transform: translateZ(-1px) scale(1.5);
            animation: parallax 15s infinite linear;
        }

        .parallax-layer:nth-child(2) {
            transform: translateZ(-2px) scale(1.4);
            animation: parallax 18s infinite linear;
        }

        /* Mouse Interaction Layer */
        .login-container {
            transform-style: preserve-3d;
            animation: rotateAnimation 3s infinite alternate;
        }

        @keyframes rotateAnimation {
            0% {
                transform: rotateY(5deg);
            }
            100% {
                transform: rotateY(-5deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                width: 280px;
                padding: 25px;
            }

            .login-header {
                font-size: 1.8em;
            }

            .input-field,
            .login-button {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                width: 260px;
                padding: 20px;
            }

            .login-header {
                font-size: 1.5em;
            }

            .input-field,
            .login-button {
                font-size: 12px;
            }
        }
        /* Add Logo Styles Here */
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo-icon {
            font-size: 2.5em;
            font-weight: 700;
            background: linear-gradient(45deg, #4CAF50, #2d572f);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-right: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .logo-text {
            font-size: 1.8em;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .logo-subtitle {
            display: block;
            font-size: 0.45em;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        /* Remove duplicate style tag and adjust header */
        .login-header {
            margin-top: -15px; /* Reduce space between logo and form */
            margin-bottom: 15px;
        }
    </style>
</head>
<div>
<div class="background-layer"></div>
<div class="parallax-layer"></div>
<div class="login-container">
        <a href="#" class="logo">
            <div class="logo-icon">sTMS</div>
            <div class="logo-text">
                Student Task
                <span class="logo-subtitle">Management System</span>
            </div>
        </a>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" class="input-field" placeholder="Email" required>
        <input type="password" name="password" class="input-field" placeholder="Password" required>
        <button type="submit" class="login-button">Login</button>
    </form>
    <a href="#" class="forgot-password">Forgot your password?</a>
</div>
</div>
</body>
</html>

