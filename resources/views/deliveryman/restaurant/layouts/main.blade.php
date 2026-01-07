<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="dm_id" content="{{auth('delivery_men')->user()->id}}">
    <meta name="description" content="Askbootstrap">
    <meta name="author" content="Askbootstrap">
    <link rel="icon" type="image/png" href="{{ asset('assets/user/img/fav.png') }}">
    <title>Swiggiweb - Online Food Ordering Website Template</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">
    <link href="{{ asset('assets/vendor/select2/select2.min.css') }}" rel="stylesheet" />


    @stack('css')

</head>

<body class="fixed-bottom-bar">

    @include('deliveryman.restaurant.layouts.header')
    @include('deliveryman.restaurant.partials.notifications')



    @yield('content')


    @include('deliveryman.restaurant.layouts.footer')

    @include('deliveryman.restaurant.layouts.nav')






    {{-- user location modle --}}
    <div class="modal fade" id="userMap" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content bg-transparent">
                <div class="d-flex justify-content-between">
                    <h5 class="modal-title text-white"><img
                            src="{{ asset('assets/user/img/icons/userBuilding.svg') }}" alt=""> MY LOCATION
                    </h5>
                    <span class="bg-transparent" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"
                            width="30">
                            <path
                                d="M12,2.01c-5.51,0-9.99,4.47-9.99,9.99c0,5.51,4.47,9.99,9.99,9.99c5.52,0,9.99-4.47,9.99-9.99C21.99,6.49,17.52,2.01,12,2.01z M12,19.87c-4.34,0-7.87-3.53-7.87-7.87c0-4.34,3.53-7.87,7.87-7.87c4.34,0,7.87,3.53,7.87,7.87C19.87,16.34,16.34,19.87,12,19.87z"
                                fill="#fff"></path>
                            <rect width="11.3" height="2.12" x="6.42" y="10.94" fill="#fff"></rect>
                        </svg>
                    </span>
                </div>

                <div class="modal-body p-0  ">
                    <div id="map-canvas" style=" width:100%;height: 50vh"></div>
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="form-group d-flex">
                                <input type="text" id="search-input" class="form-control rounded-0" placeholder="Enter Address or Place">
                                <button data-address="search" class="btn btn-primary rounded-0">Search</button>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid mt-3 bg-white p-4 shadow-lg rounded mb-2">
                        <div class="row">
                            <div class="col-md-9">
                                <h3 class="text-primary">Address</h3>
                                <hr>
                                <div class="d-flex">
                                    <i class="feather-map-pin text-primary mt-1 mt-lg-1 me-2"></i>
                                    <div>
                                        <div>
                                            <span class="mb-1 mt-1" data-user-address="true">
                                            </span>
                                        </div>
                                        <button class="btn btn-primary mt-2" data-address="change">Change</button>
                                    </div>
                                </div>

                                {{-- <div class="form-group mt-3">
                                <input type="text" name="tag"
                                    class="form-control edit-address-input edit-address-tag mb-2" placeholder="Eg. Home, Work"
                                    value="">
                            </div> --}}

                            </div>
                            <div class="col-md-3">
                                <img src="{{ asset('assets/user/img/icons/user-location.gif') }}" style="width: 100%"
                                    alt="">
                            </div>
                        </div>
                    </div>

                </div>
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
    <script src="{{asset('assets/vendor/sweetalert2/sweetalert2@11.js')}}"></script>
     <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&loading=async&callback=initMap&libraries=geometry,places"
        >
      </script>
    <script src="{{ asset('assets/js/Helpers/mapHelper.js') }}"></script>
    <script src=" {{asset('firebase/index.js')}}" type="module"></script>


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
        function initMap() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(function (position) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        try {
                            const currentLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };

                            updateLocation(currentLocation);

                            myMap.CreateMap(currentLocation, {
                                selector: "#map-canvas", // corrected syntax for object properties
                                marker: {
                                    location: currentLocation,
                                    img: "{{ asset('assets/user/img/icons/map-icon.png') }}",
                                    draggable: true
                                }
                            });

                            // Search location
                            document.querySelector('[data-address=search]').addEventListener('click', () => {
                                geocodeAddress();
                            });

                            google.maps.event.addListener(myMap.marker, 'dragend', function() {
                                getAddress(myMap.marker.getPosition());
                            });
                            getAddress(myMap.marker.getPosition());

                            // changeDMAddress();
                        } catch (error) {
                            toastr.error(error.message);
                        }
                    });
                }, function (error) {
                    console.error('Error getting location:', error);
                    toastr.error('Error getting location: ' + error.message);
                });
            } else {
                toastr.error('Geolocation is not supported by this browser.');
            }
        }

        function getAddress(latLng) {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: latLng }, function(results, status) {
                if (status === "OK") {
                    // console.log(results[0]);
                    document.querySelector('[data-user-address]').textContent = results[0].formatted_address;
                    document.querySelector('[data-address="1"]').textContent = results[0].formatted_address;
                    document.querySelector('[data-address="2"]').textContent = results[0].formatted_address;

                    // Now you can extract address components from results[0] as needed
                } else {
                    console.error('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        function geocodeAddress() {
            var geocoder = new google.maps.Geocoder();
            var address = document.getElementById('search-input').value;

            geocoder.geocode({ 'address': address }, function(results, status) {
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

        // async function changeDMAddress() {
        //     const address = document.querySelector('[data-user-address]').textContent;
        //     const location = myMap.marker.position.toJSON();
        //     try {
        //         const resp  = await fetch("{{route('user.auth.save-user-address')}}", {
        //             method: "POST",
        //             body: JSON.stringify({ address: address, location: location }),
        //             headers: {
        //                 'Content-Type': 'application/json', // Ensure the content type is set correctly
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        //         const data = await resp.json();
        //         if(data.error){
        //             throw new Error(data.error);
        //         } else if (data.success) {
        //             toastr.success(data.success, "Success!", { closeButton: true, tapToDismiss: false, progressBar: true });
        //         }
        //     } catch (error) {
        //         toastr.error(error.message);
        //     }
        // }
        function updateLocation(position) {
            fetch("{{route('deliveryman.location-update')}}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({ position: position,dm_id : $('meta[name="dm_id"]').attr('content')})
            }).then(response => response.json())
            .then(data => {
                // console.log(data.message);
            }).catch(error => {
                console.error('Error updating location:', error);
            });
        }

        window.onload = initMap;
    </script>
    <script>
        document.querySelectorAll('[data-active]').forEach(item => {
            item.addEventListener('change', () => {
                fetch(`{{ route('deliveryman.activate') }}?checked=${item.checked}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data.message);
                    })
                    .catch(error => {
                        console.error('Error updating location:', error);
                    });
            });
        });
    </script>



</body>

</html>
