@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
   <div class="row">
       <div class="col-sm-12">
           <div class="card">
               <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                       <h3 class="page-header-title">
                           <img src="{{asset('/public/assets/admin/img/bill.png')}}" alt="" class="w-24 mr-2">
                           {{ __('messages.subcription_package_list') }}
                           <span class="badge badge-soft-dark ml-2 px-2 badge-pill"
                               id="itemCount">{{$total}}</span>
                       </h3>
                   </div>
               </div>
               <div class="card-body p-0">   
                  <div class="row">
                     <div class="col-12">
                        <div class="table-responsive mt-4">
                           <div class="table-responsive datatable-custom">
                              <div class="float-end mb-2">
                                 <form action="javascript:" id="search-form">
                                    @csrf
                                    <!-- Search -->
                                    <div class="input-group input-group d-flex">
                                       <div class="d-flex">
                                          <input id="datatableSearch_" type="search" name="search" class="form-control"
                                          value="{{request()->get('search')}}"
                                          placeholder="{{ __('Ex: search_by_package_name') }}" aria-label="Search" required>
                                          <button type="submit" class="btn btn-soft-secondary">
                                             <i class="fa fa-search"></i>
                                          </button>
                                       </div>
                                       @if(request()->get('search'))
                                       <button type="reset" class="btn btn-soft-primary ml-2"
                                       onclick="location.href = '{{route('admin.subscription.list')}}'">{{__('messages.reset')}}</button>
                                       @endif
                                       <div class="mx-2 button-group">
                                             <a href="{{route('admin.subscription.create')}}" class="btn btn-primary flo">Add
                                                Subscription Plan</a>
                                       </div>
                                       </div>
                                       <!-- End Search -->
                                    </form>
                                 </div>
                           </div>
                           <table id="datatable"
                              class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                              <thead class="thead-light">
                                    <tr>
                                       <th class="">
                                          {{ __('messages.sl') }}
                                       </th>
                                       <th class="table-column-pl-0">{{__('Package Name')}}</th>
                                       <th>{{__('messages.Pricing')}}</th>
                                       <th>{{__('messages.duration') }}</th>
                                       <th>{{__('messages.total_sell')}}</th>
                                       <th class="text-center">{{__('messages.status')}}</th>
                                       <th class="text-center">{{__('messages.actions')}}</th>
                                    </tr>
                              </thead>

                              <tbody id="set-rows">
                                    @include('admin-views.subscription.partials._table')
                              </tbody>
                           </table>
                        </div>
                        @if(count($packages) === 0)
                        <div class="text-center">
                           <img src="{{asset('assets/images/icons/nodata.png')}}" alt="public">

                        </div>
                        @endif
                        <!-- End Table -->
                        <div class="page-area px-4 pb-3">
                           <div class="d-flex align-items-center justify-content-end">
                              <div>
                                    {!! $packages->links() !!}
                                    {{--<nav id="datatablePagination" aria-label="Activity pagination"></nav>--}}
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
