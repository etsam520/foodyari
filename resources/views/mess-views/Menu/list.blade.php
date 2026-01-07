@extends('mess-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
   
    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                   <h4 class="card-title">Menu List</h4>
                </div>
             </div>
            <div class="card-body">
                <div class="bd-example">
                    <ul class="nav nav-pills" data-toggle="slider-tab" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#break-fast" type="button" role="tab" aria-controls="home" aria-selected="true">Break Fast</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#Lunch" type="button" role="tab" aria-controls="profile" aria-selected="false">Lunch</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#Dinner" type="button" role="tab" aria-controls="contact" aria-selected="false">Dinner</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="break-fast" role="tabpanel"
                            aria-labelledby="break-fast-tab1">
                            <div class="table-responsive">
                                <table id="user-list-table" class="table" role="grid" data-toggle="data-table">
                                   <thead>
                                      <tr class="ligth">
                                         <th></th>
                                         <td>Date</td>
                                         <th>Name</th>
                                         <th>Description</th>
                                         <th>Type</th>
                                         <th>Addons</th>
                                         <th>Status</th>
                                         <th style="min-width: 100px">Action</th>
                                      </tr>
                                   </thead>
                                   <tbody>
                                        @foreach($breakfast as $menu)
                                        <tr>
                                            <td class="text-center"><img class="bg-soft-primary rounded img-fluid avatar-40 me-3" src="{{asset("MessMenu/$menu->image")}}" alt="profile"></td>
                                            <td>{{App\CentralLogics\Helpers::format_date($menu->created_at)}}</td>
                                            <td>{{$menu->name}}</td>
                                            <td>{{$menu->description}}</td>
                                            <td>{!!$menu->veg ==0?'<span class="badge bg-success">Veg</span>': '<span class="badge bg-dark">Non-Veg</span>'!!}</td>

                                            <td>
                                            @if($menu->addons && ($addons = json_decode($menu->addons, true)) && is_array($addons))
                                                @php($messAddons = App\Models\MessAddonModel::whereIn('id', $addons)->get())
                                                @foreach ($messAddons as $addonItem)
                                                {!!$menu->veg ==0?'<span class="badge bg-success">'.$addonItem->name.'[ @ '. App\CentralLogics\Helpers::format_currency($addonItem->price).']</span>': '<span class="badge bg-dark">'.$addonItem->name.'[ @ '. App\CentralLogics\Helpers::format_currency($addonItem->price).']</span>'!!}
                                                @endforeach
                                            @else
                                                <span class="badge bg-info">NA</span>
                                            @endif

                                            </td>
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
                                        @endforeach
                                   </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Lunch" role="tabpanel"
                            aria-labelledby="Lunch-tab1">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table id="user-list-table" class="table " role="grid" data-toggle="data-table">
                                        <thead>
                                            <tr class="ligth">
                                               <th></th>
                                               <td>Date</td>
                                               <th>Name</th>
                                               <th>Description</th>
                                               <th>Type</th>
                                               <th>Addons</th>
                                               <th>Status</th>
                                               <th style="min-width: 100px">Action</th>
                                            </tr>
                                         </thead>
                                         <tbody>
                                              @foreach($lunch as $menu)
                                              <tr>
                                                  <td class="text-center"><img class="bg-soft-primary rounded img-fluid avatar-40 me-3" src="{{asset("MessMenu/$menu->image")}}" alt="profile"></td>
                                                  <td>{{App\CentralLogics\Helpers::format_date($menu->created_at)}}</td>
                                                  <td>{{$menu->name}}</td>
                                                  <td>{{$menu->description}}</td>
                                                  <td>{!!$menu->veg ==0?'<span class="badge bg-success">Veg</span>': '<span class="badge bg-dark">Non-Veg</span>'!!}</td>
      
                                                  <td>
                                                  @if($menu->addons && ($addons = json_decode($menu->addons, true)) && is_array($addons))
                                                      @php($messAddons = App\Models\MessAddonModel::whereIn('id', $addons)->get())
                                                      @foreach ($messAddons as $addonItem)
                                                      {!!$menu->veg ==0?'<span class="badge bg-success">'.$addonItem->name.'[ @ '. App\CentralLogics\Helpers::format_currency($addonItem->price).']</span>': '<span class="badge bg-dark">'.$addonItem->name.'[ @ '. App\CentralLogics\Helpers::format_currency($addonItem->price).']</span>'!!}
                                                      @endforeach
                                                  @else
                                                      <span class="badge bg-info">NA</span>
                                                  @endif
      
                                                  </td>
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
                                    <table id="user-list-table" class="table " role="grid" data-toggle="data-table">
                                        <thead>
                                            <tr class="ligth">
                                               <th></th>
                                               <td>Date</td>
                                               <th>Name</th>
                                               <th>Description</th>
                                               <th>Type</th>
                                               <th>Addons</th>
                                               <th>Status</th>
                                               <th style="min-width: 100px">Action</th>
                                            </tr>
                                         </thead>
                                         <tbody>
                                              @foreach($dinner as $menu)
                                              <tr>
                                                  <td class="text-center"><img class="bg-soft-primary rounded img-fluid avatar-40 me-3" src="{{asset("MessMenu/$menu->image")}}" alt="profile"></td>
                                                  <td>{{App\CentralLogics\Helpers::format_date($menu->created_at)}}</td>
                                                  <td>{{$menu->name}}</td>
                                                  <td>{{$menu->description}}</td>
                                                  <td>{!!$menu->type =='veg'?'<span class="badge bg-success">Veg</span>': '<span class="badge bg-dark">Non-Veg</span>'!!}</td>
      
                                                  <td>
                                                  @if($menu->addons && ($addons = json_decode($menu->addons, true)) && is_array($addons))
                                                      @php($messAddons = App\Models\MessAddonModel::whereIn('id', $addons)->get())
                                                      @foreach ($messAddons as $addonItem)
                                                      {!!$menu->type ==0?'<span class="badge bg-success">'.$addonItem->name.'[ @ '. App\CentralLogics\Helpers::format_currency($addonItem->price).']</span>': '<span class="badge bg-dark">'.$addonItem->name.'[ @ '. App\CentralLogics\Helpers::format_currency($addonItem->price).']</span>'!!}
                                                      @endforeach
                                                  @else
                                                      <span class="badge bg-info">NA</span>
                                                  @endif
      
                                                  </td>
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
                                              @endforeach
                                         </tbody>
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
