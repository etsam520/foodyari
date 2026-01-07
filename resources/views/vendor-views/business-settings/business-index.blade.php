@extends('vendor-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="maintainance-mode-toggle-bar d-flex flex-wrap justify-content-between rounded align-items-center">

                        <h5 class="text-capitalize m-0 text--primary fw-bolder">
                            <span>
                                Temporarily Pause
                            </span>
                        </h5>
                        <label class="switch m-0 form-check form-switch ">
                            <input type="checkbox" data-temp="off" {{$restaurant->temp_close == 1? 'checked':null}} class="form-check-input" class="status"
                            style="font-size: 28px;">
                        </label>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-12">
            <form action="{{ route('vendor.business-settings.update-setup') }}" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h5 class="page-header-title">
                            <i class="tio-fastfood"></i> General Settings </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($restaurant->pos_system)
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label
                                        class="form-check  d-flex justify-content-between border rounded px-3 form-control"
                                        for="delivery">
                                        <span class="pr-2"> Delivery: <span
                                                class="input-label-secondary">
                                            </span>
                                        </span>
                                        <input type="checkbox" name="delivery" class="form-check-input"
                                            id="delivery" >

                                    </label>
                                </div>
                            </div>
                            @endif
                            @if($restaurant->pos_system)
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label
                                        class="form-check  d-flex justify-content-between border rounded px-3 form-control"
                                        for="free_delivery">
                                        <span class="pr-2"> Free delivery: <span
                                                class="input-label-secondary">
                                            </span>
                                        </span>
                                        <input type="checkbox" name="free_delivery" class="form-check-input"
                                            id="free_delivery">
                                    </label>
                                </div>
                            </div>
                            @endif

                            @if($restaurant->pos_system)
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label
                                        class="form-check  d-flex justify-content-between border rounded px-3 form-control"
                                        for="take_away">
                                        <span class="pr-2 text-capitalize"> take away: <span
                                                class="input-label-secondary">
                                            </span>
                                        </span>
                                        <input type="checkbox" class="form-check-input"
                                            id="take_away" >

                                    </label>
                                </div>
                            </div>
                            @endif

                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label
                                        class="form-check  d-flex justify-content-between border rounded px-3 form-control"
                                        for="ready_to_handover">
                                        <span class="pr-2 text-capitalize"> Ready To Hand Over: <span
                                                class="input-label-secondary">
                                            </span>
                                        </span>
                                        <input type="checkbox" class="form-check-input" name="ready_to_handover" {{$restaurant->ready_to_handover?"checked":null}}
                                            id="ready_to_handover" >

                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                        {{-- <label for="">Veg || Non veg</label> --}}
                                        <select name="type" id="" class="form-control">
                                            <option value="V" {{$restaurant->type == App\CentralLogics\Helpers::getFoodType('V') ?'selected':null}}> Veg</option>
                                            <option value="N" {{$restaurant->type == App\CentralLogics\Helpers::getFoodType('N') ?'selected':null}}>Non Veg</option>
                                            <option value="B"{{$restaurant->type == App\CentralLogics\Helpers::getFoodType('B') ?'selected':null}}> Veg | Non veg</option>
                                        </select>
                                    </label>
                                </div>
                            </div>


                            <!--div class="col-lg-4 col-sm-6">
                                <div class="form-group m-0">
                                    <label
                                        class="form-check  d-flex justify-content-between border rounded px-3 form-control"
                                        for="order_subscription_active">
                                        <span class="pr-2 text-capitalize"> Order subscription: <span
                                                class="input-label-secondary">
                                            </span>
                                        </span>
                                        <input type="checkbox" class="form-check-input" id="order_subscription_active" >

                                    </label>
                                </div>
                            </div-->

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="page-header-title">
                            <span class="card-header-icon">
                                <i class="tio-tune"></i>
                            </span> Basic Settings </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6 col-12">
                                <div class="form-group mb-0">
                                    <label class="input-label text-capitalize" for="title">Minimum Order Amount</label>
                                    <input type="number" name="minimum_order" step="0.01" min="0" max="100000"
                                        class="form-control" placeholder="100" value="{{$restaurant->minimum_order??0}}">
                                </div>
                            </div>
                            {{-- <div class="col-sm-4 col-12">
                                <div class="form-group m-0">
                                    <label class="input-label text-capitalize" for="minimum_shipping_charge">Minimum
                                        delivery charge (₹)
                                    </label>
                                    <input type="number" id="minimum_shipping_charge" min="0" max="99999999.99"
                                        step="0.01" name="minimum_delivery_charge"
                                        class="form-control shipping_input" value="{{$restaurant->minimum_shipping_charge??0}}">
                                </div>
                            </div> --}}

                            {{-- <div class="col-sm-4 col-12">
                                <div class="form-group m-0">
                                    <label class="input-label text-capitalize" for="title">Delivery charge per
                                        KM (₹)</label>
                                    <input type="number" name="per_km_delivery_charge" step="0.01" min="0"
                                        max="100000" class="form-control" placeholder="100" value="{{$restaurant->per_km_shipping_charge??0}}">
                                </div>
                            </div> --}}
                            {{-- <div class="col-sm-4 col-12">
                                <div class="form-group m-0">
                                    <label class="input-label text-capitalize" for="title">Maximum delivery Charge
                                        <span class="input-label-secondary">

                                        </span>
                                    </label>
                                    <input type="number" name="maximum_shipping_charge" step="0.01" min="0"
                                        max="999999999" class="form-control" placeholder="10000" value="{{$restaurant->maximum_shipping_charge??0}}">
                                </div>
                            </div> --}}
                            <div class="col-sm-6 col-12">
                                <div class="form-group mb-0">
                                    <label
                                        class="form-check  d-flex flex-start p-0"
                                        for="gst_status">
                                        <span class="form-check-label">GST </span>
                                        <input type="checkbox" class="form-check-input mx-3" {{!empty($restaurant->tax != 0)?'checked':null}} name="gst_status"
                                            id="gst_status" value="1" >
                                    </label>
                                    <input type="number" id="gst" name="gst" class="form-control" value="{{$restaurant->tax}}">
                                </div>
                            </div>
                        </div>
                        <hr style="border: 1px solid #cecbcb;">
                        <div class="btn-container text-end">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <div class="col-12 mb-3" >
            <div class="card">
                <div class="card-header">
                    <h5 class="page-header-title">
                        <i class="tio-date-range"></i>
                        {{ __('Opening and closing schedules') }}
                    </h5>
                </div>
                <div class="card-body" id="schedule">
                    @include('vendor-views.business-settings.partials._schedule', $restaurant)
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal --}}
<div class="modal fade" id="timeSheduleModal" tabindex="-1" role="dialog" aria-labelledby="timeSheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('messages.Create Schedule For ')}}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="javascript:" method="post" id="add-schedule">
                    @csrf
                    <input type="hidden" name="day" id="day_name_input">
                    <input type="hidden" name="day_id" id="day_id_input">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">{{__('messages.Start time')}}:</label>
                        <input type="time" class="form-control" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">{{__('messages.End time')}}:</label>
                        <input type="time" class="form-control" name="end_time" required>
                    </div>
                    <div class="btn-container justify-content-end">
                        <button type="reset" class="btn btn-reset">{{__('messages.reset')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('messages.Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script src="{{asset('assets/js/plugins/spartan-multi-image-picker.min.js')}}"></script>
{{-- <script>

    function handleError(errorResponse) {
        console.log(errorResponse)
        if (errorResponse && errorResponse.errors) {
            if (Array.isArray(errorResponse.errors)) {
                const errorMessages = Object.values(errorResponse.errors);
                const errorList = errorMessages.map(item => `<li>${item.message}</li>`);
                return `<ul>${errorList.join('')}</ul>`;
            }
            if (typeof errorResponse.errors === 'string') {
                return errorResponse.errors;
            }
            if (typeof errorResponse.errors === 'object') {
                const errorMessages = Object.values(errorResponse.errors);
                const errorList = errorMessages.map(item => `<li>${item}</li>`);
                return `<ul>${errorList.join('')}</ul>`;
            }
        }
        return errorResponse.error;
    }
</script> --}}


<script>
    document.querySelector('[data-temp="off"]').addEventListener('change', async (event) => {
    let url = `{{ route('vendor.business-settings.temp-off') }}`;
    try {
        const resp = await fetch(url, {
            method: "POST",
            body: JSON.stringify({tempOff: event.target.checked}),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!resp.ok) {
            const error = await resp.json();
            throw new Error(error.message);
        }
        const result = await resp.json();
        toastr.success(result.message)
        // Handle success if necessary
    } catch (error) {
        console.error('Error:', error);
        toastr.error(error.message || 'An error occurred while updating the setting.');
    }
});

</script>


<script>
$(document).ready(function() {
    $('#timeSheduleModal').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var day_name = button.data('day');
        var modal = $(this);
        modal.find('.modal-title').text('{{__('messages.Create Schedule For ')}} ' + day_name);
        modal.find('.modal-body input[name=day]').val(day_name);
    })

    $('#add-schedule').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: '{{route('vendor.business-settings.add-schedule')}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                if (data.errors) {
                    for (var i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    $('#schedule').empty().html(data.view);
                    $('#timeSheduleModal').modal('hide');
                    toastr.success('{{__('messages.Schedule added successfully')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest.responseText, {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    });

});

function delete_schedule(route) {
    Swal.fire({
        title: '{{__('messages.are_you_sure')}}',
        text: '{{__('messages.You want to remove this schedule')}}',
        type: 'warning',
        showCancelButton: true,
        cancelButtonColor: 'default',
        confirmButtonColor: '#377dff',
        cancelButtonText: '{{__('messages.no')}}',
        confirmButtonText: '{{__('messages.yes')}}',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.get({
                url: route,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        console.log(data.view);
                        $('#schedule').empty().html(data.view);
                        toastr.success('{{__('messages.Schedule removed successfully')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error('{{__('messages.Schedule not found')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    })
};

</script>

@endpush
