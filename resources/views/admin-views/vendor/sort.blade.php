
@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
       <div class="col-sm-12">
          <div class="card">
             <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                   <h4 class="card-title">Zone Wise Restaurant Sort</h4>
                   <hr class="hr-horizontal">
                </div>
             </div>
             
             <div class="card-body p-0">
                @foreach ($zone_wise_restaurants as $key => $restaurants )
             
                <p class="px-2 my-0 text-center text-warning underline fs-4">zone : {{Str::ucfirst($key)}}</p>
                <ul class="list-group sortable" id="">
                    @foreach ($restaurants as $restaurant)
                        
                    <li class="list-group-item" data-id="{{$restaurant->id}}">
                        <div class="d-flex">
                            <img class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded"
                             src="{{$restaurant->image?asset('restaurant/'.$restaurant->image): asset('assets/images/icons/food-default-image.png')}}" alt="{{Str::ucfirst($restaurant->name)}}" >
                            <p class="ms-3 pt-1 text-muted">{{Str::ucfirst($restaurant->name)}}</p>
                        </div>
                    </li>
                    @endforeach
                
                </ul>
             @endforeach
          </div>
       </div>
    </div>
       </div>
@endsection

@push('javascript')
<script src="{{asset('assets/vendor/sortable/Sortable.min.js')}}"></script>
<script>
    document.querySelectorAll('.sortable').forEach(element => {
        new Sortable(element, {
            multiDrag: true, 
            selectedClass: 'selected', 
            fallbackTolerance: 3, 
            animation: 150,
            onUpdate: function(eve) {
                const sortedArray = {};
                eve.target.querySelectorAll('.list-group-item').forEach((item, index) => {
                    sortedArray[index + 1] = JSON.parse(item.dataset.id);
                });
                
                fetch('{{route('admin.restaurant.sort')}}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({sortedArray : sortedArray})
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success: Updated');
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            }
        });
    });

   
 
</script>
@endpush
<!-- End Table --> 