class CreateMap {
    constructor() {
        this.currentPosition = null;
        this.map = null;
        this.marker = null;
        this.circle = null;
    }

    makeCircle(color, position) {
        return new google.maps.Circle({
            strokeColor: color,
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: color,
            fillOpacity: 0.35,
            map: this.map,
            center: position,
            clickable: false,
            radius: 1000 // 1 kilometer in meters
        });
    }

    makeMarker(location, image, draggable = true ) {
        return new google.maps.Marker({
            position: location,
            map: this.map,
            draggable: draggable,
            icon: {
                url: image,
                scaledSize: new google.maps.Size(50, 50), // scaled size
                // origin: new google.maps.Point(0,0), // origin
                // anchor: new google.maps.Point(0, 0) // anchor
            },
            title: "Current Location"
        });
    }

    createMap(location, obj) {
        this.map = new google.maps.Map(document.querySelector(obj.selector), {
            center: location,
            zoom: 18,
            disableDefaultUI: true,
            mapTypeId:  google.maps.MapTypeId.ROADMAP
        });

        if (obj.marker) {
            const markerImg = obj.marker.img ? obj.marker.img : null;
            this.marker = this.makeMarker(obj.marker.location, markerImg,obj.marker.draggable);
        }
        if (obj.circle) {
            const circleColor = obj.circle.color ? obj.circle.color : null;
            this.circle = this.makeCircle(circleColor, obj.circle.location);
        }

    }


    getDistance(startLocation, endLocation) {
        return new Promise((resolve, reject) => {
            if (!startLocation || !endLocation) {
                reject('Invalid start or end location');
                return;
            }

            const service = new google.maps.DistanceMatrixService();
            const request = {
                origins: [startLocation],
                destinations: [endLocation],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false,
            };

            service.getDistanceMatrix(request, (response, status) => {
                if (status === 'OK') {
                    const distance = response.rows[0].elements[0].distance.text;
                    resolve(distance);
                } else {
                    reject('Error fetching distance: ' + status);
                }
            });
        });
    }

    setElementsPosition(obj , selctor=null) {
        if (selctor) {
            document.querySelector(selctor.latitude).value = obj.lat();
            document.querySelector(selctor.longitude).value = obj.lng();
        }
    }

    geocodeAddress(selctor) {
        var geocoder = new google.maps.Geocoder();
        var address = document.querySelector(selctor).value;
        var map = this.map;
        var marker = this.marker;
        console.log(address);

        geocoder.geocode({ 'address': address }, function (results, status) {
            if (status === 'OK') {
                var formattedAddress = results[0].formatted_address;
                const location = results[0].geometry.location;
                map.setCenter(location);
                marker.setPosition(location);
                map=marker= null;
            } else {
                console.error('Geocode was not successful for the following reason: ' + status);
            }
        });
    }


}
