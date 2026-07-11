<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oppasabuy | Rider Sign Up</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --blue: #0d4da1;
            --red: #cf0000;
            --text: #1f2937;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f1f4f8;
            color: var(--text);
        }

        .topbar,
        .topbar-left,
        .step-indicator,
        .step,
        .navigation-buttons,
        .social-icons,
        .vehicle-options {
            display: flex;
            align-items: center;
        }

        .topbar {
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 12px 24px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        .topbar-left {
            gap: 16px;
        }

        .topbar-left img {
            height: 34px;
        }

        .topbar-left a,
        .footer a,
        .login-text a,
        .retry-text a {
            text-decoration: none;
        }

        .topbar-left a {
            color: #111827;
            font-size: 14px;
            font-weight: 600;
        }

        .topbar-right {
            font-size: 12px;
            color: #4b5563;
            text-align: right;
        }

        .register-wrapper {
            min-height: 100vh;
            padding: 40px 20px 80px;
            background: linear-gradient(180deg, #0d4da1, #0a4188);
        }

        .register-card {
            max-width: 920px;
            margin: auto;
            background: #fff;
            border-radius: 20px;
            padding: 34px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, .18);
        }

        .register-title {
            font-size: 38px;
            font-weight: 800;
        }

        .register-subtitle {
            color: #6b7280;
            margin-bottom: 28px;
        }

        .step-indicator {
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 35px;
        }

        .step {
            gap: 10px;
            color: #9ca3af;
            font-weight: 700;
        }

        .step-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #d1d5db;
            color: #fff;
        }

        .step.active {
            color: var(--blue);
        }

        .step.active .step-circle {
            background: var(--blue);
        }

        .step-line {
            width: 45px;
            height: 2px;
            background: #d1d5db;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .section-title {
            font-size: 15px;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 18px;
            text-transform: uppercase;
        }

        .subsection-title {
            font-size: 14px;
            font-weight: 800;
            color: #374151;
            margin-top: 30px;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-label {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            min-height: 48px;
            border: 2px solid #8ba7c8;
            border-radius: 10px;
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--blue);
            box-shadow: none;
        }

        textarea.form-control {
            min-height: 110px;
            resize: vertical;
        }

        .vehicle-options {
            gap: 14px;
            flex-wrap: wrap;
        }

        .vehicle-option {
            position: relative;
            flex: 1;
            min-width: 150px;
        }

        .vehicle-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .vehicle-option label {
            width: 100%;
            min-height: 100px;
            padding: 16px 12px;
            border: 2px solid #cbd5e1;
            border-radius: 12px;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 9px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 800;
            color: #475569;
            transition: .2s ease;
        }

        .vehicle-option label i {
            font-size: 28px;
        }

        .vehicle-option input:checked + label {
            border-color: var(--blue);
            background: #eff6ff;
            color: var(--blue);
            box-shadow: 0 0 0 3px rgba(13, 77, 161, .1);
        }

        .vehicle-option label:hover {
            border-color: var(--blue);
            background: #f8fbff;
        }

        .upload-note {
            margin-top: 6px;
            color: #6b7280;
            font-size: 12px;
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
        }

        .navigation-buttons {
            gap: 14px;
            margin-top: 35px;
        }

        .btn-nav {
            width: 100%;
            border: none;
            border-radius: 10px;
            font-weight: 800;
            height: 50px;
        }

        .btn-prev {
            background: #e5e7eb;
        }

        .btn-next {
            background: var(--blue);
            color: #fff;
        }

        .btn-register {
            background: var(--red);
            color: #fff;
        }

        .btn-next:hover {
            background: #093b7e;
        }

        .btn-register:hover {
            background: #a90000;
        }

        .login-text {
            text-align: center;
            margin-top: 18px;
        }

        .agreement-box {
            border-radius: 14px;
            background: #f3f4f6;
            padding: 18px;
            color: #6b7280;
        }

        .agreement-check {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #dbe3ed;
            border-radius: 10px;
            background: #fff;
        }

        .footer {
            background: #fff;
            padding: 40px 30px 24px;
        }

        .footer-grid {
            max-width: 1200px;
            margin: auto;
            gap: 30px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .footer-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .footer a {
            color: #4b5563;
            margin-bottom: 8px;
            display: block;
        }

        .social-icons {
            gap: 15px;
            font-size: 22px;
            color: var(--blue);
        }

        .copyright {
            text-align: center;
            margin-top: 28px;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .register-card {
                padding: 24px 18px;
            }

            .register-title {
                font-size: 30px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .navigation-buttons {
                flex-direction: column;
            }

            .step-line {
                display: none;
            }

            .vehicle-options {
                flex-direction: column;
            }

            .vehicle-option {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <a href="{{ url('/') }}">
            <img src="{{ asset('oppa.png') }}" alt="Oppasabuy">
        </a>
    </div>
</div>

<div class="register-wrapper">
    <div class="register-card">
        <div class="register-title">Rider Registration</div>
        <div class="register-subtitle">
            Apply as a partner rider for Oppasabuy.
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please correct the following:</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="step-indicator">
            <div class="step active" id="indicator1">
                <div class="step-circle">1</div>
                Info
            </div>

            <div class="step-line"></div>

            <div class="step" id="indicator2">
                <div class="step-circle">2</div>
                Vehicle & Documents
            </div>

            <div class="step-line"></div>

            <div class="step" id="indicator3">
                <div class="step-circle">3</div>
                Finish
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('register.rider.submit') }}"
            enctype="multipart/form-data"
            id="riderRegistrationForm"
        >
            @csrf

            <input type="hidden" name="role" value="rider">

            <div class="form-sections">
                <div class="form-step active" id="step1">
                    <div class="section-title">Rider Information</div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>

                            <input
                                type="text"
                                class="form-control"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter your full name"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>

                            <input
                                type="email"
                                class="form-control"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Enter your email address"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>

                            <input
                                type="tel"
                                class="form-control"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="09XXXXXXXXX"
                                pattern="09[0-9]{9}"
                                maxlength="11"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>

                            <input
                                type="date"
                                class="form-control"
                                name="birth_date"
                                value="{{ old('birth_date') }}"
                                required
                            >
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Complete Address</label>

                            <textarea
                                class="form-control"
                                name="address"
                                placeholder="House number, street, barangay, city and province"
                                required
                            >{{ old('address') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password</label>

                            <input
                                type="password"
                                class="form-control"
                                name="password"
                                placeholder="Minimum of 8 characters"
                                minlength="8"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>

                            <input
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                                placeholder="Enter your password again"
                                minlength="8"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact Name</label>

                            <input
                                type="text"
                                class="form-control"
                                name="emergency_contact_name"
                                value="{{ old('emergency_contact_name') }}"
                                placeholder="Enter contact person's full name"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact Number</label>

                            <input
                                type="tel"
                                class="form-control"
                                name="emergency_contact_number"
                                value="{{ old('emergency_contact_number') }}"
                                placeholder="09XXXXXXXXX"
                                pattern="09[0-9]{9}"
                                maxlength="11"
                                required
                            >
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button
                            type="button"
                            class="btn-nav btn-next"
                            onclick="nextStep(2)"
                        >
                            Next Step
                        </button>
                    </div>
                </div>

                <div class="form-step" id="step2">
                    <div class="section-title">Vehicle and Documents</div>

                    <div class="subsection-title">Choose Your Vehicle Type</div>

                    <div class="vehicle-options">
                        <div class="vehicle-option">
                            <input
                                type="radio"
                                name="vehicle_type"
                                id="vehicle_motorcycle"
                                value="Motorcycle"
                                {{ old('vehicle_type') === 'Motorcycle' ? 'checked' : '' }}
                                required
                            >

                            <label for="vehicle_motorcycle">
                                <i class="fa-solid fa-motorcycle"></i>
                                Motorcycle
                            </label>
                        </div>

                        <div class="vehicle-option">
                            <input
                                type="radio"
                                name="vehicle_type"
                                id="vehicle_car"
                                value="Car"
                                {{ old('vehicle_type') === 'Car' ? 'checked' : '' }}
                                required
                            >

                            <label for="vehicle_car">
                                <i class="fa-solid fa-car"></i>
                                Car
                            </label>
                        </div>
                    </div>

                    <div class="subsection-title">Vehicle Information</div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Brand</label>

                            <input
                                type="text"
                                class="form-control"
                                name="vehicle_brand"
                                value="{{ old('vehicle_brand') }}"
                                placeholder="Honda, Yamaha, Suzuki, Toyota"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Vehicle Model</label>

                            <input
                                type="text"
                                class="form-control"
                                name="vehicle_model"
                                value="{{ old('vehicle_model') }}"
                                placeholder="Click 125i, Mio, Vios"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Vehicle Color</label>

                            <input
                                type="text"
                                class="form-control"
                                name="vehicle_color"
                                value="{{ old('vehicle_color') }}"
                                placeholder="Black, Red, White"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Vehicle Plate Number</label>

                            <input
                                type="text"
                                class="form-control"
                                name="vehicle_plate"
                                value="{{ old('vehicle_plate') }}"
                                placeholder="ABC 1234"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">License Number</label>

                            <input
                                type="text"
                                class="form-control"
                                name="license_number"
                                value="{{ old('license_number') }}"
                                placeholder="Enter driver's license number"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">License Expiration Date</label>

                            <input
                                type="date"
                                class="form-control"
                                name="license_expiration"
                                value="{{ old('license_expiration') }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="subsection-title">Document Uploads</div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Driver's License Image</label>

                            <input
                                type="file"
                                class="form-control"
                                name="license_img"
                                accept=".jpg,.jpeg,.png,.webp"
                                required
                            >

                            <div class="upload-note">
                                Upload a clear photo of your driver's license.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">OR/CR Document</label>

                            <input
                                type="file"
                                class="form-control"
                                name="orcr_img"
                                accept=".jpg,.jpeg,.png,.webp,.pdf"
                                required
                            >

                            <div class="upload-note">
                                Upload the vehicle Official Receipt or Certificate of Registration.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Vehicle Photo</label>

                            <input
                                type="file"
                                class="form-control"
                                name="vehicle_photo"
                                accept=".jpg,.jpeg,.png,.webp"
                                required
                            >

                            <div class="upload-note">
                                Upload a clear full photo of your vehicle.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Selfie Holding Driver's License</label>

                            <input
                                type="file"
                                class="form-control"
                                name="selfie_license"
                                accept=".jpg,.jpeg,.png,.webp"
                                required
                            >

                            <div class="upload-note">
                                Your face and license details must be visible.
                            </div>
                        </div>
                    </div>

                    <div class="navigation-buttons">
                        <button
                            type="button"
                            class="btn-nav btn-prev"
                            onclick="prevStep(1)"
                        >
                            Previous
                        </button>

                        <button
                            type="button"
                            class="btn-nav btn-next"
                            onclick="nextStep(3)"
                        >
                            Next Step
                        </button>
                    </div>
                </div>

                <div class="form-step" id="step3">
                    <div class="section-title">Agreement</div>

                    <div class="agreement-box">
                        By registering as an Oppasabuy partner rider, you confirm that all information and uploaded documents are true and valid. You agree to follow Oppasabuy's delivery policies, rider safety guidelines, privacy policy, and terms and conditions.
                    </div>

                    <div class="agreement-check">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="terms"
                                value="1"
                                id="terms"
                                required
                            >

                            <label class="form-check-label" for="terms">
                                I confirm that the information I provided is correct and I agree to the terms and conditions.
                            </label>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="btn-nav btn-register mt-4"
                    >
                        SUBMIT APPLICATION
                    </button>

                    <div class="navigation-buttons">
                        <button
                            type="button"
                            class="btn-nav btn-prev"
                            onclick="prevStep(2)"
                        >
                            Previous
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="login-text">
            Already have an account?
            <a href="{{ route('login') }}" class="fw-bold">
                Sign in here
            </a>
        </div>
    </div>
</div>

<div class="footer">
    <div class="footer-grid">
        <div>
            <div class="footer-title">About Us</div>
            <a href="#">Company Profile</a>
        </div>

        <div>
            <div class="footer-title">Help</div>
            <a href="#">Support</a>
        </div>

        <div>
            <div class="footer-title">Legal</div>
            <a href="#">Terms</a>
        </div>

        <div>
            <div class="footer-title">Follow</div>

            <div class="social-icons">
                <i class="fab fa-facebook"></i>
            </div>
        </div>
    </div>

    <div class="copyright">
        2026 - Oppasabuy All Rights Reserved
    </div>
</div>

<script>
    let currentStep = 1;

    function showStep(step) {
        document.querySelectorAll('.form-step').forEach(function (element) {
            element.classList.remove('active');
        });

        document.getElementById('step' + step).classList.add('active');

        document.querySelectorAll('.step').forEach(function (element) {
            element.classList.remove('active');
        });

        for (let i = 1; i <= step; i++) {
            document.getElementById('indicator' + i).classList.add('active');
        }

        currentStep = step;

        window.scrollTo({
            top: document.querySelector('.register-card').offsetTop - 20,
            behavior: 'smooth'
        });
    }

    function validateCurrentStep(step) {
        const currentStepElement = document.getElementById('step' + step);
        const requiredFields = currentStepElement.querySelectorAll(
            'input[required], select[required], textarea[required]'
        );

        let valid = true;

        requiredFields.forEach(function (field) {
            if (!field.checkValidity()) {
                field.reportValidity();
                valid = false;
                return;
            }
        });

        if (step === 2) {
            const selectedVehicle = document.querySelector(
                'input[name="vehicle_type"]:checked'
            );

            if (!selectedVehicle) {
                alert('Please select your vehicle type.');
                valid = false;
            }
        }

        return valid;
    }

    function nextStep(step) {
        if (validateCurrentStep(currentStep)) {
            showStep(step);
        }
    }

    function prevStep(step) {
        showStep(step);
    }

    document.getElementById('riderRegistrationForm').addEventListener(
        'submit',
        function (event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                this.reportValidity();
            }
        }
    );

    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            @if(
                $errors->has('vehicle_type') ||
                $errors->has('vehicle_brand') ||
                $errors->has('vehicle_model') ||
                $errors->has('vehicle_color') ||
                $errors->has('vehicle_plate') ||
                $errors->has('license_number') ||
                $errors->has('license_expiration') ||
                $errors->has('license_img') ||
                $errors->has('orcr_img') ||
                $errors->has('vehicle_photo') ||
                $errors->has('selfie_license')
            )
                showStep(2);
            @elseif($errors->has('terms'))
                showStep(3);
            @else
                showStep(1);
            @endif
        });
    @endif
</script>

</body>
</html>