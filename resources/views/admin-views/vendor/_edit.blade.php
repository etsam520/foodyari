@extends('layouts.dashboard-main')
@push('css')
    <style>
        .pac-container{
            z-index: 99999;
        }
        .zone-info-panel {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .zone-info-panel.active {
            background: #d4edda;
            border-color: #c3e6cb;
        }
        .zone-info-text {
            font-size: 14px;
            color: #495057;
            margin: 0;
        }
        .zone-info-text.active {
            color: #155724;
            font-weight: 500;
        }
        #map-canvas {
            border: 2px solid #dee2e6;
            border-radius: 5px;
        }
    </style>
@endpush
@section('content')

    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('Update Restaurant Info') }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('admin.restaurant.update') }}" method="POST"
                                enctype="multipart/form-data" class="mt-3 text-center">
                                @csrf
                                <input type="hidden" name="id" id="restaurantId" value="{{$restaurant->id}}">
                               <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <img class="initial-57-2" id="viewer" data-preview="1"
                                            src="{{ $restaurant->logo? asset('restaurant/'.$restaurant->logo): asset('assets/images/icons/restaurant-default-image.png') }}"
                                            alt="delivery-man image">

                                        <div class="form-group pt-3">
                                            <label class="input-label">Restaurant Logo<small class="text-danger">
                                                    (Ratio 1:1)</small></label>
                                            <div class="custom-file">
                                                <input type="file" name="logo" id="customFileEg1" data-image-input="1"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6 mt-3">

                                        <img class="initial-57-2 mw-100" id="coverImageViewer"
                                            src="{{ $restaurant->cover_photo? asset('restaurant/cover/'.$restaurant->cover_photo): asset('assets/images/icons/300x100/restaurant-default-image.png') }}"
                                            alt="Product thumbnail" data-preview="2">

                                        <div class="form-group pt-3">
                                            <label for="name" class="input-label text-capitalize">Cover
                                                photo <span class="text-danger">(Ratio 3:1)</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="cover_photo" id="coverImageUpload"
                                                    class="custom-file-input" data-image-input="2" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            </div>
                                        </div>
                                    </div> --}}
                               </div>
                               <hr class="hr-horizontal">
                                <div class="row">
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="name">Restaurant Name</label>
                                            <input id="name" type="text" name="name"
                                                class="form-control h--45px" placeholder="Enter Restaurant Name"
                                                value="{{$restaurant->name?? old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="restaurant_no">Restaurant Id No.</label>
                                            <input id="restaurant_no" type="text" name="restaurant_no"
                                                class="form-control h--45px" placeholder="XXXX"
                                                value="{{$restaurant->restaurant_no?? old('restaurant_no') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="restaurant_url">Restaurant URL</label>
                                            <input id="restaurant_url" type="text" name="restaurant_url"
                                                class="form-control h--45px" placeholder="example-url"
                                                value="{{$restaurant->url_slug??Str::slug($restaurant->name)}}">
                                                <span class="text-info" data-restaurant-url="{{url("/restaurant/")}}"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="email">Email</label>
                                            <input id="email" type="text" name="email"
                                                class="form-control h--45px" placeholder="example@gmail.com"
                                                value="{{$restaurant->email?? old('email') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="mobno">Phone</label>
                                            <input type="text" class="form-control" id="mobno" name="phone" value="{{$restaurant->phone??''}}" placeholder="Phone Number">
                                        </div>
                                     </div>
                                    @php($address = json_decode($restaurant->address))
                                    {{-- @dd($address) --}}
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="street">Street</label>
                                            <input id="street" type="text" name="street"
                                                class="form-control h--45px" placeholder="Enter Address"
                                                value="{{ $address->street??old('street') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="city">City</label>
                                            <input id="city" type="text" name="city"
                                                class="form-control h--45px" placeholder="Enter City"
                                                value="{{ $address->city??old('city') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="pincode">Pincode</label>
                                            <input id="pincode" type="text" name="pincode"
                                                class="form-control h--45px" placeholder="Enter Pincode"
                                                value="{{ $address->pincode??old('pincode') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4  mt-3 col-12">
                                        <div class="form-group m-0">
                                            <label class="input-label text-capitalize" for="title">Minimum Order Amount</label>
                                            <input type="number" name="minimum_order" step="0.01" min="0" max="100000" class="form-control" placeholder="100" value="{{$restaurant->minimum_order?? old('minimum_order') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="tax">GST (%)</label>
                                            <input id="tax" type="number" name="tax"
                                                class="form-control h--45px" placeholder="Enter GST"
                                                min="0" step=".01" value="{{$restaurant->tax?? old('tax') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="comission">Admin Comission (%)</label>
                                            <input id="comission" type="number" name="comission"
                                                class="form-control h--45px" placeholder="Enter Comission"
                                                min="0" step=".01" value="{{$restaurant->comission?? old('comission') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="minimum_delivery_time">Minimum
                                                delivery time (Minutes)</label>
                                            <input id="minimum_delivery_time" type="number"
                                                name="minimum_delivery_time" class="form-control h--45px"
                                                placeholder="Enter Minimum Delivery Time" pattern="^[0-9]{2}$"
                                                value="{{ $restaurant->min_delivery_time
                                                ? Carbon\Carbon::parse($restaurant->min_delivery_time)->diffInMinutes('00:00:00')
                                                : old('minimum_delivery_time') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="maximum_delivery_time">Maximum
                                                delivery time (Minutes)</label>
                                            <input id="maximum_delivery_time" type="number"
                                                name="maximum_delivery_time" class="form-control h--45px"
                                                placeholder="Enter Maximum Delivery Time" pattern="[0-9]{2}"
                                                value="{{ $restaurant->max_delivery_time
                                                ? Carbon\Carbon::parse($restaurant->max_delivery_time)->diffInMinutes('00:00:00')
                                                : old('maximum_delivery_time') }}">
                                        </div>
                                    </div>
                                </div>
                                <hr class="hr-horizontal">
                                <hr class="hr-horizontal">
                                <div class="row">
                                    <div class="col-md-3 mt-5 ">
                                        <div class="form-group">
                                            <label class="input-label" for="zone">Zone</label>
                                        <select name="zone_id" id="zone_id" class="form-control"  data-placeholder="Select Zone"  tabindex="-1" aria-hidden="true">
                                            <option value=""  >Select Zone</option>
                                            @foreach ($zones as $zone)
                                            <option value="{{$zone->id}}" data-coordinates="{{$zone->coordinates}}" {{$zone->id == $restaurant->zone_id?'selected': null}} >{{$zone->name}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="input-label" for="radius">Service Area (radius - km.)</label>
                                            <input type="number" name="radius" id="radius" value="{{$restaurant->radius??old('radius')}}" class="form-control" >
                                        </div>
                                        <div class="form-group">
                                            <label class="input-label" for="latitude">Latitude</label>
                                            <input type="text" id="latitude" name="latitude" class="form-control h--45px disabled" value="{{$restaurant->latitude}}"
                                                placeholder="Ex : -94.22213" value="" required readonly>
                                        </div>
                                        <div class="form-group mb-md-0">
                                            <label class="input-label" for="longitude">Longitude</label>
                                            <input type="text" name="longitude" class="form-control h--45px disabled" placeholder="Ex : 103.344322"
                                                id="longitude" value="{{$restaurant->longitude}}" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-9 mt-5">
                                        <div class="zone-info-panel" id="zone-info-panel">
                                            <p class="zone-info-text" id="zone-info-text">Select a zone to view its boundaries on the map</p>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mt-3 mx-auto">
                                                <div class="form-group d-flex">
                                                    <input type="text" id="search-address-input" class="form-control rounded-0" placeholder="Enter Address or Place">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="map-canvas" style=" width:100%;height: 50vh"></div>
                                    </div>
                                </div>

                                <hr class="hr-horizontal">
                                <div class="row">
                                    @php($badges = json_decode($restaurant->badges))
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="input-label" for="badge_one">Badge
                                                One</label>
                                            <input id="badge_one" type="text" value="{{$badges->b1??null}}" name="badge_one" class="form-control h--45px" placeholder="Enter Badge One">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="input-label" for="badge_two">Badge
                                                Two</label>
                                            <input id="badge_two" type="text" value="{{$badges->b2??null}}" name="badge_two" class="form-control h--45px" placeholder="Enter Badge Two">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="input-label" for="badge_three">Badge
                                                Three</label>
                                            <input id="badge_three" type="text" value="{{$badges->b3??null}}" name="badge_three" class="form-control h--45px" placeholder="Enter Badge Three">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <button type="submit"
                                            class="btn btn-primary next action-button float-end" >Update</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('javascript')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places"></script>
<script src="{{asset('assets/js/Helpers/mapHelper.js')}}"></script>
<script src="{{ asset('assets/js/Helpers/custom.js') }}"></script>




<script>
let currentZonePolygon = null;
let map = null;
let marker = null;
var currentLocation = { lat: 23.8103, lng: 90.4125 }; // Default to Dhaka, Bangladesh

function initMap() {
    // Default fallback location (you can change this to your preferred default)
    const defaultLocation = { lat: 23.8103, lng: 90.4125 }; // Dhaka, Bangladesh
    
    // Parse and validate coordinates
    const restaurantLat = parseFloat("{{$restaurant->latitude ?? ''}}");
    const restaurantLng = parseFloat("{{$restaurant->longitude ?? ''}}");
    
    // Use restaurant coordinates if valid, otherwise use default
    currentLocation = {
        lat: isNaN(restaurantLat) ? defaultLocation.lat : restaurantLat,
        lng: isNaN(restaurantLng) ? defaultLocation.lng : restaurantLng
    };

    console.log('Initializing map with location:', currentLocation);

    myMap.CreateMap(currentLocation, {
        selector: "#map-canvas",
        marker: {
            location: currentLocation,
            img: "{{ asset('assets/user/img/icons/marker-icon.png') }}",
            draggable: true
        }
    });

    map = myMap.map;
    marker = myMap.marker;

    // Search location
    var input = document.getElementById('search-address-input');
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();

        if (!place.geometry) {
            console.log("No details available for input: '" + place.name + "'");
            return;
        }
        
        const newLocation = place.geometry.location.toJSON();
        
        // Check if new location is within zone polygon
        if (currentZonePolygon && !isLocationInPolygon(newLocation, currentZonePolygon)) {
            alert('Selected location is outside the zone boundary. Please select a location within the zone.');
            return;
        }
        
        myMap.map.setCenter(newLocation);
        myMap.marker.setPosition(newLocation);
        myMap.setElementsPosition(newLocation);
    });

    // Set initial position and update form fields
    myMap.setElementsPosition(currentLocation);
    marker.previousValidPosition = currentLocation;
    
    // Update latitude/longitude input fields with current values
    document.getElementById('latitude').value = currentLocation.lat;
    document.getElementById('longitude').value = currentLocation.lng;

    // Drag marker to get position
    google.maps.event.addListener(myMap.marker, 'dragend', function() {
        const newPosition = myMap.marker.getPosition().toJSON();
        
        // Check if new position is within zone polygon
        if (currentZonePolygon && !isLocationInPolygon(newPosition, currentZonePolygon)) {
            alert('Restaurant location must be within the selected zone boundary.');
            // Reset marker to previous valid position
            if (marker.previousValidPosition) {
                myMap.marker.setPosition(marker.previousValidPosition);
                myMap.map.setCenter(marker.previousValidPosition);
                myMap.setElementsPosition(marker.previousValidPosition);
            }
            return;
        }
        
        marker.previousValidPosition = newPosition;
        myMap.map.setCenter(newPosition);
        myMap.setElementsPosition(newPosition);
    });

    // Zone change handler
    document.getElementById('zone_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        console.log('Zone changed to:', selectedOption.value, selectedOption.textContent);
        if (selectedOption.value) {
            loadZonePolygon(selectedOption);
        } else {
            clearZonePolygon();
        }
    });

    // Load initial zone polygon if zone is already selected
    @if($restaurant->zone_id)
    const currentZoneOption = document.querySelector('#zone_id option[value="{{$restaurant->zone_id}}"]');
    if (currentZoneOption) {
        console.log('Loading initial zone polygon for zone ID:', '{{$restaurant->zone_id}}');
        loadZonePolygon(currentZoneOption);
    } else {
        console.warn('Could not find zone option for zone ID:', '{{$restaurant->zone_id}}');
        // updateZoneInfoPanel('Current zone not found in available zones', false);
    }
    @else
    console.log('No zone assigned to this restaurant');
    // updateZoneInfoPanel('No zone assigned to this restaurant', false);
    @endif
}

function loadZonePolygon(selectedOption) {
    try {
        const coordinatesData = selectedOption.getAttribute('data-coordinates');
        const zoneName = selectedOption.textContent;
        
        if (!coordinatesData) {
            console.warn('No coordinates data found for selected zone');
            updateZoneInfoPanel(`Zone "${zoneName}" selected, but no polygon data available`, false);
            return;
        }

        const zoneData = JSON.parse(coordinatesData);
        
        // Clear existing polygon
        clearZonePolygon();

        // Create polygon coordinates
        let polygonCoords = [];
        
        if (zoneData.polygon && Array.isArray(zoneData.polygon)) {
            // Handle polygon format
            polygonCoords = zoneData.polygon.map(coord => {
                const lat = parseFloat(coord.latitude || coord.lat || 0);
                const lng = parseFloat(coord.longitude || coord.lng || 0);
                
                // Validate coordinates
                if (isNaN(lat) || isNaN(lng)) {
                    console.warn('Invalid coordinates in polygon:', coord);
                    return null;
                }
                
                return { lat, lng };
            }).filter(coord => coord !== null); // Remove invalid coordinates
            
        } else if (zoneData.latitude && zoneData.longitude) {
            // Handle single point - create a small circle around it
            const centerLat = parseFloat(zoneData.latitude);
            const centerLng = parseFloat(zoneData.longitude);
            
            // Validate center coordinates
            if (isNaN(centerLat) || isNaN(centerLng)) {
                console.warn('Invalid center coordinates:', zoneData);
                updateZoneInfoPanel(`Zone "${zoneName}" has invalid coordinates`, false);
                return;
            }
            
            const radius = 0.01; // Small radius for demo
            
            // First, move marker to the center point (for single point zones, always move to center)
            const centerPoint = { lat: centerLat, lng: centerLng };
            myMap.marker.setPosition(centerPoint);
            myMap.map.setCenter(centerPoint);
            myMap.setElementsPosition(centerPoint);
            marker.previousValidPosition = centerPoint;
            
            // Update form fields
            document.getElementById('latitude').value = centerLat;
            document.getElementById('longitude').value = centerLng;
            
            for (let i = 0; i < 8; i++) {
                const angle = (i * Math.PI * 2) / 8;
                polygonCoords.push({
                    lat: centerLat + Math.cos(angle) * radius,
                    lng: centerLng + Math.sin(angle) * radius
                });
            }
        }

        if (polygonCoords.length >= 3) { // Need at least 3 points for a polygon
            // Create and display polygon
            currentZonePolygon = new google.maps.Polygon({
                paths: polygonCoords,
                strokeColor: '#28a745',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#28a745',
                fillOpacity: 0.2
            });

            currentZonePolygon.setMap(map);
            // Store polygon coordinates for validation first
            currentZonePolygon.coordinates = polygonCoords;
            
            console.log('Zone Polygon Coordinates:', polygonCoords);
            console.log('Current Location:', currentLocation);
            console.log('Is current location within polygon?', isLocationInPolygon({lat: currentLocation.lat, lng: currentLocation.lng}, currentZonePolygon));

            // Calculate polygon center and move marker there
            const isCurrentLocationInPolygon = isLocationInPolygon({lat: currentLocation.lat, lng: currentLocation.lng}, currentZonePolygon);
            console.log('Should move marker to center?', !isCurrentLocationInPolygon);
            
            if(!isCurrentLocationInPolygon) {
                const polygonCenter = calculatePolygonCenter(polygonCoords);
                myMap.marker.setPosition(polygonCenter);
                myMap.map.setCenter(polygonCenter);
                myMap.setElementsPosition(polygonCenter);
                marker.previousValidPosition = polygonCenter;
                
                // Update form fields with new coordinates
                document.getElementById('latitude').value = polygonCenter.lat;
                document.getElementById('longitude').value = polygonCenter.lng;
                
                // Update currentLocation variable
                currentLocation = polygonCenter;
                
                console.log('Marker moved to polygon center:', polygonCenter);
            } else {
                console.log('Current location is already within polygon, keeping current position');
            }

            // Fit map to polygon bounds
            try {
                const bounds = new google.maps.LatLngBounds();
                polygonCoords.forEach(coord => bounds.extend(coord));
                map.fitBounds(bounds);
            } catch (boundsError) {
                console.warn('Could not fit map to polygon bounds:', boundsError);
            }
            
            const message = isCurrentLocationInPolygon ? 
                `Zone "${zoneName}" selected. Current location is within zone boundaries.` :
                `Zone "${zoneName}" selected. Marker moved to zone center. Restaurant location must be within the green area.`;
            updateZoneInfoPanel(message, true);
            console.log('Zone polygon loaded successfully with', polygonCoords.length, 'coordinates');
        } else {
            updateZoneInfoPanel(`Zone "${zoneName}" selected, but no valid polygon coordinates found (need at least 3 points)`, false);
        }
    } catch (error) {
        console.error('Error loading zone polygon:', error);
        updateZoneInfoPanel('Error loading zone boundaries', false);
    }
}

function updateZoneInfoPanel(message, isActive = false) {
    const panel = document.getElementById('zone-info-panel');
    const text = document.getElementById('zone-info-text');
    
    if (panel && text) {
        text.textContent = message;
        if (isActive) {
            panel.classList.add('active');
            text.classList.add('active');
        } else {
            panel.classList.remove('active');
            text.classList.remove('active');
        }
    }
}

function clearZonePolygon() {
    if (currentZonePolygon) {
        currentZonePolygon.setMap(null);
        currentZonePolygon = null;
    }
    updateZoneInfoPanel('Select a zone to view its boundaries on the map', false);
}

function isValidCoordinate(coord) {
    return coord && !isNaN(coord) && isFinite(coord);
}

function calculatePolygonCenter(polygonCoords) {
    if (!polygonCoords || polygonCoords.length === 0) {
        return { lat: 23.8103, lng: 90.4125 }; // Default fallback
    }
    
    // Calculate centroid using the standard polygon centroid formula
    let area = 0;
    let centroidLat = 0;
    let centroidLng = 0;
    
    for (let i = 0; i < polygonCoords.length; i++) {
        const j = (i + 1) % polygonCoords.length;
        const xi = polygonCoords[i].lat;
        const yi = polygonCoords[i].lng;
        const xj = polygonCoords[j].lat;
        const yj = polygonCoords[j].lng;
        
        const crossProduct = xi * yj - xj * yi;
        area += crossProduct;
        centroidLat += (xi + xj) * crossProduct;
        centroidLng += (yi + yj) * crossProduct;
    }
    
    area /= 2;
    
    // If area is too small or zero, fall back to simple average
    if (Math.abs(area) < 1e-10) {
        let totalLat = 0;
        let totalLng = 0;
        
        for (let i = 0; i < polygonCoords.length; i++) {
            totalLat += polygonCoords[i].lat;
            totalLng += polygonCoords[i].lng;
        }
        
        return {
            lat: totalLat / polygonCoords.length,
            lng: totalLng / polygonCoords.length
        };
    }
    
    centroidLat /= (6 * area);
    centroidLng /= (6 * area);
    
    return {
        lat: centroidLat,
        lng: centroidLng
    };
}

function isLocationInPolygon(location, polygon) {
    if (!polygon || !polygon.coordinates) return true;
    
    const lat = location.lat;
    const lng = location.lng;
    
    // Validate input coordinates
    if (!isValidCoordinate(lat) || !isValidCoordinate(lng)) {
        console.warn('Invalid location coordinates:', location);
        return false;
    }
    
    const coordinates = polygon.coordinates;
    
    let inside = false;
    for (let i = 0, j = coordinates.length - 1; i < coordinates.length; j = i++) {
        const xi = coordinates[i].lat;
        const yi = coordinates[i].lng;
        const xj = coordinates[j].lat;
        const yj = coordinates[j].lng;
        
        // Validate polygon coordinates
        if (!isValidCoordinate(xi) || !isValidCoordinate(yi) || 
            !isValidCoordinate(xj) || !isValidCoordinate(yj)) {
            console.warn('Invalid polygon coordinates found');
            continue;
        }
        
        if (((yi > lng) !== (yj > lng)) && (lat < (xj - xi) * (lng - yi) / (yj - yi) + xi)) {
            inside = !inside;
        }
    }
    
    return inside;
}

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const zoneSelect = document.getElementById('zone_id');
    const latitude = document.getElementById('latitude').value;
    const longitude = document.getElementById('longitude').value;
    
    if (zoneSelect.value && latitude && longitude && currentZonePolygon) {
        const location = { lat: parseFloat(latitude), lng: parseFloat(longitude) };
        if (!isLocationInPolygon(location, currentZonePolygon)) {
            e.preventDefault();
            alert('Restaurant location must be within the selected zone boundary.');
            return false;
        }
    }
});

initMap();
</script>

<script type="module">
    import { readImage } from "{{asset('assets/js/Helpers/helper.js')}}";

    document.querySelector('[data-image-input="1"]')?.addEventListener('change',(event)=> {
        readImage(event.target, '[data-preview="1"]');
    })
    document.querySelector('[data-image-input="2"]')?.addEventListener('change',(event)=> {
        readImage(event.target, '[data-preview="2"]');
    })

</script>
<script>
    const restaurant_url_input = document.getElementById('restaurant_url');
    restaurant_url_input.addEventListener('input',showRestaurnt_url);

    function showRestaurnt_url (){
        // toastr.success('dkd');
        const url_slug = restaurant_url_input.value;
        const url_span = document.querySelector("[data-restaurant-url]");

        url_span.textContent  = url_span.dataset.restaurantUrl +"/"+ url_slug ;
    }
    showRestaurnt_url();
</script>

@endpush
