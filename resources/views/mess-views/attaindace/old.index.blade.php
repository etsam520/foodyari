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
                                    <div class="d-flex">
                                        <span class="border border-light border-2 p-1  rounded mx-3">
                                            <b>Thursday 7th March 2024</b>
                                        </span>
                                        <span class="border border-light border-2 p-1  rounded mx-3">
                                            <b>Select Menu</b>
                                        </span>
                                        <span class="border border-light border-2 p-1 px-2 rounded ">
                                            <b>Select By All</b>
                                        </span>
                                        <span class="border border-light border-2 p-1 rounded " data-service-type="breakfast" data-select-by="all" data-attendance="0">
                                            <b class="mx-2" data-service-fchar="B">B</b>
                                            <svg  width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                        </span>
                                        <span class="border border-light border-2 p-1 rounded " data-service-type="lunch" data-select-by="all" data-attendance="1" >
                                            <b class="mx-2" data-service-fchar="L" >L</b>
                                            <svg  fill="#1aa053" width="25px"  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-51.2 -51.2 614.40 614.40" xml:space="preserve" stroke="#1aa053" stroke-width="7.68"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M437.016,74.984c-99.979-99.979-262.075-99.979-362.033,0.002c-99.978,99.978-99.978,262.073,0.004,362.031 c99.954,99.978,262.05,99.978,362.029-0.002C536.995,337.059,536.995,174.964,437.016,74.984z M406.848,406.844 c-83.318,83.318-218.396,83.318-301.691,0.004c-83.318-83.299-83.318-218.377-0.002-301.693 c83.297-83.317,218.375-83.317,301.691,0S490.162,323.549,406.848,406.844z"></path> <path d="M368.911,155.586L234.663,289.834l-70.248-70.248c-8.331-8.331-21.839-8.331-30.17,0s-8.331,21.839,0,30.17 l85.333,85.333c8.331,8.331,21.839,8.331,30.17,0l149.333-149.333c8.331-8.331,8.331-21.839,0-30.17 S377.242,147.255,368.911,155.586z"></path> </g> </g> </g> </g></svg>
                                         </span>
                                         <span class="border border-light border-2 p-1 rounded " data-service-type="dinner" data-select-by="all" data-attendance="0"> 
                                            <b class="mx-2" data-service-fchar="D" >D</b>   
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
                                         <th>Add More</th>
                                         <th>Status</th>
                                      </tr>
                                   </thead>
                                   <tbody>
                                    <td>1</td>
                                    <td>suresh</td>
                                    <td>8986265780</td>
                                    <td>patna city</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="item">
                                                <span data-service-type="breakfast" data-select-by="single" data-plus="0">
                                                    <svg width="25px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>minus_circle [#1426]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-219.000000, -600.000000)" fill="#f16a1b"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M177.7,450 C177.7,450.552 177.2296,451 176.65,451 L170.35,451 C169.7704,451 169.3,450.552 169.3,450 C169.3,449.448 169.7704,449 170.35,449 L176.65,449 C177.2296,449 177.7,449.448 177.7,450 M173.5,458 C168.86845,458 165.1,454.411 165.1,450 C165.1,445.589 168.86845,442 173.5,442 C178.13155,442 181.9,445.589 181.9,450 C181.9,454.411 178.13155,458 173.5,458 M173.5,440 C167.70085,440 163,444.477 163,450 C163,455.523 167.70085,460 173.5,460 C179.29915,460 184,455.523 184,450 C184,444.477 179.29915,440 173.5,440" id="minus_circle-[#1426]"> </path> </g> </g> </g> </g></svg>
                                                </span>
                                                <span data-service-type="breakfast" data-select-by="single" data-plus="1">
                                                    <svg width="25px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="#1aa053"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg>
                                                </span>
                                                <span>Breakfast (<span data-service-type="breakfast" data-select-by="single" data-quantity="1">1</span>)</span>
                                            </div>
                                            <div class="item">
                                                <span data-service-type="lunch" data-select-by="single" data-plus="0">
                                                    <svg width="25px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>minus_circle [#1426]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-219.000000, -600.000000)" fill="#f16a1b"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M177.7,450 C177.7,450.552 177.2296,451 176.65,451 L170.35,451 C169.7704,451 169.3,450.552 169.3,450 C169.3,449.448 169.7704,449 170.35,449 L176.65,449 C177.2296,449 177.7,449.448 177.7,450 M173.5,458 C168.86845,458 165.1,454.411 165.1,450 C165.1,445.589 168.86845,442 173.5,442 C178.13155,442 181.9,445.589 181.9,450 C181.9,454.411 178.13155,458 173.5,458 M173.5,440 C167.70085,440 163,444.477 163,450 C163,455.523 167.70085,460 173.5,460 C179.29915,460 184,455.523 184,450 C184,444.477 179.29915,440 173.5,440" id="minus_circle-[#1426]"> </path> </g> </g> </g> </g></svg>
                                                </span>
                                                <span data-service-type="lunch" data-select-by="single" data-plus="1">
                                                    <svg width="25px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="#1aa053"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg>
                                                </span>
                                                <span>Lunch (<span data-service-type="lunch" data-select-by="single" data-quantity="0">0</span>)</span>
                                            </div>
                                            <div class="item">
                                                <span data-service-type="dinner" data-select-by="single" data-plus="0">
                                                    <svg width="25px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>minus_circle [#1426]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-219.000000, -600.000000)" fill="#f16a1b"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M177.7,450 C177.7,450.552 177.2296,451 176.65,451 L170.35,451 C169.7704,451 169.3,450.552 169.3,450 C169.3,449.448 169.7704,449 170.35,449 L176.65,449 C177.2296,449 177.7,449.448 177.7,450 M173.5,458 C168.86845,458 165.1,454.411 165.1,450 C165.1,445.589 168.86845,442 173.5,442 C178.13155,442 181.9,445.589 181.9,450 C181.9,454.411 178.13155,458 173.5,458 M173.5,440 C167.70085,440 163,444.477 163,450 C163,455.523 167.70085,460 173.5,460 C179.29915,460 184,455.523 184,450 C184,444.477 179.29915,440 173.5,440" id="minus_circle-[#1426]"> </path> </g> </g> </g> </g></svg>
                                                </span>
                                                <span data-service-type="dinner" data-select-by="single" data-plus="1">
                                                    <svg width="25px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="#1aa053"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg>
                                                </span>
                                                <span>Dinner (<span data-service-type="dinner" data-select-by="single" data-quantity="0">5</span>)</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            {{-- brakfast --}}
                                           <span data-service-type="breakfast" data-select-by="single" data-attendance="0" >
                                               <svg  width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                           </span>
                                           <span data-service-type="luch" data-select-by="single" data-attendance="1">
                                               <svg  fill="#1aa053" width="25px"  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-51.2 -51.2 614.40 614.40" xml:space="preserve" stroke="#1aa053" stroke-width="7.68"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M437.016,74.984c-99.979-99.979-262.075-99.979-362.033,0.002c-99.978,99.978-99.978,262.073,0.004,362.031 c99.954,99.978,262.05,99.978,362.029-0.002C536.995,337.059,536.995,174.964,437.016,74.984z M406.848,406.844 c-83.318,83.318-218.396,83.318-301.691,0.004c-83.318-83.299-83.318-218.377-0.002-301.693 c83.297-83.317,218.375-83.317,301.691,0S490.162,323.549,406.848,406.844z"></path> <path d="M368.911,155.586L234.663,289.834l-70.248-70.248c-8.331-8.331-21.839-8.331-30.17,0s-8.331,21.839,0,30.17 l85.333,85.333c8.331,8.331,21.839,8.331,30.17,0l149.333-149.333c8.331-8.331,8.331-21.839,0-30.17 S377.242,147.255,368.911,155.586z"></path> </g> </g> </g> </g></svg>
                                            </span>
                                            <span data-service-type="dinner" data-select-by="single" data-attendance="0">    
                                                <svg width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>
                                            </span>
                                        </div>
                                        
                                    </td>

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
    breakfast: false,
    lunch: false,
    dinner: false,
    makeBySingleURL : "{{route('mess.customer.attaindance.bySingle')}}",
    makeByAllURL : "{{route('mess.customer.attaindance.byAll')}}",
    icon : {
        uncheck : `<svg width="25px" viewBox="-1.4 -1.4 16.80 16.80" xmlns="http://www.w3.org/2000/svg" fill="#f16a1b" stroke="#f16a1b" stroke-width="0.21000000000000002"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill-rule="evenodd"> <path d="M0 7a7 7 0 1 1 14 0A7 7 0 0 1 0 7z"></path> <path d="M13 7A6 6 0 1 0 1 7a6 6 0 0 0 12 0z" fill="#FFF" style="fill: var(--svg-status-bg, #fff);"></path> <path d="M7 5.969L5.599 4.568a.29.29 0 0 0-.413.004l-.614.614a.294.294 0 0 0-.004.413L5.968 7l-1.4 1.401a.29.29 0 0 0 .004.413l.614.614c.113.114.3.117.413.004L7 8.032l1.401 1.4a.29.29 0 0 0 .413-.004l.614-.614a.294.294 0 0 0 .004-.413L8.032 7l1.4-1.401a.29.29 0 0 0-.004-.413l-.614-.614a.294.294 0 0 0-.413-.004L7 5.968z"></path> </g> </g></svg>`,
        check : `<svg  fill="#1aa053" width="25px"  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-51.2 -51.2 614.40 614.40" xml:space="preserve" stroke="#1aa053" stroke-width="7.68"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M437.016,74.984c-99.979-99.979-262.075-99.979-362.033,0.002c-99.978,99.978-99.978,262.073,0.004,362.031 c99.954,99.978,262.05,99.978,362.029-0.002C536.995,337.059,536.995,174.964,437.016,74.984z M406.848,406.844 c-83.318,83.318-218.396,83.318-301.691,0.004c-83.318-83.299-83.318-218.377-0.002-301.693 c83.297-83.317,218.375-83.317,301.691,0S490.162,323.549,406.848,406.844z"></path> <path d="M368.911,155.586L234.663,289.834l-70.248-70.248c-8.331-8.331-21.839-8.331-30.17,0s-8.331,21.839,0,30.17 l85.333,85.333c8.331,8.331,21.839,8.331,30.17,0l149.333-149.333c8.331-8.331,8.331-21.839,0-30.17 S377.242,147.255,368.911,155.586z"></path> </g> </g> </g> </g></svg>`,
    },
    selectBySingle: function() {
        toastr.success('Selected By Single');
    },
    selectByAll: function() {
        toastr.success('Selected By All');
    },
    selectByAllElementToggle: function(item) {
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
    selectBySingleElementToggle: function(item) {
        let svgStr ;
        console.log(item)
        if (item.dataset.attendance === "1") {
            item.dataset.attendance = 0;
            svgStr = this.icon.uncheck;
        }else if(item.dataset.attendance === "0"){
            item.dataset.attendance = 1;
            svgStr = this.icon.check;
        }

        const svgElem = document.createRange().createContextualFragment(svgStr).firstChild; 
        item.innerHTML = '';
        item.appendChild(svgElem);
        return true;
    }, 
    // makeBySingle = async function (item) {
    //     try {
    //         const formdata = new FormData();
    //         formdata.append('cid', item.dataset.cid);
    //         formdata.append('service_id', item.dataset.sid);

    //         const res = await fetch(url, {
    //             method: 'POST',
    //             headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    //             body: formdata
    //         });

    //         if (!res.ok) {
    //             const errorMessage = await res.json();
    //             throw new Error(handleError(errorMessage));
    //         }

    //         return await res.json();

    //     } catch (error) {
    //         toastr.error(error.message);
    //         console.error(error);
    //     }
    // },
};




document.querySelectorAll("span[data-service-type], input[data-service-type]").forEach(item => {
    try{

        if (item.tagName.toLowerCase() === 'span') {
            if (item.dataset.selectBy && item.dataset.serviceType ) {
                    item.addEventListener('click', () => {
                        if(item.dataset.attendance){
                            if (item.dataset.selectBy === 'single') {
                                attendance.selectBySingle();
                                attendance.selectBySingleElementToggle(item);
                            }
                            if (item.dataset.selectBy === 'all') {
                                attendance.selectByAll();
                                attendance.selectByAllElementToggle(item);
                            }
                        }
                        if(item.dataset.plus){
                            let spanQuantity = item.closest('.item').querySelector(`[data-service-type="${item.dataset.serviceType}"][data-quantity]`);
                            let qty = parseInt(spanQuantity.dataset.quantity);
                            if(qty >= 0){
                            if(item.dataset.plus == "1"){
                                    qty++; 
                                }else if(item.dataset.plus == "0" && qty > 0){
                                    qty--; 
                                }else{
                                    throw new Error('Data Set Plus can\'t be Null');
                                }
                                
                                spanQuantity.textContent = qty;
                                spanQuantity.dataset.quantity = qty;
                                // attendance.selectBySingle();
                                
                            }else{
                                throw new error('Quantity Can\'t be less than zero');
                            }
            
                        }
                        
                    });
                item.style.cursor = 'pointer';
            }else{
            throw new Error('span not found');   
            }
            
        }
    }catch(error){
        toastr.error(error.message);
        console.error(error);
    }

}); 
</script>
    
@endpush
