@extends('user-views.restaurant.layouts.main')
@section('containt')
<div class="container  position-relative">
    <div class="row mt-3 justify-content-center mx-1">
        <div class="col-lg-8 col-12 mb-3 p-0">
            <p class="fw-bolder mb-0">Address List</p>
        </div>
        <div class="col-lg-8 col-12 p-0">
            @foreach ($addressList as $address)
            {{-- @dd($address) --}}
            <div class="order-body">
                <div class="pb-3">

                    <div class="p-3 rounded shadow-sm bg-white">
                        <div class="d-flex">
                            <div class="w-100">
                                <div>
                                    <div class="d-flex justify-content-between">

                                        <p class="mb-0">@if($address->type != null)
                                            <span> {{ucfirst($address->type).(" | ")}}</span>
                                            @endif {{Str::ucfirst($address->address)}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 mt-3 border-top">
                            <a class="btn btn-success btn-sm w-50 ms-2 ms-auto mt-1 me-3" href="javascript:void(0)"
                                data-address="edit" data-info="{{json_encode($address)}}"><i
                                    class="feather-edit me-2"></i>Edit</a>

                            <label for="delele-addr-{{$address->id}}" class="btn btn-danger btn-sm w-50 ms-2 ms-auto mt-1"><i
                                    class="feather-trash me-2"></i>Delete</label>
                            <form action="{{route('user.auth.delete-user-address',[$address->id])}}" method="post">
                                @csrf
                                @method('delete')
                                <input type="button" id="delele-addr-{{$address->id}}" class="d-none" data-bs-toggle="modal"
                                    data-bs-target="#deleteAddress{{$address->id}}">

                                <div class="modal fade" id="deleteAddress{{$address->id}}" tabindex="-1"
                                    aria-labelledby="deleteAddressLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteAddressLabel">Delete Address</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this address?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@push('javascript')

<script>


    // Clone the element and replace the existing one


    document.querySelectorAll(`[data-address="edit"]`).forEach(editAddressBtn => {
        editAddressBtn.addEventListener('click', () =>{
            const clonedNewLocation = document.querySelector('#userNewLocation').cloneNode(true);
            document.querySelector('#userNewLocation').replaceWith(clonedNewLocation);
            const newuserNewLocation = new bootstrap.Offcanvas('#userNewLocation');

            const addressData = JSON.parse(editAddressBtn.dataset.info) ;
            newuserNewLocation.show() ;
            document.getElementById('openSavedLocation').addEventListener('click', () => {
                newuserNewLocation.hide();
            })

            clonedNewLocation.addEventListener('shown.bs.offcanvas', event => {
                const addressForm = document.getElementById('save-new-address-form');
                // Clone the form
                const clonedAddressForm = addressForm.cloneNode(true);
                // Create a hidden input for the ID
                const idElement = document.createElement('input');
                idElement.name = 'id';
                idElement.id = 'address_id';
                idElement.type = 'hidden'; // Corrected the attribute
                idElement.value = addressData.id;

                // Append the ID input to the cloned form
                clonedAddressForm.appendChild(idElement);

                // Populate the cloned form with address data
                clonedAddressForm.elements['longitude'].value = addressData.longitude;
                clonedAddressForm.elements['latitude'].value = addressData.latitude;
                clonedAddressForm.elements['address'].value = addressData.address;
                clonedAddressForm.elements['phone'].value = addressData.phone;
                clonedAddressForm.elements['type'].value = addressData.type;
                clonedAddressForm.elements['landmark'].value = addressData.landmark;

                // Replace the original form with the cloned one
                addressForm.replaceWith(clonedAddressForm);

                const savedPosition = {
                    lat: parseFloat(addressData.latitude),
                    lng: parseFloat(addressData.longitude)
                };
                reinVokeMap(savedPosition) ;
                console.log(addresForm);
            });
        })
    })




    function reinVokeMap(currentLocation) {

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

            const chooseCurrentLocation = document.querySelector('#chooseCurrentLocation');
            const c_chooseCurrentLocation = chooseCurrentLocation.cloneNode(true);
            chooseCurrentLocation.replaceWith(c_chooseCurrentLocation);
            c_chooseCurrentLocation.addEventListener('click', () => {
                myMap.marker.setPosition(currentLocation);
                getAddress(myMap.marker.getPosition());

            })


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
            return myMap ;
        }
</script>
@endpush
