<?php

$data = [];
foreach ($restaurant->schedules as $schedule) {
    $data[$schedule->day][] = ['id' => $schedule->id, 'start_time' => $schedule->opening_time, 'end_time' => $schedule->closing_time];
}
?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <span class="btn">{{__('messages.monday')}} :</span>
         <span class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#timeSheduleModal"
                    data-dayid="6" data-day="{{App\CentralLogics\Helpers::getDayname('Mon')}}"><i
                        class="fa-solid fa-plus"></i></span>
        <div class="schedult-date-content">
                   @if(!empty($data['monday']))
                @foreach($data['monday'] as $s)
                    <span class="d-inline-flex align-items-center mb-1">
                        <div class="fs-6 d-flex flex-column badge bg-soft-success p-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Opening Time') }}</span>
                            </div>
                            <div class="info">
                                {{date("h:i A", strtotime($s['start_time']))}}
                            </div>
                        </div>
                        <div class="fs-6 d-flex flex-column badge bg-soft-warning p-2 mx-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Closing Time') }}</span>
                            </div>
                            <div class="info">
                                {{date("h:i A", strtotime($s['end_time']))}}
                            </div>
                        </div>

                        <span type="button"
                            onclick="delete_schedule('{{route('vendor.business-settings.remove-schedule', $s['id'])}}')"><i
                                class="fa-solid fa-trash text-danger "></i></span>
                    </span>
                @endforeach
            @else
                <span class="btn btn-sm btn-outline-danger m-1 disabled">{{__('messages.Offday')}}</span>
            @endif
            {{-- @if(isset($data['monday']))
                <span class="d-inline-flex align-items-center">
                    <div class="fs-6 d-flex flex-column badge bg-soft-success p-2">
                        <div class="info mb-2">
                            <i class="fa-regular fa-clock"></i>
                            <span>{{ __('Opening Time') }}</span>
                        </div>
                        <div class="info">
                            {{date("h:i A", strtotime($data['monday']['start_time']))}}
                        </div>
                    </div>
                    <div class="fs-6 d-flex flex-column badge bg-soft-warning p-2 mx-2">
                        <div class="info mb-2">
                            <i class="fa-regular fa-clock"></i>
                            <span>{{ __('Closing Time') }}</span>
                        </div>
                        <div class="info">
                            {{date("h:i A", strtotime($data['monday']['end_time']))}}
                        </div>
                    </div>

                    <span type="button"
                        onclick="delete_schedule('{{route('vendor.business-settings.remove-schedule', $data['monday']['id'])}}')"><i
                            class="fa-solid fa-trash text-danger "></i></span>
                </span>
            @else
                <span class="btn btn-sm btn-outline-danger m-1 disabled">{{__('messages.Offday')}}</span>
                <span class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#timeSheduleModal"
                    data-dayid="6" data-day="{{App\CentralLogics\Helpers::getDayname('Mon')}}"><i
                        class="fa-solid fa-plus"></i></span>
            @endif --}}
        </div>
    </div>

      <div class="col-md-4">
        <span class="btn">{{__('messages.tuesday')}} :</span>
         <span class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#timeSheduleModal"
                  data-dayid="6" data-day="{{ App\CentralLogics\Helpers::getDayname('Tue') }}"><i
                  class="fa-solid fa-plus"></i></span>
        <div class="schedult-date-content">
            @if(!empty($data['tuesday']))
                @foreach($data['tuesday'] as $s)
                    <span class="d-inline-flex align-items-center mb-1">
                        <div class="fs-6 d-flex flex-column badge bg-soft-success p-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Opening Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['start_time'])) }}
                            </div>
                        </div>
                        <div class="fs-6 d-flex flex-column badge bg-soft-warning p-2 mx-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Closing Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['end_time'])) }}
                            </div>
                        </div>

                        <span type="button"
                            onclick="delete_schedule('{{ route('vendor.business-settings.remove-schedule', $s['id']) }}')"><i
                                class="fa-solid fa-trash text-danger "></i></span>
                    </span>
                @endforeach
            @else
                <span class="btn btn-sm btn-outline-danger m-1 disabled">{{__('messages.Offday')}}</span>
            @endif
           
        </div>
    </div>

    <div class="col-md-4">
        <span class="btn">{{__('messages.wednesday')}} :</span>
         <span class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#timeSheduleModal"
                  data-dayid="6" data-day="{{ App\CentralLogics\Helpers::getDayname('Wed') }}"><i
                  class="fa-solid fa-plus"></i></span>
        <div class="schedult-date-content">
            @if(!empty($data['wednesday']))
                @foreach($data['wednesday'] as $s)
                    <span class="d-inline-flex align-items-center mb-1">
                        <div class="fs-6 d-flex flex-column badge bg-soft-success p-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Opening Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['start_time'])) }}
                            </div>
                        </div>
                        <div class="fs-6 d-flex flex-column badge bg-soft-warning p-2 mx-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Closing Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['end_time'])) }}
                            </div>
                        </div>

                        <span type="button"
                            onclick="delete_schedule('{{ route('vendor.business-settings.remove-schedule', $s['id']) }}')"><i
                                class="fa-solid fa-trash text-danger "></i></span>
                    </span>
                @endforeach
            @else
                <span class="btn btn-sm btn-outline-danger m-1 disabled">{{__('messages.Offday')}}</span>
            @endif
           
        </div>
    </div>

    <div class="col-md-4">
        <span class="btn">{{__('messages.thursday')}} :</span>
         <span class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#timeSheduleModal"
                  data-dayid="6" data-day="{{ App\CentralLogics\Helpers::getDayname('Thu') }}"><i
                  class="fa-solid fa-plus"></i></span>
        <div class="schedult-date-content">
            @if(!empty($data['thursday']))
                @foreach($data['thursday'] as $s)
                    <span class="d-inline-flex align-items-center mb-1">
                        <div class="fs-6 d-flex flex-column badge bg-soft-success p-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Opening Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['start_time'])) }}
                            </div>
                        </div>
                        <div class="fs-6 d-flex flex-column badge bg-soft-warning p-2 mx-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Closing Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['end_time'])) }}
                            </div>
                        </div>

                        <span type="button"
                            onclick="delete_schedule('{{ route('vendor.business-settings.remove-schedule', $s['id']) }}')"><i
                                class="fa-solid fa-trash text-danger "></i></span>
                    </span>
                @endforeach
            @else
                <span class="btn btn-sm btn-outline-danger m-1 disabled">{{__('messages.Offday')}}</span>
            @endif
           
        </div>
    </div>

    <div class="col-md-4">
        <span class="btn">{{__('messages.friday')}} :</span>
         <span class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#timeSheduleModal"
                  data-dayid="6" data-day="{{ App\CentralLogics\Helpers::getDayname('Fri') }}"><i
                  class="fa-solid fa-plus"></i></span>
        <div class="schedult-date-content">
            @if(!empty($data['friday']))
                @foreach($data['friday'] as $s)
                    <span class="d-inline-flex align-items-center mb-1">
                        <div class="fs-6 d-flex flex-column badge bg-soft-success p-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Opening Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['start_time'])) }}
                            </div>
                        </div>
                        <div class="fs-6 d-flex flex-column badge bg-soft-warning p-2 mx-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Closing Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['end_time'])) }}
                            </div>
                        </div>

                        <span type="button"
                            onclick="delete_schedule('{{ route('vendor.business-settings.remove-schedule', $s['id']) }}')"><i
                                class="fa-solid fa-trash text-danger "></i></span>
                    </span>
                @endforeach
            @else
                <span class="btn btn-sm btn-outline-danger m-1 disabled">{{__('messages.Offday')}}</span>
            @endif
           
        </div>
    </div>

    <div class="col-md-4">
        <span class="btn">{{__('messages.saturday')}} :</span>
         <span class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#timeSheduleModal"
                  data-dayid="6" data-day="{{ App\CentralLogics\Helpers::getDayname('Sat') }}"><i
                  class="fa-solid fa-plus"></i></span>
        <div class="schedult-date-content">
            @if(!empty($data['saturday']))
                @foreach($data['saturday'] as $s)
                    <span class="d-inline-flex align-items-center mb-1">
                        <div class="fs-6 d-flex flex-column badge bg-soft-success p-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Opening Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['start_time'])) }}
                            </div>
                        </div>
                        <div class="fs-6 d-flex flex-column badge bg-soft-warning p-2 mx-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Closing Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['end_time'])) }}
                            </div>
                        </div>

                        <span type="button"
                            onclick="delete_schedule('{{ route('vendor.business-settings.remove-schedule', $s['id']) }}')"><i
                                class="fa-solid fa-trash text-danger "></i></span>
                    </span>
                @endforeach
            @else
                <span class="btn btn-sm btn-outline-danger m-1 disabled">{{__('messages.Offday')}}</span>
            @endif
           
        </div>
    </div>

    <div class="col-md-4">
        <span class="btn">{{__('messages.sunday')}} :</span>
         <span class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#timeSheduleModal"
                  data-dayid="6" data-day="{{ App\CentralLogics\Helpers::getDayname('Sun') }}"><i
                  class="fa-solid fa-plus"></i></span>
        <div class="schedult-date-content">
            @if(!empty($data['sunday']))
                @foreach($data['sunday'] as $s)
                    <span class="d-inline-flex align-items-center mb-1">
                        <div class="fs-6 d-flex flex-column badge bg-soft-success p-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Opening Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['start_time'])) }}
                            </div>
                        </div>
                        <div class="fs-6 d-flex flex-column badge bg-soft-warning p-2 mx-2">
                            <div class="info mb-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ __('Closing Time') }}</span>
                            </div>
                            <div class="info">
                                {{ date("h:i A", strtotime($s['end_time'])) }}
                            </div>
                        </div>

                        <span type="button"
                            onclick="delete_schedule('{{ route('vendor.business-settings.remove-schedule', $s['id']) }}')"><i
                                class="fa-solid fa-trash text-danger "></i></span>
                    </span>
                @endforeach
            @else
                <span class="btn btn-sm btn-outline-danger m-1 disabled">{{__('messages.Offday')}}</span>
            @endif
           
        </div>
    </div>
    
</div>
