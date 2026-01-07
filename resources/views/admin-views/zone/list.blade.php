@extends('layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">

        <div class="row">
            <div class="col-sm-12">
               <div class="card">
                  <div class="card-header d-flex justify-content-between">
                     <div class="header-title">
                        <h4 class="card-title">Zone Table</h4>
                     </div>
                  </div>
                  <div class="card-body p-0">
                     <div class="table-responsive mt-4">
                        <table id="datatable" class="table table-striped mb-0" role="grid"
                        data-toggle="data-table">
                           <thead>
                              <tr>
                                 <th>S.I</th>
                                 <th>Id</th>
                                 <th>Zone Name</th>
                                 <th>Mess</th>
                                 <th>Restaurant</th>
                                 <th>Delivery Man</th>
                                 <th>Status</th>
                                 <th>action</th>
                              </tr>
                           </thead>
                           <tbody>
                             @foreach ($zones as $zone)
                             {{-- @dd($category) --}}
                                <tr>
                                 <td>{{$loop->index + 1}}</td>
                                 <td>{{$zone->id}}</td>
                                 <td>{{$zone->name}}</td>
                                 <td>{{$zone->messes_count}}</td>
                                 <td>{{$zone->restaurants_count}}</td>
                                 <td>{{$zone->deliverymen_count}}</td>
                                 <td>
                                    <label class="form-check form-check form-switch form-check-inline" for="stocksCheckbox{{$zone->id}}">
                                        <input type="checkbox" onclick="location.href='{{route('admin.zone.status',[$zone['id'],$zone->status?0:1])}}'"class="form-check-input" id="stocksCheckbox{{$zone->id}}" {{$zone->status?'checked':''}}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                 </td>
                                 <td><a href="{{route('admin.zone.edit',$zone->id)}}" class="fa fa-edit text-warning">Edit</a></td>
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
