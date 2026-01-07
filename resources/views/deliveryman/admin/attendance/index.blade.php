

@extends('deliveryman.admin.layouts.main')

@section('content')
    <style>
        .img_border {
            height: 100px;
            width: 100px;
            border: 2px solid #ff810a;
        }
    </style>
    <div class="osahan-home-page my-4">
        <div class="res-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 m-auto">
                        <div class="card offer-section border border-none p-4 rounded-top-5">
                            <div class="d-flex mb-4">
                                <div>
                                    <img src="{{$dm['image'] != null ? asset('delivery-man/'.$dm['image']) : asset('assets/dm/images/shapes/blank-profile-picture-973460_640.webp')}}"
                                        class="rounded-circle me-3 border"  alt="Profile" width="60" height="60">
                                </div>
                                <div class="text-white">
                                    <h4 class="mb-1">Hi, {{ $dm['f_name'].' '.$dm['l_name'] }}</h4>
                                    {{-- <div class="">District, State</div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card shadow rounded-top-5 rounded-bottom-4 p-3" style="margin: -31px 10px 0px 10px;">
                            <div>
                                <h3 class="fw-bolder text-center mt-3">
                                    {{ \Carbon\Carbon::now()->format('h:i A') }}
                                </h3>
                                <div>
                                    <h5 class="text-center mt-2">
                                        {{ \Carbon\Carbon::now()->format('l, F j, Y') }}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#mark-attendance"
                                    class="btn mt-4 rounded-circle align-self-center d-flex justify-content-center align-items-center"
                                    style="width: 120px; height: 120px; position: relative; box-shadow: 0px 0px 10px lightblue; background: linear-gradient(to bottom, #364ad6, #ae116a);">
                                    <div>
                                        <img src="{{ asset('assets/images/clickme.png') }}" alt="Touch Screen Icon"
                                            style="width: 75px; height: 75px;">
                                        <div class="text-nowrap text-white fw-bolder fs-6">Click Me</div>
                                    </div>
                                </a>
                            </div>
                            <hr>

                            <div class="">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <a href="{{ route('deliveryman.dm-working-report') }}">
                                            <div class="text-center bg-body-secondary shadow-sm py-2 rounded-4">
                                                <img src="{{ asset('assets/images/working-hours.png') }}" alt=""
                                                    style="width: 30px; height: 30px;">
                                                <br><span class="text-muted"><small>Total Working Days</small></span>
                                                <div class="fw-bolder">{{Helpers::half_whole_day_display($working_days)}} Days</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 mt-2 mt-lg-0">
                                        <a href="{{ route('deliveryman.dm-distance-report') }}">
                                            <div class="text-center bg-body-secondary shadow-sm py-2 rounded-4">
                                                <img src="{{ asset('assets/images/distance.png') }}" alt=""
                                                    style="width: 30px; height: 30px;">
                                                <br><span class="text-muted"><small>Total Distance</small></span>
                                                <div class="fw-bolder">{{$total_distance}} KM</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 mt-2 mt-lg-0">
                                        <a href="{{ route('deliveryman.dm-fuel-report') }}">
                                            <div class="text-center bg-body-secondary shadow-sm py-2 rounded-4">
                                                <img src="{{ asset('assets/images/fuel-credit.png') }}" alt=""
                                                    style="width: 30px; height: 30px;">
                                                <br><span class="text-muted"><small>Fuel Credit</small></span>
                                                <div class="fw-bolder"> {{Helpers::format_currency($fuel_balance)}} </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    @if($attendance == null || ($attendance->check_in == null || $attendance->check_out == null))
    <div class="modal fade" id="mark-attendance" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bolder" id="exampleModalLongTitle">
                        @if($attendance != null && $attendance->check_in_meter != null) Check Out @else Check In @endif
                    </h5>
                    <button type="button" class="close border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                {{-- @dd($attendance) --}}
                @php($meter_url = ($attendance != null && $attendance->check_in_meter != null) ? route('deliveryman.attendance.meter-check-out') : route('deliveryman.attendance.meter-check-in'))
                <form action="{{ $meter_url }}" method="post" id="mark-attendance-form" enctype="multipart/form-data" >
                    @csrf
                    <div class="new-user-info">
                            <div class="row">
                                <div class="col-md-12 mx-auto">
                                    <div class="col-lg-12 d-flex justify-content-center align-items-center mb-5">
                                        <label for="profileimage">
                                            <img src="https://media.istockphoto.com/id/1226328537/vector/image-place-holder-with-a-gray-camera-icon.jpg?s=612x612&w=0&k=20&c=qRydgCNlE44OUSSoz5XadsH7WCkU59-l-dwrvZzhXsI="
                                                class="img_border" id="profile-pic-preview" role="button">
                                            <span for="profileimage"
                                                class="d-flex btn btn-primary btn-sm btn-block justify-content-center">Upload
                                                Image</span>
                                            <input type="file" name="meter_image" id="profileimage" accept="image/*"
                                                {{-- capture="user" --}}
                                                class="form-control form-control-sm sr-only" required>
                                        </label>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label mx-2 text-muted fw-bold" for="meter-reading">Meter
                                            Reading</label>
                                        <input type="number" class="form-control " name="meter_reading" value=""
                                            id="meter-reading" placeholder="Meter Reading" required>
                                    </div>
                                    <div class="mt-3 mb-3">
                                        <label class="form-label mx-2 text-muted fw-bold" for="note">Note</label>
                                        <textarea class="form-control" name="note" id="note"
                                            placeholder="Write here"></textarea>
                                    </div>
                                    <div>
                                        <input class="form-check-input" name="reason" type="radio" value="Health Issues"
                                            id="health-issue" style="font-size: 20px;border:1px solid #ff810a;">
                                        <label class="form-check-label ms-3 fx-6" for="health-issue">
                                            Health Issues
                                        </label>
                                    </div>
                                    <div>
                                        <input class="form-check-input" name="reason" type="radio"
                                            value="Personal or Family Emergency" id="personal-reason"
                                            style="font-size: 20px;border:1px solid #ff810a;">
                                        <label class="form-check-label ms-3 fx-6" for="personal-reason">
                                            Personal or Family Emergency
                                        </label>
                                    </div>
                                    <div>
                                        <input class="form-check-input" name="reason" type="radio" value="Vehicle Issues"
                                            id="vehicle-issue" style="font-size: 20px;border:1px solid #ff810a;">
                                        <label class="form-check-label ms-3 fx-6" for="vehicle-issue">
                                            Vehicle Issues
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary rounded-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('javascript')
<script>
    document.getElementById('profileimage').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profile-pic-preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
