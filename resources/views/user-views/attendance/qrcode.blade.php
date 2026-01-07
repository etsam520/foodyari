@extends('user-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
   
    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                   <h4 class="card-title">Customer QR Code</h4>
                </div>
             </div>
            <div class="card-body ">
                <div id="qrcode"></div>
                <span class="text-center" data-otp=""></span>
            </div>
        </div>
                
        </div>
    </div>
  
</div>
@endsection

@push('javascript')
<script src="{{asset('assets/vendor/qrcode/qrcode.min.js')}}"></script>
<script>
   document.addEventListener('DOMContentLoaded', async () => {
        try {
            const resp = await fetch("{{ route('user.mess.my-diet-qr-image') }}");
            const result = await resp.json();
            if (result.error) {
                throw new Error(result.error);
            }
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                text: result.encrypted_code,
                width: 128,
                height: 128,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
            document.querySelector('[data-otp]').textContent = "OTP : " +result.otp;
        } catch (error) {
            toastr.error(error.message);
        }
    });

</script>
    
@endpush


