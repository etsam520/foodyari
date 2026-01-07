{{-- <ul class="list-inline m-0 p-0">
    @foreach ($orders as $order)
    @php($deliveryAddress = json_decode($order->delivery_address))
    <li class="d-flex mb-4 align-items-center border-bottom shadow p-2 rounded " onclick="location.href='{{route('admin.order.details',$order->id)}}'">
        <div class="img-fluid "><img src="{{Helpers::getUploadFile('default-food.png','product')}}"
                alt="story-img" class="avatar-80"></div>
        <div class="ms-3 flex-grow-1">
            <h6>#{{$order->id}}</h6>
            <p class="mb-0">
            @if (isset($deliveryAddress->contact_person_name))
                {{ $deliveryAddress->contact_person_name }}
            @elseif ($order->lovedOne)
                {{ $order->lovedOne->name }} ❤️
            @else
                {{ Str::ucfirst($order->customer->f_name??null).' '.Str::ucfirst($order->customer->l_name??null) }}
            @endif
            </p>
            <div class="d-md-flex">
                <p class="mb-0"><i class="pt-1 me-1 fa fa-location"></i>{{ isset($deliveryAddress->distance)?App\CentralLogics\Helpers::formatDistance($deliveryAddress->distance):null}}</p>
                <p class="mb-0 ms-md-2"><i class="pt-1 me-1 fa fa-phone"></i>
                @if (isset($deliveryAddress->contact_person_number))
                    {{ $deliveryAddress->contact_person_number }}
                @elseif ($order->lovedOne)
                    {{ $order->lovedOne->phone }}
                @else
                    {{ $order->customer->phone??null }}
                @endif
                </p>
            </div>

        </div>
        <div class="w-25">

                    <p class="mb-0 small"><i class="fa-regular fa-clock"></i> {{App\CentralLogics\Helpers::format_time($order->updated_at->toTimeString())}}</p>
            <div class="d-flex flex-column flex-md-row">
                @if ($order['order_status'] == 'pending')
                <button class="btn p-1 me-md-2 my-2 my-md-0 btn-soft-success" data-order-state="confirmed" orderId="{{$order->id}}">Confirm</button>
                <button class="btn p-1 btn-soft-warning" data-order-state="canceled" orderId="{{$order->id}}">Cancel</button>
                @elseif($order['order_status'] == 'confirmed')
                <span class="badge bg-soft-info ml-2 ml-sm-3">
                    {{ __('messages.confirmed') }}
                </span>
                @elseif($order['order_status'] == 'processing')
                <span class="badge bg-soft-warning ml-2 ml-sm-3">
                    {{ __('messages.cooking') }}
                </span>
                @elseif($order['order_status'] == 'picked_up')
                <span class="badge bg-soft-warning ml-2 ml-sm-3">
                    {{ __('messages.out_for_delivery') }}
                </span>
                @elseif($order['order_status'] == 'delivered')
                <span class="badge bg-soft-success ml-2 ml-sm-3">
                    {{ __('messages.delivered') }}
                </span>
                @elseif($order['order_status'] == 'canceled')
                <span class="badge bg-soft-danger ml-2 ml-sm-3">
                    {{ __('messages.canceled') }}
                </span>
                @else
                <span class="badge bg-soft-info ml-2 ml-sm-3">
                    {{ __(str_replace('_', ' ', $order['order_status'])) }}
                </span>
                @endif

            </div>

        </div>
    </li>
    @endforeach
</ul> --}}
<table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
    <tbody>
        @foreach ($orders as $order)
            @php($deliveryAddress = json_decode($order->delivery_address))
            <tr>
                <td onclick="location.href='{{ route('admin.order.details', $order->id) }}'">
                    <div class="d-flex">
                        <div class="">
                            <img class="bg-soft-primary rounded img-fluid avatar-40 me-3" src="{{ Helpers::getUploadFile('default-food.png', 'product') }}" alt="product">
                        </div>
                        <div class="d-lg-flex justify-content-between w-100">
                            <div>
                                <div><b>{{ Str::ucfirst($order->restaurant->name) }}</b></div>
                                <div class="mt-1"><a href="{{ route('admin.order.details', $order->id) }}">#{{ $order->id }}</a> - {{ Helpers::format_currency($order->order_amount) }}</div>
                                {{-- </div>
                            <div class="ms-5"> --}}
                                <p class="mb-0">
                                    @if (isset($deliveryAddress->contact_person_name))
                                        {{ $deliveryAddress->contact_person_name }}
                                    @elseif ($order->lovedOne)
                                        {{ $order->lovedOne->name }} ❤️
                                    @else
                                        {{ Str::ucfirst($order->customer->f_name ?? null) . ' ' . Str::ucfirst($order->customer->l_name ?? null) }}
                                    @endif
                                </p>
                                <p class="mb-0"><i class="pt-1 me-1 fa fa-phone"></i>
                                @if (isset($deliveryAddress->contact_person_number))
                                    {{ $deliveryAddress->contact_person_number }}
                                @elseif ($order->lovedOne)
                                    {{ $order->lovedOne->phone }}
                                @else
                                    {{ $order->customer->phone ?? null }}
                                @endif
                                </p>

                            </div>
                            {{-- </td>
                <td class="text-end"> --}}
                            <div>
                                @if ($order['order_status'] == 'pending')
                                    <button class="btn p-1 me-md-2 my-2 my-md-0 btn-soft-success" data-order-state="confirmed" orderId="{{ $order->id }}">Confirm</button>
                                    <button class="btn p-1 btn-soft-warning" data-order-state="canceled" orderId="{{ $order->id }}">Cancel</button>
                                @elseif($order['order_status'] == 'confirmed')
                                    <div class="flex align-items-center list-user-action">
                                        <a class="btn btn-sm btn-icon btn-warning px-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Add" href="javascript:void(0)">
                                            <span class="btn-inner">
                                                {{ __('messages.confirmed') }}
                                            </span>
                                        </a>
                                    </div>
                                    <span class="badge bg-light text-primary p-2 mt-1">{{ Helpers::format_time($order->confirmed) }}</span>
                                @elseif($order['order_status'] == 'processing')
                                    <div class="flex align-items-center list-user-action">
                                        <a class="btn btn-sm btn-icon btn-success px-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Add" href="javascript:void(0)">
                                            <span class="btn-inner">
                                                {{ __('messages.cooking') }}
                                            </span>
                                        </a>
                                    </div>
                                    <span class="badge bg-light text-primary p-2 mt-1">{{ Helpers::format_time($order->processing) }}</span>
                                @elseif($order['order_status'] == 'picked_up')
                                    <div class="flex align-items-center list-user-action">
                                        <a class="btn btn-sm btn-icon btn-success px-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Add" href="javascript:void(0)">
                                            <span class="btn-inner">
                                                {{ __('messages.out_for_delivery') }}
                                            </span>
                                        </a>
                                    </div>
                                    <span class="badge bg-light text-primary p-2 mt-1">{{ Helpers::format_time($order->picked_up) }}</span>
                                @elseif($order['order_status'] == 'delivered')
                                    <div class="flex align-items-center list-user-action">
                                        <a class="btn btn-sm btn-icon btn-success px-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Add" href="javascript:void(0)">
                                            <span class="btn-inner">
                                                {{ __('messages.delivered') }}
                                            </span>
                                        </a>
                                    </div>
                                    <span class="badge bg-light text-primary p-2 mt-1">{{ Helpers::format_time($order->delivered) }}</span>
                                @elseif($order['order_status'] == 'canceled')
                                    <div class="flex align-items-center list-user-action">
                                        <a class="btn btn-sm btn-icon btn-danger px-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Add" href="javascript:void(0)">
                                            <span class="btn-inner">
                                                {{ __('messages.canceled') }}
                                            </span>
                                        </a>
                                    </div>
                                    <span class="badge bg-light text-primary p-2 mt-1">{{ Helpers::format_time($order->canceled) }}</span>
                                @else
                                    <div class="flex align-items-center list-user-action">
                                        <a class="btn btn-sm btn-icon btn-info px-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Add" href="javascript:void(0)">
                                            <span class="btn-inner">
                                                {{ Str::ucFirst(__(str_replace('_', ' ', $order['order_status']))) }}
                                            </span>
                                        </a>
                                    </div>
                                    <span class="badge bg-light text-primary p-2 mt-1">{{ Helpers::format_time($order[$order['order_status']]) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
