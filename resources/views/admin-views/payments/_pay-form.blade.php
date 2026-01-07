<!-- Validation Errors Display -->
<div id="validation-errors" class="alert alert-danger d-none">
    <h6><i class="fas fa-exclamation-triangle"></i> Validation Errors</h6>
    <ul id="error-list" class="mb-0"></ul>
</div>

<div class="row">
    <div class="col-sm-12">
        <!-- Payment Request Summary -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-money-check-alt me-2"></i>
                    Payment Request Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-sm rounded-circle me-3">
                                <img src="{{ $paymentRequest->vendor->image ? asset('storage/vendor/'.$paymentRequest->vendor->image) : asset('assets/admin/img/160x160/img1.jpg') }}" 
                                     alt="Vendor" class="avatar-img rounded-circle">
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $paymentRequest->vendor->f_name }} {{ $paymentRequest->vendor->l_name }}</h6>
                                <small class="text-muted">{{ $paymentRequest->vendor->phone }}</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-2"><strong>Request Amount:</strong></p>
                                <h4 class="text-primary">{{ Helpers::format_currency($paymentRequest->amount) }}</h4>
                            </div>
                            <div class="col-6">
                                <p class="mb-2"><strong>Wallet Balance:</strong></p>
                                <h5 class="text-success">{{ Helpers::format_currency($vendorWallet->balance) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Requested Payment Method:</strong>
                            <div class="mt-2">
                                <span class="badge badge-lg bg-info text-white">
                                    <i class="fas fa-{{ 
                                        $paymentRequest->payment_method == 'bank_transfer' ? 'university' : 
                                        ($paymentRequest->payment_method == 'upi' ? 'mobile-alt' : 
                                        ($paymentRequest->payment_method == 'cash' ? 'money-bill-wave' : 
                                        ($paymentRequest->payment_method == 'cheque' ? 'file-invoice' : 'credit-card')))
                                    }} me-2"></i>
                                    {{ ucwords(str_replace('_', ' ', $paymentRequest->payment_method)) }}
                                </span>
                            </div>
                        </div>
                        @if($paymentRequest->payments_note)
                        <div class="mb-3">
                            <strong>Request Note:</strong>
                            <p class="text-muted mt-1">{{ $paymentRequest->payments_note }}</p>
                        </div>
                        @endif
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge badge-lg bg-{{ 
                                $paymentRequest->payment_status == 'pending' ? 'warning' : 
                                ($paymentRequest->payment_status == 'approved' ? 'info' : 
                                ($paymentRequest->payment_status == 'processing' ? 'primary' : 
                                ($paymentRequest->payment_status == 'completed' ? 'success' : 'danger')))
                            }} text-white">
                                {{ ucfirst($paymentRequest->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Processing Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Process Payment
                </h5>
            </div>
            <div class="card-body">

                <form action="{{ route('admin.payments.pay-form-request') }}" method="post" id="paymentForm" enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" name="pay_id" value="{{$paymentRequest->id}}">
                    
                    <!-- Vendor Banking Details Display -->
                    @if($paymentRequest->vendor->banking_details)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-success">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-university text-success me-2"></i>
                                        Vendor Banking Details
                                    </h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-exchange-alt me-1"></i> Switch Method
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($paymentRequest->vendor->banking_details->account_number)
                                            <li><a class="dropdown-item method-switch" href="#" data-method="bank_transfer">
                                                <i class="fas fa-university me-2"></i> Bank Transfer
                                            </a></li>
                                            @endif
                                            @if($paymentRequest->vendor->banking_details->upi_id)
                                            <li><a class="dropdown-item method-switch" href="#" data-method="upi">
                                                <i class="fas fa-mobile-alt me-2"></i> UPI
                                            </a></li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item method-switch" href="#" data-method="cash">
                                                <i class="fas fa-money-bill-wave me-2"></i> Cash
                                            </a></li>
                                            <li><a class="dropdown-item method-switch" href="#" data-method="cheque">
                                                <i class="fas fa-file-invoice me-2"></i> Cheque
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="banking-details-display">
                                        <!-- Bank Transfer Details -->
                                        <div id="bank-details" class="banking-method" style="display: {{ $paymentRequest->payment_method == 'bank_transfer' ? 'block' : 'none' }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Account Holder Name</label>
                                                        <p class="fw-bold">{{ $paymentRequest->vendor->banking_details->account_holder_name ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Account Number</label>
                                                        <p class="fw-bold font-monospace">{{ $paymentRequest->vendor->banking_details->account_number ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Bank Name</label>
                                                        <p class="fw-bold">{{ $paymentRequest->vendor->banking_details->bank_name ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">IFSC Code</label>
                                                        <p class="fw-bold font-monospace">{{ $paymentRequest->vendor->banking_details->ifsc_code ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Branch</label>
                                                        <p class="fw-bold">{{ $paymentRequest->vendor->banking_details->branch ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Account Type</label>
                                                        <p class="fw-bold">{{ ucfirst($paymentRequest->vendor->banking_details->account_type ?? 'N/A') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- UPI Details -->
                                        <div id="upi-details" class="banking-method" style="display: {{ $paymentRequest->payment_method == 'upi' ? 'block' : 'none' }}">
                                            <div class="text-center">
                                                <div class="mb-3">
                                                    <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                                                    <h5>UPI Payment</h5>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">UPI ID</label>
                                                    <p class="fw-bold font-monospace h5">{{ $paymentRequest->vendor->banking_details->upi_id ?? 'N/A' }}</p>
                                                </div>
                                                @if($paymentRequest->vendor->banking_details->upi_qr_code)
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">QR Code</label>
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/'.$paymentRequest->vendor->banking_details->upi_qr_code) }}" 
                                                             alt="UPI QR Code" class="img-fluid" style="max-width: 200px;">
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Cash/Cheque/Other Methods -->
                                        <div id="other-details" class="banking-method" style="display: {{ !in_array($paymentRequest->payment_method, ['bank_transfer', 'upi']) ? 'block' : 'none' }}">
                                            <div class="text-center">
                                                <div class="mb-3">
                                                    <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
                                                    <h5 id="other-method-title">{{ ucwords(str_replace('_', ' ', $paymentRequest->payment_method)) }} Payment</h5>
                                                    <p class="text-muted">Manual payment processing required</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Payment Processing Form -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="amount">
                                            <i class="fas fa-dollar-sign me-1"></i> Payment Amount <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" name="amount" id="amount" value="{{$paymentRequest->amount}}" 
                                                   class="form-control" min="0" step="0.01" max="{{$paymentRequest->amount}}" required
                                                   placeholder="Enter payment amount">
                                        </div>
                                        <small class="text-muted">Maximum: {{ Helpers::format_currency($paymentRequest->amount) }}</small>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="payment_method">
                                            <i class="fas fa-credit-card me-1"></i> Payment Method <span class="text-danger">*</span>
                                        </label>
                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                            <option value="">Select Payment Method</option>
                                            @if($paymentRequest->vendor->banking_details && $paymentRequest->vendor->banking_details->account_number)
                                            <option value="bank_transfer" {{ $paymentRequest->payment_method == 'bank_transfer' ? 'selected' : '' }}>
                                                🏦 Bank Transfer
                                            </option>
                                            @endif
                                            @if($paymentRequest->vendor->banking_details && $paymentRequest->vendor->banking_details->upi_id)
                                            <option value="upi" {{ $paymentRequest->payment_method == 'upi' ? 'selected' : '' }}>
                                                📱 UPI
                                            </option>
                                            @endif
                                            <option value="cash" {{ $paymentRequest->payment_method == 'cash' ? 'selected' : '' }}>
                                                💵 Cash
                                            </option>
                                            <option value="cheque" {{ $paymentRequest->payment_method == 'cheque' ? 'selected' : '' }}>
                                                📄 Cheque
                                            </option>
                                            <option value="digital_wallet" {{ $paymentRequest->payment_method == 'digital_wallet' ? 'selected' : '' }}>
                                                📲 Digital Wallet
                                            </option>
                                            <option value="online_banking" {{ $paymentRequest->payment_method == 'online_banking' ? 'selected' : '' }}>
                                                🌐 Online Banking
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="payment_status">
                                            <i class="fas fa-info-circle me-1"></i> Payment Status <span class="text-danger">*</span>
                                        </label>
                                        <select name="payment_status" id="payment_status" class="form-control" required>
                                            <option value="pending" {{ $paymentRequest->payment_status == 'pending' ? 'selected' : '' }}>
                                                ⏳ Pending
                                            </option>
                                            <option value="approved" {{ $paymentRequest->payment_status == 'approved' ? 'selected' : '' }}>
                                                ✅ Approved
                                            </option>
                                            <option value="processing" {{ $paymentRequest->payment_status == 'processing' ? 'selected' : '' }}>
                                                🔄 Processing
                                            </option>
                                            <option value="completed" {{ $paymentRequest->payment_status == 'completed' ? 'selected' : '' }}>
                                                ✅ Completed
                                            </option>
                                            <option value="rejected" {{ $paymentRequest->payment_status == 'rejected' ? 'selected' : '' }}>
                                                ❌ Rejected
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="transaction_id">
                                            <i class="fas fa-hashtag me-1"></i> Transaction ID
                                        </label>
                                        <input type="text" name="transaction_id" id="transaction_id" 
                                               class="form-control" placeholder="Enter transaction ID (optional)">
                                        <small class="text-muted">Transaction reference from bank/UPI</small>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="reference_number">
                                            <i class="fas fa-barcode me-1"></i> Reference Number
                                        </label>
                                        <input type="text" name="reference_number" id="reference_number" 
                                               class="form-control" placeholder="Enter reference number (optional)">
                                        <small class="text-muted">Internal reference or cheque number</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="attachement">
                                            <i class="fas fa-paperclip me-1"></i> Attachment
                                        </label>
                                        <input type="file" class="form-control" name="attachement" id="attachement" 
                                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                        <small class="text-muted">Upload payment receipt or proof (optional)</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="remarks">
                                            <i class="fas fa-sticky-note me-1"></i> Processing Notes
                                        </label>
                                        <textarea name="remarks" id="remarks" class="form-control" rows="4" 
                                                 placeholder="Add any processing notes or remarks (optional)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="reset" id="reset_btn" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-2"></i>Reset Form
                                </button>
                                <div>
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submit-btn">
                                        <i class="fas fa-save me-2"></i>Process Payment
                                    </button>
                                </div>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Payment method switching functionality
    $('.method-switch').on('click', function(e) {
        e.preventDefault();
        const selectedMethod = $(this).data('method');
        
        // Update the select dropdown
        $('#payment_method').val(selectedMethod).trigger('change');
        
        // Show appropriate banking details
        switchBankingDisplay(selectedMethod);
        
        // Update dropdown button text
        const methodText = $(this).text();
        $(this).closest('.dropdown').find('.dropdown-toggle').html(
            '<i class="fas fa-exchange-alt me-1"></i> ' + methodText
        );
    });

    // Payment method dropdown change
    $('#payment_method').on('change', function() {
        const selectedMethod = $(this).val();
        switchBankingDisplay(selectedMethod);
    });

    function switchBankingDisplay(method) {
        // Hide all banking method displays
        $('.banking-method').hide();
        
        // Show appropriate display based on method
        if (method === 'bank_transfer') {
            $('#bank-details').show();
        } else if (method === 'upi') {
            $('#upi-details').show();
        } else {
            $('#other-details').show();
            $('#other-method-title').text(method.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) + ' Payment');
        }
    }

    // Amount validation
    $('#amount').on('input', function() {
        const amount = parseFloat($(this).val());
        const maxAmount = parseFloat($(this).attr('max'));
        
        if (amount > maxAmount) {
            $(this).addClass('is-invalid');
            showValidationError('amount', 'Amount cannot exceed the requested amount of ₹' + maxAmount);
        } else {
            $(this).removeClass('is-invalid');
            hideValidationError('amount');
        }
    });

    // Form submission with enhanced validation
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous validation errors
        clearValidationErrors();
        
        // Get form data
        const formData = new FormData(this);
        
        // Show loading state
        const submitBtn = $('#submit-btn');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...').prop('disabled', true);
        
        // Submit form via AJAX
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Payment processed successfully');
                    
                    // Close modal and refresh page
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('payModal'));
                        if (modal) modal.hide();
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Payment processing failed');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                
                if (xhr.status === 422 && response.errors) {
                    // Display validation errors
                    displayValidationErrors(response.errors);
                    toastr.error('Please fix the validation errors');
                } else {
                    const errorMessage = response?.message || 'An error occurred while processing payment';
                    toastr.error(errorMessage);
                }
            },
            complete: function() {
                // Reset button state
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Reset form functionality
    $('#reset_btn').on('click', function() {
        clearValidationErrors();
        // Reset to original method display
        switchBankingDisplay($('#payment_method option:selected').val());
    });

    // Validation error display functions
    function displayValidationErrors(errors) {
        const errorList = $('#error-list');
        const errorContainer = $('#validation-errors');
        
        errorList.empty();
        
        $.each(errors, function(field, messages) {
            // Add to error list
            $.each(messages, function(index, message) {
                errorList.append('<li>' + message + '</li>');
            });
            
            // Highlight field
            const fieldElement = $('[name="' + field + '"]');
            fieldElement.addClass('is-invalid');
            
            // Add error message below field
            const errorMessage = '<div class="invalid-feedback d-block field-error" data-field="' + field + '">' + messages[0] + '</div>';
            fieldElement.closest('.form-group').append(errorMessage);
        });
        
        errorContainer.removeClass('d-none');
        
        // Scroll to first error
        $('html, body').animate({
            scrollTop: errorContainer.offset().top - 100
        }, 500);
    }

    function showValidationError(field, message) {
        const fieldElement = $('[name="' + field + '"]');
        fieldElement.addClass('is-invalid');
        
        // Remove existing error message for this field
        $('.field-error[data-field="' + field + '"]').remove();
        
        // Add new error message
        const errorMessage = '<div class="invalid-feedback d-block field-error" data-field="' + field + '">' + message + '</div>';
        fieldElement.closest('.form-group').append(errorMessage);
    }

    function hideValidationError(field) {
        const fieldElement = $('[name="' + field + '"]');
        fieldElement.removeClass('is-invalid');
        $('.field-error[data-field="' + field + '"]').remove();
    }

    function clearValidationErrors() {
        $('#validation-errors').addClass('d-none');
        $('#error-list').empty();
        $('.is-invalid').removeClass('is-invalid');
        $('.field-error').remove();
    }

    // Initialize display based on current method
    switchBankingDisplay($('#payment_method').val());
});
</script>