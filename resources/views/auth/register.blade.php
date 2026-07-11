<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oppasabuy | Sign Up</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root{
            --blue:#0d4da1;
            --red:#cf0000;
            --text:#1f2937;
        }

        *{box-sizing:border-box}

        body{
            margin:0;
            font-family:'Inter',sans-serif;
            background:#f1f4f8;
            color:var(--text);
        }

        .topbar,.topbar-left,.step-indicator,.step,
        .navigation-buttons,.social-icons{
            display:flex;
            align-items:center;
        }

        .topbar{
            justify-content:space-between;
            flex-wrap:wrap;
            padding:12px 24px;
            background:#fff;
            border-bottom:1px solid #e5e7eb;
        }

        .topbar-left{gap:16px}

        .topbar-left img{height:34px}

        .topbar-left a,
        .footer a,
        .login-text a,
        .retry-text a{
            text-decoration:none;
        }

        .topbar-left a{
            color:#111827;
            font-size:14px;
            font-weight:600;
        }

        .topbar-right{
            font-size:12px;
            color:#4b5563;
            text-align:right;
        }

        .register-wrapper{
            min-height:100vh;
            padding:40px 20px 80px;
            background:linear-gradient(180deg,#0d4da1,#0a4188);
        }

        .register-card{
            max-width:920px;
            margin:auto;
            background:#fff;
            border-radius:20px;
            padding:34px;
            box-shadow:0 18px 45px rgba(0,0,0,.18);
        }

        .register-title{
            font-size:38px;
            font-weight:800;
        }

        .register-subtitle{
            color:#6b7280;
            margin-bottom:28px;
        }

        .step-indicator{
            justify-content:center;
            gap:14px;
            flex-wrap:wrap;
            margin-bottom:35px;
        }

        .step{
            gap:10px;
            color:#9ca3af;
            font-weight:700;
        }

        .step-circle{
            width:34px;
            height:34px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#d1d5db;
            color:#fff;
        }

        .step.active{color:var(--blue)}
        .step.active .step-circle{background:var(--blue)}

        .step-line{
            width:45px;
            height:2px;
            background:#d1d5db;
        }

        .form-step{display:none}
        .form-step.active{display:block}

        .section-title{
            font-size:15px;
            font-weight:800;
            color:#1e3a8a;
            margin-bottom:18px;
            text-transform:uppercase;
        }

        .form-label{
            font-size:14px;
            font-weight:700;
            margin-bottom:8px;
        }

        .form-control,.form-select{
            min-height:48px;
            border:2px solid #8ba7c8;
            border-radius:10px;
            box-shadow:none;
        }

        .form-control:focus,.form-select:focus{
            border-color:var(--blue);
            box-shadow:none;
        }

        textarea.form-control{min-height:100px}

        .input-group .btn{
            border:2px solid #8ba7c8;
            border-left:none;
            background:#f8fafc;
            font-size:13px;
            font-weight:700;
        }

        .password-hint,.retry-text,.verification-note{
            font-size:12px;
            color:#6b7280;
        }

        .footer-grid{
            display:grid;
            gap:16px;
        }

        .footer-grid{
            grid-template-columns:repeat(4,1fr);
        }

        .agreement-box{
            border-radius:14px;
            background:#f3f4f6;
            padding:18px;
            color:#6b7280;
        }

        .verification-note{
            text-align:center;
            margin-top:8px;
        }

        .navigation-buttons{
            gap:14px;
            margin-top:35px;
        }

        .btn-nav{
            width:100%;
            border:none;
            border-radius:10px;
            font-weight:800;
            height:50px;
        }

        .btn-prev{
            background:#e5e7eb;
        }

        .btn-next{
            background:var(--blue);
            color:#fff;
        }

        .btn-register{
            background:var(--red);
            color:#fff;
        }

        .login-text{
            text-align:center;
            margin-top:18px;
        }

        .footer{
            background:#fff;
            padding:40px 30px 24px;
        }

        .footer-grid{
            max-width:1200px;
            margin:auto;
            gap:30px;
        }

        .footer-title{
            font-size:18px;
            font-weight:800;
            margin-bottom:14px;
        }

        .footer a{
            color:#4b5563;
            margin-bottom:8px;
            display:block;
        }

        .social-icons{
            gap:16px;
            margin-top:10px;
        }

        .social-icons i{
            font-size:34px;
        }

        .fa-facebook{color:#1877F2}
        .fa-instagram{color:#E4405F}
        .fa-tiktok{color:#000}
        .fa-youtube{color:#FF0000}

        .copyright{
            text-align:center;
            margin-top:28px;
            color:#6b7280;
        }

        @media(max-width:768px){
            .register-card{padding:24px 18px}
            .footer-grid{
                grid-template-columns:1fr;
            }
            .navigation-buttons{
                flex-direction:column;
            }
            .step-line{
                display:none;
            }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <a href="{{ url('/') }}">
            <img src="{{ asset('oppa.png') }}" alt="">
        </a>
        <a href="{{ url('/auth/login') }}">
            Log in to Shop
        </a>
    </div>
    <div class="topbar-right">
        <div>Not an Oppasabuy member?</div>
        <div>Enjoy both in-store and online shopping with a single sign-up!</div>
    </div>
</div>

<div class="register-wrapper">
    <div class="register-card">
        <div class="register-title">Sign Up</div>
        <div class="register-subtitle">
            Complete the required registration fields to create your account.
        </div>

        <div class="step-indicator">
            <div class="step active" id="indicator1">
                <div class="step-circle">1</div> Account
            </div>
            <div class="step-line"></div>
            <div class="step" id="indicator2">
                <div class="step-circle">2</div> Verification
            </div>
            <div class="step-line"></div>
            <div class="step" id="indicator3">
                <div class="step-circle">3</div> Finish
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-bottom:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="account_type" value="buyer">

            <div class="form-sections">
                <div class="form-step active" id="step1">
                    <div class="section-title">Basic Information</div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-user"></i> Full Name</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-id-card"></i> Account ID</label>
                            <input type="text" class="form-control" name="account_id" value="{{ $nextId ?? '0001' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-phone"></i> Phone Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="phone" placeholder="09XXXXXXXXX" required>
                                <button type="button" class="btn">Get code</button>
                            </div>
                            <div class="retry-text">Didn't get a code? <a href="#">Try again</a></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-location-dot"></i> Full Address</label>
                            <textarea class="form-control" name="address"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-lock"></i> Password</label>
                            <input type="password" class="form-control" name="password" required>
                            <div class="password-hint">must include uppercase, lowercase, number, and special character.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-envelope"></i> Email Address</label>
                            <div class="input-group">
                                <input type="email" class="form-control" name="email" required>
                                <button type="button" class="btn">Get code</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-shield"></i> Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="navigation-buttons">
                        <button type="button" class="btn-nav btn-next" onclick="nextStep(2)">Next Step</button>
                    </div>
                </div>

                <div class="form-step" id="step2">
                    <div class="section-title">Account Verification</div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-address-card"></i> Valid ID</label>
                            <select class="form-select" name="buyer_id_type" required>
                                <option value="">Select ID Type</option>
                                <option>PhilSys National ID</option>
                                <option>Passport</option>
                                <option>Driver’s License</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa-solid fa-upload"></i> Upload Valid ID</label>
                            <input type="file" class="form-control" name="buyer_valid_id" required>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button type="button" class="btn-nav btn-prev" onclick="prevStep(1)">Previous</button>
                        <button type="button" class="btn-nav btn-next" onclick="nextStep(3)">Next Step</button>
                    </div>
                </div>

                <div class="form-step" id="step3">
                    <div class="section-title">Agreement</div>
                    <div class="agreement-box">
                        By creating an account, you agree that submitted information may be reviewed for verification and anti-scam purposes.
                    </div>
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                        <label class="form-check-label" for="agreeTerms">I agree to the registration and verification process</label>
                    </div>
                    <button type="submit" class="btn-nav btn-register mt-4">CREATE ACCOUNT</button>
                    <div class="login-text">Already have an account? <a href="{{ url('/auth/login') }}">Log in</a></div>
                    <div class="navigation-buttons">
                        <button type="button" class="btn-nav btn-prev" onclick="prevStep(2)">Previous</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="footer">
    <div class="footer-grid">
        <div>
            <div class="footer-title">Customer Service</div>
            <a href="#">Help Center</a>
            <a href="#">Returns Policy</a>
            <a href="#">Shipping & Delivery</a>
            <a href="#">FeedBack</a>
            <a href="#">Contact Us</a>
        </div>
        <div>
            <div class="footer-title">About Us</div>
            <a href="#">Get to know Oppasabuy</a>
            <a href="#">Charitable Contributions</a>
            <a href="#">Terms & Conditions</a>
        </div>
        <div>
            <div class="footer-title">Membership</div>
            <a href="#">Membership Benefits</a>
            <a href="#">Join Now</a>
        </div>
        <div>
            <div class="footer-title">Follow Us</div>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
    <div class="copyright">
        <i class="far fa-copyright"></i> 2026 - Oppasabuy All Rights Reserved
    </div>
</div>

<script>
let currentStep = 1;

function validateStep(step){
    let isValid = true;
    document.querySelectorAll(`#step${step} [required]`).forEach(field=>{
        if(field.value.trim() === ''){
            field.style.borderColor = '#cf0000';
            isValid = false;
        }else{
            field.style.borderColor = '#8ba7c8';
        }
    });

    if(step === 1){
        const pass = document.querySelector('[name="password"]').value;
        const confirm = document.querySelector('[name="password_confirmation"]').value;
        if(pass !== confirm){
            alert('Password and Confirm Password do not match.');
            isValid = false;
        }
    }

    if(!isValid){
        alert(`Please complete all required fields in Step ${step}.`);
    }
    return isValid;
}

function showStep(step){
    document.querySelectorAll('.form-step').forEach(el=>{
        el.classList.remove('active');
    });
    document.getElementById('step'+step).classList.add('active');

    document.querySelectorAll('.step').forEach(el=>{
        el.classList.remove('active');
    });

    for(let i=1;i<=step;i++){
        document.getElementById('indicator'+i).classList.add('active');
    }

    currentStep = step;
}

function nextStep(step){
    if(validateStep(currentStep)){
        showStep(step);
    }
}

function prevStep(step){
    showStep(step);
}
</script>

</body>
</html>