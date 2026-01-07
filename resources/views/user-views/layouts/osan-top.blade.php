
<div class="osahan-home-page">
    <div class="bg-primary p-3 d-none">
        <div class="text-white">
            <div class="title d-flex align-items-center">
                <a class="toggle" href="javascript:void(0)">
                    <span></span>
                </a>
                <h4 class="fw-bold m-0 ps-5">Browse</h4>
                <a class="text-white fw-bold ms-auto" data-bs-toggle="modal" data-bs-target="#exampleModal"
                    href="javascript:void(0)">Filter</a>
            </div>
        </div>
        <div class=" me-3">
            <a class="text-dark d d-flex align-items-center py-3" role="button" data-bs-toggle="modal" data-bs-target="#filters">
                <div><i class="feather-map-pin me-2 bg-light rounded-pill p-2 icofont-size"></i></div>
                <div>
                    @php($userAddress = json_decode(Session::get('userInfo')->address))
                    <p class="text-white mb-0 small">Location</p>
                    {{$userAddress->street.' '.$userAddress->city.' - '.$userAddress->pincode}}
                </div>
            </a>
            
        </div>
    </div>
    <div class="    ">
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
            <div class="box bg-white mb-3 mt-3 shadow-sm rounded">
                <div class="overflow-hidden border-top d-flex align-items-center p-2">
                    <div class="marquee-container">
                        <marquee scrollamount="12">
                            <div class="marquee-item">
                                <div class="d-flex">
                                    <div class="text-warning">This is notice</div>
                                </div>
                            </div>
                            <div class="marquee-item">
                                <div class="d-flex">
                                    <div class="text-warning">This is notice</div>
                                </div>
                            </div>
                            <div class="marquee-item">
                                <div class="d-flex">
                                    <div class="text-warning">This is notice</div>
                                </div>
                            </div>
                        </marquee>
                    </div>
                </div>
            </div>
            <div class="pt-2 pb-3 title d-flex align-items-center">
                <h5 class="m-0">Total Mess</h5>
                <a class="fw-bold ms-auto" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#filters">Filters <i
                        class="feather-chevrons-right"></i></a>
                <!-- <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#filters" class="ms-auto btn btn-primary">Filters</a> -->
            </div>
            <div class="most_sale">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <div
                            class="d-flex align-items-center list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image">
                                <div class="star mess-info position-absolute"><span class="badge text-bg-success"><i
                                            class="feather-user"></i> Mess ID</span></div>
                                <div class="favourite-heart text-danger position-absolute rounded-circle"><a href="javascript:void(0)"><i
                                            class="feather-heart"></i></a></div>
                                <div class="member-plan position-absolute"><span class=""><img alt="#" src="{{asset('assets/user/img/veg.png')}}g"
                                            class="img-fluid item-img w-100"></span>
                                </div>
                                <a href="mess-detail.php">
                                    <img alt="#" src="{{asset('assets/user/img/banner-1.jpg')}}" class="img-fluid item-img w-100">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1">
                                        <a href="restaurant.html" class="text-black">
                                            The osahan Restaurant
                                        </a>
                                    </h6>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger me-1">Badge One</span>
                                        <small>Speciality/Description</small>
                                    </div>
                                    <div class="d-flex mb-1">
                                        <ul class="rating-stars list-unstyled mb-0">
                                            <li>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star"></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="mb-0"><i class="feather-map-pin me-1"></i>Distance from Room</p>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger badge-two me-1">Badge One</span>
                                        <small>Normal/Special</small>
                                    </div>
                                    <p class="text-gray mb-0">Lunch/Breakfast/Dinner</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div
                            class="d-flex align-items-center list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image">
                                <div class="star mess-info position-absolute"><span class="badge text-bg-success"><i
                                            class="feather-user"></i> Mess ID</span></div>
                                <div class="favourite-heart text-danger position-absolute rounded-circle"><a href="javascript:void(0)"><i
                                            class="feather-heart"></i></a></div>
                                <div class="member-plan position-absolute"><span class=""><img alt="#"
                                            src="{{asset('assets/user/img/non-veg.png')}}" class="img-fluid item-img w-100"></span>
                                </div>
                                <a href="restaurant.html">
                                    <img alt="#" src="{{asset('assets/user/img/banner-2.jpg')}}" class="img-fluid item-img w-100">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1"><a href="restaurant.html" class="text-black">The osahan Restaurant
                                        </a>
                                    </h6>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger me-1">Badge One</span>
                                        <small>Speciality/Description</small>
                                    </div>
                                    <div class="d-flex mb-1">
                                        <ul class="rating-stars list-unstyled mb-0">
                                            <li>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star"></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="mb-0"><i class="feather-map-pin me-1"></i>Distance from Room</p>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger badge-two me-1">Badge One</span>
                                        <small>Normal/Special</small>
                                    </div>
                                    <p class="text-gray mb-0">Lunch/Breakfast/Dinner</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div
                            class="d-flex align-items-center list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image">
                                <div class="star mess-info position-absolute"><span class="badge text-bg-success"><i
                                            class="feather-user"></i> Mess ID</span></div>
                                <div class="favourite-heart text-danger position-absolute rounded-circle"><a href="javascript:void(0)"><i
                                            class="feather-heart"></i></a></div>
                                <div class="member-plan position-absolute"><span class=""><img alt="#"
                                            src="{{asset('assets/user/img/non-veg.png')}}" class="img-fluid item-img w-100"></span>
                                </div>
                                <a href="restaurant.html">
                                    <img alt="#" src="{{asset('assets/user/img/banner-2.jpg')}}" class="img-fluid item-img w-100">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1"><a href="restaurant.html" class="text-black">The osahan Restaurant
                                        </a>
                                    </h6>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger me-1">Badge One</span>
                                        <small>Speciality/Description</small>
                                    </div>
                                    <div class="d-flex mb-1">
                                        <ul class="rating-stars list-unstyled mb-0">
                                            <li>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star"></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="mb-0"><i class="feather-map-pin me-1"></i>Distance from Room</p>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger badge-two me-1">Badge One</span>
                                        <small>Normal/Special</small>
                                    </div>
                                    <p class="text-gray mb-0">Lunch/Breakfast/Dinner</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div
                            class="d-flex align-items-center list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image">
                                <div class="star mess-info position-absolute"><span class="badge text-bg-success"><i
                                            class="feather-user"></i> Mess ID</span></div>
                                <div class="favourite-heart text-danger position-absolute rounded-circle"><a href="javascript:void(0)"><i
                                            class="feather-heart"></i></a></div>
                                <div class="member-plan position-absolute"><span class=""><img alt="#" src="{{asset('assets/user/img/veg.png')}}"
                                            class="img-fluid item-img w-100"></span>
                                </div>
                                <a href="mess-detail.php">
                                    <img alt="#" src="{{asset('assets/user/img/banner-1.jpg')}}" class="img-fluid item-img w-100">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1">
                                        <a href="restaurant.html" class="text-black">
                                            The osahan Restaurant
                                        </a>
                                    </h6>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger me-1">Badge One</span>
                                        <small>Speciality/Description</small>
                                    </div>
                                    <div class="d-flex mb-1">
                                        <ul class="rating-stars list-unstyled mb-0">
                                            <li>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star"></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="mb-0"><i class="feather-map-pin me-1"></i>Distance from Room</p>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger badge-two me-1">Badge One</span>
                                        <small>Normal/Special</small>
                                    </div>
                                    <p class="text-gray mb-0">Lunch/Breakfast/Dinner</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div
                            class="d-flex align-items-center list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image">
                                <div class="star mess-info position-absolute"><span class="badge text-bg-success"><i
                                            class="feather-user"></i> Mess ID</span></div>
                                <div class="favourite-heart text-danger position-absolute rounded-circle"><a href="javascript:void(0)"><i
                                            class="feather-heart"></i></a></div>
                                <div class="member-plan position-absolute"><span class=""><img alt="#" src="{{asset('assets/user/img/veg.png')}}"
                                            class="img-fluid item-img w-100"></span>
                                </div>
                                <a href="mess-detail.php">
                                    <img alt="#" src="{{asset('assets/user/img/banner-1.jpg')}}" class="img-fluid item-img w-100">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1">
                                        <a href="restaurant.html" class="text-black">
                                            The osahan Restaurant
                                        </a>
                                    </h6>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger me-1">Badge One</span>
                                        <small>Speciality/Description</small>
                                    </div>
                                    <div class="d-flex mb-1">
                                        <ul class="rating-stars list-unstyled mb-0">
                                            <li>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star"></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="mb-0"><i class="feather-map-pin me-1"></i>Distance from Room</p>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger badge-two me-1">Badge One</span>
                                        <small>Normal/Special</small>
                                    </div>
                                    <p class="text-gray mb-0">Lunch/Breakfast/Dinner</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div
                            class="d-flex align-items-center list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image">
                                <div class="star mess-info position-absolute"><span class="badge text-bg-success"><i
                                            class="feather-user"></i> Mess ID</span></div>
                                <div class="favourite-heart text-danger position-absolute rounded-circle"><a href="javascript:void(0)"><i
                                            class="feather-heart"></i></a></div>
                                <div class="member-plan position-absolute"><span class=""><img alt="#" src="{{asset('assets/user/img/veg.png')}}"
                                            class="img-fluid item-img w-100"></span>
                                </div>
                                <a href="mess-detail.php">
                                    <img alt="#" src="{{asset('assets/user/img/banner-1.jpg')}}" class="img-fluid item-img w-100">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1">
                                        <a href="restaurant.html" class="text-black">
                                            The osahan Restaurant
                                        </a>
                                    </h6>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger me-1">Badge One</span>
                                        <small>Speciality/Description</small>
                                    </div>
                                    <div class="d-flex mb-1">
                                        <ul class="rating-stars list-unstyled mb-0">
                                            <li>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star star_active"></i>
                                                <i class="feather-star"></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <p class="mb-0"><i class="feather-map-pin me-1"></i>Distance from Room</p>
                                    <div class="list-card-badge mb-1">
                                        <span class="badge text-bg-danger badge-two me-1">Badge One</span>
                                        <small>Normal/Special</small>
                                    </div>
                                    <p class="text-gray mb-0">Lunch/Breakfast/Dinner</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>