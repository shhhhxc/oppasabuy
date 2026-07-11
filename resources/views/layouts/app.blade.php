<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Oppasabuy | Authentic Korean Products</title>

        <link
            href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@900&family=Inter:wght@400;600;700;800&display=swap"
            rel="stylesheet">
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet">
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <link
            rel="stylesheet"
            href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css"/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script
            src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
            <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css"/>
<script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/bundle.min.js"></script>
            

        <style>
            :root {
                --oppa-red: #cc2121;
                --oppa-blue: #0d47a1;
                --oppa-blue-dark: #163d78;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                background: #f5f5f5;
                color: #1a1a1a;
                overflow-x: hidden;
            }

            /* TOP MINI NAV */
            .top-mini-nav {
                width: 100%;
                background: #ffffff;
                border-bottom: 1px solid #ececec;
                padding: 10px 0;
            }

            .top-mini-nav .container {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 40px;
                flex-wrap: wrap;
            }

            .top-mini-nav a {
                text-decoration: none;
                color: #555;
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
            }

            .top-mini-nav a:hover {
                color: var(--oppa-blue);
            }

            /* NAVBAR */
            .navbar {
                background: #fff !important;
                border-bottom: 1px solid #ececec;
                padding: 14px 0 !important;
                position: sticky;
                top: 0;
                z-index: 1050;
            }

            .navbar .container {
                display: grid;
                grid-template-columns: auto 1fr auto;
                align-items: center;
                gap: 40px;
                max-width: 1200px;
            }

            .navbar-brand img {
                height: 50px;
            }

            /* SEARCH */
            .search-wrapper {
                position: relative;
                width: 100%;
                max-width: 700px;
            }

            .search-wrapper input {
                width: 100%;
                height: 44px;
                border: 2px solid var(--oppa-blue);
                padding: 0 55px 0 16px;
                font-size: 15px;
            }

            .search-button {
                position: absolute;
                right: 0;
                top: 0;
                width: 46px;
                height: 44px;
                border: none;
                background: var(--oppa-blue);
                color: #fff;
            }

            /* ICONS */
            .header-utilities {
                display: flex;
                gap: 25px;
                justify-content: flex-end;
                align-items: center;
            }

            .icon-btn {
                font-size: 24px;
                color: var(--oppa-blue);
                text-decoration: none;
                position: relative;
            }

            .icon-btn:hover {
                color: var(--oppa-red);
            }

            .cart-badge {
                position: absolute;
                top: -8px;
                right: -10px;
                background: var(--oppa-red);
                color: #fff;
                font-size: 10px;
                border-radius: 50%;
                padding: 2px 6px;
            }

            /* ---------- MODERN MODAL ---------- */
            .modern-modal {
                border-radius: 18px;
                overflow: hidden;
                border: none;
                box-shadow: 0 20px 60px rgba(0,0,0,0.2);
                background: #fff;
            }

            .modern-header {
                background: linear-gradient(135deg, #0d47a1, #1976d2);
                color: white;
                border: none;
                padding: 18px;
            }

            .modern-input {
                border-radius: 12px !important;
                border: 1px solid rgba(13, 71, 161, 0.15) !important;
                transition: all 0.25s ease;
            }

            .modern-input:focus {
                border-color: #0d47a1 !important;
                box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, 0.15);
            }

            .btn-modern {
                background: linear-gradient(135deg, #0d47a1, #1976d2);
                color: white;
                border-radius: 12px;
                padding: 12px;
                font-weight: 600;
                border: none;
                transition: 0.3s;
            }

            .btn-modern:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(13, 71, 161, 0.3);
                color: white;
            }

            .modern-map {
                height: 300px;
                width: 100%;
                border-radius: 14px;
                border: 2px solid rgba(13, 71, 161, 0.15);
            }

            /* DROPDOWN MENU */
            .menu-dropdown .dropdown-toggle::after {
                display: none;
            }

            .menu-dropdown .dropdown-menu {
                border-radius: 12px;
                border: 1px solid #ececec;
                padding: 10px 0;
                min-width: 220px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            }

            .menu-dropdown .dropdown-item {
                padding: 12px 18px;
                font-size: 14px;
                font-weight: 600;
                color: #444;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .menu-dropdown .dropdown-item:hover {
                background: #f5f7fb;
                color: var(--oppa-blue);
            }

            .menu-dropdown .logout-btn {
                color: var(--oppa-red);
            }

            .menu-dropdown .logout-btn:hover {
                background: #fff5f5;
                color: var(--oppa-red);
            }

            /* FOOTER FIXED */
            .footer-main {
                background: #fff;
                padding: 50px 0 30px;
                margin-top: 60px;
                border-top: 2px solid #e5e5e5;
            }

            .footer-col h6 {
                font-weight: 800;
                margin-bottom: 15px;
                color: var(--oppa-blue);
                font-size: 14px;
                text-transform: uppercase;
            }

            .footer-col ul {
                list-style: none;
                padding: 0;
            }

            .footer-col ul li {
                margin-bottom: 10px;
                font-size: 13px;
            }

            .footer-col ul li a {
                text-decoration: none;
                color: #666;
            }

            .footer-col ul li a:hover {
                color: var(--oppa-red);
            }

            .social-links {
                display: flex;
                gap: 15px;
            }

            .social-links a {
                font-size: 26px;
                color: #1877f2;
            }

            /* MOBILE */
            @media(max-width:991px) {
                .navbar .container {
                    grid-template-columns: 1fr auto;
                    gap: 15px;
                }

                .search-center {
                    grid-column: span 2;
                }

                .top-mini-nav {
                    display: none;
                }

                .footer-main {
                    text-align: center;
                }

                .social-links {
                    justify-content: center;
                }

                .footer-col {
                    margin-bottom: 25px;
                }
            }
        </style>
    </head>

    <body>

        <!-- TOP MINI NAV -->
        <nav class="top-mini-nav">
            <div class="container">
                <a href="{{ route('lifestyle.hub') }}">PERSONAL CARE & LIFESTYLE HUB</a>
                <a href="{{ route('greenmart.index') }}">GREEN MART</a>
                <a href="{{ route('store') }}">WEB STORE</a>
                <a href="{{ route('oppamall.index') }}">OPPAMALL</a>
                <a href="{{ route('membership.index') }}">MEMBERSHIP</a>
                <a href="{{ route('about') }}">ABOUT</a>
                <a href="{{ route('hatid.express') }}">OPPAEXPRESS</a>
            </a>
        </div>
    </nav>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container">

            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('oppa.png') }}" alt="Oppasabuy">
            </a>

            <div class="search-center">
                <form
                    action="{{ route('oppamall.search') }}"
                    method="GET"
                    class="search-wrapper">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search all products...">
                    <button type="submit" class="search-button">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <div class="header-utilities">

                @auth @php $user = auth()->user(); $dashboardRoute = match($user->role) {
                'admin' => route('admin.verify'), default => route('buyer.dashboard'), };
                @endphp

                <a href="{{ $dashboardRoute }}" class="icon-btn">
                    <i class="bi bi-person"></i>
                </a>

                <a href="{{ route('cart.index') }}" class="icon-btn">
                    <i class="bi bi-cart3"></i>

                    <span class="cart-badge">
                        {{ session('cart') ? count(session('cart')) : 0 }}
                    </span>
                </a>

                @else

                <a href="{{ route('login') }}" class="icon-btn">
                    <i class="bi bi-person"></i>
                </a>

                @endauth

                <!-- DROPDOWN MENU -->
                <div class="dropdown menu-dropdown">

                    <a
                        href="#"
                        class="icon-btn dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <i class="bi bi-list"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        @auth

                        <li>
                            <a class="dropdown-item" href="{{ $dashboardRoute }}">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3"></i>
                                My Cart
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit" class="dropdown-item logout-btn">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </button>
                            </form>
                        </li>

                        @else

                        <li>
                            <a class="dropdown-item" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Login
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i>
                                Register
                            </a>
                        </li>

                        @endauth

                    </ul>

                </div>

            </div>

        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <main class="container-fluid p-0">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer-main">
        <div class="container">
            <div class="row">

                <div class="col-md-3 footer-col">
                    <h6>Customer Service</h6>
                    <ul>
                        <li>
                            <a href="#">Help Center</a>
                        </li>
                        <li>
                            <a href="#">Returns Policy</a>
                        </li>
                        <li>
                            <a href="#">Shipping & Delivery</a>
                        </li>
                        <li>
                            <a href="#">Contact Us</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-3 footer-col">
                    <h6>About Us</h6>
                    <ul>
                        <li>
                            <a href="#">Get to know Oppasabuy</a>
                        </li>
                        <li>
                            <a href="#">Terms & Conditions</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-3 footer-col">
                    <h6>Membership</h6>
                    <ul>
                        <li>
                            <a href="#">Membership Benefits</a>
                        </li>
                        <li>
                            <a href="#">Join Now</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-3 footer-col">
                    <h6>Follow Us</h6>

                    <div class="social-links">
                        <a href="#">
                            <i class="bi bi-facebook"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-instagram" style="color:#e4405f"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-tiktok"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-youtube" style="color:#ff0000"></i>
                        </a>
                    </div>
                </div>

            </div>

            <div class="text-center mt-5 pt-3 border-top">
                <small class="text-muted">
                    &copy; 2026 Oppasabuy. All Rights Reserved.
                </small>
            </div>
        </div>
    </footer>
@stack('scripts')

</body>
</html>