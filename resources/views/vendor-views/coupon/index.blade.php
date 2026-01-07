@extends('vendor-views.layouts.dashboard-main')
@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@section('content')
@php($restaurant_data = \App\CentralLogics\Helpers::get_restaurant_data())
    <div class="container-fluid content-inner">
        <!-- Page Header -->
        <!-- End Page Header -->
        <div class="card mb-3">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <h5 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{__('messages.add')}} {{__('messages.new')}} {{__('messages.coupon')}}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('vendor.coupon.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.title')}}</label>
                                <input id="coupon_title" type="text" name="title" class="form-control" placeholder="{{__('messages.Enter Coupon Name')}}" required maxlength="191">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.coupon')}} {{__('messages.type')}}</label>
                                <select id="coupon_type" name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                    <option value="default">{{__('messages.default')}}</option>
                                    {{-- @if (($restaurant_data->restaurant_model == 'commission' && $restaurant_data->self_delivery_system == 1) ||($restaurant_data->restaurant_model == 'subscription' &&
                                        isset($restaurant_data->restaurant_sub) && $restaurant_data->restaurant_sub->self_delivery == 1))
                                    <option value="free_delivery">{{__('messages.free_delivery')}}</option>
                                    @endif --}}
                            </select>
                            </div>
                        </div>


                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.code')}}</label>
                                <input id="coupon_code" type="text" name="code" class="form-control"
                                    placeholder="{{__('messages.Enter Code')}}" required maxlength="100">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.limit')}} {{__('messages.for')}} {{__('messages.same')}} {{__('messages.user')}}</label>
                                <input type="number" name="limit" id="coupon_limit" class="form-control" placeholder="{{ __('messages.Enter Limit Count') }}" max="100000">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.start')}} {{__('messages.date')}}</label>
                                <input type="date" name="start_date" class="form-control" id="date_from" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.expire')}} {{__('messages.date')}}</label>
                                <input type="date" name="expire_date" class="form-control" id="date_to" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.discount')}} {{__('messages.type')}}</label>
                                <select name="discount_type" class="form-control" id="discount_type">
                                    <option value="amount">
                                            {{ __('messages.amount').' ('.\App\CentralLogics\Helpers::currency_symbol().')'  }}
                                    </option>
                                    <option value="percent"> {{ __('messages.percent').' (%)' }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.discount')}} </label>
                                <input type="number" step="0.01" min="1" max="999999999999.99" placeholder="{{ __('messages.Enter Discount') }}" name="discount" id="discount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="max_discount">{{__('messages.max')}} {{__('messages.discount')}}</label>
                                <input type="number" step="0.01" min="0" value="0" max="999999999999.99" name="max_discount" id="max_discount" class="form-control" >
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.min')}} {{__('messages.purchase')}}</label>
                                <input id="min_purchase" type="number" step="0.01" name="min_purchase" value="0" min="0" max="999999999999.99" class="form-control"
                                    placeholder="100">
                            </div>
                        </div>
                    </div>
                    <hr style="border: 1px solid #cecbcb;">
                    <div class="btn--container d-flex justify-content-end">
                        <button id="reset_btn" type="button" class="btn btn-danger me-2">{{__('messages.reset')}}</button>
                        <button type="submit" class="btn btn-primary ">{{__('messages.submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">

                <div class="search--button-wrapper">
                    <h5 class="page-header-title">{{__('messages.coupon')}} {{__('messages.list')}}<span class="badge bg-primary ms-2 py-1" id="itemCount">{{ count($coupons)}}</span></h5>
                </div>
            </div>
            <div class="card-body">

            <!-- Table -->
            <div class="table-responsive datatable-custom" id="table-div">
                <table id="example-data-table"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>{{ __('messages.sl') }}</th>
                        <th>{{__('messages.title')}}</th>
                        <th>{{__('messages.code')}}</th>
                        <th>{{__('messages.type')}}</th>
                        <th>{{__('messages.total_uses')}}</th>
                        <th>{{__('messages.min')}} {{__('messages.purchase')}}</th>
                        <th>{{__('messages.max')}} {{__('messages.discount')}}</th>
                        <th>
                            <div class="text-center">
                                {{__('messages.discount')}}
                            </div>
                        </th>
                        <th>{{__('messages.discount')}} {{__('messages.type')}}</th>
                        <th>{{__('messages.start')}} {{__('messages.date')}}</th>
                        <th>{{__('messages.expire')}} {{__('messages.date')}}</th>
                        {{-- <th>{{__('messages.created_by')}}</th> --}}
                        {{-- <th>{{__('messages.Customer_type')}}</th> --}}
                        <th>{{__('messages.status')}}</th>
                        <th class="text-center">{{__('messages.action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($coupons as $key=>$coupon)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                            <span class="d-block font-size-sm text-body">
                                {{Str::limit($coupon['title'],15,'...')}}
                            </span>
                            </td>
                            <td>{{$coupon['code']}}</td>
                            <td>{{__('messages.'.$coupon->coupon_type)}}</td>
                            <td>{{$coupon->total_uses}}</td>
                            <td>
                                <div class="text-right mw-87px">
                                    {{\App\CentralLogics\Helpers::format_currency($coupon['min_purchase'])}}
                                </div>
                            </td>
                            <td>
                                <div class="text-right mw-87px">
                                    {{\App\CentralLogics\Helpers::format_currency($coupon['max_discount'])}}
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    {{$coupon['discount']}}
                                </div>
                            </td>
                            @if ($coupon['discount_type'] == 'percent')
                            <td>{{ __('messages.percent')}}</td>
                            @elseif ($coupon['discount_type'] == 'amount')
                            <td>{{ __('messages.amount')}}</td>
                            @else
                            <td>{{$coupon['discount_type']}}</td>
                            @endif

                            <td>{{$coupon['start_date']}}</td>
                            <td>{{$coupon['expire_date']}}</td>


                            <td>
                                <label class="form-check form-switch" for="couponCheckbox{{$coupon->id}}">
                                    <input type="checkbox" onclick="location.href='{{route('vendor.coupon.status',[$coupon['id'],$coupon->status?0:1])}}'" class="form-check-input form-control fs-4" id="couponCheckbox{{$coupon->id}}" {{$coupon->status?'checked':''}}>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn btn-sm btn-outline-primary action-btn" href="{{route('vendor.coupon.update',[$coupon['id']])}}"title="{{__('messages.edit')}} {{__('messages.coupon')}}">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                         </svg>
                                    </a>
                                    <a class="btn btn-sm btn-outline-danger action-btn"  href="{{route('vendor.coupon.delete',[$coupon['id']])}}" onclick="form_alert(this,'{{__('Want to delete this Coupon')}}')" title="{{__('messages.delete')}} {{__('messages.coupon')}}">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                            <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{-- <div class="page-area px-4 pb-3">
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                            {!! $coupons->links() !!}
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        </div>
        <!-- End Table -->
    </div>

@endsection

@push('javascript')
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    function form_alert(item, message) {
        event.preventDefault();  // Prevent the default anchor action (e.g., navigating to href)

        Swal.fire({
            title: '{{ __('messages.Are you sure ?') }}',
            text: message,
            icon: 'warning',  // Correct SweetAlert syntax
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ __('messages.No') }}',
            confirmButtonText: '{{ __('messages.Yes') }}',
            reverseButtons: true
        }).then((result) => {
    if (result.isConfirmed) {
        // Create a dynamic form
        const form = document.createElement('form');
        form.method = 'POST'; // Use POST for compatibility
        form.action = item.getAttribute('href'); // Set the URL from the href attribute

        // Add a hidden _method input to simulate DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        // Add a CSRF token if required (for Laravel or other frameworks)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }

        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    }
});
    }
</script>
<script>
    $.extend(true, $.fn.DataTable.defaults, {
           responsive: true
       });

       $(document).ready(function() {
           $('#example-data-table').DataTable({
               responsive: {
                   details: true // Enables row details view when columns collapse
               },
               columnDefs: [
                   { responsivePriority: 1, targets: 0 }, // Name (Always visible)
                   { responsivePriority: 2, targets: 1 }, // Office (Higher priority)
                   { responsivePriority: 3, targets: 11}, // Position
                   { responsivePriority: 4, targets: 12}, // Position
               ]
           });
       });
</script>
@endpush
