<div class="pen-title mx-auto mb-5">
    <img src="{{ asset('assets/images/icons/foodYariLogo.png') }}" alt="logo" class="logo-desc">
</div>
<h4 class="text-center">Welcome back! Log in to your account</h4>

<form method="POST"   id="reset_password">
    @csrf
    <input type="hidden" name="fcm_token"  id="myFCM_token">
    <div class="input-group">
        <span class="input-group-text" style="height: 45px;">
            <i class="fas fa-phone icon-layer"></i>
        </span>
        <input type="text" name="phone"  value="{{$deliveryMan->phone}}" class="form-control mb-1" maxlength="13" placeholder="Phone" required="">
    </div>
    {{-- <div class="text-end mb-3"><a href="" class="text-warning" style="text-decoration: none">Change Phone</a></div> --}}
    <div class="input-group">
        <input type="number" name="otp" value="  class="form-control mb-1" maxlength="6" placeholder="# # # # # #" required="" style="text-align: center;
            font-size: 20px;
            letter-spacing: 8px;">
    </div>
    <div class="input-group">
        <input type="password" name="password" class="form-control mb-1" placeholder="Password" required="">
    </div>
    <div class="input-group">
        <input type="password" name="password_confirmation" class="form-control mb-1" placeholder="Confirm Password" required="">
    </div>
    <div class="text-end mb-3"><a href="javascript:void(0)" onclick="resendOtp(this.getAttribute('url'))"  class="text-warning" id="resend-otp" url="{{route('deliveryman.resend-otp', ['phone'=> $deliveryMan->phone])}}" style="text-decoration: none">Resend OTP</a></div>
    <button type="submit">Continue</button>
</form>
