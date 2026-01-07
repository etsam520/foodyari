@extends('user-views.layouts.main')
@section('content')
    

<div class="d-none">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="javascript:void(0)"><span></span></a>
        <h4 class="fw-bold m-0 text-white">Osahan Bar</h4>
    </div>
</div>
<div class="container">
    <div class="offer-slider">
        <div class="cat-item px-1 py-3">
            <a class="d-block text-center shadow-sm" href="trending.html">
                <img alt="#" src="{{asset('assets/user/img/banner-1.jpg')}}" class="img-fluid rounded">
            </a>
        </div>
        <div class="cat-item px-1 py-3">
            <a class="d-block text-center shadow-sm" href="trending.html">
                <img alt="#" src="{{asset('assets/user/img/banner-2.jpg')}}" class="img-fluid rounded">
            </a>
        </div>
        <div class="cat-item px-1 py-3">
            <a class="d-block text-center shadow-sm" href="trending.html">
                <img alt="#" src="{{asset('assets/user/img/banner-3.png')}}" class="img-fluid rounded">
            </a>
        </div>
        <div class="cat-item px-1 py-3">
            <a class="d-block text-center shadow-sm" href="trending.html">
                <img alt="#" src="{{asset('assets/user/img/banner-2.jpg')}}" class="img-fluid rounded">
            </a>
        </div>
        <div class="cat-item px-1 py-3">
            <a class="d-block text-center shadow-sm" href="trending.html">
                <img alt="#" src="{{asset('assets/user/img/banner-1.jpg')}}" class="img-fluid rounded">
            </a>
        </div>
        <div class="cat-item px-1 py-3">
            <a class="d-block text-center shadow-sm" href="trending.html">
                <img alt="#" src="{{asset('assets/user/img/banner-3.png')}}" class="img-fluid rounded">
            </a>
        </div>
    </div>
</div>
<div class="offer-section py-4 mt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="position-relative list-card">
                    <img alt="#" src="{{asset('assets/user/img/trending1.png')}}" class="restaurant-pic">
                    <div class="star mess-info position-absolute"><span class="badge text-bg-success"><i
                                class="feather-user"></i> Mess ID</span></div>
                    <div class="member-plan position-absolute"><span class=""><img alt="#" src="{{asset('assets/user/img/veg.png')}}"
                                class="img-fluid item-img w-100"></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="pt-3 text-white">
                    <h2 class="fw-bold">Mess Name (Chicago Restaurant)</h2>
                    <p class="text-white m-0"><i class="feather-map-pin me-1"></i>Address (963 Madyson Drive Suite 679)
                    </p>
                    <p class="label-rating text-white small mb-2"> Description/ Speciality</p>
                    <div class="rating-wrap d-flex align-items-center mt-2">
                        <ul class="rating-stars list-unstyled">
                            <li>
                                <i class="feather-star text-white"></i>
                                <i class="feather-star text-white"></i>
                                <i class="feather-star text-white"></i>
                                <i class="feather-star text-white"></i>
                                <i class="feather-star bg-dark text-white"></i>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex">
                    <p class="mb-0 me-2"><span class="bg-secondary text-white rounded py-1 px-2">Badge One</span></p>
                    <p class="mb-0"><span class="bg-success text-white rounded py-1 px-2">Badge Two</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container position-relative">
    <div class="mt-4 row">
        <div class="col-md-12">
            <div>
                <div class="osahan-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">
                    <div class="osahan-cart-item-profile bg-white p-3">
                        <div class="d-flex flex-column">
                            <div class="row g-4 mb-3">
                                <div class="col-lg-6">
                                    <h6 class="mb-3 fw-bold">Mess/Dine-in Time</h6>
                                    <div class="form-check position-relative border-custom-radio p-0">
                                        <label class="form-check-label w-100 border rounded"
                                            for="customRadioInline1"></label>
                                        <div>
                                            <div class="p-3 bg-white rounded rounded-bottom-0 shadow-sm w-100">
                                                <div class="row">
                                                    <div
                                                        class="col-lg-4 d-lg-block d-flex justify-content-center text-center">
                                                        <h6><small class="text-black-50">BREAKFAST</small>
                                                        </h6>
                                                        <p class="mb-0 mb-lg-2 ms-auto"><span
                                                                class="bg-light text-dark rounded py-1 px-2"><i
                                                                    class="feather-clock"></i> 15–30 min</span></p>
                                                    </div>
                                                    <div
                                                        class="col-lg-4 d-lg-block d-flex justify-content-center text-center">
                                                        <h6><small class="text-black-50">LUNCH</small>
                                                        </h6>
                                                        <p class="mb-0 mb-lg-2 ms-auto"><span
                                                                class="bg-light text-dark rounded py-1 px-2"><i
                                                                    class="feather-clock"></i> 15–30 min</span></p>
                                                    </div>
                                                    <div
                                                        class="col-lg-4 d-lg-block d-flex justify-content-center text-center">
                                                        <h6><small class="text-black-50">DINNER</small>
                                                        </h6>
                                                        <p class="mb-0 mb-lg-2 ms-auto"><span
                                                                class="bg-light text-dark rounded py-1 px-2"><i
                                                                    class="feather-clock"></i> 15–30 min</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h6 class="mb-3 fw-bold">Delivery Time</h6>
                                    <div class="form-check position-relative border-custom-radio p-0">
                                        <label class="form-check-label w-100 border rounded"
                                            for="customRadioInline1"></label>
                                        <div>
                                            <div class="p-3 bg-white rounded rounded-bottom-0 shadow-sm w-100">
                                                <div class="row">
                                                    <div
                                                        class="col-lg-4 d-lg-block d-flex justify-content-center text-center">
                                                        <h6><small class="text-black-50">BREAKFAST</small>
                                                        </h6>
                                                        <p class="mb-0 mb-lg-2 ms-auto"><span
                                                                class="bg-light text-dark rounded py-1 px-2"><i
                                                                    class="feather-clock"></i> 15–30 min</span></p>
                                                    </div>
                                                    <div
                                                        class="col-lg-4 d-lg-block d-flex justify-content-center text-center">
                                                        <h6><small class="text-black-50">LUNCH</small>
                                                        </h6>
                                                        <p class="mb-0 mb-lg-2 ms-auto"><span
                                                                class="bg-light text-dark rounded py-1 px-2"><i
                                                                    class="feather-clock"></i> 15–30 min</span></p>
                                                    </div>
                                                    <div
                                                        class="col-lg-4 d-lg-block d-flex justify-content-center text-center">
                                                        <h6><small class="text-black-50">DINNER</small>
                                                        </h6>
                                                        <p class="mb-0 mb-lg-2 ms-auto"><span
                                                                class="bg-light text-dark rounded py-1 px-2"><i
                                                                    class="feather-clock"></i> 15–30 min</span></p>
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
                <div class="osahan-card bg-white border-bottom overflow-hidden">
                    <div class="osahan-card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="d-flex p-3 fs-6 align-items-center btn btn-link w-100 text-warning"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                aria-expanded="false" aria-controls="collapseTwo">
                                <i class="feather-calendar me-3"></i> Weekly Menu
                                <i class="feather-chevron-down ms-auto"></i>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                        data-bs-parent="#accordionExample">
                        <div class="osahan-card-body border-top p-3">
                            <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" checked>
                                <label class="btn btn-veg-outline" for="btnradio1">Veg</label>
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio2">
                                <label class="btn btn-nv-outline" for="btnradio2">Non - Veg</label>
                            </div>
                            <!-- <hr> -->
                            <div class="mt-1">
                                <div class="btn-group w-100 overflow-x-scroll" role="group"
                                    aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="btndays" id="btndays1">
                                    <label class="btn btn-veg-outline btndays" for="btndays1">Monday</label>
                                    <input type="radio" class="btn-check" name="btndays" id="btndays2">
                                    <label class="btn btn-veg-outline btndays" for="btndays2">Tuesday</label>
                                    <input type="radio" class="btn-check" name="btndays" id="btndays3">
                                    <label class="btn btn-veg-outline btndays" for="btndays3">Wednesday</label>
                                    <input type="radio" class="btn-check" name="btndays" id="btndays4">
                                    <label class="btn btn-veg-outline btndays" for="btndays4">Thursday</label>
                                    <input type="radio" class="btn-check" name="btndays" id="btndays5">
                                    <label class="btn btn-veg-outline btndays" for="btndays">Friday</label>
                                    <input type="radio" class="btn-check" name="btndays" id="btndays6">
                                    <label class="btn btn-veg-outline btndays" for="btndays6">Saturday</label>
                                    <input type="radio" class="btn-check" name="btndays" id="btndays7">
                                    <label class="btn btn-veg-outline btndays" for="btndays7">Sunday</label>
                                </div>
                            </div>
                            <div>
                                <div class="w-100" id="three-meal">
                                    <div class="row">
                                        <div class="col-lg-4 col-12 mt-2">
                                            <div class="three-veg-meal three-meal rounded shadow-sm px-4 py-lg-5 py-4">
                                                <div class="text-center"><i class="fas fa-utensils mb-2 text-center"
                                                        style="height:40px;"></i></div>
                                                <h6 class="text-center border-bottom pb-3">BREAKFAST</h6>
                                                <p class="mb-2 text-black-50 mt-3">Spaghetti Carbonara is a
                                                    quintessential Italian pasta dish known for its creamy sauce and
                                                    rich flavor. It typically consists of al dente spaghetti noodles
                                                    tossed in a luxurious sauce made with eggs, Pecorino Romano
                                                    cheese, pancetta (or guanciale), black pepper, and sometimes a
                                                    splash of white wine.</p>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 1
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 2
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 3
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 4
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-12 mt-2">
                                            <div class="three-veg-meal three-meal rounded shadow-sm px-4 py-lg-5 py-4">
                                                <div class="text-center"><i class="fas fa-utensils mb-2 text-center"
                                                        style="height:40px;"></i></div>
                                                <h6 class="text-center border-bottom pb-3">LUNCH</h6>
                                                <p class="mb-2 text-black-50 mt-3">Spaghetti Carbonara is a
                                                    quintessential Italian pasta dish known for its creamy sauce and
                                                    rich flavor. It typically consists of al dente spaghetti noodles
                                                    tossed in a luxurious sauce made with eggs, Pecorino Romano
                                                    cheese, pancetta (or guanciale), black pepper, and sometimes a
                                                    splash of white wine.</p>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 1
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 2
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 3
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 4
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-12 mt-2">
                                            <div class="three-veg-meal three-meal rounded shadow-sm px-4 py-lg-5 py-4">
                                                <div class="text-center"><i class="fas fa-utensils mb-2 text-center"
                                                        style="height:40px;"></i></div>
                                                <h6 class="text-center border-bottom pb-3">DINNER</h6>
                                                <p class="mb-2 text-black-50 mt-3">Spaghetti Carbonara is a
                                                    quintessential Italian pasta dish known for its creamy sauce and
                                                    rich flavor. It typically consists of al dente spaghetti noodles
                                                    tossed in a luxurious sauce made with eggs, Pecorino Romano
                                                    cheese, pancetta (or guanciale), black pepper, and sometimes a
                                                    splash of white wine.</p>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 1
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 2
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 3
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 4
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
        </div>
    </div>
</div>
<!-- Menu -->
<div class="container position-relative">
    <div class="row">
        <div class="col-md-8 pt-3">
            <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                <div class="d-flex item-aligns-center">
                    <h6 class="p-3 m-0 bg-light w-100">Package Details</h6>
                </div>
                <div class="row m-0">
                    <div class="col-md-12 px-0 border-top">
                        <div class="">
                            <div class="d-flex gap-2 p-3 border-bottom gold-members">
                                <img alt="#" src="{{asset('assets/user/img/banner-1.jpg')}}" class="img-fluid package-img">
                                <div class="w-100">
                                    <div class="d-flex gap-2 mb-2">
                                        <div>
                                            <h6 class="mb-1">Chicken Tikka Sub &nbsp; - &nbsp;
                                                <span class="text-muted mb-0">₹250</span>
                                                <a href=""><i class="fas fa-eye ms-2 text-warning"></i></a>
                                            </h6>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="count-number float-end d-flex"><button type="button"
                                                    class="btn-sm left dec btn btn-outline-secondary"> <i
                                                        class="feather-minus"></i>
                                                </button><input class="count-number-input" type="text" readonly=""
                                                    value="2"><button type="button"
                                                    class="btn-sm right inc btn btn-outline-secondary"> <i
                                                        class="feather-plus"></i> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="border-top pt-2">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">No. of Normal Diet</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">No. of Special Diet</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Diet</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Breakfast</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Lunch</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Dinner</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 p-3 border-bottom gold-members">
                                <img alt="#" src="{{asset('assets/user/img/veg.png')}}" class="img-fluid package-img">
                                <div class="w-100">
                                    <div class="d-flex gap-2 mb-2">
                                        <div>
                                            <h6 class="mb-1">Chicken Tikka Sub &nbsp; - &nbsp;
                                                <span class="text-muted mb-0">₹250</span>
                                            </h6>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="count-number float-end d-flex"><button type="button"
                                                    class="btn-sm left dec btn btn-outline-secondary"> <i
                                                        class="feather-minus"></i>
                                                </button><input class="count-number-input" type="text" readonly=""
                                                    value="2"><button type="button"
                                                    class="btn-sm right inc btn btn-outline-secondary"> <i
                                                        class="feather-plus"></i> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="border-top pt-2">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">No. of Normal Diet</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">No. of Special Diet</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Diet</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Breakfast</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Lunch</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Dinner</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 p-3 border-bottom gold-members">
                                <img alt="#" src="{{asset('assets/user/img/banner-1.jpg')}}" class="img-fluid package-img">
                                <div class="w-100">
                                    <div class="d-flex gap-2 mb-2">
                                        <div>
                                            <h6 class="mb-1">Chicken Tikka Sub &nbsp; - &nbsp;
                                                <span class="text-muted mb-0">₹250</span>
                                            </h6>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="count-number float-end d-flex"><button type="button"
                                                    class="btn-sm left dec btn btn-outline-secondary"> <i
                                                        class="feather-minus"></i>
                                                </button><input class="count-number-input" type="text" readonly=""
                                                    value="2"><button type="button"
                                                    class="btn-sm right inc btn btn-outline-secondary"> <i
                                                        class="feather-plus"></i> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="border-top pt-2">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">No. of Normal Diet</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">No. of Special Diet</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Diet</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Breakfast</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Lunch</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="text-fw-bold mb-0">Total Dinner</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h6 class="p-3 m-0 bg-light w-100 border-bottom">Meal Collection</h6>
                            <div class="d-flex gap-2 border-bottom gold-members">
                                <div class="w-100">
                                    <!-- <div> -->
                                    <div class="row px-4 py-3">
                                        <div class="col-6">
                                            <div class="form-check custom-checkbox d-flex align-items-end">
                                                <input class="form-check-input" type="radio" value=""
                                                    id="flexCheckDefault"
                                                    style="font-size: 20px;border:1px solid #ff810a;" checked>
                                                <label class="form-check-label ms-3 fx-6" for="flexCheckDefault">
                                                    Deliver
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check custom-checkbox d-flex align-items-end">
                                                <input class="form-check-input" type="radio" value=""
                                                    id="flexCheckDefault"
                                                    style="font-size: 20px;border:1px solid #ff810a;">
                                                <label class="form-check-label ms-3 fx-6" for="flexCheckDefault">
                                                    Dine-in
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 p-0 mt-3">
                                            <div class="input-group">
                                                <span class="input-group-text" id="message"><i
                                                        class="feather-message-square"></i></span>
                                                <textarea placeholder="Any Special Requirement?"
                                                    aria-label="With textarea" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                            <h6 class="p-3 m-0 bg-light w-100 border-bottom">Billing Section <small
                                    class="text-black-50">Sub Total</small></h6>
                            <div class="bg-white p-3 clearfix border-bottom">
                                <p class="mb-1">Coupon <span class="text-info ms-1"><i
                                            class="feather-info"></i></span><span
                                        class="float-end text-dark">₹3140</span></p>
                                <p class="mb-1 text-success">Custom Discount <span
                                        class="float-end text-success">₹62.8</span></p>
                                <p class="mb-1">GST & Mess Charges<span class="text-info ms-1"><i
                                            class="feather-eye"></i></span><span class="float-end text-dark">₹10</span>
                                </p>
                                <p class="mb-1">Delivery Fee<span class="float-end text-dark">₹1884</span></p>
                                <p class="mb-1  text-warning">Donation<span class="float-end text-warning">₹1884</span>
                                </p>
                                <p class="mb-1  text-success">You Save<span class="float-end text-success">₹1884</span>
                                </p>
                                <hr>
                                <h6 class="fw-bold mb-0">TOTAL <span class="float-end">₹1329</span></h6>
                            </div>
                            <h6 class="p-3 m-0 bg-light w-100 border-bottom">Address</h6>
                            <div class="osahan-card-body border-bottom p-3">
                                <p class="mb-0"><i class="feather-map-pin me-1"></i> Digha - Ashiyana Road, Near
                                    Passport Office </p>
                            </div>
                            <h6 class="p-3 m-0 bg-light w-100 border-bottom">Payment Collection <small
                                    class="text-black-50">Type</small></h6>
                            <div class="d-flex gap-2 border-bottom gold-members">
                                <div class="w-100">
                                    <!-- <div> -->
                                    <div class="row px-4 py-3">
                                        <div class="col-6">
                                            <div class="form-check custom-checkbox d-flex align-items-end">
                                                <input class="form-check-input" type="radio" value=""
                                                    id="flexCheckDefault"
                                                    style="font-size: 20px;border:1px solid #ff810a;" checked>
                                                <label class="form-check-label ms-3 fx-6" for="flexCheckDefault">
                                                    Cash on Delivery
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check custom-checkbox d-flex align-items-end">
                                                <input class="form-check-input" type="radio" value=""
                                                    id="flexCheckDefault"
                                                    style="font-size: 20px;border:1px solid #ff810a;">
                                                <label class="form-check-label ms-3 fx-6" for="flexCheckDefault">
                                                    Gateway
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                            <div class="p-3">
                                <a class="btn btn-success w-100 btn-lg" href="{{route('user.mess.view4')}}">PAY ₹1329<i
                                        class="feather-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 pt-3">
            <h6 class="p-3 m-0 bg-light w-100">Total Mess <small class="text-black-50">3 </small></h6>
            <div class="osahan-cart-item rounded rounded shadow-sm overflow-hidden bg-white sticky_sidebar mb-3">
                <div class="d-flex border-bottom osahan-cart-item-profile bg-white p-3">
                    <img alt="osahan" src="{{asset('assets/user/img/starter1.jpg')}}" class="me-3 rounded-circle img-fluid">
                    <div class="d-flex flex-column">
                        <h6 class="mb-1 fw-bold">Spice Hut Indian Restaurant</h6>
                        <p class="mb-0 small text-muted"><i class="feather-map-pin"></i> 2036 2ND AVE, NEW YORK, NY
                            10029</p>
                    </div>
                </div>
                <div class="d-flex border-bottom osahan-cart-item-profile bg-white p-3">
                    <img alt="osahan" src="{{asset('assets/user/img/starter1.jpg')}}" class="me-3 rounded-circle img-fluid">
                    <div class="d-flex flex-column">
                        <h6 class="mb-1 fw-bold">Spice Hut Indian Restaurant</h6>
                        <p class="mb-0 small text-muted"><i class="feather-map-pin"></i> 2036 2ND AVE, NEW YORK, NY
                            10029</p>
                    </div>
                </div>
                <div class="d-flex border-bottom osahan-cart-item-profile bg-white p-3">
                    <img alt="osahan" src="{{asset('assets/user/img/starter1.jpg')}}" class="me-3 rounded-circle img-fluid">
                    <div class="d-flex flex-column">
                        <h6 class="mb-1 fw-bold">Spice Hut Indian Restaurant</h6>
                        <p class="mb-0 small text-muted"><i class="feather-map-pin"></i> 2036 2ND AVE, NEW YORK, NY
                            10029</p>
                    </div>
                </div>
                <div class="p-3 mt-3 restaurant-detailed-ratings-and-reviews shadow-sm rounded">
                    <a class="text-primary float-end" href="javascript:void(0)">Top Rated</a>
                    <h6 class="mb-1">All Ratings and Reviews</h6>
                    <div class="reviews-members py-3">
                        <div class="d-flex align-items-start gap-3">
                            <a href="javascript:void(0)"><img alt="#" src="{{asset('assets/user/img/reviewer1.png')}}" class="rounded-pill"></a>
                            <div>
                                <div class="reviews-members-header">
                                    <div class="star-rating float-end">
                                        <div class="d-inline-block" style="font-size: 14px;"><i
                                                class="feather-star text-warning"></i>
                                            <i class="feather-star text-warning"></i>
                                            <i class="feather-star text-warning"></i>
                                            <i class="feather-star text-warning"></i>
                                            <i class="feather-star"></i>
                                        </div>
                                    </div>
                                    <h6 class="mb-0"><a class="text-dark" href="javascript:void(0)">Trump</a></h6>
                                    <p class="text-muted small">Tue, 20 Mar 2023</p>
                                </div>
                                <div class="reviews-members-body">
                                    <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has
                                        roots
                                        in a piece of classNameical Latin literature from 45 BC, making it over 2000
                                        years old.</p>
                                </div>
                                <div class="reviews-members-footer"><a class="total-like btn btn-sm btn-outline-primary"
                                        href="javascript:void(0)"><i class="feather-thumbs-up"></i> 856M</a> <a
                                        class="total-like btn btn-sm btn-outline-primary" href="javascript:void(0)"><i
                                            class="feather-thumbs-down"></i> 158K</a>
                                    <span class="total-like-user-main ms-2" dir="rtl">
                                        <a href="javascript:void(0)"><img alt="#" src="{{asset('assets/user/img/reviewer3.png')}}"
                                                class="total-like-user rounded-pill"></a>
                                        <a href="javascript:void(0)"><img alt="#" src="{{asset('assets/user/img/reviewer4.png')}}"
                                                class="total-like-user rounded-pill"></a>
                                        <a href="javascript:void(0)"><img alt="#" src="{{asset('assets/user/img/reviewer5.png')}}"
                                                class="total-like-user rounded-pill"></a>
                                        <a href="javascript:void(0)"><img alt="#" src="{{asset('assets/user/img/reviewer6.png')}}"
                                                class="total-like-user rounded-pill"></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="reviews-members py-3">
                        <div class="d-flex align-items-start gap-3">
                            <a href="javascript:void(0)"><img alt="#" src="{{asset('assets/user/img/reviewer2.png')}}" class="rounded-pill"></a>
                            <div>
                                <div class="reviews-members-header">
                                    <div class="star-rating float-end">
                                        <div class="d-inline-block" style="font-size: 14px;"><i
                                                class="feather-star text-warning"></i>
                                            <i class="feather-star text-warning"></i>
                                            <i class="feather-star text-warning"></i>
                                            <i class="feather-star text-warning"></i>
                                            <i class="feather-star"></i>
                                        </div>
                                    </div>
                                    <h6 class="mb-0"><a class="text-dark" href="javascript:void(0)">Jhon Smith</a></h6>
                                    <p class="text-muted small">Tue, 20 Mar 2023</p>
                                </div>
                                <div class="reviews-members-body">
                                    <p>It is a long established fact that a reader will be distracted by the
                                        readable
                                        content of a page when looking at its layout.</p>
                                </div>
                                <div class="reviews-members-footer"><a class="total-like btn btn-sm btn-outline-primary"
                                        href="javascript:void(0)"><i class="feather-thumbs-up"></i> 88K</a> <a
                                        class="total-like btn btn-sm btn-outline-primary" href="javascript:void(0)"><i
                                            class="feather-thumbs-down"></i> 1K</a><span
                                        class="total-like-user-main ms-2" dir="rtl"><a href="javascript:void(0)"><img alt="#"
                                                src="{{asset('assets/user/img/reviewer3.png')}}" class="total-like-user rounded-pill"></a><a
                                            href="javascript:void(0)"><img alt="#" src="{{asset('assets/user/img/reviewer4.png')}}"
                                                class="total-like-user rounded-pill"></a><a href="javascript:void(0)"><img alt="#"
                                                src="{{asset('assets/user/img/reviewer5.png')}}" class="total-like-user rounded-pill"></a><a
                                            href="javascript:void(0)"><img alt="#" src="{{asset('assets/user/img/reviewer6.png')}}"
                                                class="total-like-user rounded-pill"></a></span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <a class="text-center w-100 d-block mt-3 fw-bold" href="javascript:void(0)">See All Reviews</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bg-white container-fluid sticky-bottom">
    <div class="row">
        <div class="col-4 text-center">
            <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-biking"></i></h1>
            <p class="text-warning mb-1">Delivery</p>
        </div>
        <div class="col-4 text-center">
            <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-utensils"></i></h1>
            <p class="text-warning mb-1">Mess/Tiffin</p>
        </div>
        <div class="col-4 text-center">
            <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-store"></i></h1>
            <p class="text-warning mb-1">Restaurant</p>
        </div>
    </div>
</div>
<!-- Footer -->
<div class="osahan-menu-fotter fixed-bottom bg-white px-3 py-2 text-center d-none">
    <div class="row">
        <div class="col">
            <a href="{{route('user.dashboard')}}" class="text-dark small fw-bold text-decoration-none">
                <p class="h4 m-0"><i class="feather-home text-dark"></i></p>
                Home
            </a>
        </div>
        <div class="col selected">
            <a href="trending.html" class="text-danger small fw-bold text-decoration-none">
                <p class="h4 m-0"><i class="feather-map-pin"></i></p>
                Trending
            </a>
        </div>
        <div class="col bg-white rounded-circle mt-n4 px-3 py-2">
            <div class="bg-danger rounded-circle mt-n0 shadow">
                <a href="checkout.html" class="text-white small fw-bold text-decoration-none">
                    <i class="feather-shopping-cart"></i>
                </a>
            </div>
        </div>
        <div class="col">
            <a href="favorites.html" class="text-dark small fw-bold text-decoration-none">
                <p class="h4 m-0"><i class="feather-heart"></i></p>
                Favorites
            </a>
        </div>
        <div class="col">
            <a href="profile.html" class="text-dark small fw-bold text-decoration-none">
                <p class="h4 m-0"><i class="feather-user"></i></p>
                Profile
            </a>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
    $(document).ready(function () {
        console.log("Initial checked radio button ID:", $('input[name="btnradio"]:checked').attr('id'));

        $('.btn-group input[type="radio"]').change(function () {
            const checkedRadioID = $('input[name="btnradio"]:checked').attr('id');

            if (checkedRadioID === 'btnradio2') {
                $('.btndays').removeClass('btn-veg-outline').addClass('btn-nv-outline');
                $('#three-meal').removeClass('three-veg-meal').addClass('three-nv-meal');
            } else {
                $('.btndays').removeClass('btn-nv-outline').addClass('btn-veg-outline');
                $('#three-meal').removeClass('three-nv-meal').addClass('three-veg-meal');
            }

            console.log("Changed radio button ID:", checkedRadioID);
        });

        $('.btn-days').change(function () {
            const selectedDayID = $('input[name="btndays"]:checked').attr('id');
            console.log("Selected day:", selectedDayID);
        });
    });
</script>    
@endpush