<div class="referral-code-section" style="margin-top: 15px;">
    <div class="form-group">
        <label for="referral_code" class="form-label">
            <i class="fas fa-gift text-success"></i> 
            Referral Code (Optional)
        </label>
        <div class="input-group">
            <input type="text" 
                   name="referral_code" 
                   id="referral_code" 
                   class="form-control" 
                   placeholder="Enter referral code to get rewards"
                   maxlength="20"
                   style="text-transform: uppercase;">
            <button type="button" 
                    class="btn btn-outline-primary btn-sm" 
                    id="validate-referral-btn" 
                    onclick="validateReferralCode()"
                    style="display: none;">
                Validate
            </button>
        </div>
        <small id="referral-feedback" class="form-text"></small>
        <small class="form-text text-muted">
            <i class="fas fa-info-circle"></i> 
            Use a friend's referral code to get exclusive rewards on your orders!
        </small>
    </div>
</div>

<script>
let referralValidationTimeout;
let isReferralValid = false;

document.getElementById('referral_code').addEventListener('input', function() {
    const referralCode = this.value.trim().toUpperCase();
    this.value = referralCode;
    
    const validateBtn = document.getElementById('validate-referral-btn');
    const feedback = document.getElementById('referral-feedback');
    
    // Clear previous timeout
    clearTimeout(referralValidationTimeout);
    
    // Reset validation state
    isReferralValid = false;
    feedback.innerHTML = '';
    
    if (referralCode.length >= 4) {
        validateBtn.style.display = 'inline-block';
        
        // Auto-validate after 1 second of no typing
        referralValidationTimeout = setTimeout(() => {
            validateReferralCode();
        }, 1000);
    } else {
        validateBtn.style.display = 'none';
    }
});

function validateReferralCode() {
    const referralCode = document.getElementById('referral_code').value.trim();
    const feedback = document.getElementById('referral-feedback');
    const validateBtn = document.getElementById('validate-referral-btn');
    
    if (!referralCode) {
        return;
    }
    
    // Show loading
    feedback.innerHTML = '<span class="text-info"><i class="fas fa-spinner fa-spin"></i> Validating...</span>';
    validateBtn.disabled = true;
    
    fetch('/validate-referral', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            referral_code: referralCode
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            feedback.innerHTML = `
                <span class="text-success">
                    <i class="fas fa-check-circle"></i> 
                    Valid referral code! You'll get rewards from ${data.sponsor.f_name}
                </span>
            `;
            isReferralValid = true;
        } else {
            feedback.innerHTML = `
                <span class="text-danger">
                    <i class="fas fa-times-circle"></i> 
                    ${data.message}
                </span>
            `;
            isReferralValid = false;
        }
    })
    .catch(error => {
        console.error('Validation error:', error);
        feedback.innerHTML = `
            <span class="text-danger">
                <i class="fas fa-exclamation-circle"></i> 
                Unable to validate referral code
            </span>
        `;
        isReferralValid = false;
    })
    .finally(() => {
        validateBtn.disabled = false;
    });
}

// Handle form submission
function handleRegistrationSubmit(originalSubmitFunction) {
    return function(event) {
        const referralCode = document.getElementById('referral_code').value.trim();
        
        if (referralCode && !isReferralValid) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Invalid Referral Code',
                text: 'Please validate your referral code or remove it to continue.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            
            return false;
        }
        
        // If we have a valid referral code or no code, proceed with original submission
        return originalSubmitFunction ? originalSubmitFunction(event) : true;
    };
}
</script>

<style>
.referral-code-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #28a745;
}

.referral-code-section .form-label {
    font-weight: 600;
    margin-bottom: 8px;
}

.input-group {
    position: relative;
}

#referral-feedback {
    margin-top: 5px;
    font-size: 13px;
}

#validate-referral-btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.text-success { color: #28a745 !important; }
.text-danger { color: #dc3545 !important; }
.text-info { color: #17a2b8 !important; }
.text-muted { color: #6c757d !important; }
</style>
