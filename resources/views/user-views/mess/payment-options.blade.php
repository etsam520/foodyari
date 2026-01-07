
@extends('user-views.layouts.main')


@push('css')
    <style>
        .offcanvas-bottom {
            height: 70vh !important;
            max-height: 70vh !important;
        }

        .overflow-x-scroll {
            display: flex;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
        }

        .card {
            flex: 0 0 auto;
            margin-right: .5rem;
        }

        .card .card-text {
            font-size: 14px;
        }

        .overflow-x-scroll::-webkit-scrollbar {
            display: none;
        }

        .overflow-x-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .osahan-card-body .card input[type="radio"] {
            display: none;
        }

        .highlight {
            background-color: #ff810a26 !important;
            border-color: #ff810a !important;
        }

        .custom-tooltip .tooltip-inner {
            background-color: rgba(255, 255, 255, 1) !important;
            color: grey !important;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .custom-tooltip .tooltip-arrow::before {
            border-top-color: #ff810a !important;
        }

        .product-count-number-input:focus {
            border: none;
            outline: none;
            box-shadow: none;
        }

        input::placeholder {
            font-size: 18px;
            color: #888;
        }
    </style>
@endpush
@section('content')
    <!-- Top Bar -->
    <div class="d-lg-none d-block m-1">
        <div class="bg-primary d-flex w-100 rounded-4">
            <h4 class="text-white fw-bolder fs-4 me-auto mb-0 py-3 px-4" onclick="window.history.back()" style="border-right: 1px solid white;">
                <i class="fas fa-arrow-left"></i>
            </h4>
            <h4 class="fw-bold m-0 text-white w-100 align-self-baseline text-center p-3 ps-0"><i class="fas fa-shopping-bag me-2"></i>Pay Options</h4>
        </div>
    </div>

    <div class="container">
        <div class="row m-0 justify-content-center">
            <div class="col-md-8 px-0 border-top">
                <div class="p-3 mt-4 bg-light rounded-4 w-100 d-flex justify-content-between">

                    <h5 class="fw-bolder mb-0">Amount Payable</h5>
                    <h4 class="fw-bolder mb-0">{{ App\CentralLogics\Helpers::format_currency($order->total) }}</h4>
                </div>
                <h6 class="p-3 px-0 mt-3 w-100 fw-bolder text-secondary" style="letter-spacing:3px;">
                    Select payment options</h6>

                <div class="bg-white rounded-4 mt-2 w-100" id="wallet-container">
                    <div class="mb-0 p-0 d-flex justify-content-between align-items-center">
                        <div class="w-100 d-flex justify-content-between align-items-center p-3">
                            <div class="align-self-center">
                                <input class="form-check-input mt-0" type="checkbox" name="paymode['wallet']" {{ $wallet->balance == 0 ? 'disabled' : null }} value="wallet" id="wallet-check" style="font-size: 20px;border:1px solid #ff810a;">
                                <label class="form-check-label ms-3 fx-6" for="wallet-check">
                                    Wallet
                                </label>
                            </div>
                            <div class="text-end d-flex">
                                @if ($wallet->balance < $order->total)
                                    <div class="text-warning text-nowrap align-self-center me-2">Low Balance : {{ App\CentralLogics\Helpers::format_currency($wallet->balance) }}</div>
                                @else
                                    <div class="text-success text-nowrap align-self-center me-2">Balance : {{ App\CentralLogics\Helpers::format_currency($wallet->balance) }}</div>
                                @endif
                                <a class="btn btn-primary text-nowrap text-white" data-bs-toggle="offcanvas" href="#wallet-top-up" role="button" aria-controls="walletTopUP">Top-up</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-4 w-100 mt-3" id="online-getway">
                    <h6 class="fw-bolder mb-0 p-3 border-bottom"><i class="fa-solid fa-money-check text-warning me-2"></i>Digital Payment</h6>
                    {{-- <div class="mb-0 p-3">
                        <div class="">
                            <input class="form-check-input" type="radio" name="gateway" value="paytm" id="flexCheckDefault" style="font-size: 20px;border:1px solid #ff810a;">
                            <label class="form-check-label ms-3 fx-6" for="flexCheckDefault">
                                <img src="{{ asset('assets/user/img/paytm.png') }}" alt="" style="height: 25px;" class="me-1">
                                Paytm
                            </label>
                        </div>
                    </div> --}}
                    <div class="mb-0 p-3">
                        <div class="">
                            <input class="form-check-input" name="gateway" type="radio" value="phonepe" id="flexCheckDefault" style="font-size: 20px;border:1px solid #ff810a;">
                            <label class="form-check-label ms-3 fx-6" for="flexCheckDefault">
                                <img src="{{ asset('assets/user/img/phonepe.webp') }}" alt="" style="height: 25px;" class="me-1">
                                Online
                            </label>
                        </div>
                    </div>
                    {{-- <div class="mb-0 p-3">
                        <div class="">
                            <input class="form-check-input" type="radio" name="gateway" value="gpay" id="flexCheckDefault" style="font-size: 20px;border:1px solid #ff810a;">
                            <label class="form-check-label ms-3 fx-6" for="flexCheckDefault">
                                <img src="{{ asset('assets/user/img/gpay.jpg') }}" alt="" style="height: 25px;" class="me-1">
                                GPay
                            </label>
                        </div>
                    </div> --}}
                </div>

                <div class="bg-white mt-3 rounded-4 w-100" id="cash-container">
                    <div class="mb-0 p-3">
                        <div class="">
                            <input class="form-check-input" type="radio" name="paymode['cash']" value="cash" id="cash" style="font-size: 20px;border:1px solid #ff810a;">
                            <label class="form-check-label ms-3 fx-6" for="cash">
                                CASH
                            </label>
                        </div>
                    </div>
                </div>
                <p class="mt-3"><span class="text-danger me-2">*</span><small>PLEASE KEEP THE CHANGE FOR FASTER DELIVERY</small></p>
            </div>
        </div>
    </div>
@endsection
@push('modal')
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="wallet-top-up" aria-labelledby="walletTopUP">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasExampleLabel">Wallet Top-Up</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="form-group col-md-6 mx-auto">
            <form action="{{route('user.wallet.top-up')}}" method="post" class="p-3 d-flex flex-column justify-content-around" method="post">
                @csrf
                    <label class="form-label mx-2" for="add-amount ">Add Amount:</label>
                    <input type="number" class="form-control " name="amount" value="" id="add-amount" placeholder="0">
                </span>
                <div class="bg-white rounded-4 w-100 mt-3">
                    <h6 class="fw-bolder mb-0 p-3 border-bottom"><i class="fa-solid fa-money-check text-warning me-2"></i>Digital Payment</h6>
                    {{-- <div class="mb-0 p-3">
                        <div class="">
                            <input class="form-check-input" type="radio" name="gateway" value="paytm" id="top-up-gatway1" style="font-size: 20px;border:1px solid #ff810a;">
                            <label class="form-check-label ms-3 fx-6" for="top-up-gatway1">
                                <img src="{{ asset('assets/user/img/paytm.png') }}" alt="" style="height: 25px;" class="me-1">
                                Paytm
                            </label>
                        </div>
                    </div> --}}
                    <div class="mb-0 p-3">
                        <div class="">
                            <input class="form-check-input" name="gateway" type="radio" value="phonepe" id="top-up-gatway2" style="font-size: 20px;border:1px solid #ff810a;">
                            <label class="form-check-label ms-3 fx-6" for="top-up-gatway2">
                                {{-- <img src="{{ asset('assets/user/img/phonepe.webp') }}" alt="" style="height: 25px;" class="me-1"> --}}
                                Online
                            </label>
                        </div>
                    </div>
                    {{-- <div class="mb-0 p-3">
                        <div class="">
                            <input class="form-check-input" type="radio" name="gateway" value="gpay" id="top-up-gatway3" style="font-size: 20px;border:1px solid #ff810a;">
                            <label class="form-check-label ms-3 fx-6" for="top-up-gatway3">
                                <img src="{{ asset('assets/user/img/gpay.jpg') }}" alt="" style="height: 25px;" class="me-1">
                                GPay
                            </label>
                        </div>
                    </div> --}}
                </div>
                <button type="submit d-block mt-4" class="btn btn-primary ">Add</button>
            </form>
        </div>
    </div>
    </div>
  </div>

@endpush

@push('javascript')
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2@11.js') }}"></script>

    <script type="module">
        const CALC_OPTION = {
            orderAmount: {{ $order->total }},
            walletBalance: {{ $wallet->balance }},
            order_id : {{$order->id}},
            cash: 0,
            wallet: 0,
            online: 0,
            pluckAmount: 0,
            gateway: null,
            // paymentOptions: [],
        };

        const walletContainer = document.getElementById('wallet-container');
        const gatewayContainer = document.getElementById('online-getway');
        const cashContainer = document.getElementById('cash-container');

        const walletCheckInput = walletContainer.querySelector('input');
        const cashContainerInput = cashContainer.querySelector('input[type=radio]');
        walletCheckInput.addEventListener('change', () => {
            try {
                if (CALC_OPTION.walletBalance == 0 || !walletCheckInput.checked) {
                    CALC_OPTION.wallet = 0;
                    CALC_OPTION.pluckAmount = 0;
                    return;
                }

                if (CALC_OPTION.orderAmount > CALC_OPTION.walletBalance) {
                    CALC_OPTION.wallet = CALC_OPTION.walletBalance;
                } else {
                    CALC_OPTION.wallet = CALC_OPTION.orderAmount;
                }

                Swal.fire({
                    title: "Custumize wallet",
                    input: "text",
                    inputAttributes: {
                        autocapitalize: "off"
                    },
                    inputValue: CALC_OPTION.wallet > CALC_OPTION.orderAmount ? CALC_OPTION.orderAmount : CALC_OPTION.wallet,
                    showCancelButton: true,
                    confirmButtonText: "OK",
                    preConfirm: (wallet_amount) => {
                        try {
                            wallet_amount = parseFloat(wallet_amount); // Ensure the input is treated as a number

                            if (isNaN(wallet_amount)) {
                                throw new Error('Amount must be a valid number');
                            }

                            if (wallet_amount > Math.ceil(CALC_OPTION.orderAmount)) {
                                throw new Error('Selected amount is over the Order Amount');
                            } else if (wallet_amount > Math.ceil(CALC_OPTION.wallet)) {
                                throw new Error('Selected amount exceeds the balance in Wallet');
                            } else if (wallet_amount <= 0) {
                                throw new Error('Amount cannot be null or negative');
                            }

                            CALC_OPTION.wallet = wallet_amount;
                            CALC_OPTION.pluckAmount = CALC_OPTION.orderAmount - CALC_OPTION.wallet;

                            if (CALC_OPTION.pluckAmount != 0) {
                                const message = `Rs. ${CALC_OPTION.wallet} will be used from the wallet and the remaining amount of Rs. ${CALC_OPTION.pluckAmount} is payable in cash or through the Payment Gateway`;
                                Swal.fire({
                                    title: message,
                                    showCloseButton: false,
                                    timer: 5000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            }
                        } catch (error) {
                            Swal.showValidationMessage(error.message);
                            return false; // Return false to indicate validation failure
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading(),
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (CALC_OPTION.pluckAmount == 0) {
                            placeOrder(CALC_OPTION);
                        }
                        return true;
                    } else {
                        CALC_OPTION.wallet = 0;
                        CALC_OPTION.pluckAmount = 0;
                        walletCheckInput.checked = false;
                    }
                });

            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            }
        });



        const getwayOptions = gatewayContainer.querySelectorAll('input[name=gateway]');
        getwayOptions.forEach(element => {
            element.addEventListener('change', () => {
                try {
                    if (element.checked != true) {
                        element.disabled = true;
                        return;
                    } else {
                        cashContainerInput.checked = false;
                    }

                    let message = '';

                    if (CALC_OPTION.pluckAmount > 0 && CALC_OPTION.wallet > 0) {
                        CALC_OPTION.online = CALC_OPTION.pluckAmount;
                        message = `Rs. ${CALC_OPTION.wallet} will be used from the wallet and the remaining amount of Rs. ${CALC_OPTION.pluckAmount} is payable via the Payment Gateway.`;
                    } else {
                        CALC_OPTION.online = CALC_OPTION.orderAmount;
                        message = `Rs. ${CALC_OPTION.online} will be paid using the Payment Gateway.`;
                    }

                    CALC_OPTION.gateway = element.value;
                    Swal.fire({
                        title: message,
                        showCloseButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    }).then(result => {

                        Swal.fire({
                            title: "Confirm to make payment?",
                            showCancelButton: true,
                            confirmButtonText: "Process",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let options = Object.entries(CALC_OPTION).filter(([key, value]) => {
                                    let flag = true;

                                    if (key === 'walletBalance') {
                                        flag = false;
                                    }else if (typeof value === 'number' && value === 0) {
                                        flag = false;
                                    }
                                    return flag ? value : null;
                                });
                                const URI = new URLSearchParams(options).toString();

                                location.href = "{{ route('user.mess.order-payment-online') }}?" + URI;
                            } else if (result.isDismissed) {
                                Swal.fire("Process Cancelled", "", "info");
                            }
                        });
                    });
                } catch (error) {
                    Swal.fire('Error', error.message, 'error');
                }
            });
        });


        cashContainer.addEventListener('change', () => {
            try {
                for (let elem of getwayOptions)
                    elem.checked = false;

                CALC_OPTION.online = 0;
                let message = '';
                if (CALC_OPTION.wallet > 0) {
                    CALC_OPTION.cash = CALC_OPTION.pluckAmount;
                    message = `Rs. ${CALC_OPTION.wallet} will be used from the wallet and the remaining amount of Rs. ${CALC_OPTION.cash} is payable via Cash.`;
                } else {
                    CALC_OPTION.cash = CALC_OPTION.orderAmount;
                    message = `Rs. ${CALC_OPTION.cash} will be colleted via Cash`;
                }

                CALC_OPTION.gateway = null;
                Swal.fire({
                    title: message,
                    showCloseButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                }).then(result => {

                    Swal.fire({
                        title: "Confirm Your?",
                        showCancelButton: true,
                        confirmButtonText: "Confirm",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Place your order processing code here
                            // Swal.fire("Payment Processed!", "", "success");
                            placeOrder(CALC_OPTION);
                        } else if (result.isDismissed) {
                            Swal.fire("Process Cancelled", "", "info");
                        }
                    });
                });
            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            }
        });

        function placeOrder(CALC_OPTION) {
            const URI = new URLSearchParams(CALC_OPTION).toString();
            location.href = "{{ route('user.mess.order-quick-payment') }}?" + URI;
            // console.log(ldd);
        }
    </script>
@endpush








