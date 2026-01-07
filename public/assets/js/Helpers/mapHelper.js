// gmap

const myMap = {
    currentPosition: null,
    map: null,
    marker: null, 
    circle: null,

    makeCircle: function (color, position) {
        return new google.maps.Circle({
            strokeColor: color,
            strokeOpacity: 0.8,
            strokeWeight: 2, 
            fillColor: color,
            fillOpacity: 0.35,
            map: this.map,
            center: position,
            clickable : false,
            radius: 1000 // 17 kilometers in meters
        });
    },

    makeMarker: function (location,draggable= true ,image =null) {
        return new google.maps.Marker({
            position: location,
            map: this.map,
            draggable : draggable,
            title: "Current Location",
            // icon: image,
            icon: {
                url: image,
                scaledSize: new google.maps.Size(50, 50), // scaled size
                // origin: new google.maps.Point(0,0), // origin
                // anchor: new google.maps.Point(0, 0) // anchor
            }
        });
    },

    CreateMap: function (location, obj ) {
        this.map = new google.maps.Map(document.querySelector(obj.selector), {
            center: location,
            zoom: 18,
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        if(obj.marker) {
            const markerimg = obj.marker.img?obj.marker.img:null;
            this.marker = this.makeMarker(obj.marker.location,obj.marker.draggable, markerimg);
        }
        if(obj.circle) {
            const c_color = obj.circle.color?obj.circle.color:null;
            this.circle = this.makeCircle(c_color, obj.circle.location);
        }
        if(obj.mapClick){
            this.mapClickEvent();
        }
        if(obj.mapDrag){
            this.mapDragEvent();
        }

        // console.log(google.maps);
        
    },

    mapClickEvent: function () {
        google.maps.event.addListener(this.map, 'click', (event) => {
            
            if (google.maps.geometry.spherical.computeDistanceBetween(event.latLng, this.circle.getCenter()) <= this.circle.getRadius()) {
                
                this.marker.setMap(null);
                this.marker = this.makeMarker(event.latLng);
                this.setElementsPosition({ lat: event.latLng.lat(), lng: event.latLng.lng() });
                this.mapDragEvent();
            } else {
                toastr.error('Marker cannot be placed outside the circle radius.');
            }
        });
    },
    mapDragEvent: function () {
        google.maps.event.addListener(this.marker, 'dragend', (event) => {
            if (google.maps.geometry.spherical.computeDistanceBetween(event.latLng, this.circle.getCenter()) <= this.circle.getRadius()) {
               
                this.setElementsPosition({ lat: event.latLng.lat(), lng: event.latLng.lng() });
            } else {
                toastr.error('Marker cannot be placed outside the circle radius.');
            }
        });
    },

    getDistance: function (startLocation, endLocation) {
        return new Promise((resolve, reject) => {
            if (!startLocation || !endLocation) {
                reject('Invalid start or end location');
                return;
            }

            // Initialize the Distance Matrix service
            const service = new google.maps.DistanceMatrixService();

            // Define the request for the Distance Matrix Service
            const request = {
                origins: [startLocation],
                destinations: [endLocation],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false,
            };

            // Make the request to the Distance Matrix Service
            service.getDistanceMatrix(request, (response, status) => {
                if (status === 'OK') {
                    const distance = response.rows[0].elements[0].distance.text;
                    reject("afsdjfhdkjfd");
                    // resolve(distance);
                } else {
                    reject('Error fetching distance: ' + status);
                }
            });
        });
    },

    setElementsPosition: function (obj) {
        document.getElementById("latitude").value = obj.lat;
        document.getElementById("longitude").value = obj.lng;
    },

    autocomplete : function (elementId) {
        var input = document.getElementById(elementId);

        var autocomplete = new google.maps.places.Autocomplete(input);
        // autocomplete.bindTo("bounds", this.map);

        console.log(input)
        console.log(autocomplete)
    
        autocomplete.addListener('place_changed', function() {
    
            var place = autocomplete.getPlace();
            console.log(place)
            
            if (!place.geometry) {
                console.log("No details available for input: '" + place.name + "'");
                return;
            }
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            return {
                lat: latitude,
                lng: longitude
            };
    
        });
    }
};
