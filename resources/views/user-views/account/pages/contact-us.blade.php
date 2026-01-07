
@extends('user-views.restaurant.layouts.main')
@section('containt')

<div class="container position-relative">
    <div class="py-5 osahan-profile row">
        <div class="col-md-12 mb-3">
            <div class="bg-white rounded shadow-sm sticky_sidebar overflow-hidden">
                <a href="{{route('user.dashboard')}}" class="">
                    <div class="d-flex align-items-center p-3">
                        <label for="user-profile" class="left me-3" type="button">
                            <img alt="user profile" id="user-profile-image"  src="{{asset('assets/images/icons/foodYariLogo.png')}}" class="rounded-circle" style="width: 50px;">
                        </label>
                        <div class="right">
                            @php($name = \App\Models\BusinessSetting::where('key', 'business_name')->first())
                            @php($email = \App\Models\BusinessSetting::where('key', 'email_address')->first())
                            <h6 class="mb-1 fw-bold">{{Str::ucfirst($name->value)}} <i class="feather-check-circle text-success"></i></h6>
                            <p class="text-muted m-0 small">{{$email->value}}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- contact us --}}
        <div class="col-md-12 mb-3">
            <div class="rounded shadow-sm">
                <div class="osahan-cart-item-profile bg-white rounded shadow-sm p-4">
                    <div class="flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <i class="feather-phone bg-primary text-white p-2 rounded-circle me-3"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Get in Touch</h5>
                                <p class="text-muted mb-0">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                            </div>
                        </div>

                        <!-- Success/Error Messages -->
                        <div id="alert-container"></div>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="feather-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="feather-alert-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form id="contactUsForm" action="{{ route('user.contact-us.submit') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="small fw-bold pb-1">
                                            Your Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name"
                                               placeholder="Enter your full name"
                                               value="{{ old('name', auth('customer')->user()->f_name ?? '') }} {{ old('l_name', auth('customer')->user()->l_name ?? '') }}"
                                               required>
                                        <div class="invalid-feedback" id="name-error"></div>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="small fw-bold pb-1">
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email"
                                               placeholder="Enter your email address"
                                               value="{{ old('email', auth('customer')->user()->email ?? '') }}"
                                               required>
                                        <div class="invalid-feedback" id="email-error"></div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="phone" class="small fw-bold pb-1">
                                            Phone Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone"
                                               placeholder="Enter your phone number"
                                               value="{{ old('phone', auth('customer')->user()->phone ?? '') }}"
                                               required>
                                        <div class="invalid-feedback" id="phone-error"></div>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="subject" class="small fw-bold pb-1">
                                            Subject <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control @error('subject') is-invalid @enderror" 
                                                id="subject" 
                                                name="subject" 
                                                required>
                                            <option value="">Select a topic</option>
                                            <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                            <option value="Order Issue" {{ old('subject') == 'Order Issue' ? 'selected' : '' }}>Order Issue</option>
                                            <option value="Payment Problem" {{ old('subject') == 'Payment Problem' ? 'selected' : '' }}>Payment Problem</option>
                                            <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                            <option value="Account Issue" {{ old('subject') == 'Account Issue' ? 'selected' : '' }}>Account Issue</option>
                                            <option value="Feedback & Suggestions" {{ old('subject') == 'Feedback & Suggestions' ? 'selected' : '' }}>Feedback & Suggestions</option>
                                            <option value="Restaurant Partnership" {{ old('subject') == 'Restaurant Partnership' ? 'selected' : '' }}>Restaurant Partnership</option>
                                            <option value="Delivery Partner" {{ old('subject') == 'Delivery Partner' ? 'selected' : '' }}>Delivery Partner</option>
                                            <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        <div class="invalid-feedback" id="subject-error"></div>
                                        @error('subject')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="message" class="small fw-bold pb-1">
                                    How can we help you? <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" 
                                          name="message"
                                          placeholder="Please describe your inquiry in detail..."
                                          rows="5" 
                                          maxlength="5000"
                                          required>{{ old('message') }}</textarea>
                                <div class="form-text">
                                    <span id="message-count">0</span>/5000 characters
                                </div>
                                <div class="invalid-feedback" id="message-error"></div>
                                @error('message')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3" id="submitBtn">
                                <span id="submit-text">
                                    <i class="feather-send me-2"></i>Send Message
                                </span>
                                <span id="submit-loading" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Sending...
                                </span>
                            </button>
                        </form>

                        <!-- Contact Information -->
                        <div class="row mt-5 pt-4 border-top">
                            <div class="col-md-4 text-center mb-3">
                                <div class="contact-info-item">
                                    <i class="feather-phone bg-primary text-white p-3 rounded-circle mb-3" style="font-size: 1.2rem;"></i>
                                    <h6 class="fw-bold">Phone</h6>
                                    @php($phone = \App\Models\BusinessSetting::where('key', 'phone')->first())
                                    <p class="text-muted">{{ $phone ? $phone->value : '+1 234 567 8900' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="contact-info-item">
                                    <i class="feather-mail bg-success text-white p-3 rounded-circle mb-3" style="font-size: 1.2rem;"></i>
                                    <h6 class="fw-bold">Email</h6>
                                    <p class="text-muted">{{ $email ? $email->value : 'contact@foodyari.com' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="contact-info-item">
                                    <i class="feather-clock bg-warning text-white p-3 rounded-circle mb-3" style="font-size: 1.2rem;"></i>
                                    <h6 class="fw-bold">Response Time</h6>
                                    <p class="text-muted">Within 24 hours</p>
                                </div>
                            </div>
                        </div>

                        @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
                        @php($default_location = isset($default_location) ? json_decode($default_location->value, true) : 0)
                        <!-- Map -->
                        <div class="mapouter pt-4">
                            <h6 class="fw-bold mb-3">Find Us</h6>
                            <div class="gmap_canvas" style="height: 400px; border-radius: 10px; overflow: hidden;">
                                <iframe class="w-100 h-100 border-0"
                                        id="gmap_canvas"
                                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&q={{ $default_location['lat'] ?? 0 }},{{ $default_location['lng'] ?? 0 }}"
                                        allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script>
$(document).ready(function() {
    // Character counter for message textarea
    $('#message').on('input', function() {
        const length = $(this).val().length;
        $('#message-count').text(length);
        
        if (length > 4500) {
            $('#message-count').addClass('text-warning');
        } else if (length > 4800) {
            $('#message-count').addClass('text-danger').removeClass('text-warning');
        } else {
            $('#message-count').removeClass('text-warning text-danger');
        }
    });

    // Initialize character count
    $('#message').trigger('input');

    // Form submission with AJAX
    $('#contactUsForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#submitBtn');
        const submitText = $('#submit-text');  
        const submitLoading = $('#submit-loading');
        
        // Clear previous errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#alert-container').empty();
        
        // Show loading state
        submitBtn.prop('disabled', true);
        submitText.addClass('d-none');
        submitLoading.removeClass('d-none');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Show success message
                    $('#alert-container').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="feather-check-circle me-2"></i>${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    
                    // Reset form
                    form[0].reset();
                    $('#message').trigger('input'); // Update character count
                    
                    // Scroll to top to show message
                    $('html, body').animate({
                        scrollTop: $('#alert-container').offset().top - 100
                    }, 500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    
                    $.each(errors, function(field, messages) {
                        const input = $(`#${field}`);
                        const errorDiv = $(`#${field}-error`);
                        
                        input.addClass('is-invalid');
                        errorDiv.text(messages[0]);
                    });
                    
                    // Focus on first error field
                    $('.is-invalid:first').focus();
                } else {
                    // General error
                    const message = xhr.responseJSON?.message || 'Something went wrong. Please try again.';
                    $('#alert-container').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="feather-alert-circle me-2"></i>${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    
                    // Scroll to show error
                    $('html, body').animate({
                        scrollTop: $('#alert-container').offset().top - 100
                    }, 500);
                }
            },
            complete: function() {
                // Hide loading state
                submitBtn.prop('disabled', false);
                submitText.removeClass('d-none');
                submitLoading.addClass('d-none');
            }
        });
    });

    // Real-time validation
    $('.form-control').on('blur', function() {
        const field = $(this);
        const fieldName = field.attr('name');
        const value = field.val().trim();
        
        // Clear previous error
        field.removeClass('is-invalid');
        $(`#${fieldName}-error`).text('');
        
        // Basic validation
        if (field.prop('required') && !value) {
            field.addClass('is-invalid');
            $(`#${fieldName}-error`).text(`${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)} is required.`);
            return;
        }
        
        // Email validation
        if (fieldName === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.addClass('is-invalid');
                $(`#${fieldName}-error`).text('Please enter a valid email address.');
            }
        }
        
        // Phone validation
        if (fieldName === 'phone' && value) {
            const phoneRegex = /^[\+]?[1-9][\d\s\-\(\)]{7,15}$/;
            if (!phoneRegex.test(value)) {
                field.addClass('is-invalid');
                $(`#${fieldName}-error`).text('Please enter a valid phone number.');
            }
        }
    });

    // Remove validation errors on input
    $('.form-control').on('input', function() {
        const field = $(this);
        const fieldName = field.attr('name');
        
        if (field.hasClass('is-invalid') && field.val().trim()) {
            field.removeClass('is-invalid');
            $(`#${fieldName}-error`).text('');
        }
    });
});

function readImage(input,selector) {
    try{
        const reader = new FileReader();
        reader.onload = function (e) {
            const imgSrc = e.target.result;
            document.querySelector(selector).src = imgSrc;
        };
        reader.readAsDataURL(input.files[0]);
    }catch(error){
        console.error(error);
    }
}
</script>
@endpush
