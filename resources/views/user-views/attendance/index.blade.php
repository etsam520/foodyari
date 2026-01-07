@extends('user-views.layouts.main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
   
    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                   <h4 class="card-title">Customer Attaindance</h4>
                </div>
             </div>
            <div class="card-body">
                <div class="bd-example">
                    <ul class="nav nav-pills" data-toggle="slider-tab" id="myTab" role="tablist">
                        {{-- <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#break-fast" type="button" role="tab" aria-controls="home" aria-selected="true">Today Attaidance</button>
                        </li> --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#Lunch" type="button" role="tab" aria-controls="profile" aria-selected="true">Daily Report</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#Dinner" type="button" role="tab" aria-controls="contact" aria-selected="false">Monthly Report</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        {{-- <div class="tab-pane fade show active" id="break-fast" role="tabpanel"
                            aria-labelledby="break-fast-tab1">
                            
                            <div class="table-responsive">
                                <table id="user-list-table" class="table" role="grid" data-toggle="data-table">
                                   <thead>
                                      <tr class="ligth">
                                         <th>S.I.</th>
                                         <th>Name</th>
                                         <th>Phone</th>
                                         <th>Addres</th>
                                         <th>Check In</th>
                                      </tr>
                                   </thead>
                                   <tbody>
                                  
                                   </tbody>
                                </table>
                            </div>
                        </div> --}}
                        <div class="tab-pane fade show active" id="Lunch" role="tabpanel"
                            aria-labelledby="Lunch-tab1">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table id="user-list-table" class="table" role="grid" data-toggle="data-table">
                                       <thead>
                                          <tr class="ligth">
                                            <tr class="ligth">
                                                <th>Day</th>
                                                <th>Date</th>
                                                <th>State</th>
                                             </tr>
                                          </tr>
                                       </thead>
                                       <tbody>
                                        @foreach ($dailyreport as $report)
                                       
                                        <tr>
                                            <td>{{ App\CentralLogics\Helpers::format_date($report->created_at) }}</td>
                                            <td>{{ App\CentralLogics\Helpers::format_day($report->created_at) }}</td>
                                            <td>
                                        
                                           <div class="d-flex">
                                            {{-- @if ( (int) $chkList->service_id == 1 && $chkList->checked == 1)  --}}
                                                @foreach ($report->checklist as $chklist )   
                                                <span class="border border-light border-2 p-1 rounded "  > 
                                                    {{-- <b class="mx-2 text-uppercase"  >{{$chklist->messService->name[0]}}</b>  --}}
                                                    @if($chklist->checked)
                                                    <svg  fill="#1aa053" width="25px"  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-51.2 -51.2 614.40 614.40" xml:space="preserve" stroke="#1aa053" stroke-width="7.68"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M437.016,74.984c-99.979-99.979-262.075-99.979-362.033,0.002c-99.978,99.978-99.978,262.073,0.004,362.031 c99.954,99.978,262.05,99.978,362.029-0.002C536.995,337.059,536.995,174.964,437.016,74.984z M406.848,406.844 c-83.318,83.318-218.396,83.318-301.691,0.004c-83.318-83.299-83.318-218.377-0.002-301.693 c83.297-83.317,218.375-83.317,301.691,0S490.162,323.549,406.848,406.844z"></path> <path d="M368.911,155.586L234.663,289.834l-70.248-70.248c-8.331-8.331-21.839-8.331-30.17,0s-8.331,21.839,0,30.17 l85.333,85.333c8.331,8.331,21.839,8.331,30.17,0l149.333-149.333c8.331-8.331,8.331-21.839,0-30.17 S377.242,147.255,368.911,155.586z"></path> </g> </g> </g> </g></svg>
                                                    @else
                                                    <svg width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                                    @endif
                                                </span>
                                                @endforeach
                                            </div> 
                                           
                                    
                                          
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Dinner" role="tabpanel"
                            aria-labelledby="dinner-tab1">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                                       <thead>
                                          <tr class="ligth">
                                             <th></th>
                                             <th>Year</th>
                                             <th>Month</th>
                                             <th>Total Breakfast</th>
                                             <th>Total Lunch</th>
                                             <th>Total Dinner</th>
                                          </tr>
                                       </thead>
                                       
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                
        </div>
    </div>
  
</div>
@endsection


