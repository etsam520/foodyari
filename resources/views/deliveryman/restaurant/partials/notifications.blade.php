
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




                <div class="notification-footer">
                    <a href="javascript:void(0)" class="btn btn-link">View All</a>
                </div>
            </div>
        </div>
    </div>


