@php
    $banner = App\Models\Banner::latest()->limit(10)->get();
@endphp
<div class="popular-slider">
    @foreach ($banner as  $banner)
        
    <div class="cat-item px-1 py-3" >
        <a class="d-block text-center shadow-sm" href="javascript:void(0)">
            <img alt="#" src="{{ asset("banner/$banner->image") }}" class="img-fluid rounded">
        </a>
    </div>
    @endforeach
    <div class="cat-item px-1 py-3">
        <a class="d-block text-center shadow-sm" href="trending.html">
            <img alt="#" src="{{ asset('assets/user/img/banner-3.png') }}" class="img-fluid rounded">
        </a>
    </div>
</div> 