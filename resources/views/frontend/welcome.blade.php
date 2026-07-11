<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oppasabuy | Welcome</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@900&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --oppa-red: #9e1b18;
            --oppa-red-dark: #7f1412;
            --oppa-blue: #0d47a1;
            --oppa-blue-dark: #163d78;
            --bg-soft: #f6f8fb;
            --border-soft: #e8edf3;
            --shadow-soft: 0 8px 22px rgba(15, 23, 42, 0.06);
            --shadow-hover: 0 18px 40px rgba(15, 23, 42, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(13, 71, 161, 0.03), transparent 25%),
                radial-gradient(circle at top right, rgba(158, 27, 24, 0.04), transparent 28%),
                #f6f8fb;
            color: #1a1a1a;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        .container {
            max-width: 1180px;
        }

       
      /* ---------- NAVIGATION BAR ---------- */
        .navbar {
            padding: 12px 0 !important;
            background: #ffffff !important;
            border-bottom: 1px solid #eceff3;
            position: sticky;
            top: 0;
            z-index: 1050;
            box-shadow: 0 3px 10px rgba(0,0,0,0.04);
        }

        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand img {
            height: 56px;
            width: auto;
        }

        /* Centered Links Logic */
        .main-nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            flex-grow: 1;
        }

        .main-nav-links .nav-link {
            font-weight: 800;
            color: var(--oppa-blue-dark) !important;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: none;
            position: relative;
            transition: color 0.2s ease;
        }

        .main-nav-links .nav-link:hover {
            color: var(--oppa-red) !important;
        }

        .active-link {
            color: var(--oppa-red) !important;
        }

        .active-link::after {
            content: "";
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--oppa-red);
        }

        .header-utilities {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-shrink: 0;
        }

        .header-utilities a {
            text-decoration: none;
            color: var(--oppa-blue-dark);
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .verify-link-wrap { position: relative; display: inline-flex; }

        .badge-now {
            background-color: #ffd34d;
            color: #7f1412;
            font-size: 8px;
            font-weight: 900;
            padding: 2px 4px;
            border-radius: 4px;
            position: absolute;
            top: -10px;
            right: -18px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.18);
        }

        /* ---------- HERO ---------- */
        .welcome-hero {
            margin-top: 24px;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid #f0e8e8;
            background:
                radial-gradient(circle at top left, rgba(254, 226, 226, 0.95) 0%, rgba(255,255,255,0) 32%),
                radial-gradient(circle at top right, rgba(254, 252, 232, 0.98) 0%, rgba(255,255,255,0) 34%),
                linear-gradient(180deg, #fffdfd 0%, #ffffff 100%);
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.07);
        }

        .welcome-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            align-items: center;
            gap: 32px;
            padding: 70px 60px;
        }

        .welcome-tag {
            display: inline-block;
            background: #fef1f1;
            color: var(--oppa-red);
            border: 1px solid #f5dada;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 800;
            padding: 8px 14px;
            margin-bottom: 18px;
        }

        .welcome-title {
            font-family: 'Playfair Display', serif;
            color: var(--oppa-red);
            font-size: 4rem;
            line-height: 1.02;
            margin: 0 0 14px 0;
            letter-spacing: -1.5px;
        }

        .welcome-subtitle {
            font-size: 1.15rem;
            color: #24456c;
            margin-bottom: 28px;
            max-width: 620px;
        }

        .welcome-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }

        .btn-main {
            background: var(--oppa-red);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 14px 24px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            box-shadow: 0 8px 20px rgba(158, 27, 24, 0.18);
        }

        .btn-main:hover {
            background: var(--oppa-red-dark);
            color: #fff;
        }

        .btn-secondary {
            background: #fff;
            color: var(--oppa-blue-dark);
            border: 1px solid #d8e0ea;
            border-radius: 10px;
            padding: 14px 24px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .btn-secondary:hover {
            color: var(--oppa-red);
            border-color: #e1caca;
        }

        .welcome-points {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .welcome-points span {
            background: #ffffff;
            border: 1px solid var(--border-soft);
            border-radius: 999px;
            padding: 10px 14px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #35527a;
            box-shadow: var(--shadow-soft);
        }

        .welcome-card {
            background: #ffffff;
            border: 1px solid var(--border-soft);
            border-radius: 22px;
            box-shadow: var(--shadow-soft);
            padding: 24px;
        }

        .welcome-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .welcome-card-top h5 {
            margin: 0;
            font-weight: 800;
            color: #162c4b;
        }

        .mini-badge {
            background: #eef4ff;
            color: var(--oppa-blue-dark);
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .mock-list {
            display: grid;
            gap: 12px;
        }

        .mock-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fbfcfe;
            border: 1px solid #e7edf4;
            border-radius: 14px;
            padding: 12px;
        }

        .mock-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .mock-icon.red { background: linear-gradient(180deg, #bb201c, #9e1b18); }
        .mock-icon.blue { background: linear-gradient(180deg, #1a56b6, #0d47a1); }
        .mock-icon.light { background: linear-gradient(180deg, #2979d7, #1565c0); }

        .mock-text strong {
            display: block;
            color: #142c4e;
            font-size: 0.95rem;
        }

        .mock-text small {
            color: #6b7280;
        }

        /* ---------- QUICK CARDS ---------- */
        .section-block {
            margin-top: 42px;
        }

        .section-title {
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.3px;
            margin-bottom: 24px;
        }

        .section-title::after {
            content: "";
            display: block;
            width: 44px;
            height: 3px;
            border-radius: 99px;
            background: var(--oppa-red);
            margin-top: 8px;
        }

        .quick-card {
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: 20px;
            padding: 28px 22px;
            box-shadow: var(--shadow-soft);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
            height: 100%;
        }

        .quick-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
        }

        .quick-card .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.3rem;
            margin-bottom: 18px;
        }

        .icon-red { background: linear-gradient(180deg, #bb201c, #9e1b18); }
        .icon-blue { background: linear-gradient(180deg, #1a56b6, #0d47a1); }
        .icon-light { background: linear-gradient(180deg, #2979d7, #1565c0); }

        .quick-card h5 {
            font-weight: 800;
            margin-bottom: 10px;
            color: #172b4d;
        }

        .quick-card p {
            color: #677181;
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        /* ---------- FOOTER ---------- */
        footer {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(4px);
            margin-top: 50px;
        }

        footer a {
            transition: color 0.2s ease;
        }

        footer a:hover {
            color: var(--oppa-red) !important;
        }

        .social-icons {
            display: flex;
            gap: 10px;
        }

        .social-icons a {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #143d84;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.2s ease;
            text-decoration: none;
            box-shadow: 0 6px 16px rgba(20, 61, 132, 0.18);
        }

        .social-icons a:hover {
            background: #9e1b18;
            transform: translateY(-2px);
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 1199px) {
            .container {
                max-width: 1140px;
            }

            .nav-top {
                grid-template-columns: 220px 1fr 160px;
            }

            .navbar-brand img {
                height: 50px;
            }

    
            .nav-bottom {
                padding-left: 230px;
                gap: 20px;
            }

            .welcome-title {
                font-size: 3.4rem;
            }
        }

        @media (max-width: 991px) {
            .nav-top {
                grid-template-columns: 1fr;
                gap: 12px;
            }


            .header-utilities {
                justify-content: center;
            }

            .nav-bottom {
                padding-left: 0;
                justify-content: center;
                flex-wrap: wrap;
                gap: 14px 18px;
            }
            .welcome-grid {
                grid-template-columns: 1fr;
                padding: 44px 26px;
            }

            .welcome-title {
                font-size: 2.8rem;
            }
        }

        @media (max-width: 767px) {
            .navbar-brand {
                justify-content: center;
            }

            .header-utilities {
                flex-wrap: wrap;
                gap: 14px;
            }

            .nav-bottom {
                gap: 12px 16px;
            }

            .welcome-title {
                font-size: 2.2rem;
                letter-spacing: -0.8px;
            }

            .welcome-subtitle {
                font-size: 1rem;
            }

            .welcome-actions {
                flex-direction: column;
            }

            .btn-main,
            .btn-secondary {
                width: 100%;
            }

            footer .container {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('oppa.png') }}" alt="Oppasabuy">
        </a>

        <div class="main-nav-links">
            <a class="nav-link {{ request()->is('/') ? 'active-link' : '' }}" href="{{ route('home') }}">Home</a>
            <a class="nav-link {{ request()->is('store*') ? 'active-link' : '' }}" href="{{ route('store') }}">Shop</a>
            <a class="nav-link {{ request()->is('about*') ? 'active-link' : '' }}" href="{{ url('/about') }}">About</a>
            <span class="verify-link-wrap">
                <a class="nav-link {{ request()->is('verified*') ? 'active-link' : '' }}" href="{{ route('verified.sellers') }}">Check Verified Seller</a>
                <span class="badge-now">NOW</span>
            </span>
        </div>

        <div class="header-utilities">
            @guest
                <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Log In</a>
                <a href="{{ route('register') }}" class="d-none d-md-flex"><i class="bi bi-person-plus-fill"></i> Sign Up</a>
            @endguest

            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ url('/admin/dashboard') }}" class="text-primary"><i class="bi bi-shield-lock-fill"></i> Admin</a>
                @elseif(auth()->user()->role === 'seller')
                    <a href="{{ url('/seller/dashboard') }}"><i class="bi bi-shop"></i> Store</a>
                @else
                    <a href="{{ url('/profile') }}"><i class="bi bi-person-circle"></i> Profile</a>
                @endif

                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger">
                    <i class="bi bi-power"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            @endauth
        </div>
    </div>
</header>

<main class="container">
    <section class="welcome-hero">
        <div class="welcome-grid">
            <div>
                <span class="welcome-tag">WELCOME TO OPPASABUY</span>
                <h1 class="welcome-title">Discover Korean favorites in one trusted marketplace.</h1>
                <p class="welcome-subtitle">
                    Shop curated K-Beauty, K-Fashion, and K-Food from verified sellers with a smoother, safer, and more premium browsing experience.
                </p>

                <div class="welcome-actions">
                    <a href="{{ url('/home') }}" class="btn btn-main">Start Shopping</a>
                    <a href="{{ url('/verified-sellers') }}" class="btn btn-secondary">Explore Sellers</a>
                </div>

                <div class="welcome-points">
                    <span>Verified Sellers</span>
                    <span>Safe Transactions</span>
                    <span>Video Proof Support</span>
                    <span>Curated Korean Picks</span>
                </div>
            </div>

            <div class="welcome-card">
                <div class="welcome-card-top">
                    <h5>Why shop here?</h5>
                    <span class="mini-badge">Trusted</span>
                </div>

                <div class="mock-list">
                    <div class="mock-item">
                        <div class="mock-icon red"><i class="bi bi-patch-check-fill"></i></div>
                        <div class="mock-text">
                            <strong>Verified sellers</strong>
                            <small>Shop with more confidence through checked seller profiles.</small>
                        </div>
                    </div>

                    <div class="mock-item">
                        <div class="mock-icon blue"><i class="bi bi-chat-dots-fill"></i></div>
                        <div class="mock-text">
                            <strong>Inquiry-based buying</strong>
                            <small>Talk directly with sellers before making decisions.</small>
                        </div>
                    </div>

                    <div class="mock-item">
                        <div class="mock-icon light"><i class="bi bi-camera-video-fill"></i></div>
                        <div class="mock-text">
                            <strong>Video proof support</strong>
                            <small>Extra protection for high-trust orders and approvals.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-block">
        <h4 class="section-title">Get Started</h4>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="quick-card">
                    <div class="icon-box icon-red">
                        <i class="bi bi-bag-heart-fill"></i>
                    </div>
                    <h5>Browse Categories</h5>
                    <p>Explore curated K-Beauty, K-Fashion, and K-Food selections in one place.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="quick-card">
                    <div class="icon-box icon-blue">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <h5>Meet Verified Sellers</h5>
                    <p>Check seller profiles, trust badges, and store presentation before ordering.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="quick-card">
                    <div class="icon-box icon-light">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5>Shop with Confidence</h5>
                    <p>Use safer inquiry flow, seller verification, and proof-based features for peace of mind.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="border-top py-4 mt-5">
    <div class="container d-flex justify-content-between align-items-center small text-muted">
        <div>
            <a href="#" class="text-decoration-none text-muted me-3">About Us</a>
            <a href="#" class="text-decoration-none text-muted me-3">Contact</a>
            <a href="#" class="text-decoration-none text-muted me-3">FAQ</a>
            <a href="#" class="text-decoration-none text-muted">Become a Seller</a>
        </div>

        <div class="social-icons">
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-twitter-x"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
        </div>
    </div>
</footer>

</body>
</html>