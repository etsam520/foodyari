@php
    $user_to_remove = Cache::get('user_to_remove', null);
    // var_dump($user_to_remove);
@endphp

@extends('user-views.restaurant.layouts.main')
@section('containt')
<div class="osahan-home-page" id="content-wrapper">
    <div class="container position-relative">
        <div class="py-5">
            <div class="col-lg-4 col-md-6 col-sm-8 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light text-center border-bottom-0 p-3">
                        <h3 class="text-dark font-25 fw-bolder">Delete your Account</h3>
                        <p class="text-50 mt-2 mb-0">Verify to continue</p>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form id="user_check_form"  method="POST" action="{{route('user.auth.verify-account')}}">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="user_phone" class="text-dark small mb-0">Phone</label>
                                <input type="text" maxlength="13" placeholder="Enter Phone" class="form-control" {{$user_to_remove != null ? 'readonly': null}}
                                 name="phone" id="user_phone" autofocus="" value="{{$user_to_remove != null ? $user_to_remove['phone']: null}}">

                            </div>
                            @if(!empty($user_to_remove))
                            <div class="form-group mb-2">
                                <label for="user_otp" class="text-dark small mb-0">OTP</label>
                                <input type="text" maxlength="13" placeholder="Enter OTP" class="form-control"
                                 name="otp" id="user_otp" autofocus="">
                                 <div id="countdown" ></div>
                                <p class="mb-0 text-end mt-3"><button type="button" class="btn badge-two py-0 d-none" id="user_resend_otp">Resend OTP</button></p>
                            </div>
                            @endif
                            <div id="user_verfication">
                                <div class="form-group mb-2 text-center">
                                    <button type="submit" id="" class="btn btn-primary px-4 border-0">Next</button>
                                </div>
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
@if(!empty($user_to_remove))
<script src="{{asset('assets/vendor/timer-countdown/src/jquery.countdown360.js')}}"></script>


Storage.getItem('resend_otp_time')) {
        var timeset = Math.floor(new Date().getTime() / 1000) + 120;
        localStorage.setItem('resend_otp_time', timeset);
    }

    var remainingTime = localStorage.getItem('resend_otp_time') - Math.floor(new Date().getTime() / 1000);

    var countdown = $("#countdown").countdown360({
        radius      : 50,
        seconds     : remainingTime > 0 ? remainingTime : 120,
        fontColor   : '#FFFFFF',
        autostart   : false,
        onComplete  : function () {
            $("#countdown").remove();
            localStorage.removeItem('resend_otp_time');
            $("#user_resend_otp").removeClass('d-none');
        }
    });

    countdown.start();
 </script>
 @endif
@endpush
