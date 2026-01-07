
@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
       <div class="row">
          <div class="col-sm-12">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                      <h4 class="card-title">Vehicla List</h4>
                   </div>
                   <div class="header-button">
                    <a href="{{route('admin.vehicle.create')}}" class="btn  btn-outline-primary p-2" > 
                     <svg width="20px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="currentColor"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg>
                      Add Vehicle</a>
                   </div>
                </div>
                <div class="card-body px-0">
                   <div class="table-responsive">
                      <table id="datatable" class="table" role="grid" data-toggle="data-table">
                         <thead>
                            <tr class="ligth">
                                <th>SL</th>
                                <th >TYPE</th>
                                <th >Minimum Coverage Area (KM)</th>
                                <th >Maximum coverage area (KM)</th>
                                <th >Extra charges  ({{ \App\CentralLogics\Helpers::currency_symbol() }})</th>
                                <th class="text-center">STATUS</th>
                               <th style="min-width: 100px">ACTION</th>
                            </tr>
                         </thead>
                         <tbody>
                            @foreach($vehicles as $key=>$vehicle)
                            <tr>
                                <td>{{$key+$vehicles->firstItem()}}</td>
                                <td>
                                    <span class="d-block text-body"><a href="{{route('admin.vehicle.view',[$vehicle->id])}}">{{Str::limit($vehicle['type'],25, '...')}}</a>
                                    </span>
                                </td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                        {{ $vehicle->starting_coverage_area }}
                                    </span>
                                </td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                        {{ $vehicle->maximum_coverage_area }}
                                    </span>
                                </td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                     {{ App\CentralLogics\Helpers::format_currency($vehicle->extra_charges) }}
                                    </span>
                                </td>
                                <td>
                                    <label class="form-check form-check form-switch form-check-inline" for="stocksCheckbox{{$vehicle->id}}">
                                        <input type="checkbox" onclick="location.href='{{route('admin.vehicle.status',[$vehicle['id'],$vehicle->status?0:1])}}'"class="form-check-input" id="stocksCheckbox{{$vehicle->id}}" {{$vehicle->status?'checked':''}}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('admin.vehicle.edit',[$vehicle['id']])}}" title="{{__('messages.edit')}} {{__('messages.vehicle')}}">
                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                             </svg>
                                        </a>
                                        <a class="btn btn-sm btn-outline-danger action-btn" href="javascript:"
                                            onclick="form_alert('vehicle-{{$vehicle['id']}}','{{__('messages.Want_to_delete_this_item')}}')" title="{{__('messages.delete')}} {{__('messages.vehicle')}}">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                    <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                        </a>
                                        <form action="{{route('admin.vehicle.delete',['vehicle' =>$vehicle['id']])}}"
                                                    method="post" id="vehicle-{{$vehicle['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                            {{-- @foreach ($employees as $employee )  
                            <tr>
                               <td class="text-center"><img class="bg-soft-primary rounded img-fluid avatar-40 me-3" src="{{asset("users").'/'.$employee->user->image}}" alt="profile"></td>
                               <td>{{$employee->user->f_name}} {{$employee->user->l_name}}</td>
                               <td>{{$employee->user->role->role}}</td>
                               <td>{{$employee->user->phone}}</td>
                               <td>{{$employee->user->email}}</td>
                               @php($address = json_decode($employee->address, true))
                               <td>{{$address['street']}}, {{$address['city']}}-{{$address['pincode']}}</td>
                               <td><span class="badge bg-primary">Active</span></td>
                               <td>
                                  <div class="flex align-items-center list-user-action">
                                     
                                     <a class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" data-original-title="Edit" href="javascript:void(0)">
                                        <span class="btn-inner">
                                           <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                              <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                              <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                              <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                           </svg>
                                        </span>
                                     </a>
                                     <a class="btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"  href="javascript:void(0)">
                                        <span class="btn-inner">
                                           <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                              <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                              <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                              <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                           </svg>
                                        </span>
                                     </a>
                                  </div>
                               </td>
                            </tr>
                           @endforeach --}}
                         </tbody>
                      </table>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
          </div>
@endsection


<!-- End Table -->