@extends('user-views.restaurant.layouts.main')

@push('css')
<style>
    .restaurant-registration-container {
        background: #f8dcb4;
        /* background: linear-gradient(135deg, #ff810a, #ff9500, #ffb347); */
        min-height: 100vh;
        padding: 20px 0;
    }

    .registration-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        /* border: 1px solid rgba(255, 255, 255, 0.2); */
        overflow: hidden;
    }

    .registration-header {
        background: linear-gradient(135deg, #ff810a, #ff9500);
        color: white;
        padding: 30px;
        /* text-align: center; */
        position: relative;
    }

    .registration-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="20" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="30" cy="80" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="70" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    }

    .registration-header h2 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .registration-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .registration-form {
        padding: 40px;
    }

    .form-section {
        margin-bottom: 35px;
    }

    .section-title {
        color: #ff810a;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ffe4d1;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background: #ff810a;
    }

    .form-group label {
        color: #333;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-control {
        border: 2px solid #e9ecef;
        /* border-radius: 12px; */
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #fff;
    }

    .form-control:focus {
        border-color: #ff810a;
        box-shadow: 0 0 0 0.2rem rgba(255, 129, 10, 0.15);
        outline: none;
    }

    .document-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 18px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .document-section:hover {
        background: #f1f3f4;
        border-color: #ff810a;
    }

    .document-title {
        color: #ff810a;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 12px;
        padding: 8px 0px;
        /* background: white; */
        /* border-radius: 6px; */
        /* border: 1px solid #ffe4d1; */
    }

    .submit-section {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .btn-submit {
        background: linear-gradient(135deg, #ff810a, #ff9500);
        color: white;
        border: none;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(255, 129, 10, 0.3);
        min-width: 200px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(255, 129, 10, 0.4);
        background: linear-gradient(135deg, #e6730a, #e68500);
        color: white;
    }

    .alert-danger {
        border-radius: 12px;
        border: none;
        background: rgba(220, 53, 69, 0.1);
        border-left: 4px solid #dc3545;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .required-asterisk {
        color: #dc3545;
        margin-left: 3px;
    }

    @media (max-width: 768px) {
        .restaurant-registration-container {
            padding: 0;
            background: none;
        }
        .registration-header {
            padding: 25px 20px;
        }
        .registration-header h2 {
            font-size: 1.5rem;
        }
        .registration-form {
            padding: 25px 20px;
        }
        .document-section {
            padding: 15px 12px;
        }
        .registration-card {
            border: none;
            border-radius: 0;
        }
        .form-section {
            margin-bottom: 20px;
        }
    }

    /* Hide reCAPTCHA badge */
    .grecaptcha-badge {
        visibility: hidden;
    }
</style>
@endpush

@section('containt')
    <div class="restaurant-registration-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10 px-0">
                    <div class="registration-card">
                        <div class="registration-header d-flex">
                            <h2><i class="fas fa-store me-2 mt-2"></i></h2>
                            <div>
                                <h2 class="mb-0">Join us as Restaurant</h2>
                                <small class="text-white">Partner with us and grow your restaurant business</p>
                            </div>
                        </div>

                        <div class="registration-form">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{route('join-as.restaurant-save')}}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Restaurant Basic Information -->
                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-utensils me-2"></i>Restaurant Information</h3>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Restaurant Name<span class="required-asterisk">*</span></label>
                                                <input type="text" class="form-control" name="restaurant_name" required
                                                       value="{{old('restaurant_name')}}" placeholder="Enter restaurant name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Phone Number<span class="required-asterisk">*</span></label>
                                                <input type="number" class="form-control" name="restaurant_phone" required
                                                       value="{{old('restaurant_phone')}}" placeholder="Enter phone number">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Email Address<span class="required-asterisk">*</span></label>
                                                <input type="email" class="form-control" name="restaurant_email" required
                                                       value="{{old('restaurant_email')}}" placeholder="Enter email address">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Owner Name<span class="required-asterisk">*</span></label>
                                                <input type="text" class="form-control" name="restaurant_owner_name" required
                                                       value="{{old('restaurant_owner_name')}}" placeholder="Enter owner name">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label">Full Address<span class="required-asterisk">*</span></label>
                                                <input type="text" class="form-control" name="restaurant_address" required
                                                       value="{{old('restaurant_address')}}" placeholder="Enter complete address">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Documents Section -->
                                <div class="form-section">
                                    <h3 class="section-title"><i class="fas fa-file-alt me-2"></i>Required Documents</h3>

                                    @foreach ($documents as $document)
                                    <div class="document-section">
                                        <h4 class="document-title">
                                            <i class="fas fa-file-text me-2"></i>{{ $document->name }}
                                        </h4>

                                        @if ($document->is_text)
                                        <div class="form-group mb-2">
                                            <label class="form-label">
                                                {{ $document->name }} ID/Number
                                                @if ($document->is_text_required)
                                                <span class="required-asterisk">*</span>
                                                @endif
                                            </label>
                                            <input type="text" class="form-control"
                                                   data-type="{{ strtolower($document->name) }}"
                                                   name="{{ $document->text_input_name }}"
                                                   value="{{ old($document->text_input_name) ?? null }}"
                                                   placeholder="Enter {{ $document->name }} ID/Number"
                                                   @if ($document->is_text_required) required @endif>
                                        </div>
                                        @endif

                                        @if ($document->is_media)
                                        <div class="form-group mb-2">
                                            <label class="form-label">
                                                Upload {{ $document->name }}
                                                @if ($document->is_media_required)
                                                <span class="required-asterisk">*</span>
                                                @endif
                                            </label>
                                            <div class="input-group" role="button" data-toggle="FileUploader"
                                                 data-type="image" id="{{ $document->media_input_name }}"
                                                 data-preview="#{{ $document->media_input_name }}_preview">
                                                <input type="file" class="form-control" name="{{ $document->media_input_name }}"
                                                       accept="image/*,.pdf" @if ($document->is_media_required) required @endif>
                                            </div>
                                            <div id="{{ $document->media_input_name }}_preview"
                                                 data-parent="#{{ $document->media_input_name }}"></div>
                                            <small class="text-muted">Accepted formats: JPG, PNG, PDF (Max 5MB)</small>
                                        </div>
                                        @endif

                                        @if ($document->has_expiry_date)
                                        <div class="form-group mb-1">
                                            <label class="form-label">Expiry Date</label>
                                            <input type="date" class="form-control"
                                                   name="{{ $document->expire_date_input_name }}"
                                                   value="{{ old($document->expire_date_input_name) ?? null }}">
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>

                                <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" style="visibility:hidden;">

                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-paper-plane me-2"></i>Submit
                                    </button>
                                    <p class="text-muted mt-3 mb-0">
                                        <small><i class="fas fa-info-circle me-1"></i>Your application will be reviewed within 2-3 business days</small>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
<script src="https://www.google.com/recaptcha/api.js?render=6Le6_pcrAAAAAF9-gOTHOZO_hNVCTgEV7wpzSuxE"></script>

    <script>
  grecaptcha.ready(function() {
    grecaptcha.execute('6Le6_pcrAAAAAF9-gOTHOZO_hNVCTgEV7wpzSuxE', {action: 'submit'}).then(function(token) {
      document.getElementById('g-recaptcha-response').value = token;
    });
  });
</script>
@endpush
