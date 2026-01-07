
@extends('vendor-views.layouts.dashboard-main')


@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
       <div class="row">
          <div class="col-sm-12">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                    <h1 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <svg fill="#3a57e8"  width="50px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 449.505 449.505" xml:space="preserve" stroke="#784545"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M306.079,223.021c-0.632-7.999-7.672-14.605-15.694-14.728l-53.093-0.814c-3.084-0.047-6.21-2.762-6.689-5.809 l-11.698-74.37c-0.424-2.694-2.936-13.678-16.649-13.678l-66.024,2.875c-8.698,0.378-15.769,4.607-15.769,16.476 c0,0-0.278,165.299-0.616,171.289l-2.31,40.898c-0.309,5.462-2.437,14.303-4.647,19.306l-26.724,60.487 c-1.764,3.991-1.735,8.403,0.08,12.105s5.284,6.428,9.52,7.48l8.897,2.208c1.324,0.329,2.71,0.495,4.118,0.495 c7.182,0,14.052-4.168,17.096-10.372l25.403-51.78c2.806-5.719,6.298-15.412,7.786-21.607l14.334-59.711l34.689,53.049 c2.86,4.374,5.884,12.767,6.471,17.961l6.706,59.392c0.954,8.454,8.654,15.332,17.164,15.332l10.146-0.035 c4.353-0.015,8.311-1.752,11.145-4.893c2.833-3.14,4.158-7.254,3.728-11.585l-7.004-70.612c-0.646-6.512-2.985-16.401-5.325-22.513 l-31.083-81.187l-0.192-17.115l72.241-2.674c4.033-0.149,7.718-1.876,10.376-4.862c2.658-2.985,3.947-6.845,3.629-10.873 L306.079,223.021z M238.43,444.503L238.43,444.503v0.002V444.503z"></path> <path d="M157.338,97.927c5.558,0,11.054-0.948,16.335-2.819c12.327-4.362,22.216-13.264,27.846-25.066 c3.981-8.345,5.483-17.433,4.486-26.398l16.406-1.851c5.717-0.645,11.52-5.205,13.498-10.607l5.495-15.007 c1.173-3.206,0.864-6.45-0.849-8.902c-1.67-2.39-4.484-3.761-7.72-3.761c-0.375,0-0.763,0.018-1.161,0.056l-47.438,4.512 C176.416,2.933,167.116,0,157.333,0c-5.556,0-11.05,0.947-16.333,2.816c-12.326,4.365-22.215,13.268-27.846,25.07 s-6.328,25.089-1.963,37.413C118.102,84.815,136.647,97.927,157.338,97.927z"></path> <path d="M364.605,174.546l-4.72-67.843c-0.561-8.057-7.587-14.611-15.691-14.611l-90.689,0.158 c-4.06,0.007-7.792,1.618-10.509,4.536c-2.716,2.917-4.058,6.754-3.775,10.805l4.72,67.843c0.561,8.057,7.587,14.611,15.664,14.611 l90.716-0.158c4.06-0.007,7.792-1.617,10.509-4.535C363.546,182.434,364.887,178.596,364.605,174.546z M259.604,185.044 L259.604,185.044L259.604,185.044L259.604,185.044z"></path> </g> </g>
                            </svg>
                        </div>
                        <span>
                            {{ __('Deliveryman List') }}
                        </span>
                    </h1>
                   </div>
                </div>
                {{-- @dd($delivery_men) --}}
                <div class="card-body px-0">
                   <div class="table-responsive">
                      <table id="datatable" class="table" role="grid" data-toggle="data-table">
                         <thead>
                            <tr>
                                <th class="text-capitalize">{{ __('messages.sl') }}</th>
                                <th class="text-capitalize w-20p">{{__('messages.name')}}</th>
                                <th class="text-capitalize">{{ __('messages.contact') }}</th>
                                <th class="text-capitalize">{{__('messages.zone')}}</th>
                                <th class="text-capitalize text-center">{{ __('Total Orders') }}</th>
                                <th class="text-capitalize">{{__('messages.availability')}} {{__('messages.status')}}</th>
                                <th class="text-capitalize text-center w-110px">{{__('messages.action')}}</th>
                            </tr>
                         </thead>
                         <tbody>
                            @foreach($delivery_men as $key=>$dm)
                                <tr>
                                    <td>{{$loop->index + 1}}</td>
                                    <td>
                                        <a class="table-rest-info d-flex justify-content-evenly " href="{{route('admin.delivery-man.preview',[$dm['id']])}}">
                                            <img onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'" class="rounded img-50"
                                                    src="{{asset("delivery-man/$dm->image")}}" alt="{{$dm->f_name}} {{$dm->l_name}}">
                                            <div class="info">
                                                <h5 class="text-hover-primary mt-2 mb-0">{{$dm->f_name.' '.$dm->l_name}}</h5>
                                                <span class="d-block text-body">
                                                    <!-- Rating -->
                                                    <span class="rating">
                                                        <svg  width="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Interface / Star"> <path id="Vector" d="M2.33496 10.3368C2.02171 10.0471 2.19187 9.52339 2.61557 9.47316L8.61914 8.76107C8.79182 8.74059 8.94181 8.63215 9.01465 8.47425L11.5469 2.98446C11.7256 2.59703 12.2764 2.59695 12.4551 2.98439L14.9873 8.47413C15.0601 8.63204 15.2092 8.74077 15.3818 8.76124L21.3857 9.47316C21.8094 9.52339 21.9791 10.0472 21.6659 10.3369L17.2278 14.4419C17.1001 14.56 17.0433 14.7357 17.0771 14.9063L18.255 20.8359C18.3382 21.2544 17.8928 21.5787 17.5205 21.3703L12.2451 18.4166C12.0934 18.3317 11.9091 18.3321 11.7573 18.417L6.48144 21.3695C6.10913 21.5779 5.66294 21.2544 5.74609 20.8359L6.92414 14.9066C6.95803 14.7361 6.90134 14.5599 6.77367 14.4419L2.33496 10.3368Z" stroke="#fbff00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>
                                                        3.5
                                                    </span>
                                                    <!-- Rating -->
                                                </span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <a class="deco-none" href="tel:{{$dm['phone']}}">{{$dm['phone']}}</a>
                                    </td>
                                    <td>
                                        @if($dm->zone)
                                        <span>{{$dm->zone->name}}</span>
                                        @else
                                        <span>{{__('messages.zone').' '.__('messages.deleted')}}</span>
                                        @endif
                                        {{--<span class="d-block font-size-sm">{{$banner['image']}}</span>--}}
                                    </td>
                                    <!-- Static Data -->
                                    <td class="text-center">
                                        <div class="pr-3">
                                            {{ $dm->orders ? count($dm->orders):0 }}
                                        </div>
                                    </td>
                                    <!-- Static Data -->
                                    <td>
                                        <div>
                                            <!-- Status -->
                                            {{ __('Currenty Assigned Orders') }} : {{$dm->current_orders}}
                                            <!-- Status -->
                                        </div>
                                        @if($dm->application_status == 'approved')
                                            @if($dm->active)
                                            <div>
                                                {{ __('Active Status') }} : <strong class="text-primary text-capitalize">{{__('messages.online')}}</strong>
                                            </div>
                                            @else
                                            <div>
                                                {{ __('Active Status') }} : <strong class="text-secondary text-capitalize">{{__('messages.offline')}}</strong>
                                            </div>
                                            @endif
                                        @elseif ($dm->application_status == 'denied')
                                            <div>
                                                {{ __('Active Status') }} : <strong class="text-danger text-capitalize">{{__('messages.denied')}}</strong>
                                            </div>
                                        @else
                                            <div>
                                                {{ __('Active Status') }} : <strong class="text-info text-capitalize">{{__('messages.pending')}}</strong>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('vendor.delivery-man.edit',[$dm['id']])}}" title="{{__('messages.edit')}} ">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 </svg>
                                            </a>
                                            <a class="btn btn-sm btn-outline-danger action-btn" href="javascript:"
                                                onclick="form_alert('delivery-man-{{$dm['id']}}','{{__('messages.Want_to_delete_this_item')}}')" title="{{__('messages.delete')}} {{__('messages.vehicle')}}">
                                                    <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                            </a>
                                            <form action="{{route('admin.delivery-man.delete',[$dm['id']])}}" method="post" id="delivery-man-{{$dm['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            {{-- @foreach($vehicles as $key=>$vehicle)
                            <tr>
                             
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