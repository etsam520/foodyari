
@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
       <div class="col-sm-12">
          <div class="card">
             <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                   <h4 class="card-title">Basic Table</h4>
                </div>
             </div>
             <div class="card-body p-0">
                <div class="table-responsive mt-4">
                   <table id="datatable" data-toggle="data-table" class="table  mb-0" role="grid">
                      <thead>
                         <tr>
                            <th>S.I</th>
                            <th>Food</th>
                            <th>Category</th>
                            <th>Restaurant</th>
                            <th>Price</th>
                            <th>Availability</th>
                            <th>Status</th>
                            <th>action</th>
                         </tr>
                      </thead>
                      <tbody>
                        @foreach ($foods as $food)
                           <tr>
                            <td>{{$loop->index + 1}}</td>
                            <td><div class="d-flex">
                                <div class="f-item align-self-baseline"><img src="{{asset("product/$food->image")}}" onerror="this.src='{{asset('assets/images/icons/food-default-image.png')}}'" alt="" style="width:75px;height:75px;border-radius:10px;"></div>
                                <div class="f-item align-self-baseline mx-3">{{$food->name}}</div>

                                </div></td>
                            <td>{{$food?->category?->name}}</td>
                            <td>{{$food->restaurant->name}}</td>
                            <td>{{$food->price == 0 ? "Customized" : $food->price}}</td>
                            <td>
                                @if($food->available_time_starts && $food->available_time_ends)
                                    <span class="badge bg-secondary">Daily: {{$food->available_time_starts}} - {{$food->available_time_ends}}</span>
                                @else
                                    <span class="badge bg-success">24/7 Available</span>
                                @endif
                                <br>
                                <a href="{{ route('admin.food-availability.show', $food->id) }}" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="fas fa-clock"></i> Manage Times
                                </a>
                            </td>
                            <td >
                                <div class="form-check  form-switch px-3">
                                   <input type="checkbox"  data-toggle="toggle" name="free_delivery" {{$food->status ===1?'checked':''}}
                                    data-status="update" data-food-id="{{$food->id}}" class="form-check-input form-control fs-4" id="free_delivery">
                               </div>
                             </td>
                            <td>
                                <div class="flex align-items-center list-user-action">

                                   <a class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" data-original-title="Edit" href="{{route('admin.food.edit',['id'=>$food->id])}}">
                                       <span class="btn-inner">
                                           <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                           <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                           <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                           <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                           </svg>
                                       </span>
                                   </a>
                                   <a class="btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="confirmDeletion('{{route('admin.food.delete',['id' => $food->id])}}')"  href="javascript:void(0)">
                                       <span class="btn-inner">
                                           <svg class="icon-20"   width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                           <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                           <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                           <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                           </svg>
                                       </span>
                                   </a>
                               </div>
                            </td>
                        </tr>
                        @endforeach

                      </tbody>
                   </table>
                </div>
             </div>
          </div>
       </div>
    </div>
       </div>
@endsection
<!-- End Table -->

@push('javascript')
<script>
    document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-status=update]').forEach(async element => {
        element.addEventListener('change', async () => {
            try {
                const resp = await fetch('{{ route('admin.food.status') }}?status=' + element.checked + '&food_id=' + element.dataset.foodId);
                if (!resp.ok) {
                    const error = await resp.json();
                    throw new Error(error.message);
                }
                const result = await resp.json();
                toastr.success(result.message);
            } catch (error) {
                toastr.error(error.message);
                console.error('Error fetching data:', error);
            }
        });
    });
});
    function confirmDeletion(url) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete this item?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url; // Redirect to the intended URL
        }
    });
}
</script>
@endpush
