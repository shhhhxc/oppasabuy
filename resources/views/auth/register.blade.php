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

        /* ================= CAMERA CAPTURE ================= */
        .camera-panel{border:2px solid #8ba7c8;border-radius:14px;padding:16px;background:#f8fafc}
        .camera-select-row{display:grid;grid-template-columns:1fr auto;gap:10px;margin-bottom:12px}
        .camera-select-row .form-select{width:100%}
        .camera-stage{position:relative;width:100%;aspect-ratio:4/3;background:#111827;border-radius:12px;overflow:hidden;display:flex;align-items:center;justify-content:center}
        .camera-stage video,.camera-stage canvas,.camera-stage img{width:100%;height:100%;object-fit:cover}
        .camera-stage canvas,.camera-stage img{display:none}
        .camera-placeholder{color:#d1d5db;text-align:center;padding:20px;font-size:13px}
        .camera-placeholder i{display:block;font-size:38px;margin-bottom:10px}
        .camera-controls{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-top:12px}
        .camera-btn{border:none;border-radius:10px;min-height:44px;font-weight:800;font-size:13px;padding:10px 12px}
        .camera-btn-primary{background:var(--blue);color:#fff}
        .camera-btn-secondary{background:#e5e7eb;color:#1f2937}
        .camera-btn-danger{background:#cf0000;color:#fff}
        .camera-btn:disabled{opacity:.55;cursor:not-allowed}
        .camera-status{margin-top:10px;font-size:12px;color:#6b7280;line-height:1.5}
        .camera-error{margin-top:10px;padding:10px 12px;border-radius:10px;background:#fff1f2;border:1px solid #fecdd3;color:#b91c1c;font-size:12px;display:none}
        .camera-fallback{margin-top:12px;padding-top:12px;border-top:1px solid #dbe3ec}
        @media(max-width:576px){.camera-select-row,.camera-controls{grid-template-columns:1fr}}

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
                            <label class="form-label"><i class="fa-solid fa-camera"></i> Take a Photo of Your Valid ID</label>

                            <div class="camera-panel">
                                <div class="camera-select-row">
                                    <select id="cameraSelect" class="form-select" aria-label="Select camera">
                                        <option value="">Select camera</option>
                                    </select>

                                    <button type="button" id="refreshCamerasBtn" class="camera-btn camera-btn-secondary" onclick="loadCameraDevices()">
                                        <i class="fa-solid fa-rotate"></i> Refresh
                                    </button>
                                </div>

                                <div class="camera-stage">
                                    <div id="cameraPlaceholder" class="camera-placeholder">
                                        <i class="fa-solid fa-camera"></i>
                                        Select a camera, then press Start Camera.
                                    </div>

                                    <video id="cameraVideo" autoplay playsinline muted></video>
                                    <canvas id="cameraCanvas"></canvas>
                                    <img id="capturedPreview" src="" alt="Captured valid ID preview">
                                </div>

                                <div class="camera-controls">
                                    <button type="button" id="startCameraBtn" class="camera-btn camera-btn-primary" onclick="startSelectedCamera()">
                                        <i class="fa-solid fa-video"></i> Start Camera
                                    </button>

                                    <button type="button" id="switchCameraBtn" class="camera-btn camera-btn-secondary" onclick="switchCamera()" disabled>
                                        <i class="fa-solid fa-camera-rotate"></i> Switch Camera
                                    </button>

                                    <button type="button" id="captureBtn" class="camera-btn camera-btn-danger" onclick="captureValidIdPhoto()" disabled>
                                        <i class="fa-solid fa-camera"></i> Take Photo
                                    </button>

                                    <button type="button" id="retakeBtn" class="camera-btn camera-btn-secondary" onclick="retakeValidIdPhoto()" disabled>
                                        <i class="fa-solid fa-arrow-rotate-left"></i> Retake
                                    </button>
                                </div>

                                <div id="cameraStatus" class="camera-status">
                                    On phones, choose the front or rear camera. On laptops and desktops, select an available webcam.
                                </div>

                                <div id="cameraError" class="camera-error"></div>

                                <div class="camera-fallback">
                                    <label class="form-label mb-2">Camera unavailable? Choose an image instead</label>
                                    <input type="file" class="form-control" id="buyerValidIdFallback" accept="image/*" onchange="useFallbackImage(event)">
                                </div>

                                <input type="file" id="buyerValidId" name="buyer_valid_id" accept="image/*" required hidden>
                            </div>
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
let cameraStream = null;
let cameraDevices = [];
let activeCameraIndex = 0;
let capturedPhotoUrl = null;

const cameraSelect = document.getElementById('cameraSelect');
const cameraVideo = document.getElementById('cameraVideo');
const cameraCanvas = document.getElementById('cameraCanvas');
const capturedPreview = document.getElementById('capturedPreview');
const cameraPlaceholder = document.getElementById('cameraPlaceholder');
const switchCameraBtn = document.getElementById('switchCameraBtn');
const captureBtn = document.getElementById('captureBtn');
const retakeBtn = document.getElementById('retakeBtn');
const cameraStatus = document.getElementById('cameraStatus');
const cameraError = document.getElementById('cameraError');
const buyerValidId = document.getElementById('buyerValidId');
const buyerValidIdFallback = document.getElementById('buyerValidIdFallback');

function showCameraError(message){
    cameraError.textContent = message;
    cameraError.style.display = 'block';
}

function clearCameraError(){
    cameraError.textContent = '';
    cameraError.style.display = 'none';
}

function stopCamera(){
    if(cameraStream){
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
    cameraVideo.srcObject = null;
    captureBtn.disabled = true;
}

async function requestCameraPermission(){
    if(!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia){
        throw new Error('Camera access is not supported by this browser.');
    }

    const permissionStream = await navigator.mediaDevices.getUserMedia({video:true,audio:false});
    permissionStream.getTracks().forEach(track => track.stop());
}

async function loadCameraDevices(){
    clearCameraError();

    try{
        await requestCameraPermission();

        const devices = await navigator.mediaDevices.enumerateDevices();
        cameraDevices = devices.filter(device => device.kind === 'videoinput');
        cameraSelect.innerHTML = '';

        if(cameraDevices.length === 0){
            cameraSelect.innerHTML = '<option value="">No camera found</option>';
            switchCameraBtn.disabled = true;
            throw new Error('No camera was detected on this device.');
        }

        cameraDevices.forEach((device, index) => {
            const option = document.createElement('option');
            option.value = device.deviceId;

            const label = device.label || `Camera ${index + 1}`;
            const lower = label.toLowerCase();

            if(lower.includes('front') || lower.includes('user')){
                option.textContent = `${label} (Front)`;
            }else if(lower.includes('back') || lower.includes('rear') || lower.includes('environment')){
                option.textContent = `${label} (Rear)`;
            }else{
                option.textContent = label;
            }

            cameraSelect.appendChild(option);
        });

        activeCameraIndex = 0;
        cameraSelect.selectedIndex = 0;
        switchCameraBtn.disabled = cameraDevices.length < 2;
        cameraStatus.textContent = `${cameraDevices.length} camera${cameraDevices.length > 1 ? 's' : ''} detected. Select one and press Start Camera.`;
    }catch(error){
        cameraStatus.textContent = 'Camera could not be started.';
        showCameraError(
            error.name === 'NotAllowedError'
                ? 'Camera permission was denied. Allow camera access in your browser settings, then press Refresh.'
                : error.message
        );
    }
}

async function startSelectedCamera(){
    clearCameraError();
    stopCamera();

    try{
        if(cameraDevices.length === 0){
            await loadCameraDevices();
        }

        const selectedDeviceId = cameraSelect.value;

        if(!selectedDeviceId){
            throw new Error('Please select a camera first.');
        }

        cameraStream = await navigator.mediaDevices.getUserMedia({
            video:{
                deviceId:{exact:selectedDeviceId},
                width:{ideal:1280},
                height:{ideal:720}
            },
            audio:false
        });

        cameraVideo.srcObject = cameraStream;
        await cameraVideo.play();

        cameraPlaceholder.style.display = 'none';
        capturedPreview.style.display = 'none';
        cameraCanvas.style.display = 'none';
        cameraVideo.style.display = 'block';

        activeCameraIndex = Math.max(0, cameraDevices.findIndex(device => device.deviceId === selectedDeviceId));
        captureBtn.disabled = false;
        retakeBtn.disabled = true;
        switchCameraBtn.disabled = cameraDevices.length < 2;
        cameraStatus.textContent = 'Camera is ready. Position the ID clearly, then press Take Photo.';
    }catch(error){
        cameraPlaceholder.style.display = 'block';
        cameraVideo.style.display = 'none';
        showCameraError(
            error.name === 'NotAllowedError'
                ? 'Camera permission was denied. Please allow camera access and try again.'
                : `Unable to start the selected camera: ${error.message}`
        );
    }
}

async function switchCamera(){
    if(cameraDevices.length < 2){
        showCameraError('Only one camera is available on this device.');
        return;
    }

    activeCameraIndex = (activeCameraIndex + 1) % cameraDevices.length;
    cameraSelect.selectedIndex = activeCameraIndex;
    await startSelectedCamera();
}

function captureValidIdPhoto(){
    clearCameraError();

    if(!cameraStream || cameraVideo.readyState < 2){
        showCameraError('Start the camera before taking a photo.');
        return;
    }

    const width = cameraVideo.videoWidth;
    const height = cameraVideo.videoHeight;

    if(!width || !height){
        showCameraError('The camera is still loading. Please wait and try again.');
        return;
    }

    cameraCanvas.width = width;
    cameraCanvas.height = height;
    cameraCanvas.getContext('2d').drawImage(cameraVideo, 0, 0, width, height);

    cameraCanvas.toBlob(blob => {
        if(!blob){
            showCameraError('Could not capture the photo. Please try again.');
            return;
        }

        const file = new File([blob], `buyer-valid-id-${Date.now()}.jpg`, {type:'image/jpeg'});
        const transfer = new DataTransfer();
        transfer.items.add(file);
        buyerValidId.files = transfer.files;

        if(capturedPhotoUrl){
            URL.revokeObjectURL(capturedPhotoUrl);
        }

        capturedPhotoUrl = URL.createObjectURL(blob);
        capturedPreview.src = capturedPhotoUrl;

        cameraVideo.style.display = 'none';
        cameraCanvas.style.display = 'none';
        capturedPreview.style.display = 'block';
        cameraPlaceholder.style.display = 'none';

        captureBtn.disabled = true;
        retakeBtn.disabled = false;
        cameraStatus.textContent = 'Photo captured successfully. Continue or press Retake.';
        stopCamera();
    }, 'image/jpeg', 0.92);
}

async function retakeValidIdPhoto(){
    buyerValidId.value = '';
    buyerValidIdFallback.value = '';
    capturedPreview.src = '';
    capturedPreview.style.display = 'none';
    cameraPlaceholder.style.display = 'block';
    retakeBtn.disabled = true;
    cameraStatus.textContent = 'Select a camera and press Start Camera to retake the photo.';
    await startSelectedCamera();
}

function useFallbackImage(event){
    clearCameraError();

    const file = event.target.files[0];
    if(!file) return;

    if(!file.type.startsWith('image/')){
        showCameraError('Please choose a valid image file.');
        event.target.value = '';
        return;
    }

    stopCamera();

    const transfer = new DataTransfer();
    transfer.items.add(file);
    buyerValidId.files = transfer.files;

    if(capturedPhotoUrl){
        URL.revokeObjectURL(capturedPhotoUrl);
    }

    capturedPhotoUrl = URL.createObjectURL(file);
    capturedPreview.src = capturedPhotoUrl;
    cameraVideo.style.display = 'none';
    cameraCanvas.style.display = 'none';
    capturedPreview.style.display = 'block';
    cameraPlaceholder.style.display = 'none';
    captureBtn.disabled = true;
    retakeBtn.disabled = false;
    cameraStatus.textContent = 'Image selected successfully.';
}

function validateStep(step){
    let isValid = true;

    document.querySelectorAll(`#step${step} [required]`).forEach(field => {
        if(field.type === 'file'){
            if(!field.files || field.files.length === 0){
                isValid = false;
            }
        }else if(field.type === 'checkbox'){
            if(!field.checked){
                isValid = false;
            }
        }else if(field.value.trim() === ''){
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

    if(step === 2 && (!buyerValidId.files || buyerValidId.files.length === 0)){
        showCameraError('Please take a photo of your valid ID before continuing.');
        isValid = false;
    }

    if(!isValid){
        alert(`Please complete all required fields in Step ${step}.`);
    }

    return isValid;
}

function showStep(step){
    document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
    document.getElementById('step' + step).classList.add('active');

    document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));

    for(let i = 1; i <= step; i++){
        document.getElementById('indicator' + i).classList.add('active');
    }

    currentStep = step;

    if(step === 2 && cameraDevices.length === 0){
        loadCameraDevices();
    }
}

function nextStep(step){
    if(validateStep(currentStep)){
        showStep(step);
    }
}

function prevStep(step){
    showStep(step);
}

cameraSelect.addEventListener('change', () => {
    activeCameraIndex = cameraSelect.selectedIndex;
});

window.addEventListener('beforeunload', stopCamera);
</script>

</body>
</html>