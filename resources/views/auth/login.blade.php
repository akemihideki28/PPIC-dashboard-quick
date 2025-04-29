<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login PPIC</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='50' height='100' fill='%23005BAA'/><rect x='50' width='50' height='100' fill='%23E31937'/><text x='50' y='60' font-family='Arial' font-size='40' text-anchor='middle' fill='white'>D+M</text></svg>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body, html {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('https://lokerbumn.com/wp-content/uploads/2023/09/PT-Dharma-Poliplast-02.jpg') no-repeat center center/cover;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }
        .login-container {
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: white;
            backdrop-filter: blur(10px);
            animation: fadeIn 1.5s ease-in-out;
            width: 350px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .remember-me input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        .btn {
            display: inline-block;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            color: white;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        .btn:hover {
            background: linear-gradient(135deg, #0056b3, #003d82);
            transform: scale(1.05);
        }
        .rollback-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background: linear-gradient(135deg, #dc3545, #a71d2a);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        .rollback-btn:hover {
            background: linear-gradient(135deg, #a71d2a, #7a141d);
            transform: scale(1.05);
        }
        .links {
            margin-top: 15px;
            font-size: 14px;
        }
        .links a {
            color: white;
            text-decoration: none;
            transition: 0.3s;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <button type="button" class="rollback-btn" onclick="window.location.href='{{ route('combo') }}'">← Back</button>
    <div class="overlay"></div>
    <div class="login-container">
        <h2>Login</h2>
        <form action="{{ route('login.action') }}" method="POST">
            @csrf
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="links">
            <a href="{{ route('register') }}">Create an Account</a>
        </div>
    </div>
</body>
</html>
