<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Askbootstrap">
    <meta name="author" content="Askbootstrap">
    <link rel="icon" type="image/png" href="{{asset('assets/images/icons/foodYariLogo.png')}}">

    <title>Foodyari | User | Account</title>
    <!-- Slick Slider -->
    <link href=" {{ asset('assets/user/vendor/slick/slick/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/slick/slick/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <!-- Feather Icon-->
    <link href="{{ asset('assets/user/vendor/icons/feather.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/user/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/user/css/style.css') }}" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link href="{{ asset('assets/user/vendor/sidebar/demo.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/user/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/user/css/mess.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">
    <link href="{{ asset('assets/vendor/select2/select2.min.css') }}" rel="stylesheet" />


    @stack('css')

</head>

<body class="fixed-bottom-bar">

    @include('user-views.layouts.header')


    @yield('content')


    @include('user-views.layouts.footer')

    @include('user-views.layouts.nav')



    <div class="modal fade" id="paycard" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Credit/Debit Card</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="m-0">Add new card</h6>
                    <p class="small">WE ACCEPT <span class="osahan-card ms-2 fw-bold">( Master Card / Visa Card / Rupay
                            )</span></p>
                    <form>
                        <div class="form-row">
                            <div class="col-md-12 form-group mb-3">
                                <label class="form-label fw-bold small">Card number</label>
                                <div class="input-group">
                                    <input placeholder="Card number" type="number" class="form-control">
                                    <button class="btn btn-outline-secondary" type="button"><i
                                            class="feather-credit-card"></i></button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-8 form-group"><label class="form-label fw-bold small">Valid
                                        through(MM/YY)</label><input placeholder="Enter Valid through(MM/YY)"
                                        type="number" class="form-control"></div>
                                <div class="col-md-4 form-group"><label
                                        class="form-label fw-bold small">CVV</label><input
                                        placeholder="Enter CVV Number" type="number" class="form-control"></div>
                            </div>
                            <div class="col-md-12 form-group mb-3"><label class="form-label fw-bold small">Name on
                                    card</label><input placeholder="Enter Card number" type="text"
                                    class="form-control"></div>
                            <div class="col-md-12 form-group mb-0">
                                <div class="form-check custom-checkbox">
                                    <input class="form-check-input" type="checkbox" value="" id="custom-Check1">
                                    <label class="form-check-label small" for="custom-Check1">
                                        Securely save this card
                                        for a faster checkout next time.
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer p-0 border-0">
                    <div class="col-6 m-0 p-0">
                        <button type="button" class="btn border-top btn-lg w-100"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                    <div class="col-6 m-0 p-0">
                        <button type="button" class="btn btn-primary btn-lg w-100">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Delivery Address</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="">
                        <div class="form-row">
                            <div class="col-md-12 form-group">
                                <label class="form-label">Delivery Area</label>
                                <div class="input-group mb-3">
                                    <input placeholder="Delivery Area" type="text" class="form-control">
                                    <button class="btn btn-outline-secondary" type="button"><i
                                            class="feather-map-pin"></i></button>
                                </div>
                            </div>
                            <div class="col-md-12 form-group mb-3"><label class="form-label">Complete
                                    Address</label><input
                                    placeholder="Complete Address e.g. house number, street name, landmark"
                                    type="text" class="form-control"></div>
                            <div class="col-md-12 form-group mb-3"><label class="form-label">Delivery
                                    Instructions</label><input
                                    placeholder="Delivery Instructions e.g. Opposite Gold Souk Mall" type="text"
                                    class="form-control"></div>
                            <div class="mb-0 col-md-12 form-group">
                                <label class="form-label">Nickname</label>
                                <div class="btn-group w-100" role="group"
                                    aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio4" checked>
                                    <label class="btn btn-outline-secondary" for="btnradio4">Home</label>

                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio5">
                                    <label class="btn btn-outline-secondary" for="btnradio5">Work</label>

                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio6">
                                    <label class="btn btn-outline-secondary" for="btnradio6">Other</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer p-0 border-0">
                    <div class="col-6 m-0 p-0">
                        <button type="button" class="btn border-top btn-lg w-100"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                    <div class="col-6 m-0 p-0">
                        <button type="button" class="btn btn-primary btn-lg w-100">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Invite Modal-->
    <div class="modal fade" id="inviteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-dark">Invite</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <button class="btn btn-light text-primary btn-sm"><i class="feather-plus"></i></button>
                    <span class="ms-2 smal text-primary">Send an invite to a friend</span>
                    <p class="mt-3 small">Invited friends (2)</p>
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3"><img alt="#" src="{{ asset('assets/user/img/user1.jpg') }}"
                                class="img-fluid rounded-circle"></div>
                        <div>
                            <p class="small fw-bold text-dark mb-0">Kate Simpson</p>
                            <P class="mb-0 small">katesimpson@outbook.com</P>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3"><img alt="#" src="{{ asset('assets/user/img/demo-2.png') }}"
                                class="img-fluid rounded-circle"></div>
                        <div>
                            <p class="small fw-bold text-dark mb-0">Andrew Smith</p>
                            <P class="mb-0 small">andrewsmith@ui8.com</P>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                </div>
            </div>
        </div>
    </div>
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

    {{-- Saved Location --}}
    <div class="offcanvas offcanvas-bottom rounded-4 rounded-bottom-0 overflow-hidden" id="userSavedLocation" tabindex="-1" aria-labelledby="userMapLabel" style="height: 80vh">
        <div class="offcanvas-header p-0">
            <div class="alert alert-warning w-100" role="alert">
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    <strong>Search Location</strong>
                    <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#userNewLocation">New</button>
                </div>
            </div>
        </div>
        <div class="offcanvas-body">
            @if ($userLocations)
                <form action="{{ route('user.auth.save-user-current-address') }}" method="POST">
                    @csrf
                    @foreach ($userLocations as $location)
                        <div class="card mb-2">
                            <label for="address_{{ $location->id }}">
                                <div class="card-body p-2 shadow-sm">
                                    <div class="w-100 d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">{{ $location->type ?? 'Home' }}</h5>
                                            <p class="card-text">{{ $location->address }}</p>
                                        </div>
                                        <input type="radio" name="address_id" value="{{ $location->id }}" @if ($location->is_default) checked @endif class="p-2" id="address_{{ $location->id }}" class=""
                                            onchange="this.form.submit()">
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </form>
            @endif
        </div>
    </div>

    {{-- New Location --}}
    <div class="offcanvas offcanvas-bottom" id="userNewLocation" tabindex="-1" aria-labelledby="userMapLabel" style="height: 100vh">
        <div class="offcanvas-body p-0">
            <button type="button" class="btn position-absolute z-3  btn-primary rounded-4 rounded-start-0 mt-2 shadow" id="openSavedLocation"><i class="feather-arrow-left fs-3"></i></button>
            <div id="map-canvas" style=" width:100%;height: 50vh"></div>
            <div class="p-2">
                <form action="{{ route('user.auth.save-user-address') }}" method="POST">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude" value="34.5432">
                    <input type="hidden" name="longitude" id="longitude" value="87.23432">
                    <div class="mb-3">
                        <label for="" class="form-label mb-0">Address</label>
                        <input type="text" class="form-control form-control-lg" name="address" id="search-input" aria-describedby="helpId" placeholder="Address" />
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label mb-0">Landmark</label>
                        <textarea name="landmark" class="form-control form-control-lg" placeholder="Ladmark"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label mb-0">Phone</label>
                        <input type="number" class="form-control form-control-lg" name="phone" placeholder="Eg: 9155289998" />
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label mb-0">Tag</label>
                        <input type="text" class="form-control form-control-lg" name="type" placeholder="Eg: Home, Office" />
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">Save Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{ asset('assets/user/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <!-- slick Slider JS-->
    <script type="text/javascript" src="{{ asset('assets/user/vendor/slick/slick/slick.min.js') }}"></script>
    <!-- Sidebar JS-->
    <script type="text/javascript" src="{{ asset('assets/user/vendor/sidebar/hc-offcanvas-nav.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script type="text/javascript" src="{{ asset('assets/user/js/osahan.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
    <script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&loading=async&callback=initMap&libraries=geometry,places">
        // {{-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places&callback=initMap"></script> --}}
    </script>
    <script src="{{ asset('assets/js/Helpers/mapHelper.js') }}"></script>

    @stack('javascript')
    {!! Toastr::message() !!}

    @if (Session::has('success'))
        <script>
            toastr.success('{{ Session::get('success') }}');
        </script>
    @endif
    @if (Session::has('info'))
        <script>
            toastr.info('{{ Session::get('info') }}');
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            toastr.info('{{ Session::get('error') }}');
        </script>
    @endif
    @if (Session::has('warning'))
        <script>
            toastr.warning('{{ Session::get('warning') }}');
        </script>
    @endif


    <script>
        const userSavedLocation = new bootstrap.Offcanvas('#userSavedLocation')
        const userNewLocation = new bootstrap.Offcanvas('#userNewLocation')

        document.querySelector('#userNewLocation').addEventListener('shown.bs.offcanvas', event => {
            initMap();
        })

        document.querySelector('#openSavedLocation').addEventListener('click', () => {
            userNewLocation.hide();
            userSavedLocation.show();
        })
    </script>

    <script>
        function initMap() {
            navigator.geolocation.getCurrentPosition((position) => {
                const currentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var now = new Date().getTime();
                let customLocation = localStorage.getItem('customLocation');
                if (customLocation) {
                    customLocation = JSON.parse(customLocation);
                    if (now < customLocation.expires) {
                        currentLocation.lat = customLocation.lat;
                        currentLocation.lng = customLocation.lng;
                    } else {
                        localStorage.removeItem('customLocation');
                    }
                }

                myMap.CreateMap(currentLocation, {
                    selector: "#map-canvas",
                    marker: {
                        location: currentLocation,
                        img: "{{ asset('assets/user/img/icons/marker-icon.png') }}",
                        draggable: true
                    }
                });
                // search location

                google.maps.event.addListener(myMap.marker, 'dragend', function() {
                    getAddress(myMap.marker.getPosition());
                });
                getAddress(myMap.marker.getPosition());


            }, showError);

            var input = document.getElementById('search-input');
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();

                if (!place.geometry) {
                    console.log("No details available for input: '" + place.name + "'");
                    return;
                }
                getAddress(place.geometry.location)
            })

        }
        // window.addEventListener('load', initMap);

        function getAddress(positon) {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                location: positon
            }, function(results, status) {
                if (status === "OK") {
                    // console.log(results[0]);
                    const formatted_address = results[0].formatted_address || null;

                    if (document.querySelector('[data-user-address]')) {
                        document.querySelector('[data-user-address]').textContent = formatted_address;
                    }
                    if (document.querySelector('[data-address="1"]')) {
                        document.querySelector('[data-address="1"]').textContent = formatted_address;
                    }
                    if (document.querySelector('[data-address="2"]')) {
                        document.querySelector('[data-address="2"]').textContent = formatted_address;
                    }

                    document.querySelector('#search-input').value = formatted_address;

                    myMap.map.setCenter(positon);
                    myMap.marker.setPosition(positon);
                    var expirationDate = new Date();
                    expirationDate.setTime(expirationDate.getTime() + (15 * 60 * 1000));

                    var data = {
                        lat: positon.lat(),
                        lng: positon.lng(),
                        expires: expirationDate.getTime()
                    };
                    localStorage.setItem('customLocation', JSON.stringify(data));
                    myMap.setElementsPosition(data)

                    // Now you can extract address components from results[0] as needed
                } else {
                    console.error('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        function geocodeAddress() {
            var geocoder = new google.maps.Geocoder();
            var address = document.getElementById('search-input').value;

            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status === 'OK') {
                    var formattedAddress = results[0].formatted_address;
                    const location = results[0].geometry.location;
                    myMap.map.setCenter(location);
                    myMap.marker.setPosition(location);
                    getAddress(myMap.marker.getPosition());
                } else {
                    console.error('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        // async function changeUserAddress() {
        //     const address = document.querySelector('[data-user-address]').textContent;
        //     const location = myMap.marker.position.toJSON();
        //     try {
        //         const resp = await fetch("{{ route('user.auth.save-user-address') }}", {
        //             method: "POST",
        //             body: JSON.stringify({
        //                 address: address,
        //                 location,
        //                 location
        //             }),
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        //         const data = await resp.json();
        //         if (data.error) {
        //             throw new Error(data.error)
        //         } else if (data.success) {
        //             toastr.success(data.success, "Success!", {
        //                 closeButton: true,
        //                 tapToDismiss: false,
        //                 progressBar: true
        //             });
        //         }

        //     } catch (error) {
        //         toastr.error(error.message)
        //     }
        // }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    toastr.warning("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    toastr.warning("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    toastr.warning("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    toastr.warning("An unknown error occurred.");
                    break;
            }
        }
    </script>

</body>

</html>
