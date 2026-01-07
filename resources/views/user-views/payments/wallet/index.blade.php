@extends('user-views.restaurant.layouts.main')
@php
    $status = 'pending';
@endphp
@section('containt')
    <div class="d-lg-none d-block m-1">
        <div class="bg-primary d-flex w-100 rounded-4">
            <h4 class="text-white fw-bolder fs-4 me-auto mb-0 py-3 px-4" onclick="window.history.back()" style="border-right: 1px solid white;">
                <i class="fas fa-arrow-left"></i>
            </h4>
            <h4 class="fw-bold m-0 text-white w-100 align-self-baseline text-center p-3 ps-0"><i class="fas fa-shopping-bag me-2"></i>Payment & Wallet</h4>
        </div>
    </div>
    <div class="container">
        <div class="row m-0 justify-content-center">
            <div class="col-md-8 px-0 border-top checkout-footer-area">
                <div class="bg-white rounded-4 mt-4">
                    <div class="">
                        <div class="p-3 m-0 w-100 text-center">
                            {{-- <div class="d-flex align-items-center"> --}}

                                <div class="mb-0 align-self-center text-secondary fw-bolder" style="font-size: 23px;">
                                    <span><i class="fa-solid fa-wallet text-warning me-2"></i></span>Available Balance
                                </div>
                            {{-- </div> --}}
                            {{-- <button class="px-2 py-1 fs-5 btn btn-link" type="button">
                                <i class="feather-chevron-right text-warning"></i>
                            </button> --}}
                        {{-- </div>
                        <div class="p-3 text-center"> --}}
                            <div class="mt-2">
                                <h4>{{Helpers::format_currency($wallet->balance)}} </h4>
                                <div>{{Helpers::numberToWords($wallet->balance)}}</div>
                            </div>
                            <hr>
                            <button data-bs-toggle="modal" data-bs-target="#addWalletForm" class="btn btn-primary w-100 btn-sm px-4">Add Money</button>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="btn-group w-100 bg-white" role="group" aria-label="Basic radio toggle button group" id="txn_method">
                        <input type="radio" class="btn-check" name="txn_method" value="all" id="txn_method_all" checked>
                        <label class="btn" for="txn_method_all">All</label>
                        <input type="radio" class="btn-check" name="txn_method" value="wallet" id="txn_method_wallet">
                        <label class="btn" for="txn_method_wallet">Wallet</label>
                        <input type="radio" class="btn-check" name="txn_method" value="cash" id="txn_method_cash">
                        <label class="btn" for="txn_method_cash">Cash</label>
                        <input type="radio" class="btn-check" name="txn_method" value="online" id="txn_method_online">
                        <label class="btn" for="txn_method_online">Online</label>
                        <input type="radio" class="btn-check" name="txn_method" value="referal" id="txn_method_referral">
                        <label class="btn ml-0" for="txn_method_referral">Referral</label>
                    </div>
                    <div class="mt-2">
                        <div class="btn-group bg-white w-100" role="group" aria-label="Basic radio toggle button group" id="txn_type">
                            <input type="radio" class="btn-check" name="txn_type" value="all" id="txn_type_all" checked>
                            <label class="btn btndays" for="txn_type_all">All Transaction</label>
                            <input type="radio" class="btn-check" name="txn_type" value="paid" id="txn_type_paid">
                            <label class="btn btndays" for="txn_type_paid">Paid</label>
                            <input type="radio" class="btn-check" name="txn_type" value="received" id="txn_type_received">
                            <label class="btn btndays" for="txn_type_received">Received</label>
                            <input type="radio" class="btn-check" name="txn_type" value="refund" id="txn_type_refund">
                            <label class="btn btndays" for="txn_type_refund">Refunded</label>
                        </div>
                    </div>
                    <div>
                        <div class="w-100 mt-3" >
                            <div class="row">
                                <div class="col-lg-12 col-12 mt-2 ">
                                    <div class="rounded shadow-sm px-4 py-lg-5 py-4 bg-white" id="txn_history">
                                        <div class="bg-light rounded-end-2 p-3 d-flex justify-content-between" style="border-left:3px solid #ff810a;">
                                            <h6 class="mb-0">Promotional Credit Expired</h6>
                                            <div>₹ 100</div>
                                        </div>
                                        <div class="bg-light rounded-end-2 p-3 d-flex justify-content-between mt-3" style="border-left:3px solid #ff810a;">
                                            <h6 class="mb-0">Promotional Credit Expired</h6>
                                            <div>₹ 100</div>
                                        </div>
                                        <div class="bg-light rounded-end-2 p-3 d-flex justify-content-between mt-3" style="border-left:3px solid #ff810a;">
                                            <h6 class="mb-0">Refund for Order 1001165</h6>
                                            <div>₹ 100</div>
                                        </div>
                                        <div class="bg-light rounded-end-2 p-3 d-flex justify-content-between mt-3" style="border-left:3px solid #ff810a;">
                                            <h6 class="mb-0">Paid for order (Order number)</h6>
                                            <div>₹ 100</div>
                                        </div>
                                        <div class="mt-4">
                                           <span class="fw-bolder text-success"> Note : </span>
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


<!-- Modal -->
<div class="modal fade" id="addWalletForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="new-user-info">

                <div class="row  mt-1">
                    <div class="form-group col-md-12 mx-auto">
                        <form action="{{ route('user.wallet.top-up') }}" method="post" class="p-3 d-flex flex-column justify-content-around" method="post">
                            @csrf
                            <label class="form-label mx-2" for="add-amount ">Add Amount:</label>
                            <input type="number" class="form-control " name="amount" value="" id="add-amount" placeholder="0">
                            </span>
                            <div class="bg-white rounded-4 w-100 mt-3">
                                <h6 class="fw-bolder mb-0 p-3 border-bottom"><i class="fa-solid fa-money-check text-warning me-2"></i>Digital Payment</h6>

                                <div class="mb-0 p-3">
                                    <div class="">
                                        <input class="form-check-input" name="gateway" type="radio" value="phonepe" id="top-up-gatway2" style="font-size: 20px;border:1px solid #ff810a;">
                                        <label class="form-check-label ms-3 fx-6" for="top-up-gatway2">
                                            Online
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <button type="submit d-block mt-4" class="btn btn-primary ">Add</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

@endsection

@push('javascript')

<script>
const TXN_METHOD = {
    all : true,
    wallet : false,
    cash : false,
    online : false,
    referal : false,
}

const TXN_TYPE = {
    all : true ,
    received : false,
    paid : false,
    refund : false,
}

document.querySelectorAll('#txn_method input[name="txn_method"]').forEach((method) => {
    method.addEventListener('change', (event) => {
        if(TXN_METHOD.hasOwnProperty(event.target.value) && event.target.checked){
            for (let key in TXN_METHOD) {
                TXN_METHOD[key] = false;
            }
            TXN_METHOD[event.target.value] = true;
        }
        getHistories();
    });
});

document.querySelectorAll('#txn_type input[name="txn_type"]').forEach((type) => {
    type.addEventListener('change', (event) => {
        if(TXN_TYPE.hasOwnProperty(event.target.value) && event.target.checked){
            for (let key in TXN_TYPE) {
                TXN_TYPE[key] = false;
            }
            TXN_TYPE[event.target.value] = true;
        }
        // console.log(TXN_TYPE);
        getHistories();

    });
});

async function getHistories() {
    const url = "{{route('user.payments.history')}}"
    try {
        const resp = await fetch(url, {
            method: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                txn_type: TXN_TYPE,
                txn_method: TXN_METHOD,
            })
        });

        if (resp.status == 201) {
            const warning = await resp.json();
            Swal.fire(warning.message);
            return true;
        } else if (!resp.ok) {
            const error = await resp.json();

            throw new Error(error.message);
        }
        const result = await resp.json();
        // $('#custom_item').modal('hide');
        appendTransactionData(result);

    } catch (error) {
        console.error('Error:', error);
    }
}
getHistories();

function appendTransactionData(transactions) {
    // Get the container where transaction history will be appended
    const txnHistoryContainer = document.getElementById('txn_history');
    txnHistoryContainer.innerHTML ='';
    const transactionArray = Object.values(transactions);

    // Loop through each transaction in the array
    transactionArray.forEach((txn) => {
        // Create the outer transaction container
        const txnContainer = document.createElement('div');
        // txnContainer.classList.add('col-lg-12', 'col-12', 'mt-2');

        // Create the inner card structure
        txnContainer.innerHTML = `
                <div class="bg-light rounded-end-2 p-3 d-flex justify-content-between" style="border-left:3px solid #ff810a;">
                    <h6 class="mb-0">${txn.remarks || 'Transaction Details'}</h6>
                    <div class="${txn.type == "paid"? 'text-danger' : 'text-success'}" >₹ ${txn.amount.toFixed(2)}</div>
                </div>
                <small class="text-muted">Date: ${new Date(txn.date).toLocaleString()}</small>
        `;

        // Append the transaction container to the main transaction history row
        txnHistoryContainer.appendChild(txnContainer);
    });
}
</script>
@endpush
