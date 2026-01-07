
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
                            {{-- @dd($name) --}}
                            <h6 class="mb-1 fw-bold">{{Str::ucfirst($name->value)}} <i class="feather-check-circle text-success"></i></h6>
                            <p class="text-muted m-0 small">{{$email->value}}</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        {{--  --}}
        <div class="col-md-12">
            <div class="rounded shadow-sm">
                <div class="osahan-privacy bg-white rounded shadow-sm p-4">
                   {!!$data['value'] !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
 <script>
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
