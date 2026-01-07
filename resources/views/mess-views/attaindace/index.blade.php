@extends('mess-views.layouts.dashboard-main')

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
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#break-fast" type="button" role="tab" aria-controls="home" aria-selected="true">Today Attaidance</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="report-tab" data-bs-toggle="tab" data-bs-target="#TodayReport" type="button" role="tab" aria-controls="today-report" aria-selected="false">Today Report</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#Lunch" type="button" role="tab" aria-controls="profile" aria-selected="false">Daily Report</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#Dinner" type="button" role="tab" aria-controls="contact" aria-selected="false">Monthly Report</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="break-fast" role="tabpanel"
                            aria-labelledby="break-fast-tab1">
                            
                            <div class="table-responsive ">
                                <div class="clearfix">
                                    <button data-report="today-attendance" class="float-end mb-2 me-4 btn btn-warning "><i class="fas fa-file-csv fa-2x"></i></button> 
                                </div>
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
                                    @foreach ($attendances as $attendance)
                                    {{-- @dd($attendance) --}}
                                        
                                    <tr>
                                        {{-- @dd($attendance) --}}
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{$attendance->customers->f_name}} {{$attendance->customers->l_name}}</td>
                                        <td>{{$attendance->customers->phone}}</td>
                                        @php($address = json_decode($attendance->customers->address, true))
                                        <td>{{$address['street']}}, {{$address['city']}}-{{$address['pincode']}}</td>
                                        <td>
                                            <div class="d-flex">
                                                @foreach ($attendance->checklist as $chklist )   
                                                <span class="border border-light border-2 p-1 rounded "  > 
                                                    <b class="mx-2 text-uppercase"  >{{Str::ucfirst($chklist->service) }}</b> 
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
                        <div class="tab-pane fade" id="TodayReport" role="tabpanel"
                            aria-labelledby="report-tab1">
                            {{-- <button class="btn btn-outline-primary">Allot To Deliveryman</button> --}}
                            <div class="table-responsive">
                                <style>
                                   th, td {
                                        vertical-align: top !important;
                                    }
                                </style>
                                <table id="user-list-table" class="table" role="grid" data-t;oggle="data-table">
                                   <thead>
                                      <tr class="ligth">
                                         <th>S.I.</th>
                                         <th>Name</th>
                                         <th>Break Fast </th>
                                         <th>Lunch </th>
                                         <th>Dinner </th>
                                      </tr>
                                   </thead>
                                   <tbody>
                                    @foreach ($attendances as $attendance)
                                    <tr>
                                        <td>{{$loop->index + 1}}</td>
                                        <td>
                                            {{$attendance->customers->f_name}} {{$attendance->customers->l_name}} <br>
                                            {{$attendance->customers->phone}} <br>
                                            @php($address = json_decode($attendance->customers->address, true))
                                            {{$address['street']}}, {{$address['city']}}-{{$address['pincode']}}
                                        </td>
                                        @foreach ($attendance->checklist as $chklist )
                                        @if ($chklist->service == 'breakfast')
                                            
                                        <td>
                                            <dl>
                                                <dt >Qty.&nbsp; :</dt>
                                                <dd class="d-flex flex-column">
                                                    <span class="badge bg-info" >{{$chklist->quantity}}</span>
                                                </dd>
                                                @if(!empty($chklist->addons))
                                                @php($addons = json_decode($chklist->addons, true))
                                                <dt>Addons :</dt>
                                                <dd class="d-flex flex-column">
                                                @foreach ($addons as $key => $value )
                                                    <span class="badge bg-info" >{{$key}} [{{$value['quantity']}}]</span>
                                                @endforeach
                                                </dd>               
                                                <dt>Extra Cost:</dt>
                                                <dd  class="d-flex flex-column">{{App\CentralLogics\Helpers::mess_addon_price_sum($addons)}}</dd> 
                                                @endif
                                            </dl> 
                                        </td>
                                        @else
                                        
                                        @endif

                                        @endforeach
                                        @if (count($attendance->checklist) ==2)
                                            <td>NA</td>
                                        @elseif (count($attendance->checklist) ==1)
                                            <td>NA</td><td>NA</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                   </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Lunch" role="tabpanel"
                            aria-labelledby="Lunch-tab1">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table id="user-list-table" class="table table-striped" role="grid" data-bls-toggle="data-table">
                                       <thead>
                                          <tr class="ligth">
                                            <tr class="ligth">
                                                <th>Day</th>
                                                <th>Date</th>
                                                <th>Total Breakfast</th>
                                                <th>Toatal Lunch</th>
                                                <th>Total Dinner</th>
                                             </tr>
                                          </tr>
                                       </thead>
                                       <tbody>
                                        
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

@push('javascript')
<script type="module">
    import {arrayToCSV, downloadCSV ,dateString } from "{{asset('assets/js/Helpers/csvHelper.js')}}";


    document.querySelector("[data-report=today-attendance]").addEventListener("click", async (event) => {
        try {
            const resp = await fetch('{{route("mess.customer.report.today-attendance")}}');
            if (!resp.ok) {
                const error = await resp.json();
                throw new Error(error.message);
            }
            const result = await resp.json();
            
            if (result.attendances.length > 0) {
                const csvContent = arrayToCSV(result.attendances);
                downloadCSV(csvContent, dateString() + "-attendance");
            } else {
                throw new Error('Data Not Available');
            }
        } catch (error) {
            Toastr.error(error.message);
        }
    });

    document.querySelector("data-bs-target=#TodayReport").addEventListener("click", async (event) => {
    });
    

    

</script>
    
@endpush
