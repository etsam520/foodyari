@extends('user-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
   
    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                   <h4 class="card-title">Subscrition Pakadge List</h4>
                </div>
             </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="user-list-table" class="table " role="grid" data-toggle="data-table">
                        <thead>
                            <tr class="ligth">
                                <th></th>
                                <th>Name</th>
                                <th>Validity(In Days)</th>
                                <th>Price</th>
                                <th>Special Diet</th>
                                <th>Total Diet</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $subscription)
                            <tr>
                                <td class="text-center">{{$loop->index + 1}}</td>
                                <td>
                                    {{Illuminate\Support\Str::title($subscription->title)}}
                                    <br>{!!$subscription->veg ==0?'<span class="badge bg-success">Veg</span>': '<span class="badge bg-dark">Non-Veg</span>'!!}
                                    {!!$subscription->speciality ==0?'<span class="badge bg-success">Normal</span>': '<span class="badge bg-dark">Special</span>'!!}

                                    <br><p>{{$subscription->description}}</p>
                                </td>
                                <td>{{Illuminate\Support\Str::title($subscription->validity)}}</td>
                             
                                <td>
                                    <strike style="color: #E21B1B">{{App\CentralLogics\Helpers::format_currency($subscription->price) }}</strike><br>
                                    @if ($subscription->discount_type == 'percent')
                                        {{App\CentralLogics\Helpers::format_currency(App\CentralLogics\Helpers::percent_discount($subscription->price,$subscription->discount) )}}
                                    @else
                                    {{App\CentralLogics\Helpers::format_currency(App\CentralLogics\Helpers::flat_discount($subscription->price,$subscription->discount) )}}
                                    @endif
                                </td>
                                @php 
                                $subs = json_decode($subscription->diets, true) ;$dietCount = 0;

                                if ($subs && is_array($subs)) {
                                    foreach ($subs as $key => $value) {
                                        $dietCount += $value;
                                    }
                                }
                                @endphp
                                <td>{{$subs['special']}}</td>
                                <td>{{$dietCount}}</td>
                                <td>
                                    <a class=" " data-bs-toggle="tooltip" href="javascript:void(0)">
                                        <svg version="1.1" width="70px" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#7a7a7a" stroke="#7a7a7a"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path style="fill:#E21B1B;" d="M0,364.952h419.136L512,146.872H0V364.952z"></path> <rect y="146.84" width="30.712" height="218.32"></rect> <g> <path style="fill:#FFFFFF;" d="M127.408,207.368c8.68-1.592,17.496-2.392,26.32-2.4c8.6-0.56,17.192,1.288,24.8,5.352 c7.072,3.344,11.592,10.464,11.6,18.288c-0.432,11.648-8.792,21.488-20.224,23.792v0.448c9.696,2.728,16.24,11.784,15.768,21.848 c-0.096,9.776-5.28,18.792-13.68,23.792c-11.568,6.208-24.672,8.984-37.768,8c-8.464,0.176-16.92-0.424-25.272-1.784 L127.408,207.368z M132.768,289.448c2.712,0.312,5.44,0.464,8.168,0.448c11.6,0,22.16-4.608,22.16-15.768 c0-9.96-8.328-12.928-18.288-12.928h-6.688L132.768,289.448z M141.384,245.584h7.144c11.744,0,19.776-5.056,19.776-13.528 c0-7.28-5.944-10.4-14.128-10.4c-2.792-0.08-5.592,0.176-8.328,0.744L141.384,245.584z"></path> <path style="fill:#FFFFFF;" d="M234.176,205.728L223.2,263.416c-0.76,3.92-1.112,7.904-1.048,11.896 c-0.496,7.4,5.096,13.8,12.496,14.296c0.696,0.048,1.392,0.04,2.08-0.024c11.152,0,18.576-7.44,22.152-26.024l11.008-57.832h21.408 l-10.848,56.944c-5.808,30.328-19.2,44.896-46.4,44.896c-20.664,0-33.304-10.552-33.304-32c0.08-4.696,0.576-9.368,1.488-13.976 l10.56-55.904L234.176,205.728z"></path> <path style="fill:#FFFFFF;" d="M311.048,305.944l7.728-40.888l-19.48-59.32h22.896l6.4,25.424 c1.792,8.328,2.832,13.088,3.864,18.144h0.296c2.976-5.352,5.952-11,10.256-18.288l15.024-25.272h25.6l-43.264,58.872l-7.88,41.336 L311.048,305.944z"></path> </g> <polygon style="fill:#F91E1E;" points="116.48,147.088 139.416,170.032 402.424,170.032 427.288,194.88 492.272,194.88 512,146.84 "></polygon> </g></svg>
                                    </a>
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
@endsection

@push('javascript')
    
@endpush
