@extends('layouts.app') @section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 80vh;">
    <div class="card shadow-lg p-5 text-center" style="border-radius: 20px; width: 100%; max-width: 400px;">
        
        <div class="position-relative mb-4">
            <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status"></div>
            <i class="bi bi-search position-absolute top-50 start-50 translate-middle" style="font-size: 1.5rem;"></i>
        </div>

        <h3 class="fw-bold">Naghahanap ng Rider...</h3>
        <p class="text-muted">Sandali lang, naghahanap kami ng pinakamalapit na rider para sa iyong Paskaay.</p>
        
        <div class="mt-3">
            <a href="#" class="btn btn-outline-danger w-100" onclick="alert('Canceling not yet implemented')">I-cancel ang booking</a>
        </div>
    </div>
</div>

<script>
    // Mag-re-reload ang page kada 5 segundo para i-check kung accepted na
    setInterval(function() {
        fetch("{{ route('paskaay.check.status', $paskaay->id) }}")
            .then(response => response.json())
            .then(data => {
                if (data.status === 'accepted') {
                    window.location.href = "{{ route('paskaay.tracking', $paskaay->id) }}";
                }
            });
    }, 5000);
</script>
@endsection