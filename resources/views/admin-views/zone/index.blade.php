@extends('layouts.dashboard-main')

@push('css')
<style>
    .btn-group .btn.active {
        background-color: #007bff !important;
        color: white !important;
        border-color: #007bff !important;
    }
    
    .drawing-controls {
        background: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 10px;
    }
    
    #map-canvas {
        border: 2px solid #dee2e6;
        border-radius: 8px;
    }
    
    .zone-info {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
    }
</style>
@endpush

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0"> 
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.zone.store')}}" method="POST" class="shadow-card">
                            @csrf                   
                            <div class="row justify-content-between">
                                <div class="col-md-12 col-xl-12 zone-setup">
                                    <div class="pl-xl-5 pl-xxl-0">
                                        <div class="form-group mb-3">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">Zone Name</label>
                                            <input id="name" type="text" name="name" class="form-control h--45px" placeholder="Enter Zone Name" value="" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>


                                            <div class="col-sm-4">
                                                <div class="form-group mb-3">
                                                    <label class="input-label text-capitalize d-inline-flex alig-items-center"
                                                    for="max_cod_order_amount">Maximum COD order amount (₹)</label>
                                                
                                                <input type="number" name="max_cod_order_amount" class="form-control"
                                                    id="max_cod_order_amount" min="0" placeholder="Enter Maximum COD Order Amount">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-0">
                                                    <label class="input-label" for="exampleFormControlInput1">Platform Charge</label>
                                                    <input type="number" min="0" max="999999999999.99"  name="platform_charge" value="" class="form-control" placeholder="Enter Price" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-0">
                                                    <label class="input-label" for="exampleFormControlInput1">Platform Charge Original</label>
                                                    <input type="number" min="0" max="999999999999.99"  name="platform_charge_original" value="" class="form-control" placeholder="Enter Price" required="">
                                                </div>
                                            </div>           

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="input-label" for="latitude">Latitude</label>
                                                    <input type="text" id="latitude" name="latitude" class="form-control h--45px disabled" placeholder="Ex : -94.22213" value="" required="" readonly="">
                                                </div> 
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group mb-md-0">
                                                    <label class="input-label" for="longitude">Longitude</label>
                                                    <input type="text" name="longitude" class="form-control h--45px disabled" placeholder="Ex : 103.344322" id="longitude" value="" required="" readonly="">
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-6 mt-3 mx-auto">
                                                    <div class="form-group d-flex">
                                                        <input type="text" id="search-address-input" class="form-control rounded-0" placeholder="Enter Address or Place">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Drawing Controls -->
                                            <div class="row mb-3">
                                                <div class="col-md-8">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-3">
                                                                <i class="fas fa-map-marked-alt text-primary"></i> Zone Area Selection
                                                            </h6>
                                                            <div class="btn-group mb-2" role="group">
                                                                <button type="button" class="btn btn-outline-primary btn-sm" id="draw-polygon">
                                                                    <i class="fas fa-draw-polygon"></i> Draw Zone Area
                                                                </button>
                                                                <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-polygon" disabled>
                                                                    <i class="fas fa-eraser"></i> Clear Area
                                                                </button>
                                                                <button type="button" class="btn btn-outline-info btn-sm" id="edit-polygon" disabled>
                                                                    <i class="fas fa-edit"></i> Edit Area
                                                                </button>
                                                            </div>
                                                            <div class="alert alert-info alert-sm mb-0">
                                                                <small>
                                                                    <strong>Instructions:</strong><br>
                                                                    1. Click "Draw Zone Area" to start drawing<br>
                                                                    2. Click on the map to add points<br>
                                                                    3. Double-click to complete the polygon<br>
                                                                    4. Use "Edit Area" to modify the shape
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-3">
                                                                <i class="fas fa-info-circle text-info"></i> Zone Info
                                                            </h6>
                                                            <div id="zone-info">
                                                                <p class="text-muted mb-1"><small>Status: <span id="zone-status">No area selected</span></small></p>
                                                                <p class="text-muted mb-1"><small>Points: <span id="zone-points">0</span></small></p>
                                                                <p class="text-muted mb-0"><small>Area: <span id="zone-area">0 km²</span></small></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="map-canvas" style=" width:100%;height: 50vh"></div>
                                            <!-- Hidden input to store polygon coordinates -->
                                            <input type="hidden" id="zone-coordinates" name="zone_coordinates" value="">
                                        </div>
                                        <div class="btn--container mt-3 justify-content-end">
                                            <button id="reset_btn" type="button" class="btn btn-secondary">Reset</button>
                                            <button type="submit" class="btn btn-warning">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection
<!-- End Table -->

@push('javascript')
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places,drawing&callback=initMap">
    </script>
    <script>
        let map;
        let marker;
        let drawingManager;
        let currentPolygon = null;
        let isDrawingMode = false;

        function initMap() {
            // Try to get user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const currentLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        createMapWithLocation(currentLocation);
                    },
                    (error) => {
                        console.error('Geolocation error:', error);
                        // Fallback to default location (Delhi, India)
                        const defaultLocation = { lat: 28.6139, lng: 77.2090 };
                        createMapWithLocation(defaultLocation);
                    }
                );
            } else {
                // Fallback to default location
                const defaultLocation = { lat: 28.6139, lng: 77.2090 };
                createMapWithLocation(defaultLocation);
            }
        }

        function createMapWithLocation(currentLocation) {
            try {
                console.log('Creating map with location:', currentLocation);
                
                // Create the map
                map = new google.maps.Map(document.getElementById('map-canvas'), {
                    center: currentLocation,
                    zoom: 15,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: true,
                    streetViewControl: true,
                    fullscreenControl: true
                });
                
                console.log('Map created successfully');

                // Create the marker
                marker = new google.maps.Marker({
                    position: currentLocation,
                    map: map,
                    draggable: true,
                    title: "Zone Center",
                    icon: {
                        url: "{{ asset('assets/user/img/icons/marker-icon.png') }}",
                        scaledSize: new google.maps.Size(40, 40)
                    }
                });

                // Initialize Drawing Manager
                initDrawingManager();

                // Set initial position in form fields
                setElementsPosition(currentLocation);

                // Setup address search autocomplete
                var input = document.getElementById('search-address-input');
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.addListener('place_changed', function() {
                    var place = autocomplete.getPlace();

                    if (!place.geometry) {
                        console.log("No details available for input: '" + place.name + "'");
                        return;
                    }
                    
                    const newLocation = place.geometry.location.toJSON();
                    map.setCenter(newLocation);
                    marker.setPosition(newLocation);
                    setElementsPosition(newLocation);
                });

                // Handle marker drag events
                google.maps.event.addListener(marker, 'dragend', function() {
                    const position = marker.getPosition().toJSON();
                    map.setCenter(position);
                    setElementsPosition(position);
                });

                // Ensure event listeners are set up
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', setupEventListeners);
                } else {
                    setupEventListeners();
                }
                
                console.log('Map initialization completed');
            } catch (error) {
                console.error('Error creating map:', error);
                showNotification('Error loading map. Please refresh the page.', 'danger');
            }
        }

        function initDrawingManager() {
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: null,
                drawingControl: false,
                polygonOptions: {
                    fillColor: '#007bff',
                    fillOpacity: 0.3,
                    strokeWeight: 3,
                    strokeColor: '#007bff',
                    clickable: true,
                    editable: true,
                    zIndex: 1
                }
            });

            drawingManager.setMap(map);

            // Listen for polygon completion
            google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
                // Remove previous polygon if exists
                if (currentPolygon) {
                    currentPolygon.setMap(null);
                }
                
                currentPolygon = polygon;
                drawingManager.setDrawingMode(null);
                isDrawingMode = false;
                
                // Update button states
                updateButtonStates();
                
                // Save polygon coordinates
                savePolygonCoordinates();
                
                // Add listeners for polygon changes
                addPolygonListeners(polygon);
                
                // Show success message
                showNotification('Zone area created successfully!', 'success');
            });
        }

        function showNotification(message, type = 'info') {
            // Create a simple notification
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        function updatePolygonStyle(isEditable) {
            if (currentPolygon) {
                if (isEditable) {
                    // Make polygon more visible in edit mode
                    currentPolygon.setOptions({
                        fillColor: '#ff6b35',
                        fillOpacity: 0.4,
                        strokeColor: '#ff6b35',
                        strokeWeight: 4
                    });
                } else {
                    // Normal polygon style
                    currentPolygon.setOptions({
                        fillColor: '#007bff',
                        fillOpacity: 0.3,
                        strokeColor: '#007bff',
                        strokeWeight: 3
                    });
                }
            }
        }

        function addPolygonListeners(polygon) {
            google.maps.event.addListener(polygon.getPath(), 'set_at', savePolygonCoordinates);
            google.maps.event.addListener(polygon.getPath(), 'insert_at', savePolygonCoordinates);
            google.maps.event.addListener(polygon.getPath(), 'remove_at', savePolygonCoordinates);
        }

        function setElementsPosition(position) {
            document.getElementById("latitude").value = position.lat;
            document.getElementById("longitude").value = position.lng;
        }

        function savePolygonCoordinates() {
            if (currentPolygon) {
                const path = currentPolygon.getPath();
                const coordinates = [];
                
                for (let i = 0; i < path.getLength(); i++) {
                    const point = path.getAt(i);
                    coordinates.push({
                        lat: point.lat(),
                        lng: point.lng()
                    });
                }
                
                document.getElementById('zone-coordinates').value = JSON.stringify(coordinates);
                
                // Update center point based on polygon centroid
                if (coordinates.length > 0) {
                    const centroid = getPolygonCentroid(coordinates);
                    setElementsPosition(centroid);
                    marker.setPosition(centroid);
                }
                
                // Update zone info
                updateZoneInfo();
            }
        }

        function getPolygonCentroid(coordinates) {
            let lat = 0, lng = 0;
            coordinates.forEach(coord => {
                lat += coord.lat;
                lng += coord.lng;
            });
            return {
                lat: lat / coordinates.length,
                lng: lng / coordinates.length
            };
        }

        function updateButtonStates() {
            const drawBtn = document.getElementById('draw-polygon');
            const editBtn = document.getElementById('edit-polygon');
            const clearBtn = document.getElementById('clear-polygon');
            
            console.log('Updating button states, currentPolygon:', currentPolygon);
            
            if (isDrawingMode) {
                drawBtn.classList.add('active');
                drawBtn.innerHTML = '<i class="fas fa-stop"></i> Stop Drawing';
            } else {
                drawBtn.classList.remove('active');
                drawBtn.innerHTML = '<i class="fas fa-draw-polygon"></i> Draw Zone Area';
            }
            
            editBtn.disabled = !currentPolygon;
            clearBtn.disabled = !currentPolygon;
            
            if (currentPolygon) {
                editBtn.classList.remove('btn-outline-info');
                editBtn.classList.add('btn-info');
                clearBtn.classList.remove('btn-outline-secondary');
                clearBtn.classList.add('btn-secondary');
            } else {
                editBtn.classList.add('btn-outline-info');
                editBtn.classList.remove('btn-info');
                clearBtn.classList.add('btn-outline-secondary');
                clearBtn.classList.remove('btn-secondary');
            }
        }

        function updateZoneInfo() {
            const statusElement = document.getElementById('zone-status');
            const pointsElement = document.getElementById('zone-points');
            const areaElement = document.getElementById('zone-area');
            
            if (currentPolygon) {
                const path = currentPolygon.getPath();
                const pointCount = path.getLength();
                
                // Calculate area using Google Maps geometry library
                const area = google.maps.geometry.spherical.computeArea(path);
                const areaInKm = (area / 1000000).toFixed(2); // Convert to km²
                
                statusElement.textContent = 'Area defined';
                statusElement.className = 'text-success';
                pointsElement.textContent = pointCount;
                areaElement.textContent = areaInKm + ' km²';
            } else {
                statusElement.textContent = 'No area selected';
                statusElement.className = 'text-muted';
                pointsElement.textContent = '0';
                areaElement.textContent = '0 km²';
            }
        }

        // Drawing Controls Event Listeners
        function setupEventListeners() {
            // Initialize zone info display
            updateZoneInfo();
            
            document.getElementById('draw-polygon').addEventListener('click', function() {
                if (isDrawingMode) {
                    drawingManager.setDrawingMode(null);
                    isDrawingMode = false;
                } else {
                    // Clear existing polygon before drawing new one
                    if (currentPolygon) {
                        if (confirm('This will replace the current zone area. Continue?')) {
                            currentPolygon.setMap(null);
                            currentPolygon = null;
                        } else {
                            return;
                        }
                    }
                    drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
                    isDrawingMode = true;
                }
                updateButtonStates();
            });

            document.getElementById('clear-polygon').addEventListener('click', function() {
                if (currentPolygon && confirm('Are you sure you want to clear the zone area?')) {
                    currentPolygon.setMap(null);
                    currentPolygon = null;
                    document.getElementById('zone-coordinates').value = '';
                    updateButtonStates();
                    updateZoneInfo();
                    showNotification('Zone area cleared!', 'warning');
                }
            });

            document.getElementById('edit-polygon').addEventListener('click', function() {
                console.log('Edit button clicked, currentPolygon:', currentPolygon);
                
                if (currentPolygon) {
                    const isEditable = currentPolygon.getEditable();
                    console.log('Current editable state:', isEditable);
                    
                    currentPolygon.setEditable(!isEditable);
                    updatePolygonStyle(!isEditable);
                    
                    if (!isEditable) {
                        // Entering edit mode
                        this.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                        this.classList.remove('btn-outline-info');
                        this.classList.add('btn-warning');
                        showNotification('Edit mode enabled. Drag the polygon points to modify the shape.', 'info');
                    } else {
                        // Exiting edit mode
                        this.innerHTML = '<i class="fas fa-edit"></i> Edit Area';
                        this.classList.remove('btn-warning');
                        this.classList.add('btn-outline-info');
                        savePolygonCoordinates();
                        showNotification('Changes saved successfully!', 'success');
                    }
                } else {
                    showNotification('No zone area to edit. Please draw an area first.', 'warning');
                }
            });

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                console.log('Form submit triggered');
                
                // Force save current polygon coordinates if in edit mode
                if (currentPolygon && currentPolygon.getEditable()) {
                    savePolygonCoordinates();
                }
                
                const zoneCoordinates = document.getElementById('zone-coordinates').value;
                console.log('Zone coordinates on submit:', zoneCoordinates);
                
                if (!zoneCoordinates || zoneCoordinates === '') {
                    e.preventDefault();
                    showNotification('Please define the zone area by drawing a polygon on the map.', 'danger');
                    document.getElementById('draw-polygon').focus();
                    return false;
                }
                
                try {
                    const coords = JSON.parse(zoneCoordinates);
                    if (coords.length < 3) {
                        e.preventDefault();
                        showNotification('Zone area must have at least 3 points.', 'danger');
                        return false;
                    }
                    
                    console.log('Form validation passed, submitting with', coords.length, 'coordinates');
                    
                    // Show loading state
                    const submitBtn = document.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Zone...';
                    
                } catch (error) {
                    console.error('Form validation error:', error);
                    e.preventDefault();
                    showNotification('Invalid zone coordinates. Please redraw the area.', 'danger');
                    return false;
                }
            });

            // Reset button functionality
            document.getElementById('reset_btn').addEventListener('click', function() {
                if (confirm('Are you sure you want to reset all changes?')) {
                    // Reset form
                    document.querySelector('form').reset();
                    
                    // Clear polygon
                    if (currentPolygon) {
                        currentPolygon.setMap(null);
                        currentPolygon = null;
                    }
                    
                    // Reset coordinates
                    document.getElementById('zone-coordinates').value = '';
                    
                    // Update UI
                    updateButtonStates();
                    updateZoneInfo();
                    
                    // Reset drawing mode
                    drawingManager.setDrawingMode(null);
                    isDrawingMode = false;
                    
                    showNotification('Form reset successfully!', 'info');
                }
            });
        }

        // Debug function
        function debugZoneState() {
            console.log('=== Zone Debug Info ===');
            console.log('Map:', map);
            console.log('Marker:', marker);
            console.log('Drawing Manager:', drawingManager);
            console.log('Current Polygon:', currentPolygon);
            console.log('Is Drawing Mode:', isDrawingMode);
            console.log('Zone Coordinates Field:', document.getElementById('zone-coordinates').value);
            console.log('Latitude Field:', document.getElementById('latitude').value);
            console.log('Longitude Field:', document.getElementById('longitude').value);
            
            if (currentPolygon) {
                console.log('Polygon Editable:', currentPolygon.getEditable());
                console.log('Polygon Path Length:', currentPolygon.getPath().getLength());
            }
            console.log('======================');
        }
        
        // Make functions globally available
        window.initMap = initMap;
        window.debugZoneState = debugZoneState;
    </script>
@endpush
