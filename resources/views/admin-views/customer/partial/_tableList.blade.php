@foreach($orders as $key=>$order)
<tr>
    <td>{{$key+$orders->firstItem()}}</td>
    <td class="table-column-pl-0 text-center">
        <a href="{{route('vendor.order.details',$order['id'])}}">{{$order['id']}}</a>
    </td>
    <td>
        <div class="text-center">
            {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
        </div>
    </td>
    <td>
        <div class="btn--container justify-content-center">
        <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                    href="{{route('vendor.order.details',$order['id'])}}" title="{{__('messages.view')}}"><i
                            class="fa fa-eye "></i></a>
        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" target="_blank"
                    href="{{route('vendor.order.generate-invoice',$order['id'])}}" title="{{__('messages.invoice')}}"><i
                            class="fa fa-print"></i> </a>
        </div>
    </td>
</tr>
@endforeach
