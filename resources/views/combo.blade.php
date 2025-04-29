<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPIC</title>
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
        .container {
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: white;
            backdrop-filter: blur(10px);
            animation: fadeIn 1.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .btn {
            padding: 12px 24px;
            font-size: 18px;
            color: white;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: linear-gradient(135deg, #0056b3, #003d82);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <h1>Selamat Datang di PT Dharma Poliplast</h1>
        <p>Silakan pilih bagian yang sesuai:</p>
        <div class="buttons">
            <a href="{{ route('login', ['destination' => 'products']) }}" class="btn">Database</a>
            <a href="{{ route('login', ['destination' => 'dashboard']) }}" class="btn">Dashboard</a>
        </div>
    </div>
</body>
</html>
