@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
       <div class="row">
          <div class="col-sm-12">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                      <h4 class="card-title">{{ $zone->name }} - Shift List</h4>
                      <p class="text-muted">Manage shifts for {{ $zone->name }} zone</p>
                   </div>
                   <div class="header-button d-flex gap-2">
                        <a href="{{ route('admin.shift.list') }}" class="btn btn-outline-secondary p-2">
                            <svg width="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                            </svg>
                            Back to All Shifts
                        </a>
                        
                        <a href="{{ route('admin.shift.create-for-zone', $zone->id) }}" class="btn btn-outline-primary p-2"> 
                            <svg width="20px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="currentColor"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg>
                             Add Shift for {{ $zone->name }}
                        </a>
                   </div>
                </div>
                <div class="card-body px-0">
                    <!-- Zone Statistics -->
                    <div class="row mb-3 px-3">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $shifts->count() }}</h5>
                                    <p class="mb-0">Total Shifts</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $shifts->where('status', 1)->count() }}</h5>
                                    <p class="mb-0">Active Shifts</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $shifts->where('status', 0)->count() }}</h5>
                                    <p class="mb-0">Inactive Shifts</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $zone->name }}</h5>
                                    <p class="mb-0">Zone</p>
                                </div>
                            </div>
                        </div>
                    </div>

                   <div class="table-responsive">
                      <table id="datatable" class="table" role="grid" data-toggle="data-table">
                         <thead>
                            <tr class="ligth">
                                <th>SL</th>
                                <th>NAME</th>
                                <th>START TIME</th>
                                <th>END TIME</th>
                                <th class="text-center">STATUS</th>
                                <th style="min-width: 100px">ACTION</th>
                            </tr>
                         </thead>
                         <tbody>
                            @forelse($shifts as $key => $shift)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                        {{$shift['name']}}
                                    </span>
                                </td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                        {{ Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                        {{date('h:i A', strtotime($shift['end_time']))}}
                                    </span>
                                </td>
                                <td>
                                    <label class="form-check form-check form-switch form-check-inline" for="stocksCheckbox{{$shift->id}}">
                                        <input type="checkbox" onclick="location.href='{{route('admin.shift.status',[$shift['id'],$shift->status?0:1])}}'"class="form-check-input" id="stocksCheckbox{{$shift->id}}" {{$shift->status?'checked':''}}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('admin.shift.edit',[$shift['id']])}}" title="{{__('messages.edit')}} {{__('messages.shift')}}">
                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                             </svg>
                                        </a>
                                        <a class="btn btn-sm btn-outline-danger action-btn" href="javascript:"
                                            onclick="form_alert('shift-{{$shift['id']}}','{{__('messages.Want_to_delete_this_item')}}')" title="{{__('messages.delete')}} {{__('messages.shift')}}">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                    <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                        </a>
                                        <form action="{{route('admin.shift.delete',['shift' =>$shift['id']])}}"
                                                    method="post" id="shift-{{$shift['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="py-4">
                                        <h5>No shifts found for {{ $zone->name }}</h5>
                                        <p class="text-muted">Create the first shift for this zone</p>
                                        <a href="{{ route('admin.shift.create-for-zone', $zone->id) }}" class="btn btn-primary">
                                            Add First Shift
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
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
