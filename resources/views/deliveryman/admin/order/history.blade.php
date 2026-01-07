@extends('deliveryman.restaurant.layouts.main')

@section('content')
    <div class="osahan-home-page">
        <div class="res-section mt-4">
            <!-- Moblile header end -->
            <div class="main">
                <div class="container">
                    @include('deliveryman.restaurant.layouts.activate')
                </div>
                <div class="container position-relative">
                    <div class="row justify-content-center pt-3">
                        <div class="col-12 col-lg-8">
                            {{-- <div class="osahan-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">
                                <div class="osahan-cart-item-profile bg-white p-3">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-3 fw-bold">Delivery Address</h6>
                                        <div class="row g-4 mb-3">
                                            <div class="col-lg-6">
                                                <div class="form-check position-relative border-custom-radio p-0">
                                                    <input type="radio" id="customRadioInline1" name="customRadioInline1"
                                                        class="form-check-input" checked="">
                                                    <label class="form-check-label w-100 border rounded"
                                                        for="customRadioInline1"></label>
                                                    <div>
                                                        <div class="p-3 bg-white rounded rounded-bottom-0 shadow-sm w-100">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <h6 class="mb-0">Home</h6>
                                                                <p class="mb-0 badge text-bg-success ms-auto"><i
                                                                        class="icofont-check-circled"></i> Default</p>
                                                            </div>
                                                            <p class="small text-muted m-0">1001 Veterans Blvd</p>
                                                            <p class="small text-muted m-0">Redwood City, CA 94063</p>
                                                        </div>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal"
                                                            class="btn btn-light border-top w-100 rounded-top-0">Edit</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-check position-relative border-custom-radio p-0">
                                                    <input type="radio" id="customRadioInline2" name="customRadioInline1"
                                                        class="form-check-input">
                                                    <label class="form-check-label w-100 border rounded"
                                                        for="customRadioInline2"></label>
                                                    <div>
                                                        <div class="p-3 rounded rounded-bottom-0 bg-white shadow-sm w-100">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <h6 class="mb-0">Work</h6>
                                                                <p class="mb-0 badge text-bg-light ms-auto"><i
                                                                        class="icofont-check-circled"></i> Select</p>
                                                            </div>
                                                            <p class="small text-muted m-0">Model Town, Ludhiana</p>
                                                            <p class="small text-muted m-0">Punjab 141002, India</p>
                                                        </div>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal"
                                                            class="btn btn-light border-top w-100 rounded-top-0">Edit</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="btn btn-primary" href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal"> ADD NEW ADDRESS </a>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="pb-3">
                                <div class="p-3 rounded shadow-sm bg-white">
                                    <div class="d-flex border-bottom pb-3">
                                        <div class="text-muted me-3">
                                            <img alt="#" src="img/popular5.png" class="img-fluid order_img rounded">
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold"><a href="restaurant.html" class="text-dark">Conrad
                                                    Chicago Restaurant</a></p>
                                            <p class="mb-0">Punjab, India</p>
                                            <p>ORDER #321DERS</p>
                                            <p class="mb-0 small"><a href="status_complete.html">View Details</a></p>
                                        </div>
                                        <div class="ms-auto">
                                            <p class="bg-success text-white py-1 px-2 rounded small text-center mb-1">
                                                Delivered</p>
                                            <p class="small fw-bold text-center"><i class="feather-clock"></i> 06/04/2023
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex pt-3">
                                        <div class="small">
                                            <p class="text- fw-bold mb-0">Kesar Sweet x 1</p>
                                            <p class="text- fw-bold mb-0">Gulab Jamun x 4</p>
                                        </div>
                                        <div class="text-muted m-0 ms-auto me-3 small">Total Payment<br>
                                            <span class="text-dark fw-bold">$12.74</span>
                                        </div>
                                        <div class="text-end">
                                            <a href="checkout.html" class="btn btn-primary px-3">Reorder</a>
                                            <a href="contact-us.html" class="btn btn-outline-primary px-3">Help</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
