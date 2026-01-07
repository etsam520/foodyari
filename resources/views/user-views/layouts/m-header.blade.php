@php
    $userLocations = [];
    if (auth('customer')->check()) {
        $userLocations = auth('customer')->user()->customerAddress()->orderByDesc('is_default')->orderByDesc('id')->get();
        $default_address = [
            'type' => $userLocations[0]?->type ?? 'Select Address',
            'address' => $userLocations[0]?->address ?? '',
        ];
    }else{
        $userLocation = Helpers::getGuestSession('guest_location');
        if($userLocation){
            $default_address['type'] = $userLocation['type'];
            $default_address['address'] = $userLocation['address'];
        }
    }


@endphp
<div class="bg-primary p-3 d-lg-none"style="min-height: 60px;">
    <div class="d-flex">
        <button class="btn btn-primary align-self-start p-0 me-2 toggle hc-nav-trigger hc-nav-1" type="button"
            aria-controls="offcanvasExample">
            <i class="feather-menu fs-2"></i>
        </button>
        <div class="d-flex align-items-center justify-content-between w-100">

                <div class="me-1">
                    <div class=" me-3">
                        <a class="text-dark d d-flex align-items-center py-3" role="button" role="button" data-bs-toggle="offcanvas" data-bs-target="@if (isset($userLocations[0])) #userSavedLocation @else #userNewLocation @endif">
                            <div class="me-1 "><i class="feather-map-pin me-2 bg-light rounded-pill p-2 icofont-size"></i>{{ $default_address['type'] }}&nbsp;| </div>
                            <div class="location-bar text-white text-wrap text-truncate ms-1 small" >{{ $default_address['address'] }}
                            </div>
                        </a>
                    </div>

                </div>

            {{-- <div class="me-0"></div> --}}
            @if(Auth::guard('customer')->check())
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
