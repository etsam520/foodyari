@extends('vendor-views.layouts.dashboard-main')

@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
@endpush
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title"> Delivery Man Wallet Information</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="new-user-info">
                        <div class="form-group">
                            <label for="dm">Select Delivery Man</label>
                            <select class="form-control" id="dm" data-options="dm">
                                <option value="">Select One
                                </option>
                                @foreach ($dm_list as $dm)
                                    <option value="{{$dm->id}}">{{$dm->f_name.' '.$dm->l_name}} ({{$dm->phone}})</option>
                                @endforeach
                            </select>
                        </div>
                        <h3 class="h-3 text-start" style="color: #38c54a" data-add-wallet="btn">
                            <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 512 512" width="50">
                                <path
                                    d="M217 100h35.5v35.5a7.5 7.5 0 0 0 15 0V100H302a7.5 7.5 0 0 0 0-15h-34.5V50.5a7.5 7.5 0 0 0-15 0V85H217a7.5 7.5 0 0 0 0 15Zm224 106h-22.5v-52.5A37.542 37.542 0 0 0 381 116h-31.502a92.5 92.5 0 1 0-178.996 0H86a52.5 52.5 0 0 0-52.5 52.5v276A67.576 67.576 0 0 0 101 512h340a37.542 37.542 0 0 0 37.5-37.5v-231A37.542 37.542 0 0 0 441 206ZM260 15a77.602 77.602 0 0 1 72.405 105.259 7.434 7.434 0 0 0-.454 1.192A77.17 77.17 0 0 1 296.237 161h-72.474a77.17 77.17 0 0 1-35.714-39.549 7.434 7.434 0 0 0-.454-1.192A77.602 77.602 0 0 1 260 15ZM59.484 141.983A37.25 37.25 0 0 1 86 131h89.824a91.7 91.7 0 0 0 21.959 30H146a7.5 7.5 0 0 0 0 15h152.065l.02.001.027-.001H373a7.5 7.5 0 0 0 0-15h-50.783a91.7 91.7 0 0 0 21.959-30H381a22.525 22.525 0 0 1 22.5 22.5V206H86a37.5 37.5 0 0 1-26.516-64.017ZM463.5 396h-82a37 37 0 0 1 0-74h82Zm0-89h-82a52 52 0 0 0 0 104h82v63.5A22.525 22.525 0 0 1 441 497H101a52.56 52.56 0 0 1-52.5-52.5V205.192A52.335 52.335 0 0 0 86 221h355a22.525 22.525 0 0 1 22.5 22.5Zm-89 52a7.5 7.5 0 1 0 7.5-7.5 7.5 7.5 0 0 0-7.5 7.5Z"
                                    fill="#38c54a"></path>
                            </svg>
                            &nbsp; <span data-balance="true"></span>
                        </h3>
                        <div class="row  mt-5"> 
                            <div class="form-group col-md-4 mx-auto d-none" data-add-amount="form">
                                <form action="javascript:void(0)" class="p-3 d-flex flex-column justify-content-around" method="post">
                                    <input type="hidden" name="walletId" >
                                    <span class="mb-3">
                                        <label class="form-label mx-2" for="add-amount " >Add Amount:</label>
                                        <input type="number" class="form-control " name="add_amount" value="{{old('add_amount')}}" id="add-amount" placeholder="0">
                                    </span>
                                    @if($errors->has('add_amount'))
                                    <span class="text-danger mb-3 ">{{$errors->first('add_amount')}}</span>
                                    @endif
                                    <button type="submit" class="btn btn-primary ">Add</button>
                                </form>
                            </div>
                            <div class="col-12 mt-5">
                                <h4 style="color: #fc6603">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="50">
                                        <g data-name="Layer 2">
                                            <path
                                                d="M87.88,29.84A29.68,29.68,0,0,0,48.4,27.66h-21a1.5,1.5,0,0,0,0,3h17.8a29.56,29.56,0,0,0-4.27,5.91H17.34a1.5,1.5,0,0,0,0,3H39.49a29.83,29.83,0,0,0-2.07,14.08h-10a1.5,1.5,0,0,0,0,3H37.87A29.41,29.41,0,0,0,39.6,62.3H17.34a1.5,1.5,0,0,0,0,3H41.07A29,29,0,0,0,45.19,71H13.7a1.5,1.5,0,0,0,0,3H47.41a1.58,1.58,0,0,0,.75-.2A29.65,29.65,0,0,0,87.88,29.84ZM85.76,69.65a26.64,26.64,0,1,1,0-37.69A26.69,26.69,0,0,1,85.76,69.65Z"
                                                fill="#fc6603" ></path>
                                            <path
                                                d="M66.92,28.49A22.31,22.31,0,1,0,89.23,50.8,22.34,22.34,0,0,0,66.92,28.49ZM68.42,70V68.25a1.5,1.5,0,0,0-3,0V70A19.32,19.32,0,0,1,47.69,52.3h1.78a1.5,1.5,0,0,0,0-3H47.69A19.31,19.31,0,0,1,65.42,31.57v1.78a1.5,1.5,0,1,0,3,0V31.57A19.31,19.31,0,0,1,86.15,49.3H84.37a1.5,1.5,0,0,0,0,3h1.78A19.32,19.32,0,0,1,68.42,70Z"
                                                fill="#fc6603" ></path>
                                            <path
                                                d="M75.16 54.3l-6.29-4 6.53-13A1.5 1.5 0 0072.72 36L65.58 50.13a1.5 1.5 0 00.53 1.94l7.43 4.76a1.48 1.48 0 00.8.23 1.5 1.5 0 00.82-2.76zM22.48 55.14a1.5 1.5 0 00-1.5-1.5H5.07a1.5 1.5 0 000 3H21A1.5 1.5 0 0022.48 55.14zM12.42 30.65H21a1.5 1.5 0 000-3H12.42a1.5 1.5 0 000 3zM10.79 48H30.56a1.5 1.5 0 100-3H10.79a1.5 1.5 0 000 3z"
                                                fill="#fc6603" ></path>
                                        </g>
                                    </svg>
                                    &nbsp;Last Updated :
                                     <small data-lastupdated="true"> </small> 
                                    </h4>
                                <a href="{{route('mess.mywalletHistories')}}" style="color: #38c54a">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="50" height="50">
                                        <path
                                            d="M13.975 4.242h-7.95a1.783 1.783 0 0 0-1.78 1.78v4.467a1.507 1.507 0 0 0 1.478 1.5l1.083.016v2.253a1.502 1.502 0 0 0 1.5 1.5h5.949a1.502 1.502 0 0 0 1.5-1.5V6.022a1.783 1.783 0 0 0-1.78-1.78Zm-7.17 6.763-1.067-.016a.502.502 0 0 1-.493-.5V6.022a.78.78 0 0 1 1.56 0Zm7.95 3.253a.5.5 0 0 1-.5.5h-5.95a.5.5 0 0 1-.5-.5V6.022a1.772 1.772 0 0 0-.18-.78h6.35a.781.781 0 0 1 .78.78Zm-1.207-7.516a.5.5 0 0 1-.5.5h-2.554a.5.5 0 0 1 0-1h2.554a.5.5 0 0 1 .5.5Zm0 2a.5.5 0 0 1-.5.5H9.494a.5.5 0 0 1 0-1h3.554a.5.5 0 0 1 .5.5Zm0 2a.5.5 0 0 1-.5.5H9.494a.5.5 0 0 1 0-1h3.554a.5.5 0 0 1 .5.5Z"
                                            fill="#38c54a" ></path>
                                    </svg>
                                     See History</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          {{-- loading history --}}
    </div>
    <div class="row mt-3" data-wallet-history="true">
    </div>
 </div>
@endsection

@push('javascript')
<script type="module">
    import { currencySymbolsuffix, formatDate } from "{{ asset('assets/js/Helpers/helper.js') }}";
    $("[data-options=dm]").select2({}).on('change', async function() {
        try {
            const dm_id = this.value;
            if (!dm_id) {
                throw new Error('Select Atleast One Delivery Man');
            }

            const resp = await fetch("{{ route('mess.delivery-man.getwalletdata') }}?dm_id=" + dm_id);
            const result = await resp.json();
            const walletId = result.id;
            document.querySelector('[data-balance]').textContent = currencySymbolsuffix(result.balance);
            document.querySelector('[data-lastupdated]').textContent = formatDate(result.updated_at);
            
            document.querySelector('[data-add-wallet]').addEventListener('click', () => {
                document.querySelector('[data-add-amount]').classList.replace('d-none', 'd-block');
                addBalanceForm(walletId);
            });
            getTransactionHistory("{{route('mess.delivery-man.walletTransactions')}}?wallet_id="+walletId, walletId)
            console.log(result);
        } catch (error) {
            toastr.error(error.message);
        }
    });

    function addBalanceForm(walletId) {
        try {
            if (!walletId) {
                throw new Error("Wallet doesn't exist");
            }
            const form = document.querySelector('[data-add-amount] form');
            form.walletId.value = walletId;
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const formData = new FormData(form);
                const requestData = Object.fromEntries(formData);
                const resp = await fetch("{{ route('mess.delivery-man.updateWallet') }}", {
                    method: 'POST',
                    body: JSON.stringify({ data: requestData }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                const result = await resp.json();
                if (result.error) {
                    toastr.error(result.error);
                } else {
                    // Handle success response
                    // Example: display a success message
                    toastr.success("Wallet updated successfully");
                }
            });
        } catch (error) {
            toastr.error(error.message);
        }
    }




async function getTransactionHistory(url,walletId) {
    try {
        const resp = await fetch(url);
        if (!resp.ok) {
            throw new Error(`Failed to fetch data (${resp.status} ${resp.statusText})`);
        }
        const result = await resp.text();
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(result, 'text/xml');

        const xmlContainer = document.createElement('div');
        xmlContainer.classList.add('col-lg-12');
        xmlContainer.innerHTML = result;
       

        const targetElement = document.querySelector('[data-wallet-history]');
        targetElement.innerHTML = null;
        targetElement.appendChild(xmlContainer);
       
        document.querySelectorAll('[data-wallet-history] .pagination a').forEach(anchor => {
        anchor.addEventListener('click',  (event) => {
            event.preventDefault();
            const url = anchor.href + `&wallet_id=${walletId}`;
            getTransactionHistory(url, walletId);
        });
    });
    } catch (error) {
        toastr.error(error.message);
    }
}


</script>
@endpush


