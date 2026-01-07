@extends('deliveryman.admin.layouts.main')
@push('css')
<style>
    #otp-container{
	margin: 0;
	padding: 0;
	/* height: 100vh; */
/* background: #007bff;  fallback for old browsers */

}

.otp-box{
	display: flex;
	flex-flow: column;
	height: 100%;
    line-height: 1.6rem;
	align-items: space-around;
	justify-content: center;
}

.userInput{
	display: flex;
	justify-content: center;
}

.userInput input{
	margin: 10px;
	width: 20px;
	border: none;
	border-radius: 5px;
	text-align: center;
	font-family: arimo;
	font-size: 1.2rem;
	background: #eef2f3;

}

.otp-submit-button{
	width: 150px;
	height: 40px;
	margin: 25px auto 0px auto;
	font-family: arimo;
	font-size: 1.1rem;
	border: none;
	border-radius: 5px;
	letter-spacing: 2px;
	cursor: pointer;
	background: #616161;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #9bc5c3, #616161);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #9bc5c3, #616161); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

}
#qr-preview{
    width: 100%;
    height: 100%;
  }

</style>
    
@endpush
@section('content')
<?php
$address = json_decode($order->delivery_address);
?>

@include('deliveryman.admin.layouts.m-header')      
<div class="osahan-home-page">
    
    <!-- Moblile header end -->  
    <div class="main">
        <div class="container">
            @include('deliveryman.admin.layouts.activate')     
        </div>
        <div class="container position-relative">
            <div class="row justify-content-center pt-3">
                <div class="col-12 col-md-12">
                    <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                        <h3 class="mb-0 p-2 px-3 text-primary">
                           Delivery Order Track</h3>
                        <div class="row m-0">
                            <div class="col-md-8 px-0 border-top">
                                <div class="d-flex align-items-top gap-2 p-3 border-bottom gold-members">
                                    
                                    <div class="w-75">
                                        <div class="d-lg-flex align-items-center gap-2">
                                            <div>
                                                @php($address = json_decode($order->delivery_address))
                                                <h4 class="mb-1">
                                                    <i class="feather-user"></i>
                                                    {{Str::ucfirst($order->customer->f_name)}}  {{Str::ucfirst($order->customer->l_name)}}
                                                </h4>
                                                {{-- <small class="text-black-50">Quantity - 1 (Full)</small> --}}
                                                <a href="tel:{{isset($address->phone)? $address->phone : $order->customer->phone}}" class="mb-0 mt-1 text-black-50 feather-phone">
                                                    {{isset($address->phone)? $address->phone : $order->customer->phone}}
                                                </a>
                                                <a href="https://www.google.com/maps?q={{$address->position->lat}},{{$address->position->lon}}" class="mb-0 mt-1 text-black-50 text-wrap feather-map-pin">
                                                {{App\CentralLogics\Helpers::formatDistance($order->distance)}}, {{Str::ucfirst($address->stringAddress)}}
                                                </a>
                                            </div>
                                            <div class="ms-auto mt-1 d-flex flex-column justify-content-evenly">
                                                {{-- <span class="badge bg-primary fs-5 mb-2">Tiffin NO - ****</span> --}}
                                                <span class="btn btn-sm btn-warning" data-scan="true" data-bs-toggle="modal" data-bs-target="#qr-varify"  type="button">Varify QR</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-md-12 py-2 mx-auto ">
                                <div id="map-canvas2" style=" width:100%;height: 50vh"></div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<div class="modal fade" id="qr-varify" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 p-0" data-wrapper="qr" >
                        <div class="scanner-wrapper ">
                           <div class="scanner">
                              <video id="qr-preview"></video>
                           </div>
                        </div>
                    </div>
                    <div class="col-12 bg-primary " id="otp-container" data-wrapper="otp">
                        <div class="d-flex flex-column justify-content-center otp-box ">
                           <h5 class="text-center text-white">ENTER OTP</h5>  
                           <div class="userInput d-flex" data-userInput="list">
                              <input type="text" id='ist' maxlength="1" onfocus="this.value=null" onkeyup="clickEvent(this,'sec')">
                              <input type="text" id="sec" maxlength="1"onfocus="this.value=null" onkeyup="clickEvent(this,'third')">
                              <input type="text" id="third" maxlength="1"onfocus="this.value=null" onkeyup="clickEvent(this,'fourth')">
                              <input type="text" id="fourth" maxlength="1"onfocus="this.value=null" onkeyup="clickEvent(this,'fifth')">
                              <input type="text" id="fifth" maxlength="1"onfocus="this.value=null" onkeyup="clickEvent(this,'sixth')">
                              <input type="text" id="sixth" maxlength="1"onfocus="this.value=null">
                           </div>
                           <button class="btn btn-warning text-white" data-submit="otp" >CONFIRM</button>
                        </div>
                       
                        <script>
                           function clickEvent(first,last){
                              if(first.value.length){
                                 document.getElementById(last).focus();
                              }
                           }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('javascript')
<script type="module">
    /*=====================// QR Module //===========================*/
    import QrScanner from "{{asset('assets/vendor/qr-scanner/qr-scanner.min.js')}}";
 
    const scanBtn = document.querySelector('[data-scan]');
    const qrWrapper =document.querySelector('[data-wrapper=qr]');
    const otpWrapper = document.querySelector('[data-wrapper=otp]');
    var qrScanner = null;
    var camarOpen = false;
    let scanned = false;
    
    const videoElem = document.querySelector('#qr-preview');
    qrScanner = new QrScanner(videoElem, async (result) => {
        if (result && !scanned) {
            scanned = true;
            qrScanner.stop();
            try {
                const resp = await fetch("{{ route('deliveryman.mess.order-varify-qr')}}?encrypted_code=" + result);
                const resultData = await resp.json();
                
                if (resultData.success) {
                    Swal.fire({ title: resultData.success,icon: "success", timer: 1000 });
                    setTimeout(function() {
                        location.href = resultData.link ? resultData.link : "{{ route('deliveryman.admin.order-list', ['state' => 'pickedUp']) }}";
                    }, 1200);

                } else {
                    throw new Error(resultData.error || 'Something went wrong');
                }
            } catch (error) {
                toastr.error(error.message);
            }
        }
    });
 
    scanBtn.addEventListener('click', async function() {
        if (!camarOpen) {
            qrScanner.start();
            camarOpen = true;
        }

        setTimeout(() => {
            qrScanner.stop();
            camarOpen = false;
            scanned = false;
            $('#qr-varify').modal('hide');
            
        }, 30000);
    });

    document.querySelector('[data-submit=otp]').addEventListener('click',async ()=> {
    const inputLists = document.querySelectorAll('[data-userInput=list] input');
    let insertedOTP ='';

    for(let input of inputLists){
            insertedOTP += input.value;
            input.value = null;
    }
    try {
        if(insertedOTP != ''){
            const resp = await fetch("{{ route('deliveryman.admin.order-varify-qr') }}?otp=" + insertedOTP);
            const resultData = await resp.json();
            if (resultData.success) {
                Swal.fire({ title: resultData.success,icon: "success", timer: 1500 });
                setTimeout(function() {
                    location.href = resultData.link ? resultData.link : "{{ route('deliveryman.admin.order-list', ['state' => 'pickedUp']) }}";
                }, 1200);
            } else {
                throw new Error(resultData.error || 'Something went wrong');
            }
        }
    } catch (error) {
        toastr.error(error.message);
    }
    
    });
 
 
 
 </script>

    
{{-- <script src="{{ asset('assets/js/Helpers/mapHelperClass.js') }}"></script> --}}
<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     const map2 = new CreateMap();
    
    //     if (navigator.geolocation) {
    //         navigator.geolocation.getCurrentPosition((position) => {
    //             const currentLocation = {
    //                 lat: position.coords.latitude,
    //                 lng: position.coords.longitude
    //             };
    
    //             const customerPosition = {
    //                 lat: {{$address->position->lat}},
    //                 lng: {{$address->position->lon}}
    //             };
    
    //             map2.createMap(currentLocation, {
    //                 selector: "#map-canvas2",
    //                 mapClick: false,
    //                 mapDrag: false
    //             });
    
    //             map2.map.setCenter({
    //                 lat: (currentLocation.lat + customerPosition.lat) / 2,
    //                 lng: (currentLocation.lng + customerPosition.lng) / 2
    //             });
    
    //             const dmMarker = map2.makeMarker(currentLocation, false);
    //             const customerMarker = map2.makeMarker(customerPosition, false);
    
    //             const path = new google.maps.Polyline({
    //                 path: [currentLocation, customerPosition],
    //                 geodesic: true, // Make the line follow the curvature of the Earth
    //                 strokeColor: '#FF0000',
    //                 strokeOpacity: 1.0,
    //                 strokeWeight: 2
    //             });
    
    //             path.setMap(map2.map);
    
    //             const distance = google.maps.geometry.spherical.computeDistanceBetween(
    //                 new google.maps.LatLng(currentLocation.lat, currentLocation.lng),
    //                 new google.maps.LatLng(customerPosition.lat, customerPosition.lng)
    //             );
    
    //             const distanceInKm = (distance / 1000).toFixed(2);
    //             const infowindow = new google.maps.InfoWindow({
    //                 content: `Distance: ${distanceInKm} km`
    //             });
    
    //             infowindow.setPosition({
    //                 lat: (currentLocation.lat + customerPosition.lat) / 2,
    //                 lng: (currentLocation.lng + customerPosition.lng) / 2
    //             });
    
    //             infowindow.open(map2.map);
    //         });
    //     } else {
    //         console.error("Geolocation is not supported by this browser.");
    //     }
    // });
    </script>
    


@endpush
