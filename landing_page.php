<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Welcome | Lifeline Fitness</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-500: #64748b;
            --slate-100: #f1f5f9;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #ffffff;
            color: var(--slate-900);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
        }

        .landing-container {
            width: 100%;
            max-width: 1000px;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        /* Decorative blobs */
        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            z-index: -1;
        }
        .blob-1 { top: -250px; right: -250px; }
        .blob-2 { bottom: -250px; left: -250px; }

        .brand-logo {
            width: 120px;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 900;
            letter-spacing: -2px;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #1e293b 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-text p {
            font-size: 1.2rem;
            color: var(--slate-500);
            font-weight: 500;
            margin-bottom: 3.5rem;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .portal-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            animation: fadeInUp 0.8s ease 0.6s both;
        }

        .portal-card {
            background: white;
            border-radius: 2.5rem;
            padding: 3rem 2rem;
            text-decoration: none;
            color: inherit;
            border: 1px solid var(--slate-100);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .portal-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.15);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: var(--slate-100);
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2rem;
            color: var(--primary);
            transition: all 0.4s ease;
        }

        .portal-card:hover .icon-box {
            background: var(--primary);
            color: white;
            transform: scale(1.1) rotate(5deg);
        }

        .portal-card h3 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .portal-card p {
            font-size: 0.95rem;
            color: var(--slate-500);
            line-height: 1.6;
        }

        .card-footer {
            margin-top: 2.5rem;
            font-weight: 800;
            font-size: 0.9rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.4s ease;
        }

        .portal-card:hover .card-footer {
            opacity: 1;
            transform: translateX(0);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .hero-text h1 { font-size: 2.5rem; }
            .portal-grid { grid-template-columns: 1fr; }
            .portal-card { padding: 2.5rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="landing-container">
        <img src="assets/img/logo.png" alt="Lifeline Fitness" class="brand-logo">
        
        <div class="hero-text">
            <h1>Lifeline Fitness Studio</h1>
            <p>Your journey to ultimate fitness starts here. Choose a portal to continue.</p>
        </div>

        <div class="portal-grid">
            <!-- Member Portal -->
            <a href="login" class="portal-card">
                <div class="icon-box">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3>Member Portal</h3>
                <p>View your active memberships, track your BMI progress, and download official receipts instantly.</p>
                <div class="card-footer">
                    ACCESS MEMBER PORTAL <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <!-- Staff Portal -->
            <a href="lifeline_hq" class="portal-card">
                <div class="icon-box">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h3>Staff Portal</h3>
                <p>Administrative access for managing memberships, tracking payments, and gym operations.</p>
                <div class="card-footer">
                    ACCESS STAFF LOGIN <i class="fas fa-arrow-right"></i>
                </div>
            </a>
        </div>

        <div class="mt-5 pt-4 text-slate-400 small fw-600">
            &copy; <?php echo date('Y') ?> Lifeline Fitness Studio. All rights reserved.
        </div>
    </div>
</body>
</html>
