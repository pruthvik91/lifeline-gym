<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lifeline Fitness - Coming Soon</title>

  <style>
    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family:Arial, sans-serif;
    }

    body{
      height:100vh;
      background:
      linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)),
      url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1600&auto=format&fit=crop');
      background-size:cover;
      background-position:center;
      display:flex;
      justify-content:center;
      align-items:center;
      text-align:center;
      color:#fff;
      overflow:hidden;
    }

    .container{
      width:90%;
      max-width:700px;
      padding:60px 40px;
      background:rgba(255,255,255,0.06);
      border:1px solid rgba(255,255,255,0.1);
      backdrop-filter:blur(8px);
      border-radius:25px;
      animation:fadeIn 1s ease;
    }

    .logo{
      font-size:22px;
      font-weight:bold;
      letter-spacing:4px;
      color:#ff3c00;
      margin-bottom:20px;
      text-transform:uppercase;
    }

    h1{
      font-size:72px;
      line-height:1.1;
      margin-bottom:20px;
      text-transform:uppercase;
      font-weight:800;
    }

    h1 span{
      color:#ff3c00;
    }

    p{
      font-size:18px;
      line-height:1.8;
      color:#dcdcdc;
      margin-bottom:35px;
    }

    .btn{
      display:inline-block;
      padding:15px 40px;
      background:#ff3c00;
      color:#fff;
      text-decoration:none;
      border-radius:50px;
      font-size:16px;
      font-weight:bold;
      transition:0.3s;
    }

    .btn:hover{
      background:#fff;
      color:#000;
      transform:translateY(-3px);
    }

    .footer{
      margin-top:40px;
      color:#aaa;
      font-size:14px;
      letter-spacing:1px;
    }

    @keyframes fadeIn{
      from{
        opacity:0;
        transform:translateY(30px);
      }
      to{
        opacity:1;
        transform:translateY(0);
      }
    }

    @media(max-width:768px){

      h1{
        font-size:48px;
      }

      p{
        font-size:16px;
      }

      .container{
        padding:40px 25px;
      }
    }

  </style>
</head>

<body>

  <div class="container">

    <div class="logo">Lifeline Fitness</div>

    <h1>Coming <span>Soon</span></h1>

    <p>
      A new era of strength, fitness, and transformation is on the way.
      Lifeline Fitness is getting ready to launch something powerful for you.
    </p>

    <a href="#" class="btn">Coming Soon</a>

    <div class="footer">
      © 2026 Lifeline Fitness. All Rights Reserved.
    </div>

  </div>

</body>
</html>