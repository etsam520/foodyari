<div class="mt-1">
    <div class=" w-100 d-flex flex-wrap justify-content-between rounded-0 d-menu-show" role="group"
        aria-label="Basic radio toggle button group">
        @php($days = App\CentralLogics\Helpers::getDayname())
        @foreach ($days as $key => $value )
            
        <input type="radio" class="btn-check flex-fill" data-type="{{$type}}" data-mess-id="{{$messId}}" value="{{$key}}" name="btndays" id="btndays{{$loop->index+1}}">
        <label class="btn btn-veg-outline btndays flex-fill rounded-0" for="btndays{{$loop->index+1}}">{{Str::upper($value)}}</label>
        @endforeach
        {{-- <input type="radio" class="btn-check" name="btndays" id="btndays2">
        <label class="btn btn-veg-outline btndays" for="btndays2">Tuesday</label>
        <input type="radio" class="btn-check" name="btndays" id="btndays3">
        <label class="btn btn-veg-outline btndays" for="btndays3">Wednesday</label>
        <input type="radio" class="btn-check" name="btndays" id="btndays4">
        <label class="btn btn-veg-outline btndays" for="btndays4">Thursday</label>
        <input type="radio" class="btn-check" name="btndays" id="btndays5">
        <label class="btn btn-veg-outline btndays" for="btndays">Friday</label>
        <input type="radio" class="btn-check" name="btndays" id="btndays6">
        <label class="btn btn-veg-outline btndays" for="btndays6">Saturday</label>
        <input type="radio" class="btn-check" name="btndays" id="btndays7">
        <label class="btn btn-veg-outline btndays" for="btndays7">Sunday</label> --}}
    </div>
</div>
<div id="insert-menu"></div>