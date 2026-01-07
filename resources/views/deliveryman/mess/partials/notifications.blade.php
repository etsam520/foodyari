
@php
    $notifications = auth('delivery_men')->user()->notifications;
@endphp

    <div class="container mt-5">
        <div class="dropdown ">
            <i class="feather-bell fs-5 text-primary"  id="dropdownMenuLink" data-toggle="dropdown"  aria-expanded="false">Notifications
            </i>
            <div class="dropdown-menu notification" aria-labelledby="dropdownMenuLink">
                <div class="notification-header">
                    Notification
                </div>
                @foreach ($notifications as $notification)    
                @php($data = json_decode($notification) )
                <div class="notification-item">
                    {{-- @dd($notification) --}}
                    <img src="https://via.placeholder.com/40" alt="User">
                    <div class="content">
                        <p>{{$data->data->title}}</p>

                    </div>
                    <div class="time">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            
                <div class="notification-footer">
                    <a href="javascript:void(0)" class="btn btn-link">View All</a>
                </div>
            </div>
        </div>
    </div>

   
