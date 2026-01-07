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
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#break-fast" type="button" role="tab" aria-controls="home" aria-selected="true">Daily Attaidance</button>
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
                            
                            <div class="table-responsive">
                                <div class="float-end mt-2 mb-2 p-2">
                                    <div class="d-flex" id="select-by-all-tollgle-button">
                                        {{-- <span class="border border-light border-2 p-1  rounded mx-3">
                                            <b>Thursday 7th March 2024</b>
                                        </span>
                                        <span class="border border-light border-2 p-1 px-2 rounded ">
                                            <b>Select By All</b>
                                        </span>
                                        <span class="border border-light border-2 p-1 rounded " data-service-id="1" data-service-type="breakfast" data-select-by="all" data-attendance="0">
                                            <b class="mx-2" data-service-fchar="B">B</b>
                                            <svg  width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                        </span>
                                        <span class="border border-light border-2 p-1 rounded " data-service-id="2" data-service-type="lunch" data-select-by="all" data-attendance="1" > --}}
                                            <b class="mx-2" data-service-fchar="L" >L</b>
                                            <svg  fill="#1aa053" width="25px"  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-51.2 -51.2 614.40 614.40" xml:space="preserve" stroke="#1aa053" stroke-width="7.68"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M437.016,74.984c-99.979-99.979-262.075-99.979-362.033,0.002c-99.978,99.978-99.978,262.073,0.004,362.031 c99.954,99.978,262.05,99.978,362.029-0.002C536.995,337.059,536.995,174.964,437.016,74.984z M406.848,406.844 c-83.318,83.318-218.396,83.318-301.691,0.004c-83.318-83.299-83.318-218.377-0.002-301.693 c83.297-83.317,218.375-83.317,301.691,0S490.162,323.549,406.848,406.844z"></path> <path d="M368.911,155.586L234.663,289.834l-70.248-70.248c-8.331-8.331-21.839-8.331-30.17,0s-8.331,21.839,0,30.17 l85.333,85.333c8.331,8.331,21.839,8.331,30.17,0l149.333-149.333c8.331-8.331,8.331-21.839,0-30.17 S377.242,147.255,368.911,155.586z"></path> </g> </g> </g> </g></svg>
                                         </span>
                                         <span class="border border-light border-2 p-1 rounded " data-service-id="3" data-service-type="dinner" data-select-by="all" data-attendance="0"> 
                                            <b class="mx-2" data-service-fchar="D" >D</b>   
                                             <svg width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                         </span>
                                         <span class="border border-light border-2 p-1 rounded " data-service-id="4" data-service-type="special" data-select-by="all" data-attendance="0"> 
                                            <b class="mx-2" data-service-fchar="S" >S</b>   
                                             <svg width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                         </span>
                                     </div>
                                </div>
                                <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                                   <thead>
                                      <tr class="ligth">
                                         <th>S.I.</th>
                                         <th>Name</th>
                                         <th>Phone</th>
                                         <th>Addres</th>
                                         <th>Pause And Play</th>
                                         <th>Check In</th>
                                      </tr>
                                   </thead>
                                   <tbody>
                                    {{-- <tr>
                                        <td>1</td>
                                        <td>suresh</td>
                                        <td>8986265780</td>
                                        <td>patna city</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <div class="item">
                                                    <span data-service-type="breakfast" data-play="0">
                                                        <svg width="35px"  viewBox="-2.4 -2.4 28.80 28.80" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M15 5V19M21 5V19M3 7.20608V16.7939C3 17.7996 3 18.3024 3.19886 18.5352C3.37141 18.7373 3.63025 18.8445 3.89512 18.8236C4.20038 18.7996 4.55593 18.4441 5.26704 17.733L10.061 12.939C10.3897 12.6103 10.554 12.446 10.6156 12.2565C10.6697 12.0898 10.6697 11.9102 10.6156 11.7435C10.554 11.554 10.3897 11.3897 10.061 11.061L5.26704 6.26704C4.55593 5.55593 4.20038 5.20038 3.89512 5.17636C3.63025 5.15551 3.37141 5.26273 3.19886 5.46476C3 5.69759 3 6.20042 3 7.20608Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                                    </span>
                                                    <span data-service-type="breakfast" data-play="1">
                                                        <svg viewBox="0 0 24 24" width="25px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M5.46484 3.92349C4.79896 3.5739 4 4.05683 4 4.80888V19.1911C4 19.9432 4.79896 20.4261 5.46483 20.0765L19.1622 12.8854C19.8758 12.5108 19.8758 11.4892 19.1622 11.1146L5.46484 3.92349ZM2 4.80888C2 2.55271 4.3969 1.10395 6.39451 2.15269L20.0919 9.34382C22.2326 10.4677 22.2325 13.5324 20.0919 14.6562L6.3945 21.8473C4.39689 22.8961 2 21.4473 2 19.1911V4.80888Z" fill="#0F0F0F"></path> </g></svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <span class="border border-light border-2 p-1 rounded " data-service-type="breakfast" data-select-by="single" data-attendance="0"> 
                                                    <b class="mx-2" data-service-fchar="B" >B</b>   
                                                    <svg width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                                </span>
                                                <span class="border border-light border-2 p-1 rounded " data-service-type="lunch" data-select-by="single" data-attendance="0"> 
                                                    <b class="mx-2" data-service-fchar="L" >L</b>   
                                                    <svg width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                                </span>
                                                <span class="border border-light border-2 p-1 rounded " data-service-type="dinner" data-select-by="single" data-attendance="0"> 
                                                    <b class="mx-2" data-service-fchar="D" >D</b>   
                                                    <svg width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                                </span>
                                                <span class="border border-light border-2 p-1 rounded " data-service-type="specila" data-select-by="single" data-attendance="0"> 
                                                    <b class="mx-2" data-service-fchar="S" >S</b>   
                                                    <svg width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                                </span>
                                            </div>
                                        
                                        </td>
                                    </tr> --}}
                                    
                                   </tbody>
                                  
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Lunch" role="tabpanel"
                            aria-labelledby="Lunch-tab1">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                                       <thead>
                                          <tr class="ligth">
                                            <tr class="ligth">
                                                <th>S.I.</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Addres</th>
                                                <th>Add More</th>
                                                <th>Status</th>
                                             </tr>
                                          </tr>
                                       </thead>
                                       
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
                                             <th>Name</th>
                                             <th>Description</th>
                                             <th>Type</th>
                                             <th>Price</th>
                                             <th>Available<br>Time Starts</th>
                                             <th>Available<br>Time Ends</th>
                                             <th>Status</th>
                                             <th style="min-width: 100px">Action</th>
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
<script>
const attendance = {
    loadTableURL : "{{route('mess.customer.getdata')}}",
    makeBySingleURL : "{{route('mess.customer.attaindance.bySingle')}}",
    makeByAllURL : "{{route('mess.customer.attaindance.byAll')}}",
    icon : {
        uncheck : `<svg width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>`,
        check : `<svg  fill="#1aa053" width="25px"  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-51.2 -51.2 614.40 614.40" xml:space="preserve" stroke="#1aa053" stroke-width="7.68"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M437.016,74.984c-99.979-99.979-262.075-99.979-362.033,0.002c-99.978,99.978-99.978,262.073,0.004,362.031 c99.954,99.978,262.05,99.978,362.029-0.002C536.995,337.059,536.995,174.964,437.016,74.984z M406.848,406.844 c-83.318,83.318-218.396,83.318-301.691,0.004c-83.318-83.299-83.318-218.377-0.002-301.693 c83.297-83.317,218.375-83.317,301.691,0S490.162,323.549,406.848,406.844z"></path> <path d="M368.911,155.586L234.663,289.834l-70.248-70.248c-8.331-8.331-21.839-8.331-30.17,0s-8.331,21.839,0,30.17 l85.333,85.333c8.331,8.331,21.839,8.331,30.17,0l149.333-149.333c8.331-8.331,8.331-21.839,0-30.17 S377.242,147.255,368.911,155.586z"></path> </g> </g> </g> </g></svg>`,
        pause : `<svg width="35px"  viewBox="-2.4 -2.4 28.80 28.80" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M15 5V19M21 5V19M3 7.20608V16.7939C3 17.7996 3 18.3024 3.19886 18.5352C3.37141 18.7373 3.63025 18.8445 3.89512 18.8236C4.20038 18.7996 4.55593 18.4441 5.26704 17.733L10.061 12.939C10.3897 12.6103 10.554 12.446 10.6156 12.2565C10.6697 12.0898 10.6697 11.9102 10.6156 11.7435C10.554 11.554 10.3897 11.3897 10.061 11.061L5.26704 6.26704C4.55593 5.55593 4.20038 5.20038 3.89512 5.17636C3.63025 5.15551 3.37141 5.26273 3.19886 5.46476C3 5.69759 3 6.20042 3 7.20608Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>`,
        play : `<svg viewBox="0 0 24 24" width="25px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M5.46484 3.92349C4.79896 3.5739 4 4.05683 4 4.80888V19.1911C4 19.9432 4.79896 20.4261 5.46483 20.0765L19.1622 12.8854C19.8758 12.5108 19.8758 11.4892 19.1622 11.1146L5.46484 3.92349ZM2 4.80888C2 2.55271 4.3969 1.10395 6.39451 2.15269L20.0919 9.34382C22.2326 10.4677 22.2325 13.5324 20.0919 14.6562L6.3945 21.8473C4.39689 22.8961 2 21.4473 2 19.1911V4.80888Z" fill="currentColor"></path> </g></svg>`
        
    },
    selectBySingle: async function(item) {
        try {
            const formdata = new FormData();
            formdata.append('customer_id', item.dataset.customerId);
            formdata.append('service_id', item.dataset.serviceId);

            const attendanceChecked = item.dataset.attendance =='0' ? 1: 0;
            formdata.append('attendance_checked', attendanceChecked) ;

            const res = await fetch(this.makeBySingleURL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                body: formdata
            });

            if (!res.ok) {
                const errorMessage = await res.json();
                throw new Error(handleError(errorMessage));
            }
            this.checkToggle(item);
            let result = await res.json();
            if(result.success){
                toastr.success(result.success);
            }

        } catch (error) {
            toastr.error(error.message);
            console.error(error);
            this.checkToggle(item);
        }
    },
    selectByAll:async function(item) {
        try {
            const formdata = new FormData();
            formdata.append('service_id', item.dataset.serviceId);

            const attendanceChecked = item.dataset.attendance =='0' ? 1: 0;
            formdata.append('attendance_checked', attendanceChecked) ;

            const res = await fetch(this.makeByAllURL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                body: formdata
            });

            if (!res.ok) {
                const errorMessage = await res.json();
                throw new Error(handleError(errorMessage));
            }
            this.checkToggle(item);
            let result = await res.json();
            if(result.success){
                toastr.success(result.success);

            }

        } catch (error) {
            toastr.error(error.message);
            console.error(error);
            this.checkToggle(item);
        }
        this.showTableData();
    },
    checkToggle: function(item) {
        let svgStr;
        if (item.dataset.attendance === "1") {
            item.dataset.attendance = 0;
            svgStr = this.icon.uncheck;
        } else if (item.dataset.attendance === "0") {
            item.dataset.attendance = 1;
            svgStr = this.icon.check;
        }

        const svgElem = document.createRange().createContextualFragment(svgStr).firstChild;
        const fChar = item.querySelector('[data-service-fchar]').cloneNode(true);
        item.innerHTML = '';
        item.appendChild(fChar);
        item.appendChild(svgElem);
        return true; // Corrected return value
    },
    paushToggle: function(item) {
        let svgStr ;
        if (item.dataset.play === "1") {
            item.dataset.play = 0;
            svgStr = this.icon.pause;
        }else if(item.dataset.play === "0"){
            item.dataset.play = 1;
            svgStr = this.icon.play;
        }
        const svgElem = document.createRange().createContextualFragment(svgStr).firstChild; 
        item.innerHTML = '';
        item.appendChild(svgElem);
        return true;
    }, 
    async loadTableData(){
        try {
            const res = await fetch(this.loadTableURL);
            if (!res.ok) {
                const errorMessage = await res.json();
                throw new Error(handleError(errorMessage));
            }
            const result = await res.json();
            // console.log(result)
            return result;

        } catch (error) {
            toastr.error(error.message);
            console.error(error);
        }
    },
   
    resetEvents :function(){ document.querySelectorAll("span[data-service-type], input[data-service-type]").forEach(item => {
            try{

                if (item.tagName.toLowerCase() === 'span') {
                    item.addEventListener('click', () => {
                        if(item.dataset.attendance){
                            if (item.dataset.selectBy === 'single') {
                                this.selectBySingle(item);
                                
                            }
                            if (item.dataset.selectBy === 'all') {
                                this.selectByAll(item);
                                
                            }
                        }
                        if(item.dataset.play){
                            this.paushToggle(item)
                        }
                        
                    });
                    item.style.cursor = 'pointer';
                }else{
                    throw new Error('span not found');   
                }
                
            }catch(error){
                toastr.error(error.message);
                console.error(error);
            }

        }); 
    },
    showTableData : async function () {
        let data = await this.loadTableData();
        const htmlToinsert =  data.customers.map(item => {
        const attendanceInfo = item.attendance[0];
        // console.log(attendanceInfo);
        const address = JSON.parse(item.address);
        const  services = [...data.services];
        let serviceTOinsert = '';
        for (let service of services) {
            let iconToinsert = this.icon.uncheck, attcheck = 0, checklistid = 0, checklist = '';
            if (attendanceInfo && attendanceInfo.checklist && Array.isArray(attendanceInfo.checklist)) {
                for (let indexedChecklist of attendanceInfo.checklist) {
                    if (indexedChecklist && indexedChecklist.service_id == service.id) {
                        checklist = indexedChecklist;
                        // toastr.error('dfjdlk'); 
                        console.log(checklist);
                        checklistid = checklist.id;
                        attcheck = checklist.checked == 1 ? 1 : 0;
                        iconToinsert = checklist.checked == 1 ? this.icon.check : this.icon.uncheck;
                        break; 
                    }
                }
            }
            serviceTOinsert += `<span class="border border-light border-2 p-1 rounded" data-checklist-id="${checklistid}" data-service-id="${service.id}" data-customer-id="${item.id}" data-service-type="${service.name.trim()}" data-select-by="single" data-attendance="${attcheck}">
                                <b class="mx-2" data-service-fchar="${service.name.charAt(0).toUpperCase()}">${service.name.charAt(0).toUpperCase()}</b>
                                ${iconToinsert}
                            </span>\n`;
        }
        let paushAndPlay = `<span data-service-type="breakfast" data-customer-id="${item.id}" data-play="0">
                                    ${this.icon.pause}
                            </span>`;
    
        
        return `<tr>
                    <td>1</td>
                    <td>${item.user.f_name.toUpperCase()}</td>
                    <td>8986265780</td>
                    <td>${address.street.toUpperCase()+', '+address.city.toUpperCase()+'-'+address.pincode.toUpperCase()}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <div class="item">
                                ${paushAndPlay}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            ${serviceTOinsert}
                        </div>
                    
                    </td>
                </tr>`;

        }).join('\n');

        let selectByallTOinsert =`<span class="border border-light border-2 p-1  rounded mx-3">
                                    <b>Thursday 7th March 2024</b>
                                </span>
                                <span class="border border-light border-2 p-1 px-2 rounded ">
                                    <b>Select By All</b>
                                </span>`;
        
        for (let service of [...data.services])
        {
            selectByallTOinsert += `<span class="border border-light border-2 p-1 rounded" data-service-id="${service.id}" data-service-type="${service.name.trim()}" data-select-by="all" data-attendance="0">
                                <b class="mx-2" data-service-fchar="${service.name.charAt(0).toUpperCase()}">${service.name.charAt(0).toUpperCase()}</b>
                                ${this.icon.uncheck}
                            </span>\n`;
        }

        document.querySelector('#user-list-table tbody').innerHTML = htmlToinsert;
        document.querySelector('#select-by-all-tollgle-button').innerHTML = selectByallTOinsert;
        this.resetEvents();
        
    },
};

attendance.showTableData();
</script>
<script>
    function handleError(errorResponse) {
    if (errorResponse && errorResponse.errors) {
        if (Array.isArray(errorResponse.errors)) {
            return errorResponse.errors.join(', ');
        }
        if (typeof errorResponse.errors === 'string') {
            return errorResponse.errors;
        }
        if (typeof errorResponse.errors === 'object') {
            const errorMessages = Object.values(errorResponse.errors);
            const errorList = errorMessages.map(item => `<li>${item}</li>`);
            return `<ul>${errorList.join('')}</ul>`;
        }
    }
    resp = JSON.stringify(errorResponse);
    resp =( JSON.parse(resp))
    // return errorResponse;
    return resp.error;
}
</script>
    
@endpush
