@php
    $breakfast = App\Models\WeeklyMenu::where('mess_id', $messId)
        ->where('type', App\CentralLogics\Helpers::getFoodType(Str::upper($type)))
        ->where('day', App\CentralLogics\Helpers::getDayname($dayKey))
        ->where('service', App\CentralLogics\Helpers::getService('B'))
        ->first();
    $lunch = App\Models\WeeklyMenu::where('mess_id', $messId)
        ->where('type', App\CentralLogics\Helpers::getFoodType(Str::upper($type)))
        ->where('day', App\CentralLogics\Helpers::getDayname($dayKey))
        ->where('service', App\CentralLogics\Helpers::getService('L'))
        ->first();
    $dinner = App\Models\WeeklyMenu::where('mess_id', $messId)
        ->where('type', App\CentralLogics\Helpers::getFoodType(Str::upper($type)))
        ->where('day', App\CentralLogics\Helpers::getDayname($dayKey))
        ->where('service', App\CentralLogics\Helpers::getService('D'))
        ->first();
    // dd($breakfast);
@endphp

<div class="tabs-section">
    <div class="tab-buttons">
        <button class="tab-button d-lg-flex justify-content-center active" data-tab="tab1"><i class="fas fa-utensils px-2 text-center"></i>
            <div>Breakfast</div>
        </button>
        <button class="tab-button d-lg-flex justify-content-center" data-tab="tab2"><i class="fas fa-utensils px-2 text-center"></i>
            <div>Lunch</div>
        </button>
        <button class="tab-button d-lg-flex justify-content-center" data-tab="tab3"><i class="fas fa-utensils px-2 text-center"></i>
            <div>Dinner</div>
        </button>
    </div>
    <div class="tab-content" id="tab1" style="border-top: 1px solid #ccc;
    border-left: 1px solid rgb(204, 204, 204);
    border-right: 1px solid rgb(204, 204, 204);
    border-bottom: 1px solid rgb(204, 204, 204);">
        <div class="p-3 bg-white rounded rounded-bottom-0 w-100">
            @if ($breakfast)
                @if ($breakfast->description)
                    <p class="mb-2 text-black-50"><small>{{ $breakfast->description }}</small></p>
                @endif
                @php($b_items = App\CentralLogics\Helpers::splitStringToArray($breakfast->name))
                @foreach ($b_items as $item)
                    <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>{{ Str::ucfirst($item) }}
                    </div>
                @endforeach
            @else
                <div class="text-center">
                    <img src="{{ asset('assets/images/icons/nodata.png') }}" width="150px" alt="not Found">
                </div>
            @endif
        </div>
    </div>
    <div class="tab-content" id="tab2" style=" display: none; border-top: 1px solid #ccc;
    border-left: 1px solid rgb(204, 204, 204);
    border-right: 1px solid rgb(204, 204, 204);
    border-bottom: 1px solid rgb(204, 204, 204);">
        <div class="p-3 bg-white rounded rounded-bottom-0 w-100">
            @if ($lunch)
                @if ($lunch->description)
                    <p class="mb-2 text-black-50"><small>{{ $lunch->description }}</small></p>
                @endif
                @php($l_items = App\CentralLogics\Helpers::splitStringToArray($lunch->name))
                @foreach ($l_items as $item)
                    <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>{{ Str::ucfirst($item) }}
                    </div>
                @endforeach
            @else
                <div class="text-center">
                    <img src="{{ asset('assets/images/icons/nodata.png') }}" width="150px" alt="not Found">
                </div>
            @endif
        </div>
    </div>
    <div class="tab-content" id="tab3" style="display: none; border-top: 1px solid #ccc;
    border-left: 1px solid rgb(204, 204, 204);
    border-right: 1px solid rgb(204, 204, 204);
    border-bottom: 1px solid rgb(204, 204, 204);">
        <div class="p-3 bg-white rounded rounded-bottom-0 w-100">
            @if ($dinner)
                @if ($dinner->description)
                    <p class="mb-2 text-black-50"><small>{{ $dinner->description }}</small></p>
                @endif
                @php($d_items = App\CentralLogics\Helpers::splitStringToArray($dinner->name))
                @foreach ($d_items as $item)
                    <div><i class="fa-solid fa-caret-right me-2 text-warning"></i>{{ Str::ucfirst($item) }}
                    </div>
                @endforeach
            @else
                <div class="text-center">
                    <img src="{{ asset('assets/images/icons/nodata.png') }}" width="150px" alt="not Found">
                </div>
            @endif
        </div>
    </div>
</div>
