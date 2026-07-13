<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oppasabuy | Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Inter',sans-serif;
            background:#fff;
            overflow-x:hidden;
        }

        :root{
            --blue:#004fb6;
            --dark-blue:#1a3668;
        }

        /* ================= HEADER ================= */

        .top-header{
            height:65px;
            padding:0 40px;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#fff;
            border-bottom:1px solid #e8e8e8;
        }

        .header-content-inner {
            width: 100%;
            max-width: 980px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .header-left img{
            height:32px;
        }

        .divider{
            width:1px;
            height:20px;
            background:#d8d8d8;
        }

        .header-left span{
            font-size:15px;
            color:#444;
            font-weight:500;
        }

        .header-right{
            text-align:right;
            line-height:1.3;
        }

        .header-right .top{
            font-size:12px;
            color:#444;
        }

        .header-right .bottom{
            font-size:12px;
            color:#333;
            font-weight:700;
        }

        /* ================= HERO ================= */

        .hero-section{
            position:relative;
            height:480px;
            background:#004fb6;
            overflow:visible;
            display:flex;
            justify-content:center;
        }

        .hero-section::before{
            content:'';
            position:absolute;
            top:-100px;
            left:-100px;
            width:600px;
            height:300px;
            background:rgba(255,255,255,0.06);
            border-radius:50%;
        }

        .hero-section::after{
            content:'';
            position:absolute;
            bottom:-100px;
            right:-100px;
            width:600px;
            height:280px;
            background:rgba(255,255,255,0.05);
            border-radius:50%;
        }

        .content-wrapper{
            width:100%;
            max-width:980px;
            position:relative;
            height:100%;
        }

        /* ================= MASCOT ================= */

        .mascot-container{
            position:absolute;
            left:0;
            bottom:-50px;
            z-index:20;
        }

        .mascot-container img{
            width:390px;
            display:block;
        }

        /* ================= LOGIN CARD ================= */

        .login-card-container{
            position:absolute;
            right:0;
            top:50%;
            transform:translateY(-40%);
            z-index:30;
        }

        .login-card{
            width:340px;
            background:#fff;
            border-radius:20px;
            padding:32px 28px;
            box-shadow:0 15px 35px rgba(0,0,0,0.18);
        }

        .card-logo{
            height:28px;
            display:block;
            margin:0 auto 15px;
        }

        .welcome-title{
            text-align:center;
            font-size:20px;
            font-weight:800;
            color:var(--dark-blue);
            margin-bottom:5px;
        }

        .welcome-sub{
            text-align:center;
            font-size:13px;
            color:#666;
            margin-bottom:25px;
        }

        .form-group{
            margin-bottom:15px;
        }

        .form-label{
            font-size:13px;
            font-weight:700;
            color:var(--dark-blue);
            margin-bottom:6px;
        }

        .form-control{
            height:44px;
            border:2px solid #2c5da9;
            border-radius:10px;
            font-size:13px;
            padding:10px 14px;
            box-shadow:none !important;
        }

        .form-control:focus{
            border-color:#2c5da9;
            background:#f9fbff;
        }

        .forgot-wrap{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:5px;
        }

        .forgot-text{
            font-size:12px;
            color:#5bc0de;
            text-decoration:none;
            font-weight:600;
        }

        .remember-row{
            display:flex;
            align-items:center;
            gap:8px;
            margin-top:5px;
            margin-bottom:20px;
        }

        .remember-row label{
            font-size:12px;
            color:#666;
            margin:0;
            cursor:pointer;
        }

        .btn-login{
            width:100%;
            height:46px;
            border:2px solid #2c5da9;
            background:#fff;
            color:#2c5da9;
            font-weight:800;
            font-size:14px;
            border-radius:10px;
            transition:0.2s ease;
        }

        .btn-login:hover{
            background:#2c5da9;
            color:#fff;
        }

        .create-account{
            margin-top:18px;
            text-align:center;
            font-size:12px;
            color:#666;
        }

        .create-account a{
            color:#5bc0de;
            font-weight:700;
            text-decoration:none;
        }

        /* ================= SUCCESS LOGIN STYLE ================= */
        .success-note {
            background-color: #f0f7ff;
            border-left: 4px solid #004fb6;
            padding: 12px;
            border-radius: 8px;
            font-size: 12px;
            color: #1a3668;
            margin-bottom: 20px;
            line-height: 1.4;
            text-align: left;
        }

        .success-note strong {
            display: block;
            margin-bottom: 4px;
            color: #004fb6;
        }

        /* ================= FOOTER ================= */

        .footer-section{
            padding:100px 40px 30px;
            border-top:1px solid #eee;
            background:#fff;
            display:flex;
            justify-content:center;
        }

        .footer-inner{
            width:100%;
            max-width:980px;
        }

        .footer-grid{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:35px;
        }

        .footer-col h6{
            font-size:16px;
            font-weight:700;
            margin-bottom:18px;
            color:#333;
        }

        .footer-links{
            list-style:none;
            padding:0;
            margin:0;
        }

        .footer-links li{
            margin-bottom:8px;
        }

        .footer-links a{
            text-decoration:none;
            color:#666;
            font-size:13px;
        }

        .footer-links a:hover{
            color:#004fb6;
        }

        .social-row{
            display:flex;
            align-items:center;
            gap:15px;
            margin-top:10px;
        }

        .social-row i{
            font-size:28px;
            cursor:pointer;
            transition:0.2s;
        }

        .social-row i:hover{
            transform:translateY(-3px);
        }

        .fa-facebook{ color:#1877F2; }
        .fa-instagram{ color:#E4405F; }
        .fa-tiktok{ color:#000; }
        .fa-youtube{ color:#FF0000; }

        .copy-text{
            text-align:center;
            margin-top:50px;
            padding-top:20px;
            border-top:1px solid #f5f5f5;
            font-size:12px;
            color:#aaa;
        }

        /* ================= RESPONSIVE ================= */

        @media(max-width:992px){

            .hero-section{
                height:700px;
            }

            .content-wrapper{
                display:flex;
                flex-direction:column;
                align-items:center;
            }

            .mascot-container{
                position:relative;
                left:auto;
                bottom:auto;
                margin-top:30px;
            }

            .mascot-container img{
                width:320px;
            }

            .login-card-container{
                position:relative;
                right:auto;
                top:auto;
                transform:none;
                margin-top:10px;
            }

            .footer-grid{
                grid-template-columns:repeat(2,1fr);
            }

            .top-header{
                height:auto;
                padding:15px 20px;
            }

            .header-content-inner{
                flex-direction:column;
                gap:15px;
            }

            .header-right{
                text-align:center;
            }
        }

        @media(max-width:576px){

            .login-card{
                width:100%;
                max-width:330px;
            }

            .footer-grid{
                grid-template-columns:1fr;
                text-align:center;
            }

            .social-row{
                justify-content:center;
            }

            .mascot-container img{
                width:240px;
            }

            .header-left{
                flex-direction:column;
            }

            .divider{
                display:none;
            }
        }

    
        .login-error {
            background:#fff1f2;
            border:1px solid #fecdd3;
            border-left:4px solid #dc2626;
            color:#b91c1c;
            padding:12px 14px;
            border-radius:9px;
            margin-bottom:16px;
            font-size:12px;
            font-weight:600;
            line-height:1.5;
        }

        .login-error i{
            margin-right:6px;
        }

        .form-control.is-invalid{
            border-color:#dc2626 !important;
            background:#fffafa;
        }


    </style>
</head>
<body>

    <header class="top-header">
        <div class="header-content-inner">
            <div class="header-left">
                <img src="{{ asset('oppa.png') }}" alt="Logo">
                <div class="divider"></div>
                <span>Log in to Shop</span>
            </div>
            <div class="header-right">
                <div class="top">
                    Not an Oppasabuy member?
                </div>
                <div class="bottom">
                    Enjoy both in-store and online shopping with a single sign-up!
                </div>
            </div>
        </div>
    </header>

    <section class="hero-section">
        <div class="content-wrapper">
            <div class="mascot-container">
                <img src="{{ asset('dog.png') }}" alt="Mascot Illustration">
            </div>

            <div class="login-card-container">
                <div class="login-card">
                    <img src="{{ asset('oppa.png') }}" class="card-logo" alt="Logo">

                    @if(session('success'))
                        <div class="welcome-title">
                            Signup Successful!
                        </div>
                        <div class="welcome-sub">
                            Welcome to the family!
                        </div>

                        <div class="success-note">
                            <strong>Note:</strong> After successful Sign Up, users will automatically be redirected to this login section to start exploring Oppasabuy features.
                        </div>
                    @else
                        <div class="welcome-title">
                            Annyeong, Welcome Back!
                        </div>
                        <div class="welcome-sub">
                            Log in to your Oppasabuy account
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="login-error">
                            <i class="fas fa-circle-exclamation"></i>
                            @if ($errors->has('email') || $errors->has('password'))
                                Incorrect email or password. Please check your credentials and try again.
                            @else
                                {{ $errors->first() }}
                            @endif
                        </div>
                    @endif

                    <<form method="POST" action="{{ route('login.authenticate') }}">">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">
                                Account ID
                            </label>
                            <input 
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                required
                                value="{{ old('email') }}"
                                autofocus
                                autocomplete="email"
                                placeholder="Enter your email"
                            >
                        </div>

                        <div class="form-group">
                            <div class="forgot-wrap">
                                <label class="form-label mb-0">
                                    Password
                                </label>
                                <a href="#" class="forgot-text">
                                    forgot?
                                </a>
                            </div>
                            <input 
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter password"
                            >
                        </div>

                        <div class="remember-row">
                            <input type="checkbox" id="rem" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="rem">
                                Remember me for 30 days
                            </label>
                        </div>

                        <button type="submit" class="btn-login">
                            LOG IN
                        </button>

                       <div class="create-account mt-4">
    <p>Don't have an account? 
        <a href="{{ route('register') }}" class="text-blue-500 underline">Create an account</a>
    </p>

<div class="mt-4 text-center">
    <p class="text-gray-600">
        Want to earn by delivering with Hatid Express?
    </p>

    <a href="{{ route('register.rider') }}"
       class="inline-block mt-2 text-green-600 font-semibold hover:text-green-700 hover:underline transition">
        Register as a Rider
    </a>
</div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-section">
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-col">
                    <h6>Customer Service</h6>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Returns Policy</a></li>
                        <li><a href="#">Shipping & Delivery</a></li>
                        <li><a href="#">Feedback</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h6>About Us</h6>
                    <ul class="footer-links">
                        <li><a href="#">Get to know Oppasabuy</a></li>
                        <li><a href="#">Charitable Contributions</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h6>Membership</h6>
                    <ul class="footer-links">
                        <li><a href="#">Membership Benefits</a></li>
                        <li><a href="#">Join Now</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h6>Follow Us</h6>
                    <div class="social-row">
                        <i class="fab fa-facebook"></i>
                        <i class="fab fa-instagram"></i>
                        <i class="fab fa-tiktok"></i>
                        <i class="fab fa-youtube"></i>
                    </div>
                </div>
            </div>

            <div class="copy-text">
                <i class="far fa-copyright"></i>
                2026 - Oppasabuy All Rights Reserved
            </div>
        </div>
    </footer>
   

</body>
</html>