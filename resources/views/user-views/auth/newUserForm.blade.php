@php
    $temp_user = Session::get('temp_user');
@endphp

<div class="pen-title mx-auto mb-5">
    <img src="{{ asset('assets/images/icons/foodYariLogo.png') }}" alt="logo" class="logo-desc">
</div>
<h4 class="text-center">Resgister yourself to create an account</h4>
<form method="POST" action="{{route('user.auth.register-user')}}" id="checkUser">
    @csrf
    <div class="input-group">
        <span class="input-group-text" style="height: 45px;">
            <i class="fas fa-phone icon-layer"></i>
        </span>
        <input type="number" name="phone" class="form-control mb-1" value="{{$temp_user['phone']}}" maxlength="10" placeholder="Phone" readonly required="">
    </div>
    <div class="text-end mb-3"><a href="" class="text-warning" style="text-decoration: none">Change Phone</a></div>
    <div class="input-group">
        <input type="number" name="otp" class="form-control" maxlength="6" placeholder="# # # # # #" required="" style="text-align: center;
            font-size: 20px;letter-spacing: 8px;" value="{{$temp_user['otp']??null}}">
    </div>
    <div class="input-group">
        <span class="input-group-text" style="height: 45px;">
            <i class="fas fa-user icon-layer"></i>
        </span>
        <input type="text" name="name" class="form-control" value="{{$temp_user['name']??null}}" placeholder="Name" required="">
    </div>
    <div class="input-group">
        <span class="input-group-text" style="height: 45px;">
            <i class="fas fa-envelope icon-layer"></i>
        </span>
        <input type="email" name="email" class="form-control" value="{{$temp_user['email']??null}}" placeholder="Email" required="">
    </div>
    
    <!-- Referral Code Input -->
    <div class="input-group">
        <span class="input-group-text" style="height: 45px;">
            <i class="fas fa-gift icon-layer"></i>
        </span>
        <input type="text" name="referral_code" id="referralCodeInput" class="form-control" 
               value="{{ $referralCode ?? request()->get('ref') ?? session('referral_code') ?? '' }}" 
               placeholder="Referral Code (Optional)" maxlength="20">
        <button type="button" class="btn btn-outline-success" id="validateReferralBtn" style="display: none;">
            <i class="fas fa-check"></i>
        </button>
    </div>
    <div id="referralMessage" class="small mt-1" style="display: none;"></div>
    <div id="sponsorInfo" class="alert alert-info mt-2" style="display: none;">
        <i class="fas fa-user-friends"></i>
        <span id="sponsorText"></span>
    </div>
    
    <button type="submit">Register and Continue</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const referralInput = document.getElementById('referralCodeInput');
    const validateBtn = document.getElementById('validateReferralBtn');
    const messageDiv = document.getElementById('referralMessage');
    const sponsorDiv = document.getElementById('sponsorInfo');
    const sponsorText = document.getElementById('sponsorText');

    // Auto-validate if referral code is pre-filled (from URL)
    if (referralInput.value.trim()) {
        validateReferralCode(referralInput.value.trim());
    }

    // Show validate button when user types
    referralInput.addEventListener('input', function() {
        const code = this.value.trim();
        if (code.length >= 3) {
            validateBtn.style.display = 'block';
        } else {
            validateBtn.style.display = 'none';
            hideMessages();
        }
    });

    // Validate on button click
    validateBtn.addEventListener('click', function() {
        const code = referralInput.value.trim();
        if (code) {
            validateReferralCode(code);
        }
    });

    // Validate on Enter key
    referralInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const code = this.value.trim();
            if (code) {
                validateReferralCode(code);
            }
        }
    });

    function validateReferralCode(code) {
        // Show loading state
        validateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        validateBtn.disabled = true;

        fetch('/validate-referral', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ referral_code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message);
                if (data.sponsor) {
                    showSponsorInfo(data.sponsor);
                }
            } else {
                showErrorMessage(data.message);
            }
        })
        .catch(error => {
            showErrorMessage('Error validating referral code');
            console.error('Validation error:', error);
        })
        .finally(() => {
            validateBtn.innerHTML = '<i class="fas fa-check"></i>';
            validateBtn.disabled = false;
        });
    }

    function showSuccessMessage(message) {
        messageDiv.innerHTML = '<i class="fas fa-check-circle text-success"></i> ' + message;
        messageDiv.className = 'small mt-1 text-success';
        messageDiv.style.display = 'block';
    }

    function showErrorMessage(message) {
        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle text-danger"></i> ' + message;
        messageDiv.className = 'small mt-1 text-danger';
        messageDiv.style.display = 'block';
        sponsorDiv.style.display = 'none';
    }

    function showSponsorInfo(sponsor) {
        sponsorText.textContent = `You'll be referred by ${sponsor.f_name} ${sponsor.l_name} and both of you will earn rewards!`;
        sponsorDiv.style.display = 'block';
    }

    function hideMessages() {
        messageDiv.style.display = 'none';
        sponsorDiv.style.display = 'none';
    }
});
</script>
