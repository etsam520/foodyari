@forelse($orders as $key=>$order)
<tr>
    <td>{{$key+$orders->firstItem()}}</td>
    <td class="table-column-pl-0 text-center">
        <a href="{{route('admin.order.details',$order['id'])}}">{{$order['id']}}</a>
    </td>
    <td>
        <div class="text-center">
            @if($order->lovedOne)
                <div class="loved-one-info">
                    <div class="fw-bold text-dark">{{ $order->lovedOne->name }}</div>
                    <small class="text-muted d-block">{{ $order->lovedOne->phone }}</small>
                    <small class="badge bg-warning text-dark">❤️ Loved One</small>
                </div>
            @else
                <div class="customer-info">
                    <div class="fw-bold text-dark">{{ $order->customer->f_name . ' ' . $order->customer->l_name }}</div>
                    <small class="text-muted">Customer</small>
                </div>
            @endif
        </div>
    </td>
    <td>
        <div class="text-center">
            {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
        </div>
    </td>
    <td>
        <div class="btn--container justify-content-center">
            <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                        href="{{route('admin.order.details',$order['id'])}}" title="{{__('messages.view')}}"><i
                                class="fa fa-eye "></i></a>
            <a class="btn btn-sm btn--primary btn-outline-primary action-btn" target="_blank"
                        href="{{route('admin.order.generate-invoice',$order['id'])}}" title="{{__('messages.invoice')}}"><i
                                class="fa fa-print"></i> </a>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center text-muted py-4">
        <i class="fas fa-search fa-2x mb-2"></i>
        <p>{{ __('No orders found matching your search criteria.') }}</p>
    </td>
</tr>
@endforelse