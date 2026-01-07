    @if ($name == 'restaurant')
        <div class="form-group">
            <label class="input-label" for="{{$name.'_choose'}}">{{__('Select Restaurant')}}</label>
            <select name="{{$name}}" id="{{$name.'_choose'}}" class="form-control select-2">
                <option disabled selected value="">---{{__('messages.select')}}---</option>
                @foreach($restaurants as $restaurant)
                <option value="{{$restaurant->id}}">{{Str::ucfirst($restaurant->name)}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="input-label" for="screen_to">{{__('Screen To')}}</label>
            <select name="screen_to" id="screen_to" class="form-control select-2">
                <option disabled  value="">---{{__('messages.select')}}---</option>
                <option value="inside_restaurant" selected>Inside Restaurant</option>
                <option value="outside_restaurant">Outside Restaurant</option>
            </select>
        </div>
    @elseif ($name == 'food')
        <div class="form-group">
            <label class="input-label" for="{{$name.'_choose'}}">{{__('Select Food')}}</label>
            <select name="{{$name}}" id="{{$name.'_choose'}}" class="form-control select-2">
                <option disabled selected value="">---{{__('messages.select')}}---</option>
                @foreach($foods as $food)
                <option value="{{$food->id}}">{{Str::ucfirst($food->name)}} ({{Str::ucfirst($food->restaurant->name)}})</option>
                @endforeach
            </select>
        </div>
    @elseif ($name == 'location')
        <div class="form-group">
            <label class="input-label" for="latitude">{{__('Enter Latitude')}}</label>
            <input name="latitude" id="latitude"  class="form-control" />
        </div>
        <div class="form-group">
            <label class="input-label" for="longitude">{{__('Enter Longitude')}}</label>
            <input name="longitude" id="longitude"  class="form-control" />
        </div>
        <div class="form-group">
            <label class="input-label" for="longitude">{{__('Enter Radius')}}</label>
            <input name="radius" id="radius" placeholder="In km."  class="form-control" />
        </div>
    @endif

        <div class="form-group">
            <label class="input-label" for="link">{{__('Enter Link')}}</label>
            <input name="link" id="link"  class="form-control" />
        </div>
