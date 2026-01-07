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
                    <img alt="#" src="img/trending1.png" class="restaurant-pic">
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
                                            <div
                                                class="three-veg-meal three-meal rounded shadow-sm px-4 py-lg-5 py-4">
                                                <div class="text-center"><i
                                                        class="fas fa-utensils mb-2 text-center" style="height:40px;"></i></div>
                                                        <h6 class="text-center border-bottom pb-3">BREAKFAST</h6>
                                                <p class="mb-2 text-black-50 mt-3">Spaghetti Carbonara is a
                                                        quintessential Italian pasta dish known for its creamy sauce and
                                                        rich flavor. It typically consists of al dente spaghetti noodles
                                                        tossed in a luxurious sauce made with eggs, Pecorino Romano
                                                        cheese, pancetta (or guanciale), black pepper, and sometimes a
                                                        splash of white wine.</small></p>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 1
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 2
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 3
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 4
                                                </div>
                                                <button id="collapse-btn" data-target="collapse-content"
                                                    class="collapse-btn btn bg-success meal-btn text-white rounded mt-2"><span
                                                        class="py-1 px-2">ADD ON</span></button>
                                                <div class="collapse-content" id="collapse-content">
                                                    <div class="progress bg-soft-warning shadow-none w-100"
                                                        style="height: 2px;margin-top: 10px;">
                                                        <div class="progress-bar bg-warning" data-toggle="progress-bar"
                                                            role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                                            aria-valuemax="100"
                                                            style="width: 50%; transition: width 2s ease 0s;"></div>
                                                    </div>
                                                    <form>
                                                        <div class="row mt-3">
                                                            <div class="col-12 mt-2">
                                                                <div class="d-flex justify-content-between">
                                                                    <label class="form-label mb-0 align-self-center"
                                                                        for="email">No. of Normal
                                                                        Diet</label>
                                                                    <div class="mess-custom-input">
                                                                        <button class="decrease-btn"
                                                                            id="decrease-btn">-</button>
                                                                        <input type="text" id="input-value" value="0">
                                                                        <button class="increase-btn"
                                                                            id="increase-btn">+</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="d-flex justify-content-between">
                                                                    <label class="form-label mb-0 align-self-center"
                                                                        for="email">No. of Special Diet</label>
                                                                    <div class="mess-custom-input">
                                                                        <button class="decrease-btn"
                                                                            id="decrease-btn">-</button>
                                                                        <input type="text" id="input-value" value="0">
                                                                        <button class="increase-btn"
                                                                            id="increase-btn">+</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-12 mt-2">
                                            <div
                                                class="three-veg-meal three-meal rounded shadow-sm px-4 py-lg-5 py-4">
                                                <div class="text-center"><i
                                                        class="fas fa-utensils mb-2 text-center" style="height:40px;"></i></div>
                                                        <h6 class="text-center border-bottom pb-3">LUNCH</h6>
                                                <p class="mb-2 text-black-50 mt-3">Spaghetti Carbonara is a
                                                        quintessential Italian pasta dish known for its creamy sauce and
                                                        rich flavor. It typically consists of al dente spaghetti noodles
                                                        tossed in a luxurious sauce made with eggs, Pecorino Romano
                                                        cheese, pancetta (or guanciale), black pepper, and sometimes a
                                                        splash of white wine.</small></p>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 1
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 2
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 3
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 4
                                                </div>
                                                <button id="collapse-btn1" data-target="collapse-content1"
                                                    class="collapse-btn btn bg-success meal-btn text-white rounded mt-2"><span
                                                        class="py-1 px-2">ADD ON</span></button>
                                                <div class="collapse-content" id="collapse-content1">
                                                    <div class="progress bg-soft-warning shadow-none w-100"
                                                        style="height: 2px;margin-top: 10px;">
                                                        <div class="progress-bar bg-warning" data-toggle="progress-bar"
                                                            role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                                            aria-valuemax="100"
                                                            style="width: 50%; transition: width 2s ease 0s;"></div>
                                                    </div>
                                                    <form>
                                                        <div class="row mt-3">
                                                            <div class="col-12 mt-2">
                                                                <div class="d-flex justify-content-between">
                                                                    <label class="form-label mb-0 align-self-center"
                                                                        for="email">No. of Normal
                                                                        Diet</label>
                                                                    <div class="mess-custom-input">
                                                                        <button class="decrease-btn"
                                                                            id="decrease-btn">-</button>
                                                                        <input type="text" id="input-value" value="0">
                                                                        <button class="increase-btn"
                                                                            id="increase-btn">+</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="d-flex justify-content-between">
                                                                    <label class="form-label mb-0 align-self-center"
                                                                        for="email">No. of Special Diet</label>
                                                                    <div class="mess-custom-input">
                                                                        <button class="decrease-btn"
                                                                            id="decrease-btn">-</button>
                                                                        <input type="text" id="input-value" value="0">
                                                                        <button class="increase-btn"
                                                                            id="increase-btn">+</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-12 mt-2">
                                            <div
                                                class="three-veg-meal three-meal rounded shadow-sm px-4 py-lg-5 py-4">
                                                <div class="text-center"><i
                                                        class="fas fa-utensils mb-2 text-center" style="height:40px;"></i></div>
                                                        <h6 class="text-center border-bottom pb-3">DINNER</h6>
                                                <p class="mb-2 text-black-50 mt-3">Spaghetti Carbonara is a
                                                        quintessential Italian pasta dish known for its creamy sauce and
                                                        rich flavor. It typically consists of al dente spaghetti noodles
                                                        tossed in a luxurious sauce made with eggs, Pecorino Romano
                                                        cheese, pancetta (or guanciale), black pepper, and sometimes a
                                                        splash of white wine.</small></p>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 1
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 2
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 3
                                                </div>
                                                <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>Item 4
                                                </div>
                                                <button id="collapse-btn2" data-target="collapse-content2"
                                                    class="collapse-btn btn bg-success meal-btn text-white rounded mt-2"><span
                                                        class="py-1 px-2">ADD ON</span></button>
                                                <div class="collapse-content" id="collapse-content2">
                                                    <div class="progress bg-soft-warning shadow-none w-100"
                                                        style="height: 2px;margin-top: 10px;">
                                                        <div class="progress-bar bg-warning" data-toggle="progress-bar"
                                                            role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                                            aria-valuemax="100"
                                                            style="width: 50%; transition: width 2s ease 0s;"></div>
                                                    </div>
                                                    <form>
                                                        <div class="row mt-3">
                                                            <div class="col-12 mt-2">
                                                                <div class="d-flex justify-content-between">
                                                                    <label class="form-label mb-0 align-self-center"
                                                                        for="email">No. of Normal
                                                                        Diet</label>
                                                                    <div class="mess-custom-input">
                                                                        <button class="decrease-btn"
                                                                            id="decrease-btn">-</button>
                                                                        <input type="text" id="input-value" value="0">
                                                                        <button class="increase-btn"
                                                                            id="increase-btn">+</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="d-flex justify-content-between">
                                                                    <label class="form-label mb-0 align-self-center"
                                                                        for="email">No. of Special Diet</label>
                                                                    <div class="mess-custom-input">
                                                                        <button class="decrease-btn"
                                                                            id="decrease-btn">-</button>
                                                                        <input type="text" id="input-value" value="0">
                                                                        <button class="increase-btn"
                                                                            id="increase-btn">+</button>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container position-relative">
    <div class="row">
        <div class="col-md-8 pt-3">
            <h6 class="p-3 m-0 bg-light w-100">Today's Attendance</h6>
            <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                <div class="row m-0">
                    <div class="col-md-12 px-0 border-top">
                        <div class="">
                            <div class="d-flex align-items-center gap-2 p-3 border-bottom gold-members">
                                <img alt="#" src="{{asset('assets/user/img/veg.png')}}" class="img-fluid package-img">
                                <div class="w-100">
                                    <div class="d-flex align-items-end gap-2">
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
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 p-3 border-bottom gold-members">
                                <img alt="#" src="{{asset('assets/user/img/veg.png')}}" class="img-fluid package-img">
                                <div class="w-100">
                                    <div class="d-flex align-items-end gap-2">
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
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 p-3 border-bottom gold-members">
                                <img alt="#" src="{{asset('assets/user/img/veg.png')}}" class="img-fluid package-img">
                                <div class="w-100">
                                    <div class="d-flex align-items-end gap-2">
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
                                </div>
                            </div>
                            <div class="text-center m-3"><button type="button"
                                    class="btn btn-primary w-100 btn-sm">Continue</button></div>
                            <h6 class="p-3 m-0 bg-light w-100 border-bottom">Confirm Your Presence</h6>
                            <div class="d-flex gap-2 border-bottom gold-members">
                                <div class="w-100">
                                    <div class="row px-4 py-3">
                                        <div class="col-md-4 col-12 pb-3 mt-3">
                                            <div
                                                class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                                <div class="list-card-image">
                                                        <img alt="#" src="{{asset('assets/user/img/qr-code.png')}}"
                                                            class="img-fluid item-img w-100">
                                                    </a>
                                                </div>
                                                <div class="p-3 position-relative">
                                                    <div class="list-card-body">
                                                        <h5 class="mb-1 text-center" style="letter-spacing:12px;font-weight:800;">0384
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 pb-3 mt-3">
                                            <div
                                                class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                                <div class="list-card-image">
                                                        <img alt="#" src="{{asset('assets/user/img/qr-code.png')}}"
                                                            class="img-fluid item-img w-100">
                                                    </a>
                                                </div>
                                                <div class="p-3 position-relative">
                                                    <div class="list-card-body">
                                                    <h5 class="mb-1 text-center" style="letter-spacing:12px;font-weight:800;">0384
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 pb-3 mt-3">
                                            <div
                                                class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                                <div class="list-card-image">
                                                        <img alt="#" src="{{asset('assets/user/img/qr-code.png')}}"
                                                            class="img-fluid item-img w-100">
                                                    </a>
                                                </div>
                                                <div class="p-3 position-relative">
                                                    <div class="list-card-body">
                                                        <h5 class="mb-1 text-center" style="letter-spacing:12px;font-weight:800;">0384
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h6 class="p-3 m-0 bg-light w-100 border-bottom">Package <small
                                    class="text-black-50">(Expire in - 4 Days)</small></h6>
                            <div class="bg-white p-3 clearfix border-bottom">
                                <p class="mb-1 text-success">Remaining Coupons <span
                                        class="float-end text-success">₹62.8</span></p>
                                <p class="mb-1">Used Coupons <span class="text-info ms-1"><i
                                            class="feather-info"></i></span><span
                                        class="float-end text-dark">₹3140</span></p>
                                <p class="mb-1">Coupons Forwarded<span class="text-info ms-1"><i
                                            class="feather-eye"></i></span><span class="float-end text-dark">₹10</span>
                                </p>
                                <p class="mb-1  text-warning">Expired Coupons<span
                                        class="float-end text-warning">₹1884</span>
                                </p>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-0">Purchase Date -- <span class="float-end text-black-50">13-02-2022</span></h6>
                                    <h6 class="mb-0">Expire Date --<span class="float-end text-black-50">23-4-2023</span></h6>
                                </div>
                            </div>
                            <div class="p-3">
                                <a class="btn btn-success w-100 btn-lg" href="successful.html">Explorer more Coupons</a>
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
            </div>
        </div>
    </div>
</div>
<div class="container-fluid fixed-bottom qr-position" style="box-shadow:none;">
    <div class="d-flex justify-content-center">
        <div class="text-center">
            <div class="shadow rounded bg-white p-2">
                <img alt="#" src="{{asset('assets/user/img/qr-code.png')}}" class="img-fluid" style="height:40px;">
                <p class="mb-0">QR Code</p>
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
                $('.three-meal').removeClass('three-veg-meal').addClass('three-nv-meal');
                $('.meal-btn').removeClass('bg-success').addClass('bg-danger');
            } else {
                $('.btndays').removeClass('btn-nv-outline').addClass('btn-veg-outline');
                $('.three-meal').removeClass('three-nv-meal').addClass('three-veg-meal');
                $('.meal-btn').removeClass('bg-danger').addClass('bg-success');
            }
            console.log("Changed radio button ID:", checkedRadioID);
        });

        $('.btn-days').change(function () {
            const selectedDayID = $('input[name="btndays"]:checked').attr('id');
            console.log("Selected day:", selectedDayID);
        });
    });

    //collapse
    document.addEventListener('DOMContentLoaded', function () {
        const collapseButtons = document.querySelectorAll('[id^="collapse-btn"]');

        collapseButtons.forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('id').replace('collapse-btn', 'collapse-content');
                const collapseContent = document.getElementById(targetId);

                if (collapseContent.classList.contains('collapsed')) {
                    collapseContent.classList.remove('collapsed');
                    collapseContent.classList.add('expanded');
                } else {
                    collapseContent.classList.remove('expanded');
                    collapseContent.classList.add('collapsed');
                }
            });

            // Ensure that the collapse content starts in the collapsed state
            const targetId = button.getAttribute('id').replace('collapse-btn', 'collapse-content');
            const collapseContent = document.getElementById(targetId);
            collapseContent.classList.add('collapsed');
        });
    });

    //increment value
    document.addEventListener('DOMContentLoaded', function () {
        const decreaseBtns = document.querySelectorAll('.decrease-btn');
        const increaseBtns = document.querySelectorAll('.increase-btn');
        const inputValues = document.querySelectorAll('.input-value');

        decreaseBtns.forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                let input = btn.nextElementSibling;
                let value = parseInt(input.value);
                if (value > 0) {
                    input.value = value - 1;
                }
            });
        });

        increaseBtns.forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                let input = btn.previousElementSibling;
                let value = parseInt(input.value);
                input.value = value + 1;
            });
        });
    });
</script>
@endpush
