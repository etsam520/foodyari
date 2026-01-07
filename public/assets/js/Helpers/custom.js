function readImage(input, selector) {
    try {
        const reader = new FileReader();
        reader.onload = function (e) {
            const imgSrc = e.target.result;
            document.querySelector(selector).src = imgSrc;
        };
        reader.readAsDataURL(input.files[0]);
    } catch (error) {
        console.error(error);
    }

}

function product_discount(price, discount, d_type = 'amount') {
    if (d_type === 'percent') {
        return parseInt(price) - (parseInt(price) * parseInt(discount) / 100);
    } else {
        return parseInt(price) - parseInt(discount);
    }
}

function currencySymbolsuffix(amount, symbol = 'INR') {
    let icon = { 'USD': '$', 'INR': '₹' };
    return icon[symbol] + ' ' + amount;
}

function haversineDistance(point1, point2) {
    const earthRadius = 6371; // Radius of the earth in kilometers

    const lat1 = point1.lat;
    const lng1 = point1.lng;
    const lat2 = point2.lat;
    const lng2 = point2.lng;

    const toRadians = (degrees) => (degrees * Math.PI) / 180;

    const dLat = toRadians(lat2 - lat1);
    const dLng = toRadians(lng2 - lng1);

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
        Math.sin(dLng / 2) * Math.sin(dLng / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = earthRadius * c; // Distance in kilometers

    return distance;
}
/*
 * Ray-casting algorithm to determine if point is in polygon
    * point: [lng, lat]
    * vs: array of [lng, lat] vertices
    * Returns true if the point is inside the polygon, false otherwise
    * Source: https://en.wikipedia.org/wiki/Point_in_polygon
    * Usage: isPointInPolygon([lng, lat], [[lng1, lat1], [lng2, lat2], ...])
    * Note: Ensure the polygon is closed (first and last vertices are the same)
    * Handles complex polygons and edge cases
 */
function isPointInPolygon(point, vs) {
  const x = point[0], y = point[1];
  let inside = false;

  for (let i = 0, j = vs.length - 1; i < vs.length; j = i++) {
    const xi = vs[i][0], yi = vs[i][1];
    const xj = vs[j][0], yj = vs[j][1];

    const intersect = ((yi > y) !== (yj > y)) &&
                      (x < ((xj - xi) * (y - yi)) / (yj - yi) + xi);
    if (intersect) inside = !inside;
  }

  return inside;
}
function getCookie(name) {
    let nameEQ = name + "=";
    let cookiesArray = document.cookie.split(';');
    for (let i = 0; i < cookiesArray.length; i++) {
        let cookie = cookiesArray[i];
        while (cookie.charAt(0) === ' ') cookie = cookie.substring(1, cookie.length);
        if (cookie.indexOf(nameEQ) === 0) return cookie.substring(nameEQ.length, cookie.length);
    }
    return null;
}

function setCookie(name, value, time, unit = 'days') {
    let expires = "";
    if (time) {
        let date = new Date();
        let milliseconds;

        switch (unit) {
            case 'seconds':
                milliseconds = time * 1000;
                break;
            case 'minutes':
                milliseconds = time * 60 * 1000;
                break;
            case 'hours':
                milliseconds = time * 60 * 60 * 1000;
                break;
            case 'days':
            default:
                milliseconds = time * 24 * 60 * 60 * 1000;
        }

        date.setTime(date.getTime() + milliseconds);
        expires = "; expires=" + date.toUTCString();
    }

    document.cookie = name + "=" + encodeURIComponent(value || "") + expires + "; path=/";
}
function convertKmToMeters(kilometers) {
    // Input validation: Check if the input is a valid number.
    if (typeof kilometers !== 'number' || isNaN(kilometers)) {
        console.error("Invalid input: Please provide a valid number for kilometers.");
        return null; // Return null or throw an error for invalid input
    }

    // Conversion factor: 1 kilometer = 1000 meters
    const meters = kilometers * 1000;

    return meters;
}


// Haversine formula to calculate distance between two points
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371e3; // Earth's radius in meters
    const φ1 = lat1 * Math.PI/180; // φ, λ in radians
    const φ2 = lat2 * Math.PI/180;
    const Δφ = (lat2-lat1) * Math.PI/180;
    const Δλ = (lon2-lon1) * Math.PI/180;

    const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
            Math.cos(φ1) * Math.cos(φ2) *
            Math.sin(Δλ/2) * Math.sin(Δλ/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return R * c; // Distance in meters
}

// Format distance for display
function formatDistance(meters) {
    if (meters < 1000) {
        return Math.round(meters) + 'm';
    } else {
        return (meters / 1000).toFixed(1) + 'km';
    }
}