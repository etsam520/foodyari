@extends('user-views.restaurant.layouts.main')
@section('containt')
<div
        style="">
        <div class="container position-relative" >
            <div class="py-5 osahan-profile row d-flex justify-content-center" style="backdrop-filter: blur(6px);">
                <div class="col-md-4 mb-3">
                    <div class="rounded-5 shadow-sm p-5 text-center bg-white" style="border-top: 16px solid #ff810a;">
                        <h4 class="mb-4  pb-3 fw-bolder">Restaurant App</h4>
                        <button onclick="restaurantRedirectToStore()" class="btn btn-primary"><i class="fas fa-download me-2"></i>Download</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
<script>

    function restaurantRedirectToStore() {
        var userAgent = navigator.userAgent.toLowerCase();
        if (/android/i.test(userAgent)) {
            window.open("https://play.google.com/store/apps/details?id=com.foodyari.store&pcampaignid=web_share", "_blank");
        } else if (/iphone|ipod/i.test(userAgent)) {
            window.open("https://apps.apple.com/in/app/foodyari/id6661029723?platform=iphone", "_blank");
        } else if (/windows/i.test(userAgent)) {
            window.open("https://www.foodyari.com", "_blank");
        } else {
            // Default behavior for other devices (you can change this URL if needed)
            window.open("https://www.foodyari.com", "_blank");
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        adminRedirectToStore();
    });

</script>


@endpush
