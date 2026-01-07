@extends('user-views.restaurant.layouts.main')
@push('css')
<style>
    .print-button {
            margin-top: 20px;
        }
        .restaurant-images img, .restaurant-documents img {
            width: 200px;
            height: auto;
            margin-right: 10px;
        }
</style>
@endpush
@section('containt')
    <div
        style="background: url(https://static.vecteezy.com/system/resources/previews/009/715/641/non_2x/abstract-gradient-geometric-background-dynamic-orange-poster-graphics-abstract-background-texture-design-vector.jpg);
    background-color: #ffffff;
    background-blend-mode: darken;">
        <div class="container position-relative" style="background: #ffffff63;max-width: 100%;">
            <div class="py-5 osahan-profile row d-flex justify-content-center" style="backdrop-filter: blur(6px);">
                <div class="col-md-8 mb-3">
                    <div class="rounded shadow-sm p-4 bg-white">
                        <div class="container mt-5">
                            {{-- 'joinasDeliveryman','kyc','documentDetails' --}}
                            <h1 class="text-center">Deliveryman Registration Successful</h1>
                            <p class="text-center">Thank you for registering your Deliveryman with us. Below are the details you provided:</p>

                            <div class="restaurant-details mt-4">
                                <h2>Deliveryman Details</h2>
                                <p><strong>Registraion no : {{$joinasDeliveryman['registration_no']}}</strong></p>
                                <p><strong>Date : {{$joinasDeliveryman['created_at']}}</strong></p>
                                <p><strong>Deliveryman Name:</strong> {{ $joinasDeliveryman['deliveryman_name'] }}</p>
                                <p><strong>Bike NO:</strong> {{ $joinasDeliveryman['bike_number'] }}</p>
                                <p><strong>Address:</strong> {{ $joinasDeliveryman['deliveryman_address'] }}</p>
                                <p><strong>Contact:</strong> {{ $joinasDeliveryman['deliveryman_phone'] }}</p>
                                <p><strong>Email:</strong> {{ $joinasDeliveryman['deliveryman_email'] }}</p>
                                <p><strong>Status :</strong> {{ $joinasDeliveryman['status'] }}</p>

                            </div>

                            <div class="restaurant-documents mt-4">
                                <h2>Documents</h2>
                                <div class="restaurant-details mt-4">
                                    @foreach($documentDetails as $documentDeatil)
                                    @php
                                        $documentDeatil = App\Models\DocumentDetails::with('document')->find($documentDeatil['id']);
                                    @endphp
                                    <p><strong>{{Str::ucfirst($documentDeatil->document->name)}}:</strong> {{ $documentDeatil['text_value'] }}</p>

                                    @endforeach
                                </div>
                            </div>

                            <button class="btn btn-primary print-button" onclick="window.print()">Print</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


