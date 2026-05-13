<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Page Not Found | Lifeline Gym</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-400: #94a3b8;
            --slate-100: #f1f5f9;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 2rem;
            text-align: center;
            position: relative;
            z-index: 1;
            box-sizing: border-box;
        }

        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.95)), url('clean_luxury_gym_lobby_1778667932824.png');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        .error-container {
            width: 100%;
            max-width: 600px;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 2.5rem;
            color: white;
            animation: slideUp 0.8s ease-out;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 20px 40px rgba(79, 70, 229, 0.3);
        }

        .icon-container {
            font-size: 4rem;
            color: var(--slate-400);
            margin-bottom: 1rem;
        }

        h2 {
            font-weight: 800;
            font-size: 2rem;
            margin-top: 0;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        p {
            color: var(--slate-400);
            font-size: 1.1rem;
            margin-bottom: 3rem;
            line-height: 1.6;
            max-width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 1.2rem 2.5rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
        }

        .btn-home:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(79, 70, 229, 0.5);
        }

        @media (max-width: 600px) {
            .error-code { font-size: 6rem; }
            h2 { font-size: 1.5rem; }
            p { font-size: 1rem; max-width: 100%; }
            .error-container { padding: 3rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="bg-image"></div>
    
    <div class="error-container">
        <div class="icon-container">
            <i class="fas fa-dumbbell"></i>
        </div>
        <div class="error-code">404</div>
        <h2>Heavy Lifting, But No Page Found</h2>
        <p>Looks like you've wandered out of bounds. The page you are looking for has been moved, deleted, or never existed.</p>
        <a href="login" class="btn-home">
            <i class="fas fa-arrow-left"></i> Back to Login
        </a>
    </div>
</body>
</html>
