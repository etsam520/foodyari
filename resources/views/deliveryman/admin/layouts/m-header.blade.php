<div class="bg-primary p-3 d-lg-none"style="min-height: 60px;">
    <div class="d-flex">
        <button class="btn btn-primary align-self-start p-0 me-2 toggle hc-nav-trigger hc-nav-1" type="button"
            aria-controls="offcanvasExample">
            <i class="feather-menu fs-2"></i>
        </button>
        <div class="d-flex align-items-center justify-content-between w-100">
            @if (Auth::guard('delivery_men')->check())
                <div class="me-1">
                    <a class="text-white py-3" role="button" data-bs-toggle="modal" data-bs-target="#userMap">
                        <div class="text-break d-flex">
                            <i class="feather-map-pin me-2 text-white fs-3 icofont-size mt-1"></i>
                            <div class="location-bar" data-address="2"></div>
                        </div>
                        <div>

                        </div>
                    </a>

                </div>
            @endif
            {{-- <div class="me-0"></div> --}}
            @if(Auth::guard('delivery_men')->check())
            <div class="d-flex">

                <div class=" me-0">
                    <a class="text-white py-3" role="button">
                        <div><i
                                class="feather-bell text-primary me-2 bg-light rounded-pill p-2 fs-3 icofont-size"></i>
                        </div>
                    </a>
                </div>
                <div class=" me-1">
                    <a class="text-white  py-3" role="button">
                        <div><i
                                class="feather-user text-primary me-2 bg-light rounded-pill p-2 fs-3 icofont-size"></i>
                        </div>
                    </a>

                </div>
            </div>
            @endif
        </div>

    </div>
</div>
