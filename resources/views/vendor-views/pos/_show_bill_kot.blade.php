<div class="card aos-init aos-animate" data-aos="fade-up" data-aos-delay="500">
    <p class="py-2 ps-3 text-muted">Last Order</p>
    @php($order = App\Models\Order::with(['customer'])->find($order->id))
    <div class="text-center card-body d-flex justify-content-around">
        <div>

        <h3 class="mb-2">#{{$order->id}}</h3>
        <p class="mb-0 text-gray">{{$order->customer?$order->customer->f_name : 'Walk-in'}}</p>
        </div>
        <hr class="hr-vertial">
        <div>
        {{-- <h2 class="mb-2">7,500</h2> --}}
        <p class="mb-0 ">
            <a class="badge py-1 px-2 bg-soft-success"  href="{{route('vendor.order.generate-KOT',$order->id)}}"  type="button">Print KOT</a>
            <a class="badge py-1 px-2 bg-soft-warning" href="{{route('vendor.order.generate-invoice',$order->id)}}" type="button"><i class="fa fa-print"></i>Print Bill</a>
        </p>
        </div>
    </div>
</div>
