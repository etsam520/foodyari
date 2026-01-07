@extends('user-views.restaurant.layouts.main')

@push('css')
<style>
    /* Order Tabs Styling */
    .order-tabs-container {
        background: white;
        border-radius: 12px;
        padding: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .order-tabs-container::-webkit-scrollbar {
        display: none;
    }

    .order-tabs {
        display: flex;
        gap: 6px;
        min-width: max-content;
    }

    .order-tab-item {
        flex: 1;
        min-width: 0;
    }

    .order-tab-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 8px;
        border: none;
        border-radius: 10px;
        background: transparent;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-height: 70px;
    }

    .order-tab-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 129, 10, 0.1), rgba(255, 149, 0, 0.05));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .order-tab-link:hover::before {
        opacity: 1;
    }

    .order-tab-link.active {
        background: linear-gradient(135deg, #ff810a, #ff9500);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 129, 10, 0.3);
    }

    .order-tab-icon {
        width: 28px;
        height: 28px;
        object-fit: contain;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
        filter: grayscale(0.3);
        transition: all 0.3s ease;
    }

    .order-tab-link.active .order-tab-icon {
        filter: brightness(0) invert(1);
        transform: scale(1.1);
    }

    .order-tab-link:hover .order-tab-icon {
        filter: grayscale(0);
        transform: scale(1.05);
    }

    .order-tab-text {
        font-size: 0.85rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
        white-space: nowrap;
    }

    .order-tab-link.active .order-tab-text {
        color: white;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .order-tabs-container {
            padding: 6px;
        }

        .order-tab-link {
            padding: 10px 6px;
            min-height: 65px;
        }

        .order-tab-icon {
            width: 24px;
            height: 24px;
            margin-bottom: 5px;
        }

        .order-tab-text {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .order-tabs {
            gap: 4px;
        }

        .order-tab-link {
            padding: 8px 4px;
            min-height: 60px;
        }

        .order-tab-icon {
            width: 22px;
            height: 22px;
        }

        .order-tab-text {
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@section('containt')
<div class="d-lg-none d-block">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="text-white fw-bolder fs-4 me-3" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h4 class="fw-bold m-0 text-white">Orders</h4>
    </div>
</div>

<div class="container  position-relative">
    <div class="row mt-3 justify-content-center mx-1">
        <div class="col-lg-8 col-12 mb-3 p-0">
            <div class="order-tabs-container">
                <ul class="order-tabs nav" id="myTab" role="tablist">
                    <li class="order-tab-item nav-item" role="presentation">
                        <a class="order-tab-link nav-link {{ $status == 'All' ? 'active' : '' }}"
                            href="{{ route('user.restaurant.order-list', ['status' => 'all']) }}"
                            role="tab" aria-controls="all" aria-selected="{{ $status == 'All' ? 'true' : 'false' }}">
                            <img src="{{ asset('assets/user/img/all-orders.png') }}" alt="All Orders"
                                class="order-tab-icon">
                            <span class="order-tab-text">All</span>
                        </a>
                    </li>
                    @php
                        $hasLive = collect($orders->toArray())->contains(function ($order) {
                            return \Carbon\Carbon::parse($order['created_at'])->isAfter(now());
                        });
                    @endphp
                    @if($hasLive)
                    <li class="order-tab-item nav-item" role="presentation">
                        <a class="order-tab-link nav-link {{ $status == 'Live' ? 'active' : '' }}"
                            href="{{ route('user.restaurant.order-list', ['status' => 'live']) }}"
                            role="tab" aria-controls="live" aria-selected="{{ $status == 'Live' ? 'true' : 'false' }}">
                            <img src="{{ asset('assets/user/img/live-orders.png') }}" alt="Live Orders"
                                class="order-tab-icon">
                            <span class="order-tab-text">Live</span>
                        </a>
                    </li>
                    @endif
                    <li class="order-tab-item nav-item" role="presentation">
                        <a class="order-tab-link nav-link {{ $status == 'Delivered' ? 'active' : '' }}"
                            href="{{ route('user.restaurant.order-list', ['status' => 'delivered']) }}"
                            role="tab" aria-controls="delivered" aria-selected="{{ $status == 'Delivered' ? 'true' : 'false' }}">
                            <img src="{{ asset('assets/user/img/order-delivered.png') }}" alt="Order Delivered"
                                class="order-tab-icon">
                            <span class="order-tab-text">Delivered</span>
                        </a>
                    </li>
                    <li class="order-tab-item nav-item" role="presentation">
                        <a class="order-tab-link nav-link {{ $status == 'Canceled' ? 'active' : '' }}"
                            href="{{ route('user.restaurant.order-list', ['status' => 'canceled']) }}"
                            role="tab" aria-controls="canceled" aria-selected="{{ $status == 'Canceled' ? 'true' : 'false' }}">
                            <img src="{{ asset('assets/user/img/cancelled-food.png') }}" alt="Cancelled Orders"
                                class="order-tab-icon">
                            <span class="order-tab-text">Canceled</span>
                        </a>
                    </li>
                    <li class="order-tab-item nav-item" role="presentation">
                        <a class="order-tab-link nav-link"
                            href="{{ route('user.restaurant.scheduled-orders') }}"
                            role="tab" aria-controls="scheduled" aria-selected="false">
                            <img src="{{ asset('assets/user/img/scheduled-orders.png') }}" alt="Scheduled Orders"
                                class="order-tab-icon"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <i class="feather-clock order-tab-icon d-none" style="font-size: 26px;"></i>
                            <span class="order-tab-text">Scheduled</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-8 col-12 p-0">
            @foreach ($orders as $order)
            @php($restaurant = $order->restaurant)
            <div class="tab-contentd" id="myTabContent">
                <div class="tab-pane fade show active" id="{{ $order->order_status }}" role="tabpanel"
                    aria-labelledby="{{ $order->order_status }}-tab">
                    <div class="order-body">
                        <div class="pb-3">

                            <div class="p-3 rounded shadow-sm bg-white">
                                <div class="d-flex"
                                    onclick="location.href='{{ route('user.restaurant.order-trace', ['order_id' => $order->id]) }}'"
                                    >

                                    <div class="text-muted me-3">
                                        <img alt="#" src="{{ Helpers::getUploadFile($restaurant->logo, 'restaurant') }}"
                                            class="img-fluid order_img rounded">
                                    </div>
                                    <div class="w-100">
                                        <div>
                                            <div class="d-flex justify-content-between">
                                                <p class="mb-0">ORDER ID #{{ $order->id }}</p>
                                                <p class="small fw-bold text-center mb-0 text-nowrap">
                                                    <i class="feather-clock"></i>
                                                    {{-- @dd() --}}
                                                    {{ App\CentralLogics\Helpers::format_date($order->updated_at) . ' - ' . App\CentralLogics\Helpers::format_time($order->updated_at->toTimeString()) }}
                                                </p>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div class="">
                                                    <p class="fw-bolder mb-0"><a href="javascript:void(0)"
                                                            class="text-dark">{{ Str::upper($restaurant->name) }}</a>
                                                    </p>
                                                </div>
                                                <?php
                                                    $foodItems = null;$deliveryMan = null;
                                                ?>

                                                @if($order->order_status == 'delivered')
                                                <?php
                                                    $foodItems = Helpers::getCusomerOrderItems($order->id);
                                                    $deliveryMan = Helpers::formatDmById($order->delivery_man_id);
                                                ?>
                                                    <a class="btn btn-success w-25 ms-2 ms-auto mt-1 d-lg-block d-none"
                                                        href="javascript:void(0)" data-create="review" data-deliveryman-id="{{$order->delivery_man_id}}"
                                                        data-food-items="{{ json_encode($foodItems) }}"
                                                        data-deliveryman="{{ json_encode($deliveryMan) }}"
                                                        data-order-id="{{ $order->id }}"><i
                                                            class="feather-star me-2"></i>Review</a>
                                                @endif
                                                @if(in_array($order->order_status, ['delivered', 'canceled']) && $order->order_amount > 0)
                                                    <?php
                                                        $hasRefunds = $order->refunds()->exists();
                                                        $canRefund = \Carbon\Carbon::parse($order->updated_at)->diffInDays(now()) <= 7;
                                                    ?>

                                                    @if($hasRefunds)
                                                        <a class="btn btn-outline-info btn-sm ms-1 mt-1 d-lg-block d-none"
                                                           href="{{ route('user.refunds.show', $order->refunds()->first()->id) }}">
                                                            <i class="feather-eye me-1"></i>View Refund
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between pt-3 mt-3 border-top  d-lg-none d-block">
                                    <a class="btn btn-primary w-50 ms-2 ms-auto me-2 mt-1" href="{{ route('user.restaurant.order-invoice', ['order_id' => $order->id]) }}"><i
                                                class="feather-printer me-2"></i>Invoice</a>
                                    @if ($order->order_status != 'delivered' && $order->order_status != 'canceled' && $order->created_at->diffInMinutes() < 120)
                                        <a class="btn btn-success w-50 ms-2 ms-auto mt-1"
                                            href="{{ route('user.restaurant.order-trace', "order_id=$order->id") }}"><i
                                                class="feather-map-pin me-2"></i>Trace Order</a>
                                    @elseif ($order->order_status == 'delivered')
                                        <a class="btn btn-success w-50 ms-2 ms-auto mt-1" href="javascript:void(0)"
                                            data-create="review" data-deliveryman-id="{{$order->delivery_man_id}}" data-order-id="{{ $order->id }}"
                                            data-food-items="{{ json_encode($foodItems) }}"
                                            data-deliveryman="{{ json_encode($deliveryMan) }}"><i
                                                class="feather-star me-2"></i>Review</a>
                                    @endif

                                </div>

                                {{-- Refund buttons for mobile --}}
                                @if(in_array($order->order_status, ['delivered', 'canceled']) && $order->order_amount > 0)
                                    <?php
                                        $hasRefunds = $order->refunds()->exists();
                                        $canRefund = \Carbon\Carbon::parse($order->updated_at)->diffInDays(now()) <= 7;
                                    ?>
                                    @if($hasRefunds)
                                        <div class="d-flex justify-content-center pt-2 d-lg-none d-block">
                                            <a class="btn btn-outline-info btn-sm w-75"
                                               href="{{ route('user.refunds.show', $order->refunds()->first()->id) }}">
                                                <i class="feather-eye me-1"></i>View Refund Status
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


<!-- review off canvas -->

<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered d-flex align-items-end">
        <div class="modal-content rounded-5">
            <div class="p-0">
                <div class="position-relative">
                    <img src="{{ asset('assets/user/img/review.jpeg') }}" alt="" class="w-100 rounded-top-5"
                        style="height:400px;object-fit:fill;">
                </div>
                <button type="button" class="btn-close position-absolute" style="top:25px;right:25px;"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bg-white p-lg-2 p-0 rating-review-select-page">
                    <h5 class="mb-3 fw-bolder bg-white text-primary">Your Feedback Matters to us!</h5>
                    <hr>
                    <div class="bg-light p-4 border rounded-4" data-append-food-items>
                        <div class="row mb-3 border-bottom pb-3">
                            <div class="fw-bolder col-6"> Product</div>
                            <div class="fw-bolder col-6 text-end">Qty</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div><span class="me-2">1.</span>Noodles</div>
                            <div class="">Half</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div><span class="me-2">2.</span>Noodles</div>
                            <div class="">Half</div>
                        </div>
                    </div>
                    <div class="bg-light p-4 border rounded-4 mt-3 d-none" data-append-deliveryman>
                        {{-- <div class="row mb-3 d-flex pb-3"> --}}
                            <img src="http://localhost:8080/foodyari_live/public/assets/user/img/user2.png" class="rounded-circle img-50 me-2 w-25" alt="">
                            <div class="fw-bolder col-6"> Abc Name</div>
                            {{-- <div class="fw-bolder col-6 text-end">Details</div> --}}
                        {{-- </div> --}}
                    </div>
                    <div class="bg-white rounded shadow mt-3">
                        <nav>
                            <div class="nav nav-tabs bg-light w-100 flex-nowrap custom-tabsa border-0 c-t-order justify-content-between"
                                id="myTab" role="tablist">
                                    <a class="nav-link border-0 text-dark py-3 d-lg-flex justify-content-center text-center w-50 active"
                                        id="nav-deliveryman-tab" data-bs-toggle="tab" data-bs-target="#nav-deliveryman" type="button"
                                        role="tab" aria-controls="nav-deliveryman" aria-selected="true">

                                        {{-- <img src="{{ asset('assets/user/img/live-orders.png') }}"
                                            alt="Delivery Man" class="me-lg-2 me-0 text-success" style="height: 26px;"> --}}

                                        <span> <b>Delivery Man</b></span></a>

                                    <a class="nav-link border-0 text-dark py-3 d-lg-flex justify-content-center text-center w-50"
                                        id="nav-restaurant-tab" data-bs-toggle="tab" data-bs-target="#nav-restaurant"
                                        type="button" role="tab" aria-controls="nav-restaurant" aria-selected="false">
                                        {{-- <img src="{{ asset('assets/user/img/all-orders.png') }}" alt="All Orders"
                                            class="me-lg-2 me-0 text-success" style="height: 20px;"> --}}
                                        <span> <b>Restaurant</b></span></a>

                            </div>
                        </nav>
                        <div class="tab-content p-lg-3 p-3" id="nav-tabContent">

                            <div class="tab-pane fade show active" id="nav-deliveryman" role="tabpanel"
                                aria-labelledby="nav-deliveryman-tab" tabindex="0">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <div class="star-rating"  data-rate-to="deliveryman">
                                        <div class="d-inline-block">
                                            <i class="feather-star text-warning feather-icon" data-rating="1"></i>
                                            <i class="feather-star text-warning feather-icon" data-rating="2"></i>
                                            <i class="feather-star text-warning feather-icon" data-rating="3"></i>
                                            <i class="feather-star text-warning feather-icon" data-rating="4"></i>
                                            <i class="feather-star feather-icon" data-rating="5"></i>
                                        </div>
                                    </div>
                                </div>
                                <form method="post" id="deliverymanRatingForm" onsubmit="event.preventDefault()">
                                <h6 class="form-group mb-3 fw-bolder text-center"><label class="form-label">Your Comment</label>
                                    <textarea name="review" class="form-control"></textarea>
                                </h6>
                                <div class="form-group mb-0"><button type="submit" class="btn btn-primary w-100"> Submit
                                    Comment
                                </button></div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="nav-restaurant" role="tabpanel"
                                aria-labelledby="nav-restaurant-tab" tabindex="0">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <div class="star-rating" data-rate-to="restaurant">
                                        <div class="d-inline-block">
                                            <i class="feather-star text-warning feather-icon" data-rating="1"></i>
                                            <i class="feather-star text-warning feather-icon" data-rating="2"></i>
                                            <i class="feather-star text-warning feather-icon" data-rating="3"></i>
                                            <i class="feather-star text-warning feather-icon" data-rating="4"></i>
                                            <i class="feather-star feather-icon" data-rating="5"></i>
                                        </div>
                                    </div>
                                </div>
                                <form method="post" id="restaurantRatingForm" onsubmit="event.preventDefault()">
                                <h6 class="form-group mb-3 fw-bolder text-center"><label class="form-label">Your Comment</label>
                                    <textarea name="review" class="form-control"></textarea>
                                </h6>
                                <div class="form-group mb-0"><button type="submit" class="btn btn-primary w-100"> Submit
                                    Comment
                                </button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
    <script>
        const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));

        const ratingStars_res = document.querySelectorAll('[data-rate-to="restaurant"] [data-rating]');
        const ratingStars_dm = document.querySelectorAll('[data-rate-to="deliveryman"] [data-rating]');

        const resRatingForm = document.getElementById('restaurantRatingForm');
        resRatingForm.action = "{{route('user.review.')}}";
        const dmRatingForm = document.getElementById('deliverymanRatingForm');
        dmRatingForm.action = "{{route('user.review.dm')}}";



        document.querySelectorAll('[data-create="review"]').forEach(reviewButton => {


            reviewButton.addEventListener('click',async () => {
                event.stopPropagation();

                formate_review_modal(reviewButton);
                const orderId = reviewButton.dataset.orderId;
                // console.log();
                // return;
                reviewModal.show();
                const deliverymanId = parseInt(reviewButton.dataset.deliverymanId);
                const dmRatingDiv = document.querySelector('[data-rate-to="deliveryman"]');
                const resRatingDiv = document.querySelector('[data-rate-to="restaurant"]');

                if (isNaN(deliverymanId) ||  await checkDmReview(orderId)) {
                    if(!dmRatingDiv.classList.contains('d-none')){
                        dmRatingDiv.classList.add('d-none');
                    }
                    if(!dmRatingForm.classList.contains('d-none')){
                        dmRatingForm.classList.add('d-none');
                    }
                } else {
                    if(dmRatingDiv.classList.contains('d-none')){
                        dmRatingDiv.classList.remove('d-none');
                    }
                    if(dmRatingForm.classList.contains('d-none')){
                        dmRatingForm.classList.remove('d-none');
                    }
                    makeRating(ratingStars_dm,reviewButton,dmRatingForm,deliverymanId);
                }

                if (await checkRestaurantReview(orderId)) {
                    if(!resRatingDiv.classList.contains('d-none')){
                        resRatingDiv.classList.add('d-none');
                    }
                    if(!resRatingForm.classList.contains('d-none')){
                        resRatingForm.classList.add('d-none');
                    }
                } else {
                    if(resRatingDiv.classList.contains('d-none')){
                        resRatingDiv.classList.remove('d-none');
                    }
                    if(resRatingForm.classList.contains('d-none')){
                        resRatingForm.classList.remove('d-none');
                    }
                    makeRating(ratingStars_res,reviewButton,resRatingForm);
                }
            });
        });
        function makeRating (ratingStars,reviewButton,ratingForm,deliverymanId = NaN){
           var orderId = null;
           var ratingValue = 0;
           ratingStars.forEach(ratingStar => {
                if (ratingStar.classList.contains('text-warning')) {
                    ratingStar.classList.remove('text-warning');
                }
                const newRatingStar = ratingStar.cloneNode(true);

                newRatingStar.addEventListener('click', () => {
                    const newRatingStars = newRatingStar.parentElement.querySelectorAll('[data-rating]');
                    // toastr.success('dkd');
                    let starsToMark =  newRatingStar.dataset.rating || 0;
                    ratingValue = starsToMark;

                    orderId = reviewButton.dataset.orderId;

                    for (let j = 0; j < newRatingStars.length; j++) {
                        if (newRatingStars[j].classList.contains('text-warning')) {
                            newRatingStars[j].classList.remove('text-warning');
                        }
                    }
                    for (let i = 0; i < starsToMark; i++) {
                        newRatingStars[i].classList.add('text-warning');

                    }

                })

                ratingStar.replaceWith(newRatingStar);
            });


            ratingForm.addEventListener('submit', async function () {
                const ratingFormData = new FormData(ratingForm);
                if (ratingValue == 0 || ratingValue > 5) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Please select a rating before submitting!',
                    });
                    return false;
                }
                try {
                    ratingFormData.append('rating', ratingValue);
                    if(!isNaN(deliverymanId)){
                        ratingFormData.append('deliveryman_id',deliverymanId);
                        console.log('deliverymanId : '+deliverymanId)
                    }else{
                    console.log('deliverymanId : '+deliverymanId)
                    }

                    const resp = await fetch(`${ratingForm.action}?order_id=${orderId}`, {
                        method: "POST",
                        body: ratingFormData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    });

                    if (!resp.ok) {
                        const error = await resp.json();
                        toastr.error(error.message);
                    } else {
                        const result = await resp.json();
                        console.log(result);
                        ratingForm.reset();
                        ratingValue = 0;
                        orderId = null;
                        reviewModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Thank You!',
                            text: 'Thanks for your rating!',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                } catch (error) {
                    console.error("Error occurred:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while submitting your rating.',
                    });
                }
            })
        }

        async function checkDmReview(orderId){
            try{
                const url = "{{route('user.review.check-dm')}}"+"?order_id="+orderId ;
                const resp = await fetch(url);
                if(!resp.ok){
                    return false;
                }
                const result = await resp.json();
                return result.review_found ; // returns true || false
            }catch(error){
                console.error(error);
                return false
            }
        }

        async function checkRestaurantReview(orderId){
            try{
                const url = "{{route('user.review.check-res')}}"+"?order_id="+orderId ;
                const resp = await fetch(url);
                if(!resp.ok){
                    return false;
                }
                const result = await resp.json();
                return result.review_found ; // returns true || false
            }catch(error){
                console.error(error);
                return false
            }
        }

        function formate_review_modal(reviewButton){
            const deliverman =  reviewButton.dataset.deliveryman != null ? JSON.parse(reviewButton.dataset.deliveryman) : null;
            const foodItems =  reviewButton.dataset.foodItems != null ? JSON.parse(reviewButton.dataset.foodItems) : null;

            if(foodItems != null){
                const foodItemsDiv = document.querySelector('[data-append-food-items]');
                foodItemsDiv.innerHTML = '';
                const foodItemsHtml = foodItems.map((foodItem, index) => {
                    return `
                    <div class="d-flex justify-content-between">
                            <div><span class="me-2">${index+1}.</span>${foodItem.foodName}</div>
                            <div class="">${foodItem.quantity}</div>
                        </div>
                    `;
                }).join('');
                foodItemsDiv.innerHTML =  `<div class="row mb-3 border-bottom pb-3">
                            <div class="fw-bolder col-6"> Product</div>
                            <div class="fw-bolder col-6 text-end">Qty</div>
                            ${foodItemsHtml}
                        </div>`;
            }

            if(deliverman != null){
                const deliveryManDiv = document.querySelector('[data-append-deliveryman]');
                deliveryManDiv.innerHTML = '';
                const deliveryManHtml = `
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <img src="${deliverman.image}" class="rounded-circle img-50 w-25 me-2" alt="">
                        <div class="fw-bolder col-6"> ${deliverman.name}</div>
                    </div>
                `;
                deliveryManDiv.innerHTML =  deliveryManHtml;
            }
            const restaurantDiv = document.querySelector('[data-append-restaurant]');
        }

        var dm_tab = document.querySelector('#nav-deliveryman-tab[data-bs-toggle="tab"]')
        var dm_restaurant = document.querySelector('#nav-restaurant-tab[data-bs-toggle="tab"]')
        dm_tab.addEventListener('shown.bs.tab', function (event) {
            const target = event.target; // newly activated tab

            const isSelected = JSON.parse(target.getAttribute('aria-selected'));
            const foodItemsDiv = document.querySelector('[data-append-food-items]');
            const deliveryManDiv = document.querySelector('[data-append-deliveryman]');
            if (isSelected) {
                deliveryManDiv.classList.remove('d-none');
                foodItemsDiv.classList.add('d-none');
            }else{
                deliveryManDiv.classList.add('d-none');
                foodItemsDiv.classList.remove('d-none');
            }
        });
        dm_restaurant.addEventListener('shown.bs.tab', function (event) {
            const target = event.target; // newly activated tab
            const isSelected = JSON.parse(target.getAttribute('aria-selected'));
            const foodItemsDiv = document.querySelector('[data-append-food-items]');
            const deliveryManDiv = document.querySelector('[data-append-deliveryman]');
            if (isSelected) {
                foodItemsDiv.classList.remove('d-none');
                deliveryManDiv.classList.add('d-none');
            }else{
                foodItemsDiv.classList.add('d-none');
                deliveryManDiv.classList.remove('d-none');
            }
        });
        function shareLink(link) {
            // const link = window.location.href; // Get the current page URL or any custom link

            // Copy the link to the clipboard
            navigator.clipboard.writeText(link).then(() => {
                console.log('Link copied to clipboard: ', link);

                // Now open the share dialog (if supported)
                if (navigator.share) {
                    navigator.share({
                        title: 'Check this out!',
                        text: 'Take a look at this amazing page!',
                        url: link
                    }).then(() => {
                        console.log('Thanks for sharing!');
                    }).catch((err) => {
                        console.log('Error while sharing: ', err);
                    });
                } else {
                    alert('Web Share API not supported in this browser. But the link is copied to your clipboard!');
                }
            }).catch((err) => {
                console.error('Error in copying link to clipboard: ', err);
            });
        }
    </script>
@endpush
