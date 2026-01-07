@if (session()->has('address'))

@php
    $address = session()->get('address')
@endphp
<ul class="list-group list-group-flush mb-3">
    
    <li class="list-group-item  ">
        <dl class="row">
            <dd class="col-4 fs-6">{{ __('Name') }}</dd>
            <dd class="col-8">{{Str::ucfirst($address['contact_person_name'])}}</dd>

            <dd class="col-4">{{ __('contact') }}</dd>
            <dd class="col-8">{{ $address['contact_person_number'] }}</dd>
            <dd class="col-4">{{ __('Distance') }}</dd>
            <dd class="col-8">{{ App\CentralLogics\Helpers::formatDistance($address['distance']) }}</dd>
            @php($userAddress =$address['stringAddress'])
            <dd class="col-4"><i class="fa fa-map-marker-alt text-danger position-absolute" style="left: 0px;margin-top: 4px;"></i> {{ __('Location') }}</dd>
            <dd class="col-8">{{ Str::ucfirst( $userAddress?? "No Address Available") }}</dd>
        </dl>
    </li>
    
</ul>

@endif
