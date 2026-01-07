
<ul class="list-group list-group-flush mb-3">
    
    <li class="list-group-item  ">
        <dl class="row">
            <dd class="col-4 fs-6">{{ __('Name') }}</dd>
            <dd class="col-8">{{Str::ucfirst($customer->f_name).' '.Str::ucfirst($customer->l_name) }}</dd>

            <dd class="col-4">{{ __('contact') }}</dd>
            <dd class="col-8">{{ $customer->phone }}</dd>

             {{-- @php($customerAddress = $customer->customerAddress->first()) --}}
            <dd class="col-4"><i class="fa fa-map-marker-alt text-danger position-absolute" style="left: 0px;margin-top: 4px;"></i> {{ __('Location') }}</dd>
            <dd class="col-8">{{ Str::ucfirst( "Set Delivery Address") }}</dd>
        </dl>
    </li>
    
</ul>


