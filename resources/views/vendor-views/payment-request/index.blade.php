@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between">
                        <div class="fs-4 fw-bolder"> ₹ 4,627.00</div>
                        <div class="d-flex">
                            <p class="mb-0 d-flex align-items-center fw-bolder">
                                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="me-2 icon-20">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.56517 3C3.70108 3 3 3.71286 3 4.5904V5.52644C3 6.17647 3.24719 6.80158 3.68936 7.27177L8.5351 12.4243L8.53723 12.4211C9.47271 13.3788 9.99905 14.6734 9.99905 16.0233V20.5952C9.99905 20.9007 10.3187 21.0957 10.584 20.9516L13.3436 19.4479C13.7602 19.2204 14.0201 18.7784 14.0201 18.2984V16.0114C14.0201 14.6691 14.539 13.3799 15.466 12.4243L20.3117 7.27177C20.7528 6.80158 21 6.17647 21 5.52644V4.5904C21 3.71286 20.3 3 19.4359 3H4.56517Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                </svg>
                                Filter ::
                            </p>
                            <div class="d-flex align-items-center flex-wrap ms-3">
                                <div class="dropdown me-3 fw-bolder">
                                    <span class="dropdown-toggle align-items-center d-flex" id="dropdownMenuButton04"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            class="me-2 icon-20">
                                            <path d="M3.09277 9.40421H20.9167" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M16.442 13.3097H16.4512" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M12.0045 13.3097H12.0137" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M7.55818 13.3097H7.56744" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M16.442 17.1962H16.4512" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M12.0045 17.1962H12.0137" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M7.55818 17.1962H7.56744" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M16.0433 2V5.29078" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M7.96515 2V5.29078" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16.2383 3.5791H7.77096C4.83427 3.5791 3 5.21504 3 8.22213V17.2718C3 20.3261 4.83427 21.9999 7.77096 21.9999H16.229C19.175 21.9999 21 20.3545 21 17.3474V8.22213C21.0092 5.21504 19.1842 3.5791 16.2383 3.5791Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>

                                        {{-- @if($filter == 'today')
                                        Today :
                                        @elseif ($filter == 'this_week')
                                        Week:
                                        @elseif ($filter == 'this_month')
                                        Month :
                                        @elseif ($filter == 'this_year')
                                        Year
                                        @elseif ($filter == 'previous_year')
                                        Previous Year
                                        @else
                                        Select
                                        @endif --}}
                                    </span>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton22"
                                        style="min-width:275px;">
                                        <li><a class="dropdown-item"
                                                href="{!! route('vendor.dashboard') . '?filter=this_week' !!}">This Week</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{!! route('vendor.dashboard') . '?filter=this_month' !!}">This
                                                Month</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{!! route('vendor.dashboard') . '?filter=this_year' !!}">This Year</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{!! route('vendor.dashboard') . '?filter=previous_year' !!}">Previous
                                                Year</a></li>
                                        <li><a class="dropdown-item"
                                                href="{!! route('vendor.dashboard') . '?filter=today' !!}">Today</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">
                                                <form action="">
                                                    <div
                                                        class="m-0 d-flex flex-column align-items-center justify-content-center">

                                                        <input type="text" name="date_range"
                                                            class="form-control range_flatpicker d-flex flatpickr-input active"
                                                            placeholder="Date Range" readonly="readonly" required>
                                                        <input type="hidden" name="filter" value="custom">
                                                        <button class="badge rounded-pill bg-success ms-1 mb-1 px-3 py-2"
                                                            type="submit">Go</button>
                                                    </div>
                                                </form>

                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h5 class="page-header-title">
                                {{__('Payout') . " " . __('Request')}}
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="banking-details-alert" class="alert alert-warning" style="display: none;">
                            <i class="fa fa-exclamation-triangle"></i>
                            <span id="banking-alert-message">Please add your banking details first.</span>
                            <a href="{{ route('vendor.banking.add-bank-details') }}" class="btn btn-sm btn-primary ms-2">
                                Add Banking Details
                            </a>
                        </div>

                        <form action="{{ route('vendor.banking.payment-request') }}" method="post" id="paymentForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" for="amount">
                                            Amount <span class="text-danger">*</span>
                                            <small class="text-muted">(Available: {{Helpers::format_currency($vendorWallet->balance)}})</small>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="amount" 
                                            id="amount" 
                                            class="form-control h--45px"
                                            placeholder="Enter Amount"
                                            min="1"
                                            max="{{ $vendorWallet->balance }}"
                                            step="0.01"
                                            required
                                        >
                                        <div class="invalid-feedback" id="amount-error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="input-label" for="payment_method">
                                            Payment Method <span class="text-danger">*</span>
                                        </label>
                                        <select name="payment_method" id="payment_method" class="form-control h--45px" required>
                                            <option value="">Select Payment Method</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="upi">UPI Payment</option>
                                        </select>
                                        <div class="invalid-feedback" id="payment_method-error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="input-label" for="banking_details_id">
                                            Banking Details <span class="text-danger">*</span>
                                        </label>
                                        <select name="banking_details_id" id="banking_details_id" class="form-control h--45px" required disabled>
                                            <option value="">Select Banking Details</option>
                                        </select>
                                        <div class="invalid-feedback" id="banking_details_id-error"></div>
                                        <small class="text-muted" id="banking-details-info"></small>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" for="payments_note">Request Note</label>
                                        <textarea 
                                            name="payments_note" 
                                            id="payments_note" 
                                            class="form-control" 
                                            rows="8" 
                                            placeholder="Add any additional notes for the payment request..."
                                            maxlength="500"
                                        ></textarea>
                                        <small class="text-muted">
                                            <span id="note-counter">0</span>/500 characters
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div id="payment-preview" class="alert alert-info" style="display: none;">
                                        <h6><i class="fa fa-info-circle"></i> Payment Request Summary</h6>
                                        <div id="preview-content"></div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <hr style="border: 1px solid #cecbcb;">
                                    <div class="text-end">
                                        <button type="reset" id="reset_btn" class="btn btn-danger">
                                            <i class="fa fa-refresh"></i> Reset
                                        </button>
                                        <button type="submit" id="submit_btn" class="btn btn-primary">
                                            <i class="fa fa-paper-plane"></i> Submit Request
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h5 class="page-header-title"> Payout Request <div class="list-group"></div>
                        </div>
                    </div>
                    <div class="card-body">

                        <!-- Table -->
                        <div class="table-responsive datatable-custom">
                            <table id="datatable"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                data-toggle="data-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="w-60px">{{ __('messages.sl') }}</th>
                                        <th class="w-90px">Amount</th>
                                        <th class="w-120px">Date</th>
                                        <th class="w-100px">Method</th>
                                        <th class="w-150px">Banking Details</th>
                                        <th class="w-100px">Status</th>
                                        <th class="w-100px">Txn ID</th>
                                        <th class="w-150px">Notes</th>
                                        <th class="w-100px">Remarks</th>
                                    </tr>
                                </thead>


                                <tbody id="set-rows">
                                    @foreach($paymentRequests as $key => $paymentRequest)
                                        <tr class="class-all">
                                            <td>{{$key + 1}}</td>
                                            <td>
                                                <strong>{{\App\CentralLogics\Helpers::format_currency($paymentRequest->amount)}}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{date('d M Y', strtotime($paymentRequest['created_at']))}}
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    {{date('h:i A', strtotime($paymentRequest['created_at']))}}
                                                </small>
                                            </td>
                                            <td>
                                                @if($paymentRequest->payment_method === 'bank_transfer')
                                                    <span class="badge badge-info">Bank Transfer</span>
                                                @elseif($paymentRequest->payment_method === 'upi')
                                                    <span class="badge badge-primary">UPI Payment</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ucfirst($paymentRequest->payment_method)}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($paymentRequest->bankingDetails)
                                                    @if($paymentRequest->payment_method === 'bank_transfer')
                                                        <small>
                                                            <strong>{{$paymentRequest->bankingDetails->bank_name}}</strong><br>
                                                            {{$paymentRequest->bankingDetails->account_holder_name}}<br>
                                                            <span class="text-muted">****{{substr($paymentRequest->bankingDetails->account_number, -4)}}</span>
                                                        </small>
                                                    @elseif($paymentRequest->payment_method === 'upi')
                                                        <small>
                                                            <strong>UPI:</strong><br>
                                                            {{$paymentRequest->bankingDetails->upi_id}}
                                                        </small>
                                                    @endif
                                                @else
                                                    <small class="text-muted">Banking details not found</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($paymentRequest->payment_status == 'complete' || $paymentRequest->payment_status == 'paid')
                                                    <span class="badge badge-success">
                                                        <i class="fa fa-check"></i> Paid
                                                    </span>
                                                @elseif($paymentRequest->payment_status == 'pending')
                                                    <span class="badge badge-warning">
                                                        <i class="fa fa-clock-o"></i> Pending
                                                    </span>
                                                @elseif($paymentRequest->payment_status == 'processing')
                                                    <span class="badge badge-info">
                                                        <i class="fa fa-spinner"></i> Processing
                                                    </span>
                                                @elseif($paymentRequest->payment_status == 'rejected' || $paymentRequest->payment_status == 'reject')
                                                    <span class="badge badge-danger">
                                                        <i class="fa fa-times"></i> Rejected
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        {{ucfirst($paymentRequest->payment_status)}}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($paymentRequest->txn_id)
                                                    <small class="font-monospace">{{$paymentRequest->txn_id}}</small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($paymentRequest->payments_note)
                                                    <small title="{{$paymentRequest->payments_note}}">
                                                        {{Str::limit($paymentRequest->payments_note, 50)}}
                                                    </small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($paymentRequest->remarks)
                                                    <small title="{{$paymentRequest->remarks}}">
                                                        {{Str::limit($paymentRequest->remarks, 30)}}
                                                    </small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($paymentRequests) === 0)
                            <div class="text-center">
                                <img src="{{asset('assets/images/icons/nodata.png')}}" alt="public">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('javascript')

    <script>
        let bankingDetailsData = [];
        
        // Load banking details on page load
        async function loadBankingDetails() {
            try {
                const response = await fetch('{{ route("vendor.banking.get-bank-details") }}');
                if (!response.ok) {
                    throw new Error('Failed to fetch banking details');
                }
                
                const result = await response.json();
                bankingDetailsData = Array.isArray(result) ? result : [result].filter(Boolean);
                
                if (bankingDetailsData.length === 0) {
                    document.getElementById('banking-details-alert').style.display = 'block';
                    document.getElementById('paymentForm').style.display = 'none';
                    return;
                }
                
                document.getElementById('banking-details-alert').style.display = 'none';
                document.getElementById('paymentForm').style.display = 'block';
                
            } catch (error) {
                console.error('Error loading banking details:', error);
                document.getElementById('banking-details-alert').style.display = 'block';
                document.getElementById('banking-alert-message').textContent = 'Error loading banking details. Please refresh the page.';
            }
        }

        // Filter banking details based on payment method
        function filterBankingDetails(paymentMethod) {
            const select = document.getElementById('banking_details_id');
            const infoElement = document.getElementById('banking-details-info');
            
            select.innerHTML = '<option value="">Select Banking Details</option>';
            
            const filteredDetails = bankingDetailsData.filter(detail => {
                if (paymentMethod === 'bank_transfer') {
                    return detail.account_number && detail.ifsc_code && detail.account_holder_name;
                } else if (paymentMethod === 'upi') {
                    return detail.upi_id;
                }
                return false;
            });
            
            if (filteredDetails.length === 0) {
                select.disabled = true;
                infoElement.textContent = paymentMethod === 'bank_transfer' 
                    ? 'No complete bank account details found. Please add bank details.'
                    : 'No UPI details found. Please add UPI details.';
                infoElement.className = 'text-danger';
                return;
            }
            
            select.disabled = false;
            infoElement.textContent = '';
            
            filteredDetails.forEach(detail => {
                const option = document.createElement('option');
                option.value = detail.id;
                
                if (paymentMethod === 'bank_transfer') {
                    option.textContent = `${detail.bank_name} - ${detail.account_holder_name} (****${detail.account_number.slice(-4)})`;
                } else {
                    option.textContent = `UPI: ${detail.upi_id}`;
                }
                
                select.appendChild(option);
            });
        }

        // Update payment preview
        function updatePaymentPreview() {
            const amount = document.getElementById('amount').value;
            const paymentMethod = document.getElementById('payment_method').value;
            const bankingDetailsId = document.getElementById('banking_details_id').value;
            const note = document.getElementById('payments_note').value;
            
            const previewDiv = document.getElementById('payment-preview');
            const previewContent = document.getElementById('preview-content');
            
            if (!amount || !paymentMethod || !bankingDetailsId) {
                previewDiv.style.display = 'none';
                return;
            }
            
            const selectedBankingDetail = bankingDetailsData.find(detail => detail.id == bankingDetailsId);
            if (!selectedBankingDetail) {
                previewDiv.style.display = 'none';
                return;
            }
            
            let bankingInfo = '';
            if (paymentMethod === 'bank_transfer') {
                bankingInfo = `
                    <strong>Bank:</strong> ${selectedBankingDetail.bank_name}<br>
                    <strong>Account Holder:</strong> ${selectedBankingDetail.account_holder_name}<br>
                    <strong>Account:</strong> ****${selectedBankingDetail.account_number.slice(-4)}<br>
                    <strong>IFSC:</strong> ${selectedBankingDetail.ifsc_code}
                `;
            } else {
                bankingInfo = `<strong>UPI ID:</strong> ${selectedBankingDetail.upi_id}`;
            }
            
            previewContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Amount:</strong> ₹${parseFloat(amount).toLocaleString('en-IN', {minimumFractionDigits: 2})}<br>
                        <strong>Method:</strong> ${paymentMethod === 'bank_transfer' ? 'Bank Transfer' : 'UPI Payment'}<br>
                        ${note ? `<strong>Note:</strong> ${note.substring(0, 100)}${note.length > 100 ? '...' : ''}` : ''}
                    </div>
                    <div class="col-md-6">
                        ${bankingInfo}
                    </div>
                </div>
            `;
            
            previewDiv.style.display = 'block';
        }

        // Character counter for notes
        document.getElementById('payments_note').addEventListener('input', function() {
            const counter = document.getElementById('note-counter');
            counter.textContent = this.value.length;
            updatePaymentPreview();
        });

        // Payment method change handler
        document.getElementById('payment_method').addEventListener('change', function() {
            filterBankingDetails(this.value);
            updatePaymentPreview();
        });

        // Banking details change handler
        document.getElementById('banking_details_id').addEventListener('change', updatePaymentPreview);
        
        // Amount change handler
        document.getElementById('amount').addEventListener('input', updatePaymentPreview);

        // Form validation
        function clearValidationErrors() {
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        }

        function showValidationError(fieldName, message) {
            const field = document.getElementById(fieldName);
            const errorDiv = document.getElementById(fieldName + '-error');
            
            if (field && errorDiv) {
                field.classList.add('is-invalid');
                errorDiv.textContent = message;
            }
        }

        // Form submission
        const paymentForm = document.querySelector('#paymentForm');
        paymentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearValidationErrors();
            
            const submitBtn = document.getElementById('submit_btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;

            try {
                const formData = new FormData(paymentForm);
                
                const response = await fetch(paymentForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    if (result.errors) {
                        // Laravel validation errors
                        Object.keys(result.errors).forEach(field => {
                            showValidationError(field, result.errors[field][0]);
                        });
                    } else {
                        throw new Error(result.message || 'An error occurred');
                    }
                    return;
                }

                // Success
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: result.message,
                    showConfirmButton: false,
                    timer: 3000
                });

                paymentForm.reset();
                document.getElementById('payment-preview').style.display = 'none';
                document.getElementById('note-counter').textContent = '0';
                
                // Refresh the page to show updated payment requests
                setTimeout(() => location.reload(), 2000);

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message
                });
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Reset form handler
        document.getElementById('reset_btn').addEventListener('click', function() {
            document.getElementById('payment-preview').style.display = 'none';
            document.getElementById('note-counter').textContent = '0';
            clearValidationErrors();
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadBankingDetails();
        });

        paymentRequests
    </script>

@endpush
