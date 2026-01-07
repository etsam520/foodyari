@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between" data-bs-toggle="collapse"
                        data-bs-target="#bankingFormCollapse" style="cursor: pointer;">
                        <div class="header-title">
                            <h5 class="page-header-title">
                                {{__('Add Bank/UPI') . " " . __('Details')}}
                            </h5>
                        </div>
                        <i class="feather-plus" id="toggleIcon"></i>
                    </div>
                    <div id="bankingFormCollapse" class="collapse">
                        <div class="card-body">

                            <form action="{{ route('vendor.banking.save-bank-details') }}" method="post" id="BankingForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row g-3">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="input-label" for="account_holder_name">Account Holder
                                                        Name</label>
                                                    <input type="text" name="account_holder_name" id="account_holder_name"
                                                        class="form-control h--45px"
                                                        placeholder="{{ __('Enter Account Holder Name') }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="input-label" for="bank_name">Bank Name</label>
                                                    <input type="text" name="bank_name" id="bank_name"
                                                        class="form-control h--45px"
                                                        placeholder="{{ __('Enter Bank Name') }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="input-label" for="ifsc_code">IFSC Code</label>
                                                    <input type="text" name="ifsc_code" id="ifsc_code"
                                                        class="form-control h--45px"
                                                        placeholder="{{ __('Enter IFSC Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="input-label" for="account_number">Account Number</label>
                                                    <input type="text" name="account_number" id="account_number"
                                                        class="form-control h--45px"
                                                        placeholder="{{ __('Enter Account Number') }}"
                                                        minlength="9" maxlength="20">
                                                    <small class="text-muted">Account number should be 9-20 digits</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="input-label" for="confirm_account_number">Confirm Account
                                                        Number</label>
                                                    <input type="text" name="confirm_account_number" id="confirm_account_number"
                                                        class="form-control h--45px"
                                                        placeholder="{{ __('Confirm Account Number') }}"
                                                        minlength="9" maxlength="20">
                                                    <small id="account_match_message" class="text-muted"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <label for="" class="text-center mb-2">OR</label>
                                        <br>
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="input-label" for="upi_id">UPI ID</label>
                                                    <input type="text" name="upi_id" id="upi_id"
                                                        class="form-control h--45px" placeholder="{{ __('Enter UPI ') }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="input-label" for="payment_note">Request Note</label>
                                                    <textarea name="payment_note" id="payment_note" class="form-control"
                                                        cols="30" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="border: 1px solid #cecbcb;">
                                        <div class="col-md-12 text-end">
                                            <button type="reset" id="reset_btn" class="btn btn-danger">Reset</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h5 class="page-header-title">
                                {{__('Bank/UPI') . " " . __('Details')}}
                            </h5>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('vendor.banking.banking-history-view') }}" class="btn btn-outline-primary btn-sm me-2">
                                <i class="feather-clock"></i> {{ __('View History') }}
                            </a>
                            <button class="btn btn-primary btn-sm" onclick="refreshBankingDetails()">
                                <i class="feather-refresh"></i> {{ __('Refresh') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="bankingCardsContainer">
                            <!-- Banking cards will be dynamically loaded here -->
                            <div class="col-12 text-center py-5" id="loadingBankingCards">
                                <i class="feather-credit-card text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">Loading banking details...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .banking-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: default;
    }
    
    .banking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .bank-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .upi-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .banking-card .btn {
        transition: all 0.2s;
    }
    
    .banking-card .btn:hover {
        transform: scale(1.05);
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-1px);
    }
    
    .alert-danger {
        border-left: 4px solid #dc3545;
    }
    
    .text-success i, .text-danger i {
        font-size: 14px;
    }
    
    #loadingBankingCards {
        opacity: 0.7;
    }
    
    .banking-card .position-absolute button {
        backdrop-filter: blur(10px);
    }
    
    /* Validation Error Styles */
    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }
    
    .invalid-feedback {
        display: block !important;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .alert-danger .alert-heading {
        color: #721c24;
        margin-bottom: 0.5rem;
    }
    
    .alert-danger ul li {
        margin-bottom: 0.25rem;
    }
    
    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    /* Success validation styles */
    .is-valid {
        border-color: #198754 !important;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important;
    }
    
    .valid-feedback {
        display: block !important;
        color: #198754;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endpush

@push('javascript')

    <script>
        const bankingForm = document.getElementById('BankingForm');
        let currentBankingDetailsId = null;

        // Enhanced form validation
        function validateForm() {
            const accountNumber = bankingForm.account_number.value.trim();
            const confirmAccountNumber = document.getElementById('confirm_account_number')?.value.trim();
            const upiId = bankingForm.upi_id.value.trim();
            const ifscCode = bankingForm.ifsc_code.value.trim();

            // Clear previous error messages
            clearErrorMessages();
            clearFieldErrors();

            let isValid = true;
            let errors = [];

            // Check if at least one payment method is provided
            if (!accountNumber && !upiId) {
                errors.push('Please provide either bank account details or UPI ID');
                isValid = false;
            }

            // Validate account number if provided
            if (accountNumber) {
                const accountField = bankingForm.account_number;
                if (accountNumber.length < 9 || accountNumber.length > 20) {
                    errors.push('Account number must be between 9-20 digits');
                    showFieldError(accountField, 'Account number must be between 9-20 digits');
                    isValid = false;
                } else {
                    showFieldSuccess(accountField);
                }
                
                const confirmField = document.getElementById('confirm_account_number');
                if (confirmAccountNumber && accountNumber !== confirmAccountNumber) {
                    errors.push('Account numbers do not match');
                    showFieldError(confirmField, 'Account numbers do not match');
                    document.getElementById('account_match_message').innerHTML = '<span class="text-danger"><i class="feather-x"></i> Account numbers do not match</span>';
                    isValid = false;
                } else if (confirmAccountNumber) {
                    showFieldSuccess(confirmField);
                    document.getElementById('account_match_message').innerHTML = '<span class="text-success"><i class="feather-check"></i> Account numbers match</span>';
                }
            }

            // Validate IFSC code if bank details provided
            if (accountNumber && ifscCode) {
                const ifscField = bankingForm.ifsc_code;
                const ifscPattern = /^[A-Z]{4}0[A-Z0-9]{6}$/;
                if (!ifscPattern.test(ifscCode)) {
                    errors.push('Invalid IFSC code format (e.g., SBIN0001234)');
                    showFieldError(ifscField, 'Invalid IFSC code format (e.g., SBIN0001234)');
                    isValid = false;
                } else {
                    showFieldSuccess(ifscField);
                }
            }

            // Validate UPI ID if provided
            if (upiId) {
                const upiField = bankingForm.upi_id;
                const upiPattern = /^[\w\.\-_]{2,256}@[a-zA-Z]{2,64}$/;
                if (!upiPattern.test(upiId)) {
                    errors.push('Invalid UPI ID format (e.g., user@bank)');
                    showFieldError(upiField, 'Invalid UPI ID format (e.g., user@bank)');
                    isValid = false;
                } else {
                    showFieldSuccess(upiField);
                }
            }

            if (!isValid) {
                displayErrors(errors);
            }

            return isValid;
        }

        function clearErrorMessages() {
            const errorContainer = document.getElementById('error-messages');
            if (errorContainer) {
                errorContainer.remove();
            }
        }

        function displayErrors(errors) {
            const errorHtml = `
                <div id="error-messages" class="alert alert-danger">
                    <ul class="mb-0">
                        ${errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                </div>
            `;
            bankingForm.insertAdjacentHTML('afterbegin', errorHtml);
        }

        function displayValidationErrors(errors) {
            // Clear previous errors
            clearErrorMessages();
            clearFieldErrors();

            let errorMessages = [];
            
            // Display errors for each field
            Object.keys(errors).forEach(fieldName => {
                const fieldErrors = errors[fieldName];
                const field = document.querySelector(`[name="${fieldName}"]`);
                
                if (field) {
                    // Add error class to field
                    field.classList.add('is-invalid');
                    
                    // Create or update error message for field
                    let errorDiv = field.parentNode.querySelector('.invalid-feedback');
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        field.parentNode.appendChild(errorDiv);
                    }
                    errorDiv.innerHTML = fieldErrors.join('<br>');
                }

                // Also collect for summary
                fieldErrors.forEach(error => {
                    errorMessages.push(`<strong>${getFieldLabel(fieldName)}:</strong> ${error}`);
                });
            });

            // Display summary of errors at top of form
            if (errorMessages.length > 0) {
                const errorHtml = `
                    <div id="error-messages" class="alert alert-danger">
                        <h6 class="alert-heading"><i class="feather-alert-triangle me-2"></i>Please fix the following errors:</h6>
                        <ul class="mb-0">
                            ${errorMessages.map(error => `<li>${error}</li>`).join('')}
                        </ul>
                    </div>
                `;
                bankingForm.insertAdjacentHTML('afterbegin', errorHtml);
            }
        }

        function clearFieldErrors() {
            // Remove error classes and messages from all fields
            const fields = bankingForm.querySelectorAll('.is-invalid');
            fields.forEach(field => {
                field.classList.remove('is-invalid');
            });

            const errorDivs = bankingForm.querySelectorAll('.invalid-feedback');
            errorDivs.forEach(div => {
                div.remove();
            });
        }

        function getFieldLabel(fieldName) {
            const labels = {
                'account_holder_name': 'Account Holder Name',
                'bank_name': 'Bank Name',
                'ifsc_code': 'IFSC Code',
                'account_number': 'Account Number',
                'upi_id': 'UPI ID',
                'payment_note': 'Payment Note'
            };
            return labels[fieldName] || fieldName.replace('_', ' ').toUpperCase();
        }

        function showFieldError(field, message) {
            if (!field) return;
            
            // Add error class
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            
            // Remove existing feedback
            const existingFeedback = field.parentNode.querySelector('.invalid-feedback, .valid-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.innerHTML = message;
            field.parentNode.appendChild(errorDiv);
        }

        function showFieldSuccess(field) {
            if (!field) return;
            
            // Add success class
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            
            // Remove existing feedback
            const existingFeedback = field.parentNode.querySelector('.invalid-feedback, .valid-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
        }

        function clearFieldState(field) {
            if (!field) return;
            
            field.classList.remove('is-invalid', 'is-valid');
            const existingFeedback = field.parentNode.querySelector('.invalid-feedback, .valid-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
        }

        function resetForm() {
            bankingForm.reset();
            clearErrorMessages();
            clearFieldErrors();
            document.getElementById('account_match_message').innerHTML = '';
            
            // Clear all field states
            const allFields = bankingForm.querySelectorAll('input, textarea');
            allFields.forEach(field => {
                clearFieldState(field);
            });
        }

        // Account number confirmation validation
        if (document.getElementById('confirm_account_number')) {
            document.getElementById('confirm_account_number').addEventListener('input', function() {
                const accountNumber = bankingForm.account_number.value.trim();
                const confirmAccountNumber = this.value.trim();
                const messageElement = document.getElementById('account_match_message');
                
                if (confirmAccountNumber && accountNumber) {
                    if (accountNumber === confirmAccountNumber) {
                        messageElement.innerHTML = '<span class="text-success"><i class="feather-check"></i> Account numbers match</span>';
                    } else {
                        messageElement.innerHTML = '<span class="text-danger"><i class="feather-x"></i> Account numbers do not match</span>';
                    }
                } else {
                    messageElement.innerHTML = '';
                }
            });
        }

        // IFSC code formatting and validation
        if (bankingForm.ifsc_code) {
            bankingForm.ifsc_code.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
                
                // Real-time validation
                const ifscPattern = /^[A-Z]{4}0[A-Z0-9]{6}$/;
                if (this.value.length === 11) {
                    if (ifscPattern.test(this.value)) {
                        showFieldSuccess(this);
                    } else {
                        showFieldError(this, 'Invalid IFSC code format (e.g., SBIN0001234)');
                    }
                } else if (this.value.length > 0) {
                    clearFieldState(this);
                }
            });
        }

        // UPI ID formatting and validation
        if (bankingForm.upi_id) {
            bankingForm.upi_id.addEventListener('input', function() {
                this.value = this.value.toLowerCase();
                
                // Real-time validation
                const upiPattern = /^[\w\.\-_]{2,256}@[a-zA-Z]{2,64}$/;
                if (this.value.length > 5 && this.value.includes('@')) {
                    if (upiPattern.test(this.value)) {
                        showFieldSuccess(this);
                    } else {
                        showFieldError(this, 'Invalid UPI ID format (e.g., user@bank)');
                    }
                } else if (this.value.length > 0) {
                    clearFieldState(this);
                }
            });
        }

        // Account number validation
        if (bankingForm.account_number) {
            bankingForm.account_number.addEventListener('input', function() {
                if (this.value.length >= 9 && this.value.length <= 20) {
                    showFieldSuccess(this);
                } else if (this.value.length > 0) {
                    if (this.value.length < 9) {
                        showFieldError(this, 'Account number too short (minimum 9 digits)');
                    } else {
                        showFieldError(this, 'Account number too long (maximum 20 digits)');
                    }
                } else {
                    clearFieldState(this);
                }
            });
        }

        bankingForm.addEventListener('submit', async (event) => {
            try {
                event.preventDefault();

                if (!validateForm()) {
                    return;
                }

                // Show loading state
                const submitBtn = bankingForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Saving...';
                submitBtn.disabled = true;

                const formData = new FormData(bankingForm);

                const resp = await fetch(bankingForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData,
                });

                const result = await resp.json();

                if (!resp.ok) {
                    // Handle validation errors specifically
                    if (resp.status === 422 && result.errors) {
                        displayValidationErrors(result.errors);
                        throw new Error('Please fix the validation errors below');
                    } else {
                        throw new Error(result.message || 'An error occurred');
                    }
                }

                toastr.success(result.message);
                
                // Refresh the banking details display
                await refreshBankingDetails();
                
                // Collapse the form with delay to show success
                setTimeout(() => {
                    const collapseElement = document.getElementById('bankingFormCollapse');
                    if (collapseElement.classList.contains('show')) {
                        const bsCollapse = bootstrap.Collapse.getInstance(collapseElement) || new bootstrap.Collapse(collapseElement);
                        bsCollapse.hide();
                    }
                }, 1000);

                // Reset form if it was a new entry
                if (!currentBankingDetailsId) {
                    setTimeout(() => {
                        resetForm();
                    }, 1200);
                }

            } catch (error) {
                toastr.error(error.message);
                console.error('Banking form error:', error);
            } finally {
                // Restore button state
                const submitBtn = bankingForm.querySelector('button[type="submit"]');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Load existing banking details
        async function loadBankingDetails() {
            try {
                const resp = await fetch(`{{ route('vendor.banking.get-bank-details') }}`);
                if (!resp.ok) {
                    throw new Error(`HTTP error! Status: ${resp.status}`);
                }
                const result = await resp.json();
                
                if (result) {
                    currentBankingDetailsId = result.id;
                    bankingForm.account_number.value = result.account_number || '';
                    bankingForm.bank_name.value = result.bank_name || '';
                    bankingForm.ifsc_code.value = result.ifsc_code || '';
                    bankingForm.upi_id.value = result.upi_id || '';
                    bankingForm.account_holder_name.value = result.account_holder_name || '';
                    
                    // Load payment note from data object
                    if (result.data && result.data.payment_note) {
                        bankingForm.payment_note.value = result.data.payment_note;
                    }
                }
            } catch (error) {
                console.error('Error fetching bank details:', error);
            }
        }

        async function refreshBankingDetails() {
            await loadBankingDetails();
            await loadBankingCards();
            toastr.success('Banking details refreshed');
        }

        // Function to load and display banking cards
        async function loadBankingCards() {
            try {
                const resp = await fetch(`{{ route('vendor.banking.get-bank-details') }}`);
                const result = await resp.json();
                
                const container = document.getElementById('bankingCardsContainer');
                const loadingElement = document.getElementById('loadingBankingCards');
                
                // Hide loading indicator
                if (loadingElement) {
                    loadingElement.style.display = 'none';
                }
                
                if (!result) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="feather-plus-circle text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No banking details found</p>
                            <button class="btn btn-primary" onclick="document.querySelector('[data-bs-target=\"#bankingFormCollapse\"]').click()">
                                <i class="feather-plus"></i> Add Banking Details
                            </button>
                        </div>
                    `;
                    return;
                }

                let cardsHtml = '';

                // Bank Account Card
                if (result.account_number && result.ifsc_code) {
                    const maskedAccountNumber = result.account_number.replace(/\d(?=\d{4})/g, '*');
                    cardsHtml += `
                        <div class="col-lg-4 mt-3">
                            <div class="rounded-3 p-4 position-relative banking-card bank-card"
                                style="height:200px;background-image: url('{{ asset('assets/images/card.jpg') }}'); background-size: cover;">
                                <div class="text-white fw-bolder mb-3">${result.bank_name || 'Bank Account'}</div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <img src="{{ asset('assets/images/chip.png') }}" style="height: 45px;" alt="">
                                    <div class="text-white">IFSC: ${result.ifsc_code}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="text-white" style="letter-spacing: 2px;">${maskedAccountNumber}</div>
                                        <div class="text-white fw-500">${result.account_holder_name || 'Account Holder'}</div>
                                    </div>
                                    <img src="{{ asset('assets/images/logo.png') }}" style="height: 45px;" alt="">
                                </div>
                                <div class="position-absolute end-0 m-2" style="top:-16px;">
                                    <button class="btn btn-sm btn-warning me-1 px-2" onclick="editBankingDetails(${result.id})" title="Edit Bank Details">
                                        <i class="feather-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger px-2" onclick="deleteBankAccount(${result.id})" title="Delete Bank Details">
                                        <i class="feather-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }

                // UPI Card
                if (result.upi_id) {
                    cardsHtml += `
                        <div class="col-lg-4 mt-3">
                            <div class="rounded-3 p-2 position-relative bg-primary banking-card upi-card" style="height: 200px;">
                                <div>
                                    <img src="{{ asset('assets/images/upi.jpg') }}" class="rounded-2 w-100"
                                        style="height: 120px;" alt="">
                                    <div class="card mt-3">
                                        <div class="card-header d-flex justify-content-between align-items-center p-2 rounded-2">
                                            <div class="header-title">
                                                <div class="page-header-title">
                                                    ${result.upi_id}
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-secondary px-2"
                                                onclick="copyUPIId('${result.upi_id}')" title="Copy UPI ID">
                                                <i class="feather-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute end-0 m-2" style="top:-16px;">
                                    <button class="btn btn-sm btn-warning me-1 px-2" onclick="editBankingDetails(${result.id})" title="Edit UPI Details">
                                        <i class="feather-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger px-2" onclick="deleteUPIDetails(${result.id})" title="Delete UPI Details">
                                        <i class="feather-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }

                // If no banking details at all
                if (!result.account_number && !result.upi_id) {
                    cardsHtml = `
                        <div class="col-12 text-center py-5">
                            <i class="feather-credit-card text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Banking details saved but incomplete</p>
                            <button class="btn btn-primary" onclick="document.querySelector('[data-bs-target=\"#bankingFormCollapse\"]').click()">
                                <i class="feather-edit"></i> Complete Setup
                            </button>
                        </div>
                    `;
                }

                container.innerHTML = cardsHtml;

            } catch (error) {
                console.error('Error loading banking cards:', error);
                const container = document.getElementById('bankingCardsContainer');
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="feather-alert-circle text-danger" style="font-size: 3rem;"></i>
                        <p class="text-danger mt-3">Failed to load banking details</p>
                        <button class="btn btn-outline-primary" onclick="loadBankingCards()">
                            <i class="feather-refresh"></i> Retry
                        </button>
                    </div>
                `;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadBankingDetails();
            loadBankingCards();
            
            // Add reset button functionality
            const resetBtn = document.getElementById('reset_btn');
            if (resetBtn) {
                resetBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    resetForm();
                    toastr.info('Form has been reset');
                });
            }
        });

        // Toggle form collapse icon
        document.addEventListener('DOMContentLoaded', function() {
            const collapseElement = document.getElementById('bankingFormCollapse');
            const toggleIcon = document.getElementById('toggleIcon');
            
            collapseElement.addEventListener('show.bs.collapse', function() {
                toggleIcon.className = 'feather-minus';
            });
            
            collapseElement.addEventListener('hide.bs.collapse', function() {
                toggleIcon.className = 'feather-plus';
            });
        });

        // Banking details actions
        async function editBankingDetails(id) {
            // Show form if not visible
            const collapseElement = document.getElementById('bankingFormCollapse');
            if (!collapseElement.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getInstance(collapseElement) || new bootstrap.Collapse(collapseElement);
                bsCollapse.show();
            }

            // Scroll to form
            setTimeout(() => {
                document.querySelector('.card-header[data-bs-toggle="collapse"]').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                
                // Highlight the form briefly
                const formCard = collapseElement.closest('.card');
                formCard.style.border = '2px solid #667eea';
                setTimeout(() => {
                    formCard.style.border = '';
                }, 2000);
            }, 300);

            toastr.info('Form opened for editing. Make your changes and submit.');
        }

        async function deleteBankingDetails(id) {
            if (!confirm('Are you sure you want to delete these banking details?')) {
                return;
            }

            try {
                const resp = await fetch(`{{ route('vendor.banking.delete-bank-details', '') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await resp.json();

                if (!resp.ok) {
                    throw new Error(result.message);
                }

                toastr.success(result.message);
                await refreshBankingDetails();
                bankingForm.reset();
                currentBankingDetailsId = null;

            } catch (error) {
                toastr.error(error.message);
            }
        }

        // Utility functions for card actions
        function copyUPIId(upiId) {
            navigator.clipboard.writeText(upiId).then(() => {
                toastr.success('UPI ID copied to clipboard!');
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = upiId;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                toastr.success('UPI ID copied to clipboard!');
            });
        }

        async function deleteBankAccount(id) {
            if (!confirm('Are you sure you want to delete the bank account details? This will remove only the bank account information, UPI details will remain.')) {
                return;
            }

            try {
                // Get current banking details
                const resp = await fetch(`{{ route('vendor.banking.get-bank-details') }}`);
                const currentDetails = await resp.json();

                if (!currentDetails) {
                    throw new Error('No banking details found');
                }

                // Update details to remove bank account info, keep UPI
                const updateResp = await fetch(`{{ route('vendor.banking.update-bank-details', '') }}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        account_number: null,
                        bank_name: null,
                        ifsc_code: null,
                        account_holder_name: null,
                        upi_id: currentDetails.upi_id, // Keep UPI
                        payment_note: currentDetails.data?.payment_note || null
                    })
                });

                const result = await updateResp.json();

                if (!updateResp.ok) {
                    throw new Error(result.message);
                }

                toastr.success('Bank account details removed successfully');
                await refreshBankingDetails();

            } catch (error) {
                toastr.error(error.message);
            }
        }

        async function deleteUPIDetails(id) {
            if (!confirm('Are you sure you want to delete the UPI details? This will remove only the UPI information, bank account details will remain.')) {
                return;
            }

            try {
                // Get current banking details
                const resp = await fetch(`{{ route('vendor.banking.get-bank-details') }}`);
                const currentDetails = await resp.json();

                if (!currentDetails) {
                    throw new Error('No banking details found');
                }

                // Update details to remove UPI info, keep bank account
                const updateResp = await fetch(`{{ route('vendor.banking.update-bank-details', '') }}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        account_number: currentDetails.account_number,
                        bank_name: currentDetails.bank_name,
                        ifsc_code: currentDetails.ifsc_code,
                        account_holder_name: currentDetails.account_holder_name,
                        upi_id: null, // Remove UPI
                        payment_note: currentDetails.data?.payment_note || null
                    })
                });

                const result = await updateResp.json();

                if (!updateResp.ok) {
                    throw new Error(result.message);
                }

                toastr.success('UPI details removed successfully');
                await refreshBankingDetails();

            } catch (error) {
                toastr.error(error.message);
            }
        }
    </script>

@endpush
