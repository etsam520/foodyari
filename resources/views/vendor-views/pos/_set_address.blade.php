<?php
if (session()->has('address')) {
    $old = session()->get('address');
} else {
    $old = null;
}
?>
<form id='delivery_address_store'>
    @csrf

    <div class="row g-2" id="delivery_address">
        <div class="col-md-6">
            <label class="input-label" for="">{{ __('messages.contact_person_name') }}<span class="input-label-secondary text-danger">*</span></label>
            <input type="text" class="form-control" name="contact_person_name" value="{{ $old ? $old['contact_person_name'] : '' }}" placeholder="{{ __('messages.Ex :') }} Jhone">
        </div>
        <div class="col-md-6">
            <label class="input-label" for="">{{ __('Contact Number') }}<span class="input-label-secondary text-danger">*</span></label>
            <input type="tel" class="form-control" name="contact_person_number" value="{{ $old ? $old['contact_person_number'] : '' }}" placeholder="{{ __('messages.Ex :') }} +3264124565">
        </div>
        <div class="col-md-4">
            <label class="input-label" for="">{{ __('messages.Road') }}<span class="input-label-secondary text-danger">*</span></label>
            <input type="text" class="form-control" name="road" value="{{ $old ? $old['road'] : '' }}" placeholder="{{ __('messages.Ex :') }} 4th">
        </div>
        <div class="col-md-4">
            <label class="input-label" for="">{{ __('messages.House') }}<span class="input-label-secondary text-danger">*</span></label>
            <input type="text" class="form-control" name="house" value="{{ $old ? $old['house'] : '' }}" placeholder="{{ __('messages.Ex :') }} 45/C">
        </div>
        <div class="col-md-4">
            <label class="input-label" for="">{{ __('messages.Floor') }}<span class="input-label-secondary text-danger">*</span></label>
            <input type="text" class="form-control" name="floor" value="{{ $old ? $old['floor'] : '' }}" placeholder="{{ __('messages.Ex :') }} 1A">
        </div>
        <div class="col-md-6">
            <label class="input-label" for="">{{ __('messages.longitude') }}<span class="input-label-secondary text-danger">*</span></label>
            <input type="text" class="form-control" id="longitude" name="longitude" value="{{ $old ? $old['longitude'] : '' }}" readonly>
        </div>
        <div class="col-md-6">
            <label class="input-label" for="">{{ __('messages.latitude') }}<span class="input-label-secondary text-danger">*</span></label>
            <input type="text" class="form-control" id="latitude" name="latitude" value="{{ $old ? $old['latitude'] : '' }}" readonly>
        </div>
        <div class="col-md-12">
            <label class="input-label" for="">{{ __('messages.address') }}</label>
            <textarea name="address" class="form-control" cols="30" rows="3" placeholder="{{ __('messages.Ex :') }} address">{{ $old ? $old['address'] : '' }}</textarea>
        </div>
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <span class="text-primary">
                    {{ __('* pin the address in the map to calculate delivery fee') }}
                </span>
                <div>
                    <input type="hidden" name="distance" id="distance">
                    <span>{{ __('Delivery fee') }} :</span>
                    <input type="hidden" name="delivery_fee" id="delivery_fee" value="{{ $old ? $old['delivery_fee'] : '' }}">
                    <strong>{{ $old ? $old['delivery_fee'] : 0 }} {{ \App\CentralLogics\Helpers::currency_symbol() }}</strong>
                </div>
            </div>
            <input id="pac-input" class="controls rounded initial-8" title="{{ __('messages.search_your_location_here') }}" type="text" placeholder="{{ __('messages.search_here') }}" />
            <div class="mb-2 h-200px" id="map"></div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="btn--container justify-content-end">
            <button class="btn btn-sm btn--primary w-100" type="button" onclick="deliveryAdressStore()">
                {{ __('Update') }} {{ __('messages.Delivery address') }}
            </button>
        </div>
    </div>
</form>