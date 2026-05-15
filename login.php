<?php 
session_start();
include('./db_connect.php');

// Auto-login check
if(!isset($_SESSION['member_id']) && isset($_COOKIE['member_mid']) && isset($_COOKIE['member_phn'])){
    $mid_cookie = $_COOKIE['member_mid'];
    $phn_cookie = $_COOKIE['member_phn'];
    $chk = $conn->query("SELECT * FROM members where member_id = '$mid_cookie' and contact = '$phn_cookie' ");
    if($chk->num_rows > 0){
        $res = $chk->fetch_array();
        $_SESSION['member_id'] = $res['id'];
        $_SESSION['member_name'] = $res['firstname'] . ' ' . $res['lastname'];
        $_SESSION['member_mid'] = $res['member_id'];
    }
}

if(isset($_SESSION['member_id']))
    header("location:member_dashboard");

$pre_mid = isset($_GET['mid']) ? $_GET['mid'] : (isset($_COOKIE['member_mid']) ? $_COOKIE['member_mid'] : '');
$pre_phn = isset($_GET['phn']) ? $_GET['phn'] : (isset($_COOKIE['member_phn']) ? $_COOKIE['member_phn'] : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Member Login | Lifeline Gym</title>
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #3730a3;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-600: #475569;
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
            padding: 2rem 1rem;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .login-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.4)), url('clean_luxury_gym_lobby_1778667932824.png');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: white;
            padding: 3.5rem 2.5rem;
            border-radius: 2.5rem;
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.12), 0 30px 60px -30px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 1);
            position: relative;
            animation: cardFadeIn 0.8s ease-out;
        }

        @keyframes cardFadeIn {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .brand-logo-container {
            width: 70px;
            height: 70px;
            background: var(--slate-100);
            border-radius: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2.5rem;
            transition: all 0.3s ease;
        }

        .brand-logo-container img {
            width: 45px;
        }

        .login-header { text-align: center; margin-bottom: 2.5rem; }
        .login-header h2 { font-weight: 800; color: var(--slate-900); font-size: 1.8rem; letter-spacing: -0.5px; margin-bottom: 0.5rem; }
        .login-header p { color: var(--slate-400); font-size: 0.9rem; font-weight: 600; }

        .input-group-premium {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group-premium label {
            font-size: 0.65rem;
            font-weight: 800;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.6rem;
            display: block;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-wrapper input {
            width: 100%;
            padding: 1rem 1rem 1rem 3.25rem;
            background: var(--slate-50);
            border: 1.5px solid var(--slate-100);
            border-radius: 1rem;
            font-weight: 700;
            color: var(--slate-800);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .input-wrapper input::placeholder { color: var(--slate-400); font-weight: 500; }

        .input-wrapper input:focus {
            outline: none;
            background: white;
            border-color: var(--primary);
            box-shadow: 0 10px 20px -10px rgba(79, 70, 229, 0.2);
        }

        .input-wrapper input:focus + i {
            color: var(--primary);
        }

        .btn-access {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 1.1rem;
            font-weight: 800;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
        }

        .btn-access:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.5);
        }

        .btn-access:active { transform: translateY(-1px); }

        .alert {
            background: #fff1f2;
            border: 1px solid #ffe4e6;
            color: #e11d48;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 1rem;
            margin-top: 1.5rem;
        }

        .footer-note {
            margin-top: 2.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--slate-400);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-bg"></div>
    
    <div class="login-card">
        <div class="brand-logo-container">
            <img src="assets/img/logo.png" alt="Lifeline Fitness">
        </div>

        <div class="login-header">
            <h2>Member Portal</h2>
            <p>Access your health & gym profile</p>
        </div>

        <form id="member-login-form">
            <div class="input-group-premium">
                <label>Member ID</label>
                <div class="input-wrapper">
                    <input type="text" name="member_id" value="<?php echo $pre_mid ?>" placeholder="Enter your ID" required>
                    <i class="fas fa-id-card"></i>
                </div>
            </div>

            <div class="input-group-premium">
                <label>Mobile Number</label>
                <div class="input-wrapper">
                    <input type="tel" name="contact" value="<?php echo $pre_phn ?>" placeholder="Enter mobile number" required>
                    <i class="fas fa-phone"></i>
                </div>
            </div>

            <div class="d-flex align-items-center mb-4 px-1">
                <div class="form-check custom-checkbox">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label small fw-600 text-slate-400 ms-2" for="remember" style="cursor:pointer">
                        Stay signed in
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-access">
                <span>Sign In</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>
        
        <div id="msg"></div>

        <div class="footer-note">
            Lifeline Fitness Studio &copy; <?php echo date('Y') ?>
            <a href="index.php?admin=1" style="opacity: 0.05; cursor: default; text-decoration: none; color: inherit; position: absolute; bottom: 10px; right: 10px;">.</a>
        </div>
    </div>

    <!-- PWA Install Prompt -->
    <div id="pwa-install-banner" style="display: none; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 400px; background: white; padding: 1.5rem; border-radius: 1.5rem; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.2); z-index: 9999; text-align: center; border: 1px solid var(--slate-100);">
        <button id="pwa-close" style="position: absolute; top: 10px; right: 15px; background: transparent; border: none; font-size: 1.2rem; color: var(--slate-400); cursor: pointer;"><i class="fas fa-times"></i></button>
        <img src="assets/img/logo.png" alt="Logo" style="width: 50px; height: 50px; border-radius: 12px; margin-bottom: 10px;">
        <h4 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin-bottom: 5px;">Install Lifeline Gym App</h4>
        <p style="font-size: 0.85rem; color: var(--slate-500); margin-bottom: 15px;">Add to your home screen for quick and easy access to your membership portal.</p>
        <button id="pwa-install-btn" style="width: 100%; padding: 0.9rem; background: var(--primary); color: white; border: none; border-radius: 1rem; font-weight: 700; font-size: 0.95rem; cursor: pointer; box-shadow: 0 10px 20px -10px rgba(79, 70, 229, 0.4);">Install App</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // PWA Installation Logic
        let deferredPrompt;
        const installBanner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');
        const closeBtn = document.getElementById('pwa-close');

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('./sw.js')
                .then(registration => {
                    console.log('ServiceWorker registration successful');
                })
                .catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            setTimeout(() => {
                installBanner.style.display = 'block';
            }, 1000); 
        });

        installBtn.addEventListener('click', async () => {
            installBanner.style.display = 'none';
            if(deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response to the install prompt: ${outcome}`);
                deferredPrompt = null;
            }
        });

        closeBtn.addEventListener('click', () => {
            installBanner.style.display = 'none';
        });

        window.addEventListener('appinstalled', (evt) => {
            installBanner.style.display = 'none';
            console.log('App installed');
        });

        $('#member-login-form').submit(function(e){
            e.preventDefault()
            $('#msg').html('')
            $('.btn-access').attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Checking...')
            
            $.ajax({
                url: 'ajax.php?action=member_login',
                method: 'POST',
                data: $(this).serialize(),
                error: err => {
                    console.log(err)
                    $('.btn-access').removeAttr('disabled').html('<span>Sign In</span> <i class="fas fa-arrow-right"></i>')
                },
                success: function(resp){
                    if(resp == 1){
                        location.href = 'member_dashboard';
                    }else{
                        $('#msg').html('<div class="alert"><i class="fas fa-exclamation-circle me-2"></i>Invalid ID or Phone number.</div>')
                        $('.btn-access').removeAttr('disabled').html('<span>Sign In</span> <i class="fas fa-arrow-right"></i>')
                    }
                }
            })
        })
    </script>
</body>
</html>
