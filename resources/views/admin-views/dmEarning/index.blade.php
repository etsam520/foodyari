@extends('layouts.dashboard-main')
@push('css')
<style>
    span.select2.select2-container{
        width: 100% !important;
    }
</style>
@endpush

@php
    $zones = App\Models\Zone::isActive()->get();
@endphp
@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{__('Deliveryman Cash Collections')}}</h4>
                    </div>
                    <div>
                        <a href="{{route('admin.earning.payouts')}}" class="btn btn-info">
                            {{__('View All Payouts')}}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.earning.dm_save_cash_txn')}}" id="deliveryman_cash_txn_form" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-4 col-sm-6" id="zone_wise">
                                <label class="input-label" for="choice_zones">{{__('messages.select')}} {{__('messages.zone')}}</label>
                                <select name="zone_id" id="choice_zones"
                                    class="form-control js-select2-custom select-2"
                                    data-placeholder="{{__('messages.select_zone')}}">
                                    <option value="">{{__('messages.select_zone')}}</option>
                                @foreach($zones as $zone)
                                    <option value="{{$zone->id}}">{{$zone->name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-sm-6" id="deliveryman_select_container">
                                <label class="input-label" for="deliveryman_select">{{ __('messages.select') }} {{ __('messages.deliveryman') }}</label>
                                <select name="deliveryman_id" id="deliveryman_select"
                                    class="form-control js-select2-custom"
                                    data-placeholder="{{ __('messages.deliveryman') }}">
                                </select>
                            </div>

                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="dm_cash_in_hand">Amount </label>
                                    <input id="dm_cash_in_hand" type="number" name="dm_cash_in_hand" class="form-control"
                                         required step="0.01" min="0.01" placeholder="Ex. 100">
                                </div>
                            </div>
                            <div class="form-group col-lg-4 col-sm-6" >
                                <label class="input-label" for="payment_method">{{ __('messages.select') }} {{ __('Payment Method') }}</label>
                                <select name="payment_method" id="payment_method"
                                    class="form-control">
                                    <option value="cash" selected>Cash</option>
                                    <option value="upi">UPI</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="_notes">Notes.</label>
                                    <input type="text" name="notes" id="_notes" class="form-control" placeholder="{{ __('messages.Ex :') }} paying" maxlength="255">
                                </div>
                            </div>

                        </div>
                        <div class="btn--container justify-content-end">
                            <button id="reset_btn_cash" type="button" class="btn btn-secondary">{{__('messages.reset')}}</button>
                            <button type="submit" class="btn btn-warning">{{__('messages.submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{__('Deliveryman Clear Wallet')}}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.earning.dm_save_wallet_txn')}}" id="deliveryman_wallet_txn_form" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-4 col-sm-6" id="zone_wise_2">
                                <label class="input-label" for="choice_zones_2">{{__('messages.select')}} {{__('messages.zone')}}</label>
                                <select name="zone_id" id="choice_zones_2"
                                    class="form-control js-select2-custom select-2"
                                    data-placeholder="{{__('messages.select_zone')}}">
                                    <option value="">{{__('messages.select_zone')}}</option>
                                @foreach($zones as $zone)
                                    <option value="{{$zone->id}}">{{$zone->name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-sm-6" >
                                <label class="input-label" for="deliveryman_select_2">{{ __('messages.select') }} {{ __('messages.deliveryman') }}</label>
                                <select name="deliveryman_id" id="deliveryman_select_2"
                                    class="form-control js-select2-custom"
                                    data-placeholder="{{ __('messages.deliveryman') }}">
                                </select>
                            </div>
                            {{-- <div class="form-group col-lg-4 col-sm-6" >
                                <label class="input-label" for="work_with">{{ __('messages.select') }} {{ __('Process For') }}</label>
                                <select name="process_for" id="work_with"
                                    class="form-control">
                                    <option value="cash_transaction" selected>Cash Transaction</option>
                                    <option value="wallet_transaction">Wallet Transaction</option>
                                </select>
                            </div> --}}
                            {{-- <div class="form-group col-lg-3 col-sm-6" >
                                <label class="input-label" for="txn_type">{{ __('messages.select') }} {{ __('Transaction Type') }}</label>
                                <select name="transaction_type" id="txn_type"
                                    class="form-control">
                                    <option value="deduct" selected>Deduct</option>
                                    <option value="deposit">Deposit</option>
                                </select>
                            </div> --}}
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="dm_wallet_amout">Amount </label>
                                    <input id="dm_wallet_amout" type="number" name="amount" class="form-control"
                                         required step="0.01" min="0.01" placeholder="Ex. 100">
                                </div>
                            </div>
                            <div class="form-group col-lg-4 col-sm-6" >
                                <label class="input-label" for="payment_method_2">{{ __('messages.select') }} {{ __('Payment Method') }}</label>
                                <select name="payment_method" id="payment_method_2"
                                    class="form-control">
                                    <option value="cash" selected>Cash</option>
                                    <option value="upi">UPI</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="_notes_2">Notes.</label>
                                    <input type="text" name="notes" id="_notes_2" class="form-control" placeholder="{{ __('messages.Ex :') }} paying" maxlength="255">
                                </div>
                            </div>

                        </div>
                        <div class="btn--container justify-content-end">
                            <button id="reset_btn_wallet" type="button" class="btn btn-secondary">{{__('messages.reset')}}</button>
                            <button type="submit" class="btn btn-warning">{{__('messages.submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>


@endsection

@push('javascript')
<script>
    let CASH_IN_HAND_LIMIT = 0;
    let WALLET_LIMIT = 0;

    // Initialize Select2 and fetch all delivery men
    $(document).ready(function() {
        $('.js-select2-custom').select2();
        getDeliveryman('all','#deliveryman_select_2');
        getDeliveryman('all','#deliveryman_select');
    });

    // Zone change handlers
    $('#choice_zones').on('change', async (event) => {
        const zoneId = event.target.value;
        if (zoneId) {
            getDeliveryman(zoneId, '#deliveryman_select');
        } else {
            getDeliveryman('all', '#deliveryman_select');
        }
    });

    $('#choice_zones_2').on('change', async (event) => {
        const zoneId = event.target.value;
        if (zoneId) {
            getDeliveryman(zoneId, '#deliveryman_select_2');
        } else {
            getDeliveryman('all', '#deliveryman_select_2');
        }
    });

    async function getDeliveryman(zone_id, id_to_append) {
        try {
            const resp = await fetch(`{{ route('admin.delivery-man.get-deliverymen')}}?zone_id=${zone_id}`);
            const result = await resp.json();

            let dataToAppend = [
                {
                    id: '',
                    text: 'Select One'
                }
            ];

            const mappedResults = result.map(item => {
                return {
                    id: item.id,
                    text: item.name + ' ( ' + item.phone + ' )'
                };
            });

            dataToAppend = dataToAppend.concat(mappedResults);

            $(id_to_append).empty();
            $(id_to_append).select2({
                data: dataToAppend
            });

        } catch (error) {
            console.error('Error fetching delivery men:', error);
            toastr.error('An error occurred while fetching delivery men.');
        }
    }

    // Cash form delivery man selection
    $('#deliveryman_select').on('change', async (event) => {
        const dmId = event.target.value;
        if (dmId) {
            await renderDmCashInHand(dmId);
        }
    });

    async function renderDmCashInHand(dmId) {
        try {
            const resp = await fetch(`{{ route('admin.earning.dm-cash-in-hand')}}?dm_id=${dmId}`);
            const result = await resp.json();
            CASH_IN_HAND_LIMIT = result.amount;
            document.querySelector('label[for=dm_cash_in_hand]').textContent = `Amount (Cash in Hand: ${result.amount})`;
        } catch (error) {
            console.error('Error fetching cash in hand:', error);
            toastr.error('An error occurred while fetching cash in hand data.');
        }
    }

    // Wallet form delivery man selection
    $('#deliveryman_select_2').on('change', async (event) => {
        const dmId = event.target.value;
        if (dmId) {
            await renderDmWalletBalance(dmId);
        }
    });

    async function renderDmWalletBalance(dmId) {
        try {
            const resp = await fetch(`{{ route('admin.earning.dm-wallet-balance')}}?dm_id=${dmId}`);
            const result = await resp.json();
            WALLET_LIMIT = result.amount;
            document.querySelector('label[for=dm_wallet_amout]').textContent = `Amount (Wallet Balance: ${result.amount})`;
        } catch (error) {
            console.error('Error fetching wallet balance:', error);
            toastr.error('An error occurred while fetching wallet balance data.');
        }
    }

    // Amount validation
    document.querySelector('#dm_cash_in_hand').addEventListener('input', (event) => {
        const amount = parseFloat(event.target.value);
        if (amount > CASH_IN_HAND_LIMIT) {
            toastr.error('Amount exceeds the cash in hand limit');
            event.target.setCustomValidity('Amount exceeds limit');
        } else {
            event.target.setCustomValidity('');
        }
    });

    document.querySelector('#dm_wallet_amout').addEventListener('input', (event) => {
        const amount = parseFloat(event.target.value);
        if (amount > WALLET_LIMIT) {
            toastr.error('Amount exceeds the wallet balance limit');
            event.target.setCustomValidity('Amount exceeds limit');
        } else {
            event.target.setCustomValidity('');
        }
    });

    // Cash transaction form submission
    const deliverymanCashTxnForm = document.getElementById('deliveryman_cash_txn_form');
    deliverymanCashTxnForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const dmId = deliverymanCashTxnForm.deliveryman_id.value;
        if (!dmId) {
            toastr.error('Please select a delivery man');
            return false;
        }

        const amount = parseFloat(deliverymanCashTxnForm.dm_cash_in_hand.value);
        if (amount > CASH_IN_HAND_LIMIT) {
            toastr.error('Amount exceeds the available cash in hand');
            return false;
        }

        const formData = new FormData(deliverymanCashTxnForm);
        const submitBtn = deliverymanCashTxnForm.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';

        try {
            const response = await fetch(deliverymanCashTxnForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                deliverymanCashTxnForm.reset();
                CASH_IN_HAND_LIMIT = 0;
                document.querySelector('label[for=dm_cash_in_hand]').textContent = 'Amount';
                toastr.success('Transaction saved successfully!');
            } else {
                const errorData = await response.json();
                toastr.error(errorData.message || 'An error occurred');
            }
        } catch (error) {
            console.error('Error submitting cash transaction:', error);
            toastr.error('An unexpected error occurred. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = '{{__("messages.submit")}}';
        }
    });

    // Wallet transaction form submission
    const deliverymanWalletTxnForm = document.getElementById('deliveryman_wallet_txn_form');
    deliverymanWalletTxnForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const dmId = deliverymanWalletTxnForm.deliveryman_id.value;
        if (!dmId) {
            toastr.error('Please select a delivery man');
            return false;
        }

        const amount = parseFloat(deliverymanWalletTxnForm.amount.value);
        if (amount > WALLET_LIMIT) {
            toastr.error('Amount exceeds the available wallet balance');
            return false;
        }

        const formData = new FormData(deliverymanWalletTxnForm);
        const submitBtn = deliverymanWalletTxnForm.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';

        try {
            const response = await fetch(deliverymanWalletTxnForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                deliverymanWalletTxnForm.reset();
                WALLET_LIMIT = 0;
                document.querySelector('label[for=dm_wallet_amout]').textContent = 'Amount';
                toastr.success('Transaction saved successfully!');
            } else {
                const errorData = await response.json();
                toastr.error(errorData.message || 'An error occurred');
            }
        } catch (error) {
            console.error('Error submitting wallet transaction:', error);
            toastr.error('An unexpected error occurred. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = '{{__("messages.submit")}}';
        }
    });

    // Reset buttons
    document.getElementById('reset_btn_cash').addEventListener('click', function() {
        deliverymanCashTxnForm.reset();
        CASH_IN_HAND_LIMIT = 0;
        document.querySelector('label[for=dm_cash_in_hand]').textContent = 'Amount';
    });

    document.getElementById('reset_btn_wallet').addEventListener('click', function() {
        deliverymanWalletTxnForm.reset();
        WALLET_LIMIT = 0;
        document.querySelector('label[for=dm_wallet_amout]').textContent = 'Amount';
    });
</script>
@endpush
