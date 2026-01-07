@extends('user-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        @if(!empty($meesMunu))
        <div class="col-md-10 card p-4">
            <div class="d-flex justify-content-around">
                <div class="f-item ">
                    <h3>Today Menu</h3>
                </div>
                <div class="f-item">
                    <button
                        class="btn {{$userInfo->diet_status === 0?'btn-outline-success'  :'btn-outline-danger'}} " data-hold-diet="{{$userInfo->diet_status === 0 ? 1:0}}">
                        {{$userInfo->diet_status === 0 ? 'Active Your Diet':'Hold Your Diet'}}
                    </button>
                </div>
            </div>
        </div>
        @php ($defaultMessIcons = [
            asset('assets/images/icons/breakfast.png'),asset('assets/images/icons/lunch-box.png'),asset('assets/images/icons/dinner.png')
        ])
        @foreach ($meesMunu as $menu)
        @php($last_updated = Carbon\Carbon::now()->diff(Carbon\Carbon::parse($menu->created_at)))
        {{-- @php($service = $menu->messServices[0]) --}}
        {{-- @dd($menu) --}}
         <div class="col-md-4">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-5 p-2">
                        <img class="bd-placeholder-img" src="{{$menu->image ?asset("MessMenu/$menu->image") :$defaultMessIcons[$loop->index] }}"  width="150px" height="auto" alt="">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <h5 class="card-title">{{$menu->name}}</h5>
                            <p class="card-text">{{$menu->description}}</p>
                            <p class="card-text">
                                <small class="text-muted">Last updated {{ $last_updated->format('%h hrs %I mins') }} ago</small><br>
                                <small class="text-info">{{App\CentralLogics\Helpers::getService('D')}}</small><br><span data-cancel-for="{{$menu->name}}" data-menu-id="{{$menu->id}}" class="badge bg-danger mx-2 p-1">Cancel</span><span data-addons-for="{{$menu->name}}" data-menu-id="{{$menu->id}}" class="badge bg-info mx-2 p-1">Addons</span>
                            </p>
                        </div>
                    </div>
                    @php($addons = App\Models\MessAddonModel::whereIn('id',json_decode($menu->addons))->get())
{{--
                    @if(!empty($addons))
                        <div class="col-md-12 px-3">
                            <p class="card-text"> Addons
                                <dl class="row pricing">
                                    @foreach ($addons as  $addon)
                                     <dt class="col-6">{{$addon->name}} <i>[@ {{$addon->price}}]</i></dt>
                                        <dd class="col-6 text-right">
                                            <div class="btn-group mr-2 shadow-sm" role="group">
                                                <button type="button" class="btn btn-sm btn-danger change_tiffin" data-type="dinner" data-value="-1">+</button>
                                                <button type="button" class="btn btn-sm btn-light border count_tiffin px-3" data-type="dinner">1</button>
                                                <button type="button" class="btn btn-sm btn-success change_tiffin" data-type="dinner" data-value="1">-</button>
                                              </div>
                                        </dd>
                                    @endforeach
                                    <dt class="col-8">Extra Cost:</dt>
                                     <dd class="col-4 text-right">0 ₹</dd>
                                </dl>
                            </p>
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="row row d-flex justify-content-center">
        <div class="col-md-10 col-lg-10">

            <div class="row row-cols-1">
               <div class="card p-3">
                 <h5>NEAREST MESS</h5>
                </div>
                <div class="overflow-hidden d-slider1 ">

                    <ul  class="p-0 m-0 mb-2 swiper-wrapper list-inline">
                        @foreach ($messList as $mess)
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                           <a href="{{route('user.mess.subscriptions', $mess->id)}}">
                               <div class="card-body">
                                   <div class="progress-widget">
                                       <div class="progress-detail">
                                           <h4 class="counter">{{$mess->name}}</h4>
                                           <p  class="mb-2">{{$mess->address}}</p>
                                       </div>
                                   </div>
                               </div>
                            </a>
                        </li>
                        @endforeach


                    </ul>
                    <div class="swiper-button swiper-button-next"></div>
                    <div class="swiper-button swiper-button-prev"></div>
                </div>
            </div>
        </div>

        {{--  mess ad res--}}
        {{-- <div class="col-lg-10">
            <div class="card border-0 ">
                <div class="card-body border  text-center m-3 p-0" style="cursor: pointer" data-mess="click">
                    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                          <div class="carousel-item active" data-bs-interval="10000">
                            <img src="{{asset('assets/images/dashboard/top-image.png')}}" class="d-block w-100" alt="...">
                          </div>
                          <div class="carousel-item" data-bs-interval="2000">
                            <img src="{{asset('assets/images/dashboard/top-header5.png')}}" class="d-block w-100" alt="...">
                          </div>
                          <div class="carousel-item">
                            <img src="{{asset('assets/images/dashboard/top-image.png')}}" class="d-block w-100" alt="...">
                          </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Next</span>
                        </button>
                      </div>
                        <div class="marquee-container py-3">
                            <marquee scrollamount="12">
                                <div class="marquee-item">
                                    <i class="fa-solid fa-circle-check me-3 mt-2" style="color: #fff;"></i>
                                    <a  style="color: #2dcd7c;" >
                                    </a>Notice 1
                                </div>
                                <div class="marquee-item">
                                    <i class="fa-solid fa-circle-check me-3 mt-2" style="color: #fff;"></i>
                                    <a  style="color: #2dcd7c;" >
                                    </a>Notice 2
                                </div>
                                <div class="marquee-item">
                                    <i class="fa-solid fa-circle-check me-3 mt-2" style="color: #fff;"></i>
                                    <a  style="color: #2dcd7c;" >
                                    </a>Notice 3
                                </div>
                                <div class="marquee-item">
                                    <i class="fa-solid fa-circle-check me-3 mt-2" style="color: #fff;"></i>
                                    <a  style="color: #2dcd7c;" >
                                    </a>Notice 4
                                </div>
                            </marquee>
                        </div>
                        <div class="border-top  py-3">
                            <p class="mb-0"><b>Total 130 Mess on Foodyari</b></p>
                        </div>
                        <div class="border-top d-flex flex-md-row flex-sm-column justify-content-between p-2">
                            <div class="item">
                                <span class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="50" height="50"><path fill="#222222" d="M25.68 57.63a2 2 0 0 1-2-2V37L6.73 19.33a7.66 7.66 0 0 1 5.52-13h39.5a7.65 7.65 0 0 1 5.4 13.08L39.53 37v14.2a2 2 0 0 1-1.3 1.88L26.38 57.5a1.85 1.85 0 0 1-.7.13ZM12.25 10.37a3.65 3.65 0 0 0-2.63 6.19l17.5 18.21a2 2 0 0 1 .56 1.39v16.59l7.85-2.94V36.19a2 2 0 0 1 .59-1.41l18.21-18.16a3.66 3.66 0 0 0-2.58-6.25ZM37.53 51.2Z" class="color222 svgShape"></path></svg></span>
                            </div>
                            <div class="item">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="No of Normal Diet">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="No of Special Diet">
                                </div>
                            </div>
                            <div class="item">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="Total Diet">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="Diet Cost">
                                </div>
                            </div>
                            <div class="item">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="Veg Or Non veg">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="Nearest">
                                </div>
                            </div>
                        </div>
                        <div class="border-top">
                            <div class="container-fluid">
                                <div class="row p-0 mt-3 mb-3">
                                    <div class="col-lg-5 m-auto card" >
                                        <div class="row card-body" style="background: #ec7a30">
                                            <div class="col-sm-4 p-0 mb-0">
                                                <div class="position-relative text-white"  style="min-height: 200px">
                                                    <span class="badge bg-info position-absolute top-0 start-0" >Veg</span>
                                                    <span class="position-absolute top-0 end-0 ">
                                                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 1792 1792" width="35px" height="35px"><path d="M1420 128q23 0 44 9 33 13 52.5 41t19.5 62v1289q0 34-19.5 62t-52.5 41q-19 8-44 8-48 0-83-32l-441-424-441 424q-36 33-83 33-23 0-44-9-33-13-52.5-41t-19.5-62V240q0-34 19.5-62t52.5-41q21-9 44-9h1048z" fill="#0dcaf0" ></path></svg>
                                                    </span>
                                                    <span  style="position: absolute;top: 3rem;left: 0;">

                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 67 67" width="100px" height="100px"><path fill="#a3d6fb" d="M33.5 2.503c-16.326 0-29.562 13.235-29.562 29.562S17.174 61.628 33.5 61.628s29.563-13.236 29.563-29.563c0-16.327-13.236-29.562-29.563-29.562z"></path><path fill="#d0ecfb" d="M57.125 32.062a23.589 23.589 0 0 1-6.915 16.71c-4.283 4.275-10.186 6.923-16.71 6.923-13.048 0-23.625-10.576-23.625-23.633A23.557 23.557 0 0 1 16.79 15.36c4.283-4.283 10.186-6.923 16.71-6.923s12.427 2.64 16.71 6.923a23.557 23.557 0 0 1 6.915 16.702z" ></path><path d="M58.62 16.489a29.32 29.32 0 0 0-15.57-4.44c-16.33 0-29.57 13.24-29.57 29.56 0 5.72 1.62 11.05 4.43 15.57a29.185 29.185 0 0 1-5.31-4.21c-5.35-5.35-8.66-12.74-8.66-20.9 0-16.33 13.23-29.57 29.56-29.57 8.17 0 15.55 3.31 20.9 8.66 1.6 1.6 3.03 3.39 4.22 5.33z" opacity=".1" fill="#000000"></path><path fill="#6b6dad" d="M54.96 53.379a1 1 0 0 1-.726-1.688 28.387 28.387 0 0 0 7.826-19.622c0-15.775-12.765-28.57-28.56-28.57-15.748 0-28.56 12.816-28.56 28.57 0 7.28 2.748 14.213 7.739 19.525a1 1 0 1 1-1.458 1.37 30.41 30.41 0 0 1-8.28-20.895c0-16.857 13.709-30.57 30.56-30.57 16.882 0 30.559 13.67 30.559 30.57a30.378 30.378 0 0 1-8.374 20.998.999.999 0 0 1-.726.312zm-21.46 9.25a30.6 30.6 0 0 1-10.388-1.81 1 1 0 0 1 .677-1.881c6.157 2.216 13.034 2.24 19.14.097a1 1 0 1 1 .662 1.887 30.387 30.387 0 0 1-10.09 1.707z" ></path><path fill="#c0c7dc" d="M54.97 58.047a2.82 2.82 0 0 1-3.997 3.976L34.037 45.108a1.014 1.014 0 0 0-1.446 0l-1.69 1.69a1.02 1.02 0 0 1-1.436-.01L14.176 31.52a10.12 10.12 0 0 1-2.966-7.133c0-2.212.733-4.413 2.169-6.252.393-.478 1.116-.52 1.541-.096l40.05 40.008z"></path><path d="M17.32 22.069a10.13 10.13 0 0 0-2.17 6.26c.01 2.41.87 4.82 2.59 6.74l-3.56-3.55a10.11 10.11 0 0 1-2.97-7.13c0-2.21.73-4.41 2.17-6.25.39-.48 1.12-.52 1.54-.1l3.94 3.94c-.43-.43-1.15-.38-1.54.09zM54.964 62.03c-1.1 1.1-2.88 1.1-3.99-.01l-3.94-3.94a2.825 2.825 0 0 0 3.99.02c1.1-1.1 1.11-2.86.05-3.95l3.9 3.9a2.821 2.821 0 0 1-.01 3.98z" opacity=".1" fill="#000000" ></path><path fill="#c0c7dc" d="M57.176 28.639 45.634 40.18c-2.635 2.634-6.583 3.176-9.76 1.617L13.99 63.684a2.76 2.76 0 0 1-3.999-.087c-.996-1.112-.844-2.851.21-3.905L31.99 37.9c-.606-1.17-.873-2.447-.873-3.732a8.437 8.437 0 0 1 2.483-6.02l11.542-11.542a1.71 1.71 0 0 1 2.418-.007l.007.007a1.71 1.71 0 0 1-.007 2.418l-10.95 10.95 2.39 2.39 10.95-10.951a1.71 1.71 0 1 1 2.417 2.418l-10.95 10.95 2.39 2.39 10.95-10.95a1.71 1.71 0 1 1 2.418 2.418z"></path><path d="M43.41 23.179c.67-.67.67-1.76.01-2.42l-.01-.01c-.67-.66-1.75-.66-2.42.01l4.15-4.15c.67-.67 1.76-.67 2.42-.01l.01.01c.66.66.66 1.74-.01 2.42l-4.15 4.15zM48.26 27.939c.63-.68.61-1.73-.04-2.38-.67-.66-1.75-.66-2.42.01l4.15-4.16c.67-.67 1.75-.67 2.42 0 .67.67.67 1.75 0 2.42l-4.11 4.11zM57.18 28.639l-4.16 4.15c.67-.67.67-1.75.01-2.42-.65-.65-1.7-.67-2.38-.04l4.11-4.11c.67-.67 1.75-.67 2.42 0 .67.67.67 1.75 0 2.42zM18.144 59.525l-4.15 4.16a2.76 2.76 0 0 1-4-.09c-1-1.11-.85-2.85.21-3.9l4.1-4.1c-1.01 1.06-1.14 2.76-.16 3.85a2.759 2.759 0 0 0 4 .08z" opacity=".1" fill="#000000"></path><path fill="#6b6dad" d="M57.88 29.35a2.725 2.725 0 0 0 .01-3.84c-1.03-1.03-2.81-1.02-3.84 0L43.81 35.76l-.98-.98 10.24-10.24a2.702 2.702 0 0 0 0-3.84 2.702 2.702 0 0 0-3.83.01L39 30.95l-.98-.98 10.25-10.24a2.716 2.716 0 0 0 0-3.84 2.713 2.713 0 0 0-3.83.01L32.89 27.44a9.465 9.465 0 0 0-2.54 4.6L15.63 17.33c-.4-.4-.96-.61-1.53-.58-.58.03-1.12.3-1.51.77-1.53 1.97-2.38 4.4-2.38 6.87.01 2.96 1.17 5.75 3.26 7.84l11.4 11.38L9.49 58.98c-1.47 1.48-1.58 3.8-.24 5.29a3.76 3.76 0 0 0 5.45.12c1.906-1.9-6.9 6.883 18.63-18.58l16.94 16.92c1.47 1.49 3.917 1.495 5.4.01a3.827 3.827 0 0 0 .01-5.4L41.75 43.43c1.7-.39 3.3-1.25 4.59-2.54l11.54-11.54zm-43 1.46a9.075 9.075 0 0 1-2.67-6.42c0-2.05.68-4 2-5.64l15.92 15.9c.05 1.07.27 2.09.66 3.04l-4.51 4.51-11.4-11.39zm39.38 27.94c.71.71.7 1.86-.01 2.58-.7.7-1.86.7-2.57-.01L34.74 44.4c-.01-.01-.02-.02-.03-.02l1.39-1.39c.99.4 2.02.62 3.05.67l15.11 15.09zM36.45 40.96c-.05-.02-.09-.04-.14-.06a.99.99 0 0 0-1.14.19c-5.718 5.72-16.749 16.75-21.89 21.89-.713.694-1.832.71-2.55-.05-.61-.69-.53-1.82.18-2.53l15.38-15.38 1.41-1.41 5-5c.31-.31.38-.78.18-1.17-.01-.03-.03-.05-.04-.08-.48-.95-.72-2.02-.72-3.2 0-.11 0-.23.02-.34.06-1.87.83-3.63 2.17-4.97l11.54-11.54c.29-.29.74-.27 1.01 0 .28.28.27.73-.01 1.01L35.9 29.27a.996.996 0 0 0 0 1.41l2.39 2.39c.19.19.44.29.71.29s.52-.1.71-.29l10.95-10.95c.262-.28.735-.275 1-.01.292.292.27.76 0 1.01L40.71 34.07c-.39.39-.39 1.03 0 1.42l2.39 2.39c.39.39 1.02.39 1.41 0l10.95-10.95c.28-.27.74-.28 1.02 0 .27.27.27.72-.01 1L44.93 39.47c-2.322 2.323-5.759 2.767-8.48 1.49z"></path></svg>
                                                    </span>
                                                    <span class="badge bg-success" style="position: absolute;top: 10rem;left: 0;">REg: 38409RYRY</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 p-1 card" >
                                                <div class="container card-body" >
                                                    <table class="table table-responsive">
                                                        <tr class="row">
                                                            <td  class="col-12 h-1">ABC MEss</td>
                                                        </tr>
                                                        <tr class="row">
                                                            <td  class="col-12 h-1">Description</td>
                                                        </tr>
                                                        <tr class="row">
                                                            <td  class="col-6"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 46" width="50px" height="50px"><path fill="#fbb03b" d="M23,40a83.45,83.45,0,0,1-11.87-.85,5.08,5.08,0,0,1-4.28-4.28,83.35,83.35,0,0,1,0-23.73,5.08,5.08,0,0,1,4.28-4.28,83.42,83.42,0,0,1,23.73,0,5.08,5.08,0,0,1,4.28,4.28,83.35,83.35,0,0,1,0,23.73,5.08,5.08,0,0,1-4.28,4.28A83.45,83.45,0,0,1,23,40Z" ></path><path fill="#f6ffb8" d="M24,15.12l1.41,4.32a1,1,0,0,0,1,.73h4.55a1,1,0,0,1,.62,1.9l-3.68,2.67a1,1,0,0,0-.38,1.17l1.4,4.32A1,1,0,0,1,27.3,31.4l-3.68-2.67a1,1,0,0,0-1.23,0L18.7,31.4a1,1,0,0,1-1.61-1.17l1.4-4.32a1,1,0,0,0-.38-1.17l-3.68-2.67a1,1,0,0,1,.62-1.9H19.6a1,1,0,0,0,1-.73L22,15.12A1,1,0,0,1,24,15.12Z" ></path></svg>
                                                                <b>4.7</b>
                                                            </td>
                                                            <td  class="col-6">Distace from</td>
                                                        </tr>
                                                        <tr class="row">
                                                            <td  class="col-6">Total Coupouns sold</td>
                                                            <td  class="col-6">One Diet Cost</td>
                                                        </tr>
                                                        <tr class="row">
                                                            <td  class="col-6">Price Rrange</td>
                                                            <td  class="col-6">Shift</td>
                                                        </tr>
                                                        <tr class="row">
                                                            <td  class="col-6">Lunch</td>
                                                            <td class="col-6 ">Break Fast</td>
                                                            <td class="col-12 ">Dinner</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 m-auto card" >
                                        <div class="row card-body" style="background: #ec7a30">
                                            <div class="col-sm-4 p-0 mb-0">
                                                <div class="position-relative text-white"  style="min-height: 200px">
                                                    <span class="badge bg-info position-absolute top-0 start-0" >Veg</span>
                                                    <span class="position-absolute top-0 end-0 ">
                                                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 1792 1792" width="35px" height="35px"><path d="M1420 128q23 0 44 9 33 13 52.5 41t19.5 62v1289q0 34-19.5 62t-52.5 41q-19 8-44 8-48 0-83-32l-441-424-441 424q-36 33-83 33-23 0-44-9-33-13-52.5-41t-19.5-62V240q0-34 19.5-62t52.5-41q21-9 44-9h1048z" fill="#0dcaf0" ></path></svg>
                                                    </span>
                                                    <span  style="position: absolute;top: 3rem;left: 0;">

                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 67 67" width="100px" height="100px"><path fill="#a3d6fb" d="M33.5 2.503c-16.326 0-29.562 13.235-29.562 29.562S17.174 61.628 33.5 61.628s29.563-13.236 29.563-29.563c0-16.327-13.236-29.562-29.563-29.562z"></path><path fill="#d0ecfb" d="M57.125 32.062a23.589 23.589 0 0 1-6.915 16.71c-4.283 4.275-10.186 6.923-16.71 6.923-13.048 0-23.625-10.576-23.625-23.633A23.557 23.557 0 0 1 16.79 15.36c4.283-4.283 10.186-6.923 16.71-6.923s12.427 2.64 16.71 6.923a23.557 23.557 0 0 1 6.915 16.702z" ></path><path d="M58.62 16.489a29.32 29.32 0 0 0-15.57-4.44c-16.33 0-29.57 13.24-29.57 29.56 0 5.72 1.62 11.05 4.43 15.57a29.185 29.185 0 0 1-5.31-4.21c-5.35-5.35-8.66-12.74-8.66-20.9 0-16.33 13.23-29.57 29.56-29.57 8.17 0 15.55 3.31 20.9 8.66 1.6 1.6 3.03 3.39 4.22 5.33z" opacity=".1" fill="#000000"></path><path fill="#6b6dad" d="M54.96 53.379a1 1 0 0 1-.726-1.688 28.387 28.387 0 0 0 7.826-19.622c0-15.775-12.765-28.57-28.56-28.57-15.748 0-28.56 12.816-28.56 28.57 0 7.28 2.748 14.213 7.739 19.525a1 1 0 1 1-1.458 1.37 30.41 30.41 0 0 1-8.28-20.895c0-16.857 13.709-30.57 30.56-30.57 16.882 0 30.559 13.67 30.559 30.57a30.378 30.378 0 0 1-8.374 20.998.999.999 0 0 1-.726.312zm-21.46 9.25a30.6 30.6 0 0 1-10.388-1.81 1 1 0 0 1 .677-1.881c6.157 2.216 13.034 2.24 19.14.097a1 1 0 1 1 .662 1.887 30.387 30.387 0 0 1-10.09 1.707z" ></path><path fill="#c0c7dc" d="M54.97 58.047a2.82 2.82 0 0 1-3.997 3.976L34.037 45.108a1.014 1.014 0 0 0-1.446 0l-1.69 1.69a1.02 1.02 0 0 1-1.436-.01L14.176 31.52a10.12 10.12 0 0 1-2.966-7.133c0-2.212.733-4.413 2.169-6.252.393-.478 1.116-.52 1.541-.096l40.05 40.008z"></path><path d="M17.32 22.069a10.13 10.13 0 0 0-2.17 6.26c.01 2.41.87 4.82 2.59 6.74l-3.56-3.55a10.11 10.11 0 0 1-2.97-7.13c0-2.21.73-4.41 2.17-6.25.39-.48 1.12-.52 1.54-.1l3.94 3.94c-.43-.43-1.15-.38-1.54.09zM54.964 62.03c-1.1 1.1-2.88 1.1-3.99-.01l-3.94-3.94a2.825 2.825 0 0 0 3.99.02c1.1-1.1 1.11-2.86.05-3.95l3.9 3.9a2.821 2.821 0 0 1-.01 3.98z" opacity=".1" fill="#000000" ></path><path fill="#c0c7dc" d="M57.176 28.639 45.634 40.18c-2.635 2.634-6.583 3.176-9.76 1.617L13.99 63.684a2.76 2.76 0 0 1-3.999-.087c-.996-1.112-.844-2.851.21-3.905L31.99 37.9c-.606-1.17-.873-2.447-.873-3.732a8.437 8.437 0 0 1 2.483-6.02l11.542-11.542a1.71 1.71 0 0 1 2.418-.007l.007.007a1.71 1.71 0 0 1-.007 2.418l-10.95 10.95 2.39 2.39 10.95-10.951a1.71 1.71 0 1 1 2.417 2.418l-10.95 10.95 2.39 2.39 10.95-10.95a1.71 1.71 0 1 1 2.418 2.418z"></path><path d="M43.41 23.179c.67-.67.67-1.76.01-2.42l-.01-.01c-.67-.66-1.75-.66-2.42.01l4.15-4.15c.67-.67 1.76-.67 2.42-.01l.01.01c.66.66.66 1.74-.01 2.42l-4.15 4.15zM48.26 27.939c.63-.68.61-1.73-.04-2.38-.67-.66-1.75-.66-2.42.01l4.15-4.16c.67-.67 1.75-.67 2.42 0 .67.67.67 1.75 0 2.42l-4.11 4.11zM57.18 28.639l-4.16 4.15c.67-.67.67-1.75.01-2.42-.65-.65-1.7-.67-2.38-.04l4.11-4.11c.67-.67 1.75-.67 2.42 0 .67.67.67 1.75 0 2.42zM18.144 59.525l-4.15 4.16a2.76 2.76 0 0 1-4-.09c-1-1.11-.85-2.85.21-3.9l4.1-4.1c-1.01 1.06-1.14 2.76-.16 3.85a2.759 2.759 0 0 0 4 .08z" opacity=".1" fill="#000000"></path><path fill="#6b6dad" d="M57.88 29.35a2.725 2.725 0 0 0 .01-3.84c-1.03-1.03-2.81-1.02-3.84 0L43.81 35.76l-.98-.98 10.24-10.24a2.702 2.702 0 0 0 0-3.84 2.702 2.702 0 0 0-3.83.01L39 30.95l-.98-.98 10.25-10.24a2.716 2.716 0 0 0 0-3.84 2.713 2.713 0 0 0-3.83.01L32.89 27.44a9.465 9.465 0 0 0-2.54 4.6L15.63 17.33c-.4-.4-.96-.61-1.53-.58-.58.03-1.12.3-1.51.77-1.53 1.97-2.38 4.4-2.38 6.87.01 2.96 1.17 5.75 3.26 7.84l11.4 11.38L9.49 58.98c-1.47 1.48-1.58 3.8-.24 5.29a3.76 3.76 0 0 0 5.45.12c1.906-1.9-6.9 6.883 18.63-18.58l16.94 16.92c1.47 1.49 3.917 1.495 5.4.01a3.827 3.827 0 0 0 .01-5.4L41.75 43.43c1.7-.39 3.3-1.25 4.59-2.54l11.54-11.54zm-43 1.46a9.075 9.075 0 0 1-2.67-6.42c0-2.05.68-4 2-5.64l15.92 15.9c.05 1.07.27 2.09.66 3.04l-4.51 4.51-11.4-11.39zm39.38 27.94c.71.71.7 1.86-.01 2.58-.7.7-1.86.7-2.57-.01L34.74 44.4c-.01-.01-.02-.02-.03-.02l1.39-1.39c.99.4 2.02.62 3.05.67l15.11 15.09zM36.45 40.96c-.05-.02-.09-.04-.14-.06a.99.99 0 0 0-1.14.19c-5.718 5.72-16.749 16.75-21.89 21.89-.713.694-1.832.71-2.55-.05-.61-.69-.53-1.82.18-2.53l15.38-15.38 1.41-1.41 5-5c.31-.31.38-.78.18-1.17-.01-.03-.03-.05-.04-.08-.48-.95-.72-2.02-.72-3.2 0-.11 0-.23.02-.34.06-1.87.83-3.63 2.17-4.97l11.54-11.54c.29-.29.74-.27 1.01 0 .28.28.27.73-.01 1.01L35.9 29.27a.996.996 0 0 0 0 1.41l2.39 2.39c.19.19.44.29.71.29s.52-.1.71-.29l10.95-10.95c.262-.28.735-.275 1-.01.292.292.27.76 0 1.01L40.71 34.07c-.39.39-.39 1.03 0 1.42l2.39 2.39c.39.39 1.02.39 1.41 0l10.95-10.95c.28-.27.74-.28 1.02 0 .27.27.27.72-.01 1L44.93 39.47c-2.322 2.323-5.759 2.767-8.48 1.49z"></path></svg>
                                                    </span>
                                                    <span class="badge bg-success" style="position: absolute;top: 10rem;left: 0;">REg: 38409RYRY</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 p-1 card" >
                                                <div class="container card-body" >
                                                    <table class="table table-responsive">
                                                        <tr class="row">
                                                            <td  class="col-12 h-1">ABC MEss</td>
                                                        </tr>
                                                        <tr class="row">
                                                            <td  class="col-12 h-1">Description</td>
                                                        </tr>
                                                        <tr class="row">
                                                            <td  class="col-6"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 46" width="50px" height="50px"><path fill="#fbb03b" d="M23,40a83.45,83.45,0,0,1-11.87-.85,5.08,5.08,0,0,1-4.28-4.28,83.35,83.35,0,0,1,0-23.73,5.08,5.08,0,0,1,4.28-4.28,83.42,83.42,0,0,1,23.73,0,5.08,5.08,0,0,1,4.28,4.28,83.35,83.35,0,0,1,0,23.73,5.08,5.08,0,0,1-4.28,4.28A83.45,83.45,0,0,1,23,40Z" ></path><path fill="#f6ffb8" d="M24,15.12l1.41,4.32a1,1,0,0,0,1,.73h4.55a1,1,0,0,1,.62,1.9l-3.68,2.67a1,1,0,0,0-.38,1.17l1.4,4.32A1,1,0,0,1,27.3,31.4l-3.68-2.67a1,1,0,0,0-1.23,0L18.7,31.4a1,1,0,0,1-1.61-1.17l1.4-4.32a1,1,0,0,0-.38-1.17l-3.68-2.67a1,1,0,0,1,.62-1.9H19.6a1,1,0,0,0,1-.73L22,15.12A1,1,0,0,1,24,15.12Z" ></path></svg>
                                                                <b>4.7</b>
                                                            </td>
                                                            <td  class="col-6">Distace from</td>
                                                        </tr>
                                                        <tr class="row">
                                                            <td  class="col-6">Total Coupouns sold</td>
                                                            <td  class="col-6">One Diet Cost</td>
                                                        </tr>
                                                        <tr class="row">
                                                            <td  class="col-6">Price Rrange</td>
                                                            <td  class="col-6">Shift</td>
                                                        </tr>
                                                        <tr class="row">
                                                            <td  class="col-6">Lunch</td>
                                                            <td class="col-6 ">Break Fast</td>
                                                            <td class="col-12 ">Dinner</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                            <div class="d-flex m-auto justify-content-center " >
                                <button class="btn d-block  d-block text-white" style="font-size:1.4rem ;width:100% ;background: #ec7a30;">Delivery</button>
                                <a href="{{route('user.mess.view2')}}" class="btn d-block  d-block text-white" style="font-size:1.4rem ;width:100% ;background: #ec7a30;">Mess</a>
                                <button class="btn d-block  d-block text-white" style="font-size:1.4rem ;width:100% ;background: #ec7a30;">Restaurant</button>
                            </div>

                    </div>
                </div>
            </div>
        </div> --}}
    </div>


</div>
@endsection

@push('javascript')
<script src="{{asset('assets/vendor/sweetalert/sweetalert.min.js')}}"></script>
<script>
    /**
     * ==============================// Events for Add Addons  start //=========================
    */
    document.querySelectorAll('[data-addons-for]').forEach(element => {
        element.style.cursor = "pointer";
        element.addEventListener('click',async function(){
            try{
                const resp = await fetch("{{route('user.mess.addons')}}?menu_id="+element.dataset.menuId);
                const result =await resp.json();
                /**
                 * ===================// Intializing a object to calculate price //=========================
                */
                const addonObj = new Object({
                        setattrubute: function(key, value) {
                            this[key] = {price : value.price , quantity : value.quantity};
                        },
                        sumUP: function() {
                            let sum = 0;
                            for (let key in this) {
                                if (typeof this[key] === 'object') {
                                    if(typeof this[key].price === 'number' &&  typeof this[key].quantity === 'number'){
                                        sum += (this[key].price * this[key].quantity);
                                    }
                                }
                            }
                            return sum;
                        }
                    });

                if(result.error){
                    throw new Error(result.error);
                }
                /**
                 *=============================// seting this to append addons with selected menu//=================================
                */
                let ElemToappend = `<div class="col-md-12 px-3" data-group="${element.dataset.addonsFor}">
                        <p class="card-text"> Addons
                            <dl class="row pricing">`;
                ;
                if (Array.isArray(result)){
                    ElemToappend += result.map(item => {
                        addonObj.setattrubute(item.name, {price : item.price, quantity : 0, id : item.id})
                        return `<dt class="col-6">${item.name} <i>[${item.price}]</i></dt>
                                    <dd class="col-6 text-right">
                                        <div class="btn-group mr-2 shadow-sm" role="group">
                                            <button type="button" class="btn btn-sm btn-danger" data-name="${item.name}"  data-plus="0">-</button>
                                            <button type="button" class="btn btn-sm btn-light border px-3" data-quantity="true">0</button>
                                            <button type="button" class="btn btn-sm btn-success " data-name="${item.name}" data-plus="1">+</button>
                                        </div>
                                    </dd>`;
                    }).join('\n');
                }else{

                    throw new Error('Failed to Load the data')
                }

                    ElemToappend += `<dt class="col-8">Extra Cost:</dt>
                                    <dd class="col-4 text-right" data-sum="0">${addonObj.sumUP()} ₹</dd>
                                    <dt class="col-8">Did You Confirm ?</dt>
                                    <dd class="col-4 text-right"> <button type="button" class="btn btn-sm btn-success "  data-confirm="0" >I do.</button></dd>

                                </dl>
                            </p>
                        </div>`;
                    let checkElemAlreadyExists = document.querySelector(`[data-group="${element.dataset.addonsFor}"]`);
                    if (!checkElemAlreadyExists) {
                        element.closest('.row').insertAdjacentHTML('beforeend', ElemToappend);
                    }
                    /**
                     * =======================// increasing and decreasing qunatity value //======================
                    */
                    element.closest('.row').querySelectorAll('[data-plus]').forEach(item => {
                        item.addEventListener('click',() =>{
                            if (item.dataset.plus == "0") {
                                if (addonObj[item.dataset.name].quantity > 0) {
                                    addonObj[item.dataset.name].quantity--;
                                }
                            } else {
                                addonObj[item.dataset.name].quantity++;
                            }
                            item.closest('div').querySelector('[data-quantity]').textContent = addonObj[item.dataset.name].quantity;
                            item.closest('div').querySelector('[data-quantity]').dataset.quantity = addonObj[item.dataset.name].quantity;
                            item.closest('.row').querySelector('[data-sum]').dataset.quantity = addonObj.sumUP();
                            item.closest('.row').querySelector('[data-sum]').textContent = addonObj.sumUP() +' ₹';
                        })
                    });
                    /**
                     * Making request to store addons
                    */
                    element.closest('.row').querySelector('[data-confirm]').addEventListener('click',async () =>{
                        const currentELEM = event.target;
                        const res2 = await fetch("{{route('user.mess.addons')}}", {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            body: JSON.stringify({addons : addonObj, menu_id : element.dataset.menuId })
                        });
                        const result2 = await res2.json();
                        if(result2.success){
                            toastr.success(result2.success);
                        }else if(result2.error){
                            toastr.error(result2.error);
                        }
                    })
            }catch(error){
                toastr.error(error.message);
                console.error(error)
            }
        })
    });
    /**
     *======================================// Events for Add Addons  end //==========================
    */
</script>
<script>
    /***
     * ========================// cancelling Diet //==============
     */
     document.querySelectorAll('[data-cancel-for]').forEach(element => {
        element.style.cursor = "pointer";
        element.addEventListener('click', async ()=> {
            const currentELEM = event.target;
            const willCancel = await swal({title: "Are you sure?",icon: "warning",buttons: true,dangerMode: true,})
            if(willCancel){
                const res = await fetch("{{route('user.mess.dietCancel')}}?menu_id="+element.dataset.menuId);
                const result = await res.json();
                if(result.success){
                    toastr.success(result.success);
                }else if(result.error){
                    toastr.error(result.error);
                }
            }else{
                swal("Your Diet ain't Cancelled !");
            }
        })
     })


     /***@argument
      *
      * =================// holding Diet //======================
      */
     document.querySelectorAll('[data-hold-diet]').forEach(element => {
        element.style.cursor = "pointer";
        element.addEventListener('click', async ()=> {
            const currentELEM = event.target;
            const willCancel = await swal({title: "Are you sure?",icon: "warning",buttons: true,dangerMode: true,})
            if(willCancel){
                const res = await fetch("{{route('user.mess.hold-diet')}}");
                const result = await res.json();
                if(result.success){
                    toastr.success(result.success);
                    element.dataset.holdDiet = result.holdDietIndex;
                    element.classList.toggle('btn-outline-danger');
                    element.classList.toggle('btn-outline-success');
                    element.textContent = result.textContent;
                }else if(result.error){
                    toastr.error(result.error);
                }
            }else{
                swal("Your Diet ain't Cancelled !");
            }
        })
     })
</script>

@endpush
