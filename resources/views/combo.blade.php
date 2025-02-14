<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Dharma Poliplast</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .background {
            background-image: url('https://lokerbumn.com/wp-content/uploads/2023/09/PT-Dharma-Poliplast-02.jpg');
            background-size: cover;
            background-position: center;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .content {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        .buttons {
            margin-top: 20px;
        }

        .buttons button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .buttons button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="content">
            <h1>Selamat Datang di Halaman Kami</h1>
            <p>Ini adalah halaman upervisor PT Dharma Poliplast</p>
            <p>Silahkan Login Sesuai Supervisor nya</p>
            <div class="buttons">
                <a href="{{ route('halaman_utama') }}">
                    <button>Grafik</button>
                </a>
                <a href="{{ route('login') }}">
                    <button>Operator</button>
                </a>
                <a href="{{ route('login') }}">
                    <button>PPIC</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
