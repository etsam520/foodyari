@php
    $today = Carbon\Carbon::now()->toDateString();
    $status = App\Models\DeliverymanAttendance::whereDate('created_at', $today)->where('deliveryman_id', auth('delivery_men')->user()->id)->latest()->limit(1)->first();
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="d-flex w-100 bg-white justify-content-between">
            <h6 class="p-3 m-0 mx-2 text-wrap">Welcome, Deliveryman Name</h6>
            <div class="d-lg-none d-block">
                <div class="form-check form-switch d-flex justify-content-end align-self-center p-3 pb-0 fs-6">
                    <input class="form-check-input" data-active="offline" type="checkbox" role="switch" id="flexSwitchCheckChecked1"
                        {{$status? $status->is_online == 1? 'checked': null : null}}>
                    <label class="form-check-label" for="flexSwitchCheckChecked1">Online/Offline</label>
                </div>
            </div>
            <div class="d-lg-block d-none">
                <div class="form-check form-switch align-self-center p-3 fs-6">
                    <input class="form-check-input" data-active="offline" type="checkbox" role="switch" id="flexSwitchCheckChecked2"
                    {{$status? $status->is_online == 1? 'checked': null : null}}>
                    <label class="form-check-label"  for="flexSwitchCheckChecked2">Online/Offline</label>
                </div>
            </div>
        </div>
    </div>
</div>

