<aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all ">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="{{route('admin.dashboard')}}" class="navbar-brand">
            <!--Logo start-->
            <!--logo End-->

            <!--Logo start-->
                <div class="logo-main">
                    <div class="logo-normal">
                        <img src="{{asset('assets/images/icons/foodyari.logo.jpg')}}" alt="logo" style="width: 50px;border-radius: 50%;">
                    </div>
                    <div class="logo-mini">
                        <img src="{{asset('assets/images/icons/foodyari.logo.jpg')}}" alt="logo" style="width: 50px;border-radius: 50%;">

                    </div>
                </div>
                <!--logo End-->




            {{-- <h4 class="logo-title">Food Yari</h4> --}}
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list">
            <!-- Sidebar Menu Start -->
            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Home</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.dashboard')}}">
                        <i class="icon">
                            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="icon-20">
                                <path opacity="0.4"
                                    d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z"
                                    fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z"
                                    fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.fund.index')}}">
                        <i class="icon">
                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M21.9964 8.37513H17.7618C15.7911 8.37859 14.1947 9.93514 14.1911 11.8566C14.1884 13.7823 15.7867 15.3458 17.7618 15.3484H22V15.6543C22 19.0136 19.9636 21 16.5173 21H7.48356C4.03644 21 2 19.0136 2 15.6543V8.33786C2 4.97862 4.03644 3 7.48356 3H16.5138C19.96 3 21.9964 4.97862 21.9964 8.33786V8.37513ZM6.73956 8.36733H12.3796H12.3831H12.3902C12.8124 8.36559 13.1538 8.03019 13.152 7.61765C13.1502 7.20598 12.8053 6.87318 12.3831 6.87491H6.73956C6.32 6.87664 5.97956 7.20858 5.97778 7.61852C5.976 8.03019 6.31733 8.36559 6.73956 8.36733Z"
                                    fill="currentColor"></path>
                                <path opacity="0.4"
                                    d="M16.0374 12.2966C16.2465 13.2478 17.0805 13.917 18.0326 13.8996H21.2825C21.6787 13.8996 22 13.5715 22 13.166V10.6344C21.9991 10.2297 21.6787 9.90077 21.2825 9.8999H17.9561C16.8731 9.90338 15.9983 10.8024 16 11.9102C16 12.0398 16.0128 12.1695 16.0374 12.2966Z"
                                    fill="currentColor"></path>
                                <circle cx="18" cy="11.8999" r="1" fill="currentColor"></circle>
                            </svg>

                        </i>
                        <span class="item-name">Transactions<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Promotions</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.coupon.add-new')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20">
                                <path
                                    d="M47,21a1,1,0,0,0,1-1V12a3,3,0,0,0-3-3H18a1,1,0,0,0-1,1,2,2,0,0,1-4,0,1,1,0,0,0-1-1H3a3,3,0,0,0-3,3v8a1,1,0,0,0,1,1,3,3,0,0,1,0,6,1,1,0,0,0-1,1v8a3,3,0,0,0,3,3h9a1,1,0,0,0,1-1,2,2,0,0,1,4,0,1,1,0,0,0,1,1H45a3,3,0,0,0,3-3V28a1,1,0,0,0-1-1,3,3,0,0,1,0-6Zm-1,7.9V36a1,1,0,0,1-1,1H18.87a4,4,0,0,0-7.74,0H3a1,1,0,0,1-1-1V28.9a5,5,0,0,0,0-9.8V12a1,1,0,0,1,1-1h8.13a4,4,0,0,0,7.74,0H45a1,1,0,0,1,1,1v7.1A5,5,0,0,0,46,28.9Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M14 17v2a1 1 0 0 0 2 0V17A1 1 0 0 0 14 17zM14 23v2a1 1 0 0 0 2 0V23A1 1 0 0 0 14 23zM14 29v2a1 1 0 0 0 2 0V29A1 1 0 0 0 14 29zM36.29 16.29l-14 14A1 1 0 0 0 23 32c.59 0-.53.94 14.71-14.29A1 1 0 0 0 36.29 16.29zM35 25a4 4 0 1 0 4 4A4 4 0 0 0 35 25zm0 6a2 2 0 1 1 2-2A2 2 0 0 1 35 31zM25 23a4 4 0 1 0-4-4A4 4 0 0 0 25 23zm0-6a2 2 0 1 1-2 2A2 2 0 0 1 25 17z"
                                    fill="currentColor" ></path>
                            </svg>

                        </i>
                        <span class="item-name">Coupons<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.banner.add-new')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 162.481 237.183" viewBox="0 0 162.481 237.183" width="20" >
                                <path
                                    d="M155.101,93.568c-0.294-0.023-0.572-0.054-0.86-0.08v-8.673l-5.16-3.56l5.16-4.188V57.044H115.52
                                c-3.233-3.678-7.599-6.314-14.019-6.314c-0.051,0-0.1,0.007-0.151,0.008c-0.26-0.026-0.524-0.04-0.791-0.04H61.923
                                c-0.267,0-0.531,0.014-0.791,0.04c-0.051-0.001-0.1-0.008-0.152-0.008c-6.421,0-10.787,2.636-14.019,6.314H33.704l-4.399,4.451
                                l-4.617-4.451H8.24v16.938l5.094,5.015L8.24,83.14v10.348c-0.288,0.026-0.567,0.057-0.86,0.08
                                c-4.405,0.343-7.698,4.191-7.355,8.596c0.326,4.194,3.83,7.38,7.967,7.38c0.082,0,0.166-0.007,0.249-0.01v37.176h31.596
                                l5.712-4.379l4.504,4.379h4.143v78.108c0,6.83,5.537,12.363,12.363,12.363c6.828,0,12.365-5.533,12.365-12.363v-78.108h4.635
                                v78.108c0,6.83,5.537,12.363,12.363,12.363c6.828,0,12.365-5.533,12.365-12.363v-78.108H133.3l3.746-4.668l3.535,4.668h13.659
                                v-37.176c0.083,0.003,0.166,0.01,0.249,0.01c4.137,0,7.641-3.187,7.967-7.38C162.799,97.76,159.506,93.911,155.101,93.568z
                                M142.073,143.711l-4.959-6.547l-5.254,6.547h-80.59l-5.49-5.337l-6.962,5.337H11.24V84.566l6.586-5.357l-6.586-6.484V60.044
                                h12.236l5.879,5.667l5.601-5.667H151.24v15.594l-7.16,5.812l7.16,4.94v57.32H142.073z"
                                    fill="currentColor"></path>
                                <circle cx="81.241" cy="22.5" r="22.5" fill="currentColor"></circle>
                            </svg>
                        </i>
                        <span class="item-name">Banner<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.marquee.add-new')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2C10.34 2 9 3.34 9 5V7H7C5.34 7 4 8.34 4 10V12H20V10C20 8.34 18.66 7 17 7H15V5C15 3.34 13.66 2 12 2Z" fill="currentColor"/>
                                <path opacity="0.4" d="M4 14H20V16H4V14Z" fill="currentColor"/>
                            </svg>
                        </i>
                        <span class="item-name">Notice<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <!-- Admin Notifications Menu -->
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-toggle="collapse" href="#sidebar-notifications" role="button" aria-expanded="false" aria-controls="sidebar-notifications">
                        <i class="icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.7695 11.6453C19.039 10.7923 18.7071 10.0531 18.7071 8.79716V8.37013C18.7071 6.73354 18.3304 5.67907 17.5115 4.62459C16.2493 2.98699 14.1244 2 12.0442 2H11.9558C9.91935 2 7.86106 2.94167 6.577 4.5128C5.71333 5.58842 5.29293 6.68822 5.29293 8.37013V8.79716C5.29293 10.0531 4.98284 10.7923 4.23049 11.6453C3.67691 12.2738 3.5 13.0815 3.5 13.9557C3.5 14.8309 3.78723 15.6598 4.36367 16.3336C5.11602 17.1413 6.17846 17.6569 7.26375 17.7466C8.83505 17.9258 10.4063 17.9933 12.0005 17.9933C13.5937 17.9933 15.165 17.8805 16.7372 17.7466C17.8215 17.6569 18.884 17.1413 19.6363 16.3336C20.2118 15.6598 20.5 14.8309 20.5 13.9557C20.5 13.0815 20.3231 12.2738 19.7695 11.6453Z" fill="currentColor"/>
                                <path opacity="0.4" d="M14.0088 19.2283C13.5088 19.1215 10.4627 19.1215 9.96275 19.2283C9.53539 19.327 9.07324 19.5566 9.07324 20.0602C9.09809 20.5406 9.37935 20.9646 9.76895 21.2335L9.76795 21.2345C10.2718 21.6273 10.8632 21.877 11.4824 21.9667C11.8123 22.012 12.1482 22.01 12.4901 21.9667C13.1083 21.877 13.6997 21.6273 14.2036 21.2345L14.2026 21.2335C14.5922 20.9646 14.8734 20.5406 14.8983 20.0602C14.8983 19.5566 14.4361 19.327 14.0088 19.2283Z" fill="currentColor"/>
                            </svg>       
                        </i>
                        <span class="item-name d-flex justify-content-between align-items-center">
                            Notifications
                            <span class="badge rounded-pill bg-danger ms-2" id="sidebar-notification-badge" style="display: none; font-size: 10px;">
                                <span id="sidebar-notification-count">0</span>
                            </span>
                        </span>
                    </a>
                    <ul class="collapse sub-nav" id="sidebar-notifications" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.notification.inbox') }}">
                                <i class="icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </i>
                                <span class="item-name d-flex justify-content-between align-items-center">
                                    Inbox
                                    <span class="badge rounded-pill bg-primary ms-2" id="sidebar-inbox-count" style="display: none; font-size: 9px;">0</span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.notification.add-new') }}">
                                <i class="icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </i>
                                <span class="item-name">Send Notification</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.chat.index')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        </i>
                        <span class="item-name">Chat System</span>
                    </a>
                </li>
                {{-- contact us --}}
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.contact-us.index')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </i>
                        <span class="item-name">Contact Messages</span>
                    </a>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Order Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#orders" role="button"
                        aria-expanded="false" aria-controls="orders">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="icon-20" width="20">
                                <path
                                    d="M57.64423,53.41349l-4.995-34.96809a5.79886,5.79886,0,0,0-5.71139-4.953h-4.6807l-.9398-2.821C38.35577,1.78071,25.64643,1.77149,22.682,10.672L21.7422,13.4924H17.06151a5.79885,5.79885,0,0,0-5.71139,4.953l-4.995,34.96809a5.80619,5.80619,0,0,0,5.71146,6.585l39.86629.00006A5.8063,5.8063,0,0,0,57.64423,53.41349ZM24.57994,11.3047C26.9,4.233,37.10036,4.23455,39.41939,11.30433l.72907,2.18807H23.85093ZM54.77876,56.69822a3.76656,3.76656,0,0,1-2.84592,1.29885H12.06648a3.7913,3.7913,0,0,1-3.72934-4.30022l4.995-34.968a3.787,3.787,0,0,1,3.72941-3.23489c.00391-.0025,24.47393.00177,24.47772,0h5.39859a3.78623,3.78623,0,0,1,3.72941,3.23489l4.995,34.96809A3.765,3.765,0,0,1,54.77876,56.69822Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M37.54686,32.79815,31.3048,39.0412l-2.76774-2.76774A1.00083,1.00083,0,0,0,27.122,37.6886l3.47525,3.47531a1.00637,1.00637,0,0,0,1.41514,0L38.962,34.21329A1.00085,1.00085,0,0,0,37.54686,32.79815Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M31.99966,23.47072A13.5254,13.5254,0,0,0,18.48935,36.981c.74154,17.92334,26.28184,17.91815,27.02062-.00012A13.52537,13.52537,0,0,0,31.99966,23.47072Zm0,25.01909A11.5222,11.5222,0,0,1,20.49088,36.981c.63226-15.26794,22.38769-15.26354,23.01756.00006A11.52218,11.52218,0,0,1,31.99966,48.48981Z"
                                    fill="currentColor" ></path>
                            </svg>
                        </i>
                        <span class="item-name">Orders</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.order.list', 'all')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">All</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.order.list', 'pending')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Pending</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.order.list', 'accepted')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Approved</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.order.list', 'confirmed')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Confirmed</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.order.list', 'processing')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Proessing</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.order.list', 'handover')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Handover</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.order.list', 'delivered')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Delivered</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.order.list', 'canceled')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Canceled</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.order.list', 'scheduled')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> S</i>
                                <span class="item-name">Scheduled</span>
                            </a>
                        </li>
                    </ul>
                </li>

                 <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.refund.index')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="icon-20" width="20">
                                <path
                                    d="M57.64423,53.41349l-4.995-34.96809a5.79886,5.79886,0,0,0-5.71139-4.953h-4.6807l-.9398-2.821C38.35577,1.78071,25.64643,1.77149,22.682,10.672L21.7422,13.4924H17.06151a5.79885,5.79885,0,0,0-5.71139,4.953l-4.995,34.96809a5.80619,5.80619,0,0,0,5.71146,6.585l39.86629.00006A5.8063,5.8063,0,0,0,57.64423,53.41349ZM24.57994,11.3047C26.9,4.233,37.10036,4.23455,39.41939,11.30433l.72907,2.18807H23.85093ZM54.77876,56.69822a3.76656,3.76656,0,0,1-2.84592,1.29885H12.06648a3.7913,3.7913,0,0,1-3.72934-4.30022l4.995-34.968a3.787,3.787,0,0,1,3.72941-3.23489c.00391-.0025,24.47393.00177,24.47772,0h5.39859a3.78623,3.78623,0,0,1,3.72941,3.23489l4.995,34.96809A3.765,3.765,0,0,1,54.77876,56.69822Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M37.54686,32.79815,31.3048,39.0412l-2.76774-2.76774A1.00083,1.00083,0,0,0,27.122,37.6886l3.47525,3.47531a1.00637,1.00637,0,0,0,1.41514,0L38.962,34.21329A1.00085,1.00085,0,0,0,37.54686,32.79815Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M31.99966,23.47072A13.5254,13.5254,0,0,0,18.48935,36.981c.74154,17.92334,26.28184,17.91815,27.02062-.00012A13.52537,13.52537,0,0,0,31.99966,23.47072Zm0,25.01909A11.5222,11.5222,0,0,1,20.49088,36.981c.63226-15.26794,22.38769-15.26354,23.01756.00006A11.52218,11.52218,0,0,1,31.99966,48.48981Z"
                                    fill="currentColor" ></path>
                            </svg>

                        </i>
                        <span class="item-name">Refunds<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Restaurant Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#zones" role="button"
                        aria-expanded="false" aria-controls="zones">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 512 512" version="1.0"
                                viewBox="0 0 512 512" width="25" >
                                <polygon fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-miterlimit="10" stroke-width="20"
                                    points="275.209 317.969 415.659 317.969 377.973 250.506 270.273 250.506"></polygon>
                                <polygon fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-miterlimit="10" stroke-width="20"
                                    points="432.851 349.688 277.46 349.688 285.165 458.242 491.689 458.242"></polygon>
                                <polyline fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-miterlimit="10" stroke-width="20"
                                    points="159.006 250.506 130.211 250.506 92.525 317.969 232.976 317.969 237.911 250.506 221.839 250.506"></polyline>
                                <polygon fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-miterlimit="10" stroke-width="20"
                                    points="230.724 349.688 75.333 349.688 16.496 458.242 223.019 458.242"></polygon>
                                <circle cx="173.864" cy="124.491" r="20.955" fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-miterlimit="10" stroke-width="20"></circle>
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-miterlimit="10" stroke-width="20"
                                    d="M184.527,250.506c0-28.122,18.706-53.389,41.698-81.225c10.051-12.169,16.09-27.774,16.09-44.79
                                c0-38.874-31.514-70.388-70.388-70.389c-38.755,0-70.156,31.202-70.388,69.957c-0.103,17.19,5.956,32.96,16.098,45.234
                                c22.745,27.526,41.416,56.421,41.416,81.214"
                                ></path>
                            </svg>
                        </i>
                        <span class="item-name">Zones</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="zones" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.zone.add')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> C </i>
                                <span class="item-name">Add</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="zones" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.zone.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> C </i>
                                <span class="item-name">List</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="zones" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.zone-delivery-charge.index')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> D </i>
                                <span class="item-name">Delivery Charges</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="zones" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.zone-delivery-charge.environmental-factors')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> E </i>
                                <span class="item-name">Environmental Factors</span>
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#restaurants" role="button"
                        aria-expanded="false" aria-controls="sidebar-user">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"
                                width="20">
                                <path
                                    d="M12 1C6.5 1 2 4.6 2 9c0 .5.5 1 1 1h8v4H8c-.5 0-1 .5-1 1s.5 1 1 1h3v6c0 .5.5 1 1 1s1-.5 1-1v-6h3c.5 0 1-.5 1-1s-.5-1-1-1h-3v-4h8c.5 0 1-.5 1-1C22 4.6 17.5 1 12 1zM4.1 8C4.8 5.2 8 3 12 3c4 0 7.2 2.2 7.9 5H4.1zM22.2 23c-.1 0-.2 0-.2 0-.5 0-.9-.3-1-.8-.2-.7-.3-1.5-.4-2.2H18v2c0 .5-.5 1-1 1s-1-.5-1-1v-3c0-.5.5-1 1-1h3.6c0-2.8.4-5 .5-5.2.1-.5.6-.9 1.2-.8.5.1.9.6.8 1.2 0 0-.9 4.8 0 8.6C23.1 22.3 22.8 22.8 22.2 23zM8 19v3c0 .5-.5 1-1 1s-1-.5-1-1v-2H3.3c-.1.8-.2 1.5-.4 2.2C2.9 22.7 2.5 23 2 23c-.1 0-.2 0-.2 0-.5-.1-.9-.7-.7-1.2C2 18 1 13.2 1 13.2c-.1-.5.2-1.1.8-1.2.6-.1 1.1.2 1.2.8C3 13 3.5 15.2 3.4 18H7C7.5 18 8 18.5 8 19z"
                                    fill="currentColor" ></path>
                            </svg>
                        </i>
                        <span class="item-name">Restaurants</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="restaurants" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('admin.restaurant.add')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name">Add</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('admin.restaurant.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> L </i>
                                <span class="item-name">List</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.owner.list')}}">
                        <i class="icon">
                            <svg  width="20" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"
                                ><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g>
                                <path fill="currentColor" d="M364.032,355.035c-3.862-1.446-8.072-3.436-12.35-5.794l-71.57,98.935l-5.09-64.814h-38.033l-5.091,64.814 l-71.569-98.935c-4.408,2.466-8.656,4.487-12.361,5.794c-37.478,13.193-129.549,51.136-123.607,122.21 C25.787,494.301,119.582,512,256.006,512c136.413,0,230.208-17.699,231.634-34.755 C493.583,406.102,401.273,368.961,364.032,355.035z"></path>
                                <path fill="currentColor" d="M171.501,208.271c5.21,29.516,13.966,55.554,25.494,68.38c0,15.382,0,26.604,0,35.587 c0,0.902-0.158,1.852-0.416,2.833l40.41,19.462v28.545h38.033v-28.545l40.39-19.452c-0.258-0.981-0.416-1.932-0.416-2.843 c0-8.983,0-20.205,0-35.587c11.548-12.826,20.304-38.864,25.514-68.38c12.143-4.338,19.096-11.281,27.762-41.658 c9.231-32.358-13.876-31.258-13.876-31.258c18.69-61.873-5.922-120.022-47.124-115.753c-28.426-49.73-123.627,11.36-153.48,7.102 c0,17.055,7.112,29.842,7.112,29.842c-10.379,19.69-6.378,58.951-3.446,78.809c-1.704-0.03-22.602,0.188-13.728,31.258 C152.405,196.99,159.338,203.934,171.501,208.271z"></path> </g> </g>
                            </svg>

                        </i>
                        <span class="item-name">Owner List<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Messes Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#messes" role="button"
                        aria-expanded="false" aria-controls="messes">
                        <i class="icon">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="20">
                                <g data-name="restaurant (not cutlery)">
                                    <path d="M56 39a1 1 0 0 0-1 1v1a1 1 0 0 0 2 0v-1a1 1 0 0 0-1-1Z" fill="currentColor"
                                    ></path>
                                    <path
                                        d="M60.89 24.55 56 14.76A5 5 0 0 0 51.53 12H46V6a3 3 0 0 0-3-3H21a3 3 0 0 0-3 3v6h-5.53A5 5 0 0 0 8 14.76l-4.89 9.79A1 1 0 0 0 3 25v.83a5.15 5.15 0 0 0 4 5.05V60a1 1 0 0 0 1 1h48a1 1 0 0 0 1-1V45a1 1 0 0 0-2 0v14H41.71a6.25 6.25 0 0 0-5.83-4H33v-7h11a3 3 0 0 0 0-6h-4.28A7.88 7.88 0 0 0 33 36.07v-1.1h1a1 1 0 0 0 0-2h-4a1 1 0 0 0 0 2h1v1.07A8 8 0 0 0 24.27 42H20a3 3 0 0 0 0 6h11v7h-2.88a6.25 6.25 0 0 0-5.83 4H9V30.87a4.92 4.92 0 0 0 2.36-1.17A5.26 5.26 0 0 0 12 29a5 5 0 0 0 8 0 5 5 0 0 0 8 0 5 5 0 0 0 8 0 5 5 0 0 0 8 0 5 5 0 0 0 8 0 5.26 5.26 0 0 0 .64.7A4.92 4.92 0 0 0 55 30.87V36a1 1 0 0 0 2 0v-5.12a5.15 5.15 0 0 0 4-5.05V25a1 1 0 0 0-.11-.45ZM32 38a5.93 5.93 0 0 1 4.24 1.76 5.8 5.8 0 0 1 1.4 2.24H26.36A6 6 0 0 1 32 38Zm-12 8a1 1 0 0 1 0-2h24a1 1 0 0 1 0 2Zm8.12 11h7.76a4.28 4.28 0 0 1 3.62 2h-15a4.28 4.28 0 0 1 3.62-2ZM11 25.77V26a3 3 0 0 1-1 2.22 3 3 0 0 1-2.3.78A3.12 3.12 0 0 1 5 25.83v-.59l4.79-9.58A3 3 0 0 1 12.47 14h1.27Zm8 .14v.05c0 .05 0 0 0 0a3 3 0 0 1-6 .08L15.8 14h2.37a1 1 0 0 1 0 .1l.08.16a3.24 3.24 0 0 0 .17.32l.09.15a3 3 0 0 0 .28.33l.06.07a4.12 4.12 0 0 0 .4.33l.13.08a2.21 2.21 0 0 0 .34.18l.16.07Zm8 .09a3 3 0 0 1-6 0l.91-10H27Zm8 0a3 3 0 0 1-6 0V16h6Zm5 3a3 3 0 0 1-3-3V16h5.09L43 26a3 3 0 0 1-3 3Zm4-16a1 1 0 0 1-1 1H21a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h22a1 1 0 0 1 1 1Zm4 16a3 3 0 0 1-3-3s0 0 0-.05l-.92-10.12.16-.07a2.21 2.21 0 0 0 .34-.18l.13-.08a4.12 4.12 0 0 0 .4-.33l.06-.07a3 3 0 0 0 .28-.33l.09-.15a3.24 3.24 0 0 0 .17-.32s0-.11.08-.16a1 1 0 0 1 0-.1h2.41L51 26.08A3 3 0 0 1 48 29Zm11-3.17A3.12 3.12 0 0 1 56.3 29a3 3 0 0 1-2.3-.78A3 3 0 0 1 53 26s0-.07 0-.11v-.12L50.26 14h1.27a3 3 0 0 1 2.68 1.66L59 25.24Z"
                                        fill="currentColor"></path>
                                </g>
                            </svg>
                        </i>
                        <span class="item-name">Messes</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="messes" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.mess.add')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name">Add</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="messes" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.mess.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> L </i>
                                <span class="item-name">List</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Food Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.category.add')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" width="20">
                                <path
                                    d="M907.9 875.8H116.7c-2.6 0-5.2-.1-7.8-.5 3.5.5 7.1 1 10.6 1.4-4.5-.7-8.8-1.8-12.9-3.5 3.2 1.3 6.4 2.7 9.6 4-4-1.8-7.8-4-11.3-6.6l8.1 6.3c-3.4-2.7-6.5-5.8-9.2-9.2l6.3 8.1c-2.7-3.5-4.9-7.3-6.6-11.3 1.3 3.2 2.7 6.4 4 9.6-1.7-4.2-2.9-8.5-3.5-12.9.5 3.5 1 7.1 1.4 10.6-1-8-.5-16.4-.5-24.4V160.3c0-2.6.2-5.1.5-7.7-.5 3.5-1 7.1-1.4 10.6.7-4.5 1.8-8.8 3.5-12.9-1.3 3.2-2.7 6.4-4 9.6 1.8-4 4-7.8 6.6-11.3l-6.3 8.1c2.7-3.4 5.8-6.5 9.2-9.2l-8.1 6.3c3.5-2.7 7.3-4.9 11.3-6.6-3.2 1.3-6.4 2.7-9.6 4 4.2-1.7 8.5-2.9 12.9-3.5-3.5.5-7.1 1-10.6 1.4 9.9-1.2 20.2-.5 30.1-.5h200.1c11.5 0 23.6-1 35 .3-3.5-.5-7.1-1-10.6-1.4 3 .5 5.8 1.2 8.6 2.3-3.2-1.3-6.4-2.7-9.6-4 2.7 1.2 5.2 2.7 7.6 4.4l-8.1-6.3c2.4 1.9 4.4 4 6.3 6.4l-6.3-8.1c25.4 33.7 50.9 67.4 76.3 101.1 9.9 13.1 19.7 26.1 29.6 39.2.1.1.1.2.2.2 2.1 2.7-11.1-9.2 6.3 8.1 7.6 7.6 17.6 11.5 28.3 11.7H815.5c30.5 0 60.9-.1 91.4 0 2.6 0 5.2.2 7.8.5-3.5-.5-7.1-1-10.6-1.4 4.5.7 8.8 1.8 12.9 3.5-3.2-1.3-6.4-2.7-9.6-4 4 1.8 7.8 4 11.3 6.6l-8.1-6.3c3.4 2.7 6.5 5.8 9.2 9.2l-6.3-8.1c2.7 3.5 4.9 7.3 6.6 11.3-1.3-3.2-2.7-6.4-4-9.6 1.7 4.2 2.9 8.5 3.5 12.9-.5-3.5-1-7.1-1.4-10.6.8 6.7.5 13.6.5 20.4V799c0 21.9.2 43.8 0 65.7 0 2.5-.2 5-.5 7.5.5-3.5 1-7.1 1.4-10.6-.7 4.5-1.8 8.8-3.5 12.9 1.3-3.2 2.7-6.4 4-9.6-1.8 4-4 7.8-6.6 11.3l6.3-8.1c-2.7 3.4-5.8 6.5-9.2 9.2l8.1-6.3c-3.5 2.7-7.3 4.9-11.3 6.6 3.2-1.3 6.4-2.7 9.6-4-4.2 1.7-8.5 2.9-12.9 3.5 3.5-.5 7.1-1 10.6-1.4-2.1 0-4.4.1-6.8.1-10.3.1-20.9 4.4-28.3 11.7-6.9 6.9-12.2 18.3-11.7 28.3 1 21.4 17.6 40.3 40 40 28.4-.4 54.5-13.2 71.8-35.6 12.5-16.1 19-35.3 19.2-55.7.1-5.6 0-11.1 0-16.7V348.8c0-28.7 2.8-59.3-14.2-84.5-8.2-12.2-18.2-22.4-30.9-29.9-13-7.6-26.9-11.1-41.8-12.3-2.7-.2-5.4-.2-8.1-.2H502.7l34.5 19.8c-21-27.8-41.9-55.5-62.9-83.3-12.1-16-24.1-32-36.2-47.9-2.3-3.1-4.6-6.1-6.9-9.2-3.1-4.1-6.4-9-10.3-12.6-7.2-6.6-14-10.9-22.8-14.6-9.3-3.9-18.5-5.9-28.8-6.1-31.1-.5-62.3 0-93.4 0H141.5c-8.5 0-17.1-.1-25.6 0-25.2.3-50.9 10.8-67.5 30-14.9 17.3-23.3 38.1-23.6 61.1-.1 13.8 0 27.7 0 41.6V822.2c0 14.1-.2 28.2 0 42.3.1 7.5 1.1 15.1 2.8 22.4 3.3 14.2 10.6 26.1 19.6 37.2 16 20 42.1 30.8 67.3 31.6 3.4.1 6.8 0 10.2 0h783c20.9 0 41-18.4 40-40-.7-21.5-17.3-39.9-39.8-39.9z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M907.9 875.8H116.7c-2.6 0-5.2-.1-7.8-.5 3.5.5 7.1 1 10.6 1.4-4.5-.7-8.8-1.8-12.9-3.5 3.2 1.3 6.4 2.7 9.6 4-4-1.8-7.8-4-11.3-6.6l8.1 6.3c-3.4-2.7-6.5-5.8-9.2-9.2l6.3 8.1c-2.7-3.5-4.9-7.3-6.6-11.3 1.3 3.2 2.7 6.4 4 9.6-1.7-4.2-2.9-8.5-3.5-12.9.5 3.5 1 7.1 1.4 10.6-.8-6.7-.5-13.6-.5-20.4V379.3c0-21.9-.2-43.8 0-65.7 0-2.5.2-5 .5-7.5-.5 3.5-1 7.1-1.4 10.6.7-4.5 1.8-8.8 3.5-12.9-1.3 3.2-2.7 6.4-4 9.6 1.8-4 4-7.8 6.6-11.3l-6.3 8.1c2.7-3.4 5.8-6.5 9.2-9.2l-8.1 6.3c3.5-2.7 7.3-4.9 11.3-6.6-3.2 1.3-6.4 2.7-9.6 4 4.2-1.7 8.5-2.9 12.9-3.5-3.5.5-7.1 1-10.6 1.4 8.8-1.1 17.9-.5 26.7-.5H906.9c2.6 0 5.2.1 7.8.5-3.5-.5-7.1-1-10.6-1.4 4.5.7 8.8 1.8 12.9 3.5-3.2-1.3-6.4-2.7-9.6-4 4 1.8 7.8 4 11.3 6.6l-8.1-6.3c3.4 2.7 6.5 5.8 9.2 9.2l-6.3-8.1c2.7 3.5 4.9 7.3 6.6 11.3-1.3-3.2-2.7-6.4-4-9.6 1.7 4.2 2.9 8.5 3.5 12.9-.5-3.5-1-7.1-1.4-10.6.8 6.7.5 13.6.5 20.4v472.1c0 21.9.2 43.8 0 65.7 0 2.5-.2 5-.5 7.5.5-3.5 1-7.1 1.4-10.6-.7 4.5-1.8 8.8-3.5 12.9 1.3-3.2 2.7-6.4 4-9.6-1.8 4-4 7.8-6.6 11.3l6.3-8.1c-2.7 3.4-5.8 6.5-9.2 9.2l8.1-6.3c-3.5 2.7-7.3 4.9-11.3 6.6 3.2-1.3 6.4-2.7 9.6-4-4.2 1.7-8.5 2.9-12.9 3.5 3.5-.5 7.1-1 10.6-1.4-2.1.4-4.4.5-6.8.5-10.3.1-20.9 4.4-28.3 11.7-6.9 6.9-12.2 18.3-11.7 28.3 1 21.4 17.6 40.3 40 40 17.5-.2 36-5 50.3-15.3 17.4-12.5 29.4-28.1 36.5-48.3 3.4-9.9 4.3-20.6 4.3-31V313c-.2-16.5-4.5-33.7-13.5-47.7-4.7-7.3-10.3-14.6-17.1-20.3-9.8-8.1-18.2-13-30-17.7-13.8-5.6-28.5-5.4-43-5.4H120.1c-2.3 0-4.7 0-7 .1-39.4 2.1-73.7 27.8-84.8 66.1-4.2 14.4-3.4 29.4-3.4 44.2V840c0 8.2-.1 16.4 0 24.6.2 17.2 4.9 35.6 14.9 49.8 12.2 17.4 27.6 29.1 47.5 36.5 9.8 3.7 20.6 4.7 31 4.7H907.9c20.9 0 41-18.4 40-40-.9-21.4-17.5-39.8-40-39.8z"
                                    fill="currentColor" ></path>
                            </svg>

                        </i>
                        <span class="item-name">Category<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.addon.add')}}">
                        <i class="icon">
                            <svg class="icon-20" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="m 7.015625 0 c -1.109375 0 -2 0.890625 -2 2 v 1 h -3.015625 c -1.109375 0 -2 0.890625 -2 2 v 2 h 1.015625 c 1.105469 0 2 0.890625 2 2 s -0.894531 2 -2 2 h -1.015625 v 2.988281 c 0 1.105469 0.890625 2 2 2 h 3.015625 v -0.988281 c 0 -1.109375 0.890625 -2 2 -2 c 1.105469 0 2 0.890625 2 2 v 0.988281 h 2 c 1.105469 0 2 -0.894531 2 -2 v -2.988281 h 1 c 1.105469 0 2 -0.890625 2 -2 s -0.894531 -2 -2 -2 h -1 v -2 c 0 -1.109375 -0.894531 -2 -2 -2 h -2 v -1 c 0 -1.109375 -0.894531 -2 -2 -2 z m 0 0" fill="currentColor"></path> </g></svg>
                                                    </i>
                        <span class="item-name">Addons<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#food" role="button"
                        aria-expanded="false" aria-controls="food">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.39 511.91" width="20">
                                <g data-name="Layer 2">
                                    <g data-name="food, plate, tray, hand, restaurant, cover, restaurants, food and restaurant">
                                        <path d="M90.23,511.91H10a10,10,0,0,1-10-10V361.36a10,10,0,0,1,10-10H90.23a10,10,0,0,1,10,10V501.91A10,10,0,0,1,90.23,511.91ZM20,491.91H80.23V371.36H20Z" fill="currentColor"></path>
                                        <path d="M252.15,491.83H90.23a10,10,0,0,1-10-10V361.36a10,10,0,0,1,3.59-7.68L144,303.49a10,10,0,0,1,6.41-2.32h90.25a30.08,30.08,0,0,1,0,60.15H200.54a10.08,10.08,0,0,0,0,20.16h34.84a280.3,280.3,0,0,0,103.29-19.64l83.75-33a80.06,80.06,0,0,1,86.05,17.9,10,10,0,0,1-1.5,15.37L365.16,457.37A201.88,201.88,0,0,1,252.15,491.83Zm-151.92-20H252.15A182,182,0,0,0,354,440.77L484.64,353a60,60,0,0,0-54.88-5.58L346,380.44a300.12,300.12,0,0,1-110.63,21H200.54a30.08,30.08,0,0,1,0-60.16h40.11a10.08,10.08,0,0,0,0-20.15H154L100.23,366Z" fill="currentColor"></path>
                                        <path d="M392.55,361.32h-192a10,10,0,0,1,0-20h192a10,10,0,0,1,0,20Z" fill="currentColor"></path>
                                        <path d="M102.7,361c-.31,0-.62,0-.94,0A90.29,90.29,0,0,1,20.06,271a10,10,0,0,1,10-10H481.33a10,10,0,0,1,10,10,90.25,90.25,0,0,1-22.68,59.87c-1.14,1.3-2.41,2.66-3.74,4a87.77,87.77,0,0,1-6.74,6.09,10,10,0,1,1-12.62-15.51,67.86,67.86,0,0,0,5.21-4.72c1-1,2-2.05,2.88-3.07a70.3,70.3,0,0,0,17-36.65H40.75a69.86,69.86,0,0,0,62.88,60A10,10,0,0,1,102.7,361Z" fill="currentColor"></path>
                                        <path d="M461.28 281a10 10 0 01-10-10c0-101.82-79.35-187.58-180.65-195.23a10 10 0 011.5-20A215.73 215.73 0 01471.28 271 10 10 0 01461.28 281zM50.11 281a10 10 0 01-10-10A215.73 215.73 0 01239.26 55.83a10 10 0 011.5 20C139.46 83.43 60.11 169.19 60.11 271A10 10 0 0150.11 281z" fill="currentColor"></path>
                                        <path d="M255.69,80.23a40.12,40.12,0,1,1,40.09-40.11A40.14,40.14,0,0,1,255.69,80.23Zm0-60.23a20.12,20.12,0,1,0,20.09,20.12A20.12,20.12,0,0,0,255.69,20Z" fill="currentColor"></path>
                                    </g>
                                </g>
                            </svg>
                        </i>
                        <span class="item-name">Food</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="food" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.food.add')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> H </i>
                                <span class="item-name"> Add </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.food.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> H </i>
                                <span class="item-name"> List </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.food-availability.index')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> T </i>
                                <span class="item-name"> Availability Times </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.food.reqeusts')}}">
                        <i class="icon">
                            <svg fill="currentColor"  width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.64 483.64" xml:space="preserve" stroke="#7d5454"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M312.4,238.886l-29.9-5.1c-2.9-11.5-7.4-22.4-13.3-32.4l17.7-24.7c3.3-4.6,2.8-10.8-1.2-14.8l-10.5-10.6l-10.5-10.6 c-2.2-2.2-5.2-3.4-8.1-3.4c-2.3,0-4.6,0.7-6.6,2.1l-24.9,17.4c-10.1-6.1-21.2-10.8-33.1-13.7l-4.9-29.7c-0.9-5.6-5.7-9.6-11.3-9.7 l-15-0.1l-15-0.1l0,0c-5.5,0-10.4,4-11.4,9.6l-5.3,30.2c-11.5,2.9-22.4,7.6-32.3,13.4l-24.3-17.8c-2-1.4-4.4-2.2-6.7-2.2 c-2.9,0-5.9,1.1-8.1,3.3l-10.6,10.5l-10.6,10.5c-3.9,3.9-4.5,10.1-1.3,14.8l17.7,25c-5.9,10-10.4,20.8-13.3,32.3l-29.7,4.9 c-5.6,0.9-9.6,5.7-9.7,11.3l-0.1,15l-0.1,15c0,5.6,4,10.5,9.6,11.4l30.2,5.3c2.9,11.5,7.6,22.4,13.4,32.3l-17.6,24.5 c-3.3,4.6-2.8,10.8,1.2,14.8l10.5,10.6l10.5,10.9c2.2,2.2,5.2,3.4,8.1,3.4c2.3,0,4.6-0.7,6.6-2.1l25-17.7 c9.7,5.7,20.4,10.2,31.5,13.1l4.9,30c0.9,5.6,5.7,9.6,11.3,9.7l15,0.1l15,0.1l0,0c5.5,0,10.4-4,11.4-9.6l5.1-29.9 c11.5-2.9,22.4-7.4,32.4-13.3l24.7,17.7c2,1.4,4.4,2.2,6.7,2.2c2.9,0,5.9-1.1,8.1-3.3l10.6-10.5l10.6-10.5 c3.9-3.9,4.5-10.1,1.3-14.8l-17.5-24.9c6-10,10.5-20.8,13.5-32.3l30-4.9c5.6-0.9,9.6-5.7,9.7-11.3l0.1-15l0.1-15 C321.9,244.586,317.9,239.886,312.4,238.886z M182.7,359.186c-7.3,1.7-14.6,2.5-21.8,2.5c-44.2,0-84.2-30.4-94.6-75.4 c-12-52.3,20.6-104.4,72.9-116.4c7.3-1.7,14.6-2.5,21.8-2.5c44.2,0,84.2,30.4,94.6,75.4 C267.6,295.086,234.9,347.186,182.7,359.186z"></path> <path d="M364,229.486l8,1l0,0c2.9,0.3,5.8-1.5,6.6-4.4l4.6-15.5c6.3-0.8,12.3-2.5,18-5l12,10.9c1,0.9,2.2,1.4,3.4,1.6 c1.6,0.2,3.2-0.2,4.5-1.2l6.3-4.9l6.3-4.9c2.3-1.8,3-5.1,1.6-7.7l-7.7-14.3c3.8-4.9,6.9-10.4,9.2-16.3l16.2-0.7 c3-0.1,5.5-2.4,5.8-5.4l1-8l1-8c0.2-2.9-1.6-5.7-4.4-6.5l-15.5-4.6c-0.8-6.3-2.5-12.3-5-18l10.9-12c2-2.2,2.1-5.6,0.3-7.9 l-4.9-6.3l-4.9-6.3c-1-1.3-2.5-2.1-4.1-2.3c-1.2-0.1-2.5,0.1-3.6,0.7l-14.3,7.7c-5-3.9-10.6-7-16.7-9.3l-0.8-16.1 c-0.1-3-2.4-5.5-5.4-5.8l-8-1l-8-1l0,0c-2.9-0.3-5.8,1.5-6.6,4.4l-4.7,15.7c-6.3,0.8-12.3,2.6-18,5.1l-11.8-11 c-1-0.9-2.2-1.4-3.4-1.6c-1.6-0.2-3.2,0.2-4.5,1.2l-6.3,4.9l-6.3,4.9c-2.3,1.8-3,5.1-1.6,7.7l7.8,14.4c-3.7,4.9-6.8,10.4-9.1,16.3 l-16,0.7c-3,0.1-5.5,2.4-5.8,5.4l-1,8l-1,8c-0.4,2.9,1.5,5.8,4.4,6.7l15.7,4.7c0.8,6.3,2.6,12.3,5.1,18l-10.8,11.9 c-2,2.2-2.1,5.6-0.3,7.9l4.9,6.3l4.9,6.3c1,1.3,2.5,2.1,4.1,2.3c1.2,0.1,2.5-0.1,3.6-0.7l14.4-7.8c4.8,3.6,10.2,6.7,15.9,8.9 l0.7,16.2c0.1,3,2.4,5.5,5.4,5.8L364,229.486z M339.4,142.886c0.7-19.4,17-34.5,36.4-33.8s34.5,17,33.8,36.4 c-0.7,19.4-17,34.5-36.4,33.8C353.8,178.586,338.7,162.286,339.4,142.886z"></path> <path d="M483.6,293.386l-0.7-6l-0.7-6c-0.4-2.1-2.2-3.8-4.4-4l-12.1-0.6c-1.7-4.4-4-8.5-6.8-12.2l5.8-10.6c1.1-2,0.6-4.4-1.2-5.8 l-4.7-3.7l-4.7-3.7c-1-0.8-2.2-1.1-3.4-1c-0.9,0.1-1.8,0.5-2.5,1.1l-9,8.1c-4.3-2-8.9-3.3-13.7-3.9l-3.3-11.5 c-0.6-2.2-2.7-3.5-4.9-3.3l-6,0.7l-6,0.7l0,0c-2.2,0.3-3.9,2.1-4,4.3l-0.7,12.2c-4.4,1.7-8.5,4.1-12.1,6.8l-10.5-5.9 c-0.9-0.5-1.8-0.6-2.8-0.5c-1.2,0.1-2.3,0.7-3,1.7l-3.7,4.7l-3.7,4.7c-1.4,1.7-1.3,4.2,0.2,5.9l8.2,9.1c-1.9,4.2-3.1,8.7-3.7,13.4 l-11.5,3.3c-2.2,0.6-3.5,2.7-3.3,4.9l0.7,6l0.7,6c0.3,2.2,2.1,3.9,4.3,4.1l12.2,0.7c1.7,4.4,4.1,8.5,6.8,12.1l-5.8,10.5 c-1.1,2-0.6,4.4,1.2,5.8l4.7,3.7l4.7,3.7c1,0.8,2.2,1.1,3.4,1c0.9-0.1,1.8-0.5,2.5-1.1l9.1-8.2c4.1,1.8,8.6,3.1,13.1,3.7l3.3,11.6 c0.6,2.2,2.7,3.5,4.9,3.3l6-0.7l6-0.7l0,0c2.2-0.3,3.9-2.1,4-4.3l0.6-12.1c4.4-1.7,8.5-4,12.2-6.8l10.6,5.8 c0.9,0.5,1.8,0.6,2.8,0.5c1.2-0.1,2.3-0.7,3-1.7l3.7-4.7l3.7-4.7c1.4-1.7,1.3-4.2-0.2-5.9l-8.1-9c1.9-4.2,3.2-8.7,3.8-13.4 l11.6-3.3C482.5,297.686,483.9,295.586,483.6,293.386z M424.4,320.486c-14.2,2.9-28-6.3-30.9-20.5s6.3-28,20.5-30.9 s28,6.3,30.9,20.5S438.6,317.586,424.4,320.486z"></path> <path d="M216.7,294.486c-17.3-4.4-31.3-14.3-31.3-14.3l-11,34.7l-2.1,6.5v-0.1l-1.8,5.5l-5.8-16.4c14.6-20.4-3.9-19.6-3.9-19.6 s-18.5-0.8-3.9,19.6l-5.8,16.5l-1.8-5.6l-13-41.2c0,0-14.1,9.9-31.3,14.3c-8.6,2.2-11.7,9.6-12.7,16.4 c15.5,21.3,40.5,34.7,68.4,34.7c6.4,0,12.8-0.7,19.1-2.2c20.2-4.6,37.7-16.3,49.7-32.9C228.3,303.786,225.1,296.686,216.7,294.486 z"></path> <path d="M131.8,257.286c1.5,9.7,8.9,22,21.2,26.3c5,1.8,10.5,1.8,15.6,0c12.1-4.3,19.8-16.6,21.3-26.2c1.6-0.1,3.8-2.4,6.1-10.5 c3.1-11.1-0.2-12.8-3-12.5c0.5-1.5,0.9-3.1,1.2-4.6c4.8-28.8-9.4-29.8-9.4-29.8s-2.3-4.5-8.5-7.9c-4.1-2.5-9.9-4.4-17.5-3.7 c-2.5,0.1-4.8,0.6-7,1.3l0,0c-2.8,0.9-5.4,2.3-7.7,3.9c-2.8,1.8-5.5,4-7.9,6.5c-3.8,3.8-7.1,8.8-8.5,15c-1.2,4.6-0.9,9.5,0.1,14.6 l0,0c0.3,1.5,0.7,3,1.2,4.6c-2.8-0.3-6.2,1.4-3,12.5C128,254.786,130.2,257.086,131.8,257.286z"></path> </g> </g> </g></svg>
                        </i>
                        <span class="item-name">Food Requests
                            <span class="badge rounded-pill bg-success item-name"> {{App\Models\PaymentRequest::where('payment_status', 'pending')->count()}}</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#qr-templates" role="button"
                        aria-expanded="false" aria-controls="qr-templates">
                        <i class="icon">
                            <svg fill="currentColor" width="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M3 11V3h8v8H3zM5 5v4h4V5H5zM3 21v-8h8v8H3zM5 15v4h4v-4H5zM13 3v8h8V3h-8zM19 9h-4V5h4v4zM19 13h2v2h-2v-2zM19 15h2v2h-2v-2zM17 13h2v2h-2v-2zM15 13h2v2h-2v-2zM21 17h2v2h-2v-2zM19 17h2v2h-2v-2zM17 17h2v2h-2v-2zM15 17h2v2h-2v-2zM13 17h2v2h-2v-2zM21 19h2v2h-2v-2zM17 19h2v2h-2v-2zM15 19h2v2h-2v-2zM21 21h2v2h-2v-2zM19 21h2v2h-2v-2zM13 19h2v2h-2v-2zM13 21h2v2h-2v-2z"/>
                                </g>
                            </svg>
                        </i>
                        <span class="item-name">QR Templates</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="qr-templates" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('admin.qr-template.index')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> Q </i>
                                <span class="item-name"> All Templates </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('admin.qr-template.create')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> C </i>
                                <span class="item-name"> Create Template </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Subscription Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#subscription" role="button"
                        aria-expanded="false" aria-controls="subscription">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 64 64" width="20">
                                <path
                                    d="M43 22H24a2 2 0 0 0-2 2v2h23v-2a2 2 0 0 0-2-2zM24 43h19a2 2 0 0 0 2-2V28H22v13a2 2 0 0 0 2 2zm16-13h2v2h-2zm0 4h2v2h-2zm-5-4h2v2h-2zm0 4h2v2h-2zm0 4h2v2h-2zm-5-8h2v2h-2zm0 4h2v2h-2zm0 4h2v2h-2zm-5-4h2v2h-2zm0 4h2v2h-2zM54 1a9 9 0 1 0 9 9 9 9 0 0 0-9-9zm-1.061 12.535a1 1 0 0 1-1.414 0L49.4 11.414 50.818 10l1.414 1.414 4.95-4.949L58.6 7.879z"
                                    fill="currentColor"></path>
                                <path
                                    d="M32 58c-.816 0-1.656-.049-2.5-.131V55.4l-6 3.6 6 3.6v-2.725c.843.076 1.682.125 2.5.125a28.01 28.01 0 0 0 26.4-37.335l-1.884.67A26.011 26.011 0 0 1 32 58zm0-52c.808 0 1.635.046 2.5.124V8.6l6-3.6-6-3.6v2.718A29.519 29.519 0 0 0 32 4 28.01 28.01 0 0 0 5.6 41.335l1.884-.67A26.011 26.011 0 0 1 32 6zM10 63a9 9 0 1 0-9-9 9.011 9.011 0 0 0 9 9zm.75-8h-1.5A3.136 3.136 0 0 1 6 52a3.1 3.1 0 0 1 3-2.977V48h2v1.023A3.1 3.1 0 0 1 14 52h-2a1.147 1.147 0 0 0-1.25-1h-1.5a1.025 1.025 0 1 0 0 2h1.5A3.136 3.136 0 0 1 14 56a3.1 3.1 0 0 1-3 2.977V60H9v-1.023A3.1 3.1 0 0 1 6 56h2a1.162 1.162 0 0 0 1.25 1h1.5a1.025 1.025 0 1 0 0-2z"
                                    fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Subscription</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="subscription" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.subscription.create')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> C </i>
                                <span class="item-name"> Add </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.subscription.list',['for'=>'restaurant'])}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> S </i>
                                <span class="item-name">Restaurant Package All </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.subscription.list',['for'=>'mess'])}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> S </i>
                                <span class="item-name">Mess Package All </span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Delivery Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#vehicles" role="button"
                        aria-expanded="false" aria-controls="vehicles">
                        <i class="icon">
                            <svg fill="currentColor" width="20" class="icon-20" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 491.1 491.1" xml:space="preserve" stroke="#8d1616"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g transform="translate(0 -540.36)"> <g> <g> <path d="M401.5,863.31c-12,0-23.4,4.7-32,13.2c-8.6,8.6-13.4,19.8-13.4,31.8s4.7,23.2,13.4,31.8c8.7,8.5,20,13.2,32,13.2 c24.6,0,44.6-20.2,44.6-45S426.1,863.31,401.5,863.31z M401.5,933.31c-13.8,0-25.4-11.4-25.4-25s11.6-25,25.4-25 c13.6,0,24.6,11.2,24.6,25S415.1,933.31,401.5,933.31z"></path> <path d="M413.1,713.41c-1.8-1.7-4.2-2.6-6.7-2.6h-51.3c-5.5,0-10,4.5-10,10v82c0,5.5,4.5,10,10,10h81.4c5.5,0,10-4.5,10-10v-54.9 c0-2.8-1.2-5.5-3.3-7.4L413.1,713.41z M426.5,792.81h-61.4v-62.1h37.4l24,21.6V792.81z"></path> <path d="M157.3,863.31c-12,0-23.4,4.7-32,13.2c-8.6,8.6-13.4,19.8-13.4,31.8s4.7,23.2,13.4,31.8c8.7,8.5,20,13.2,32,13.2 c24.6,0,44.6-20.2,44.6-45S181.9,863.31,157.3,863.31z M157.3,933.31c-13.8,0-25.4-11.4-25.4-25s11.6-25,25.4-25 c13.6,0,24.6,11.2,24.6,25S170.9,933.31,157.3,933.31z"></path> <path d="M90.6,875.61H70.5v-26.6c0-5.5-4.5-10-10-10s-10,4.5-10,10v36.6c0,5.5,4.5,10,10,10h30.1c5.5,0,10-4.5,10-10 S96.1,875.61,90.6,875.61z"></path> <path d="M141.3,821.11c0-5.5-4.5-10-10-10H10c-5.5,0-10,4.5-10,10s4.5,10,10,10h121.3C136.8,831.11,141.3,826.71,141.3,821.11z"></path> <path d="M30.3,785.01l121.3,0.7c5.5,0,10-4.4,10.1-9.9c0.1-5.6-4.4-10.1-9.9-10.1l-121.3-0.7c-0.1,0-0.1,0-0.1,0 c-5.5,0-10,4.4-10,9.9C20.3,780.51,24.8,785.01,30.3,785.01z"></path> <path d="M50.7,739.61H172c5.5,0,10-4.5,10-10s-4.5-10-10-10H50.7c-5.5,0-10,4.5-10,10S45.2,739.61,50.7,739.61z"></path> <path d="M487.4,726.11L487.4,726.11l-71.6-59.3c-1.8-1.5-4-2.3-6.4-2.3h-84.2v-36c0-5.5-4.5-10-10-10H60.5c-5.5,0-10,4.5-10,10 v73.2c0,5.5,4.5,10,10,10s10-4.5,10-10v-63.2h234.8v237.1h-82c-5.5,0-10,4.5-10,10s4.5,10,10,10h122.1c5.5,0,10-4.5,10-10 s-4.5-10-10-10h-20.1v-191.1h80.6l65.2,54l-0.7,136.9H460c-5.5,0-10,4.5-10,10s4.5,10,10,10h20.3c5.5,0,10-4.4,10-9.9l0.8-151.6 C491,730.91,489.7,728.01,487.4,726.11z"></path> </g> </g> </g> </g>
                            </svg>
                        </i>
                        <span class="item-name">Vehicles</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="vehicles" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.vehicle.create')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon">A</i>
                                <span class="item-name"> Add </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.vehicle.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon">A</i>
                                <span class="item-name"> List </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.shift.list')}}">
                        <i class="icon">
                            <svg fill="currentColor" width="20" class="icon-20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 412.879 412.879" xml:space="preserve" stroke="#775555"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M199.982,176.808c-2.065-4.935-7.03-8.097-11.772-10.043l-32.049-14.755l-15.397-12.985l-24.678,24.546l10.333,69.083 c0.037,0.24-0.024,0.486-0.167,0.682l-10.92,14.943c-0.174,0.238-0.451,0.381-0.746,0.381s-0.572-0.143-0.746-0.381 l-10.919-14.943c-0.144-0.195-0.203-0.441-0.168-0.682l10.333-69.083l-24.677-24.547L73.012,152.01l-32.048,14.755 c-4.743,1.946-10.426,6.39-11.773,10.043c0,0-34.834,82.937-15.94,82.937h202.673 C234.816,259.745,199.982,176.808,199.982,176.808z"></path> <path d="M114.585,138.558c32.776,0,57.236-30.539,57.794-81.752C172.734,21.268,155.809,0,114.585,0 C73.359,0,56.431,21.268,56.793,56.806C57.348,108.019,81.807,138.558,114.585,138.558z"></path> <path d="M383.687,329.941c-2.064-4.936-7.03-8.098-11.771-10.043l-32.05-14.756l-15.396-12.984l-24.692,24.562l10.348,69.186 c0.036,0.238-0.023,0.484-0.167,0.682l-10.92,14.943c-0.174,0.238-0.45,0.379-0.746,0.379c-0.295,0-0.571-0.141-0.745-0.379 l-10.918-14.943c-0.145-0.195-0.204-0.441-0.168-0.682l10.348-69.186l-24.692-24.562l-15.397,12.984l-32.048,14.756 c-4.743,1.945-10.207,6.34-11.772,10.043c0,0-34.834,82.938-15.94,82.938h202.674 C418.521,412.877,383.687,329.941,383.687,329.941z"></path> <path d="M240.498,209.939c0.557,51.213,25.015,81.752,57.792,81.752s57.236-30.539,57.795-81.752 c0.355-35.539-16.57-56.807-57.795-56.807C257.064,153.133,240.135,174.401,240.498,209.939z"></path> <path d="M162.375,383.572c-41.489,0-78.795-23.887-96.431-60.586l9.228-1.771c2.342-0.45,4.265-2.112,5.047-4.366 c0.781-2.252,0.302-4.748-1.259-6.554l-27.263-31.512c-1.28-1.479-3.129-2.308-5.045-2.308c-0.418,0-0.839,0.039-1.259,0.119 c-2.34,0.449-4.265,2.114-5.047,4.366l-13.663,39.367c-0.781,2.254-0.302,4.751,1.259,6.554c1.56,1.805,3.966,2.639,6.303,2.188 l11.476-2.202c19.917,46.272,65.62,76.724,116.654,76.724c5.528,0,10.01-4.48,10.01-10.01 C172.384,388.053,167.903,383.572,162.375,383.572z"></path> <path d="M201.348,31.93c41.489,0,78.794,23.886,96.431,60.586l-9.229,1.771c-2.341,0.449-4.266,2.112-5.046,4.365 c-0.781,2.252-0.303,4.75,1.258,6.554l27.264,31.512c1.279,1.48,3.129,2.307,5.045,2.307c0.418,0,0.839-0.038,1.258-0.119 c2.342-0.449,4.266-2.114,5.049-4.366l13.662-39.366c0.781-2.253,0.302-4.751-1.259-6.554c-1.56-1.804-3.966-2.639-6.304-2.188 l-11.475,2.203c-19.918-46.272-65.622-76.724-116.655-76.724c-5.529,0-10.011,4.48-10.011,10.01 C191.337,27.449,195.819,31.93,201.348,31.93z"></path> </g> </g> </g>
                            </svg>

                        </i>
                        <span class="item-name">Shift Setup<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#dlvh-man" role="button"
                        aria-expanded="false" aria-controls="dlvh-man">
                        <i class="icon">
                            <svg fill="currentColor" width="20" class="icon-20"version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 449.505 449.505" xml:space="preserve" stroke="#784545"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M306.079,223.021c-0.632-7.999-7.672-14.605-15.694-14.728l-53.093-0.814c-3.084-0.047-6.21-2.762-6.689-5.809 l-11.698-74.37c-0.424-2.694-2.936-13.678-16.649-13.678l-66.024,2.875c-8.698,0.378-15.769,4.607-15.769,16.476 c0,0-0.278,165.299-0.616,171.289l-2.31,40.898c-0.309,5.462-2.437,14.303-4.647,19.306l-26.724,60.487 c-1.764,3.991-1.735,8.403,0.08,12.105s5.284,6.428,9.52,7.48l8.897,2.208c1.324,0.329,2.71,0.495,4.118,0.495 c7.182,0,14.052-4.168,17.096-10.372l25.403-51.78c2.806-5.719,6.298-15.412,7.786-21.607l14.334-59.711l34.689,53.049 c2.86,4.374,5.884,12.767,6.471,17.961l6.706,59.392c0.954,8.454,8.654,15.332,17.164,15.332l10.146-0.035 c4.353-0.015,8.311-1.752,11.145-4.893c2.833-3.14,4.158-7.254,3.728-11.585l-7.004-70.612c-0.646-6.512-2.985-16.401-5.325-22.513 l-31.083-81.187l-0.192-17.115l72.241-2.674c4.033-0.149,7.718-1.876,10.376-4.862c2.658-2.985,3.947-6.845,3.629-10.873 L306.079,223.021z M238.43,444.503L238.43,444.503v0.002V444.503z"></path> <path d="M157.338,97.927c5.558,0,11.054-0.948,16.335-2.819c12.327-4.362,22.216-13.264,27.846-25.066 c3.981-8.345,5.483-17.433,4.486-26.398l16.406-1.851c5.717-0.645,11.52-5.205,13.498-10.607l5.495-15.007 c1.173-3.206,0.864-6.45-0.849-8.902c-1.67-2.39-4.484-3.761-7.72-3.761c-0.375,0-0.763,0.018-1.161,0.056l-47.438,4.512 C176.416,2.933,167.116,0,157.333,0c-5.556,0-11.05,0.947-16.333,2.816c-12.326,4.365-22.215,13.268-27.846,25.07 s-6.328,25.089-1.963,37.413C118.102,84.815,136.647,97.927,157.338,97.927z"></path> <path d="M364.605,174.546l-4.72-67.843c-0.561-8.057-7.587-14.611-15.691-14.611l-90.689,0.158 c-4.06,0.007-7.792,1.618-10.509,4.536c-2.716,2.917-4.058,6.754-3.775,10.805l4.72,67.843c0.561,8.057,7.587,14.611,15.664,14.611 l90.716-0.158c4.06-0.007,7.792-1.617,10.509-4.535C363.546,182.434,364.887,178.596,364.605,174.546z M259.604,185.044 L259.604,185.044L259.604,185.044L259.604,185.044z"></path> </g> </g>
                            </svg>
                        </i>
                        <span class="item-name">Delivery Man </span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="dlvh-man" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.delivery-man.add')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name"> Add </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.delivery-man.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> L </i>
                                <span class="item-name"> List </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Customer Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.customer.list')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="20px">
                                <circle cx="19.498" cy="24.873" r="1" fill="currentColor" ></circle>
                                <circle cx="24.96" cy="24.873" r="1" fill="currentColor" ></circle>
                                <path
                                    d="M26.424,31h-8.409c-1.439,0-2.72-.966-3.115-2.35l-1.079-3.772c-.281-.987-.089-2.022,.528-2.842,.618-.819,1.562-1.289,2.588-1.289h10.566c1.026,0,1.969,.47,2.587,1.289,.617,.818,.81,1.854,.528,2.841l-1.079,3.774c-.396,1.383-1.677,2.349-3.115,2.349Zm-9.487-8.253c-.393,0-.754,.18-.99,.493s-.311,.71-.202,1.088l1.079,3.772c.15,.53,.641,.899,1.191,.899h8.409c.551,0,1.041-.369,1.191-.898l1.079-3.774c.108-.377,.035-.773-.201-1.087s-.598-.493-.99-.493h-10.566Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M26.167 22.747h-7.896v-3.948c0-2.177 1.771-3.947 3.948-3.947s3.947 1.771 3.947 3.947v3.948zm-5.896-2h3.896v-1.948c0-1.073-.874-1.947-1.947-1.947s-1.948.874-1.948 1.947v1.948zM12 13c-3.309 0-6-2.691-6-6S8.691 1 12 1s6 2.691 6 6-2.691 6-6 6zm0-10c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M3.978,30.919c-1.762-.361-2.978-1.998-2.978-3.797v-4.792c0-5.492,3.889-10.383,9.319-11.202,2.99-.451,5.948,.318,8.286,2.076,.476,.358,.543,1.051,.138,1.489h0c-.345,.373-.917,.429-1.323,.122-1.719-1.299-3.852-1.943-6.046-1.793-4.772,.327-8.374,4.503-8.374,9.286v4.823c0,.882,.601,1.701,1.471,1.845,1.107,.182,2.067-.671,2.067-1.745v-4.483c0-.552,.448-1,1-1h0c.552,0,1,.448,1,1v4.483c0,2.337-2.137,4.184-4.56,3.688Z"
                                    fill="currentColor" ></path>
                                <path d="M12.502,31H4.885v-2h7.617c.552,0,1,.448,1,1h0c0,.552-.448,1-1,1Z" fill="currentColor"></path>
                            </svg>

                        </i>
                        <span class="item-name">Customer List<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.customer.rating')}}">
                        <i class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32">
                                <path
                                    d="M0 0 C0.69291138 -0.0049649 1.38582275 -0.00992981 2.09973145 -0.01504517 C4.39755236 -0.02967753 6.69530416 -0.03647163 8.99316406 -0.04199219 C10.59018964 -0.04774412 12.18721519 -0.05350185 13.78424072 -0.05926514 C17.13676272 -0.06977912 20.48926071 -0.07561873 23.84179688 -0.07910156 C28.1302449 -0.08457773 32.41837405 -0.10858507 36.70672226 -0.13707352 C40.00792351 -0.15573433 43.30904862 -0.16090739 46.61029816 -0.16243744 C48.19069405 -0.1654583 49.77108877 -0.1734677 51.3514328 -0.18662262 C53.56786519 -0.20375061 55.78352758 -0.20178651 58 -0.1953125 C59.88957428 -0.20078094 59.88957428 -0.20078094 61.81732178 -0.20635986 C65.6539649 0.20333116 68.02551582 1.06996216 71.25878906 3.16113281 C73.18381611 5.878837 73.49311021 7.97900805 73.37133789 11.26098633 C73.34979141 12.06083954 73.32824493 12.86069275 73.30604553 13.68478394 C73.26138405 14.97438522 73.26138405 14.97438522 73.21582031 16.29003906 C71.88460655 42.98725639 71.88460655 42.98725639 83.96191406 65.73535156 C84.51943359 66.29915527 85.07695312 66.86295898 85.65136719 67.44384766 C91.92779726 74.14925609 94.58475828 81.89279309 94.35713196 91.04470825 C94.33112411 91.72508148 94.30511627 92.40545471 94.27832031 93.10644531 C94.24813122 94.61163602 94.21886431 96.11684544 94.19042969 97.62207031 C94.13509687 99.945561 94.07191517 102.26805804 93.99145508 104.59082031 C93.67164906 114.42197182 94.24289053 122.06021186 98.11669922 131.23876953 C100.25198113 136.70249858 100.12442028 140.95377322 98.00878906 146.34863281 C88.45283552 156.12176712 67.37239288 163.22468801 53.88378906 164.47363281 C49.44289489 164.09079711 47.61578594 163.0705301 44.25878906 160.16113281 C42.69441436 157.81457077 41.47704557 155.67916485 40.25878906 153.16113281 C39.32436674 151.24296382 38.38657345 149.32643504 37.44628906 147.41113281 C37.00671875 146.48816406 36.56714844 145.56519531 36.11425781 144.61425781 C35.19580078 142.90496094 35.19580078 142.90496094 34.25878906 141.16113281 C33.88197998 140.23349121 33.5051709 139.30584961 33.11694336 138.35009766 C31.47329803 134.94404889 30.08186053 132.69845733 27.25878906 130.16113281 C21.38716728 128.75735684 15.69421431 129.01882935 9.69628906 129.28613281 C7.30818737 129.36046685 4.922057 129.41849359 2.53297424 129.44821167 C1.05307254 129.46980102 -0.42676758 129.51821318 -1.90467834 129.59793091 C-5.64058758 129.68537222 -8.22018533 129.22826393 -11.21264648 127.05493164 C-14.28274717 123.54137526 -14.40657856 122.13251923 -14.19433594 117.54003906 C-14.175 116.27482422 -14.15566406 115.00960937 -14.13574219 113.70605469 C-14.03950735 111.05439481 -13.92756276 108.4032578 -13.79980469 105.75292969 C-13.75774802 101.99650474 -13.78957384 98.79937609 -14.74121094 95.16113281 C-17.16686937 92.88318387 -19.31429471 91.7061781 -22.38476562 90.44995117 C-24.74121094 89.16113281 -24.74121094 89.16113281 -26.0703125 86.72973633 C-27.27942467 82.10052632 -26.39451392 78.38934243 -24.17871094 74.22363281 C-23.70433594 73.54300781 -23.22996094 72.86238281 -22.74121094 72.16113281 C-23.66353516 71.58105469 -23.66353516 71.58105469 -24.60449219 70.98925781 C-25.81685547 70.20808594 -25.81685547 70.20808594 -27.05371094 69.41113281 C-28.25447266 68.64542969 -28.25447266 68.64542969 -29.47949219 67.86425781 C-31.79699752 66.11912426 -33.24547461 64.63978158 -34.74121094 62.16113281 C-35.28496074 56.54238486 -34.89858793 53.00996176 -31.74121094 48.16113281 C-29.55371094 46.28613281 -29.55371094 46.28613281 -27.74121094 45.16113281 C-27.96808594 43.98550781 -28.19496094 42.80988281 -28.42871094 41.59863281 C-28.99098385 37.0683196 -28.01852936 34.1660721 -25.74121094 30.16113281 C-22.39412499 26.44214843 -18.47537196 26.68874954 -13.74121094 26.16113281 C-13.76739502 25.58145752 -13.7935791 25.00178223 -13.82055664 24.40454102 C-13.92491706 21.76118406 -13.9896542 19.11826082 -14.05371094 16.47363281 C-14.09560547 15.56162109 -14.1375 14.64960937 -14.18066406 13.70996094 C-14.30472803 6.88644274 -14.30472803 6.88644274 -11.34667969 3.07910156 C-7.25723293 0.06872921 -5.07887731 0.02759861 0 0 Z M-8.74121094 8.16113281 C-9.33908226 11.20615682 -9.24547487 14.22068718 -9.1953125 17.31420898 C-9.19589661 18.25098984 -9.19648071 19.18777069 -9.19708252 20.15293884 C-9.19429264 23.25370152 -9.1631576 26.35350653 -9.13183594 29.45410156 C-9.12437677 31.60215225 -9.11868231 33.75020973 -9.11468506 35.89826965 C-9.09940325 41.555397 -9.0600992 47.21211437 -9.01586914 52.86907959 C-8.96778255 59.65464556 -8.94939459 66.44034852 -8.92605591 73.22603607 C-8.88725006 83.53802023 -8.81061326 93.84910462 -8.74121094 104.16113281 C11.38878906 104.16113281 31.51878906 104.16113281 52.25878906 104.16113281 C49.65877262 98.85829221 49.65877262 98.85829221 46.26269531 94.50488281 C45.70388672 93.87453125 45.14507812 93.24417969 44.56933594 92.59472656 C43.99248047 91.95664063 43.415625 91.31855469 42.82128906 90.66113281 C30.87951528 77.36264457 30.87951528 77.36264457 31.25878906 68.16113281 C32.25878906 64.78613281 32.25878906 64.78613281 34.25878906 62.16113281 C37.63378906 60.47363281 37.63378906 60.47363281 41.25878906 59.16113281 C45.74618576 56.80524955 49.61394758 53.66942404 53.25878906 50.16113281 C53.25878906 49.83113281 53.25878906 49.50113281 53.25878906 49.16113281 C52.185 49.10699219 51.11121094 49.05285156 50.00488281 48.99707031 C38.99293275 47.98084087 38.99293275 47.98084087 35.81396484 44.64501953 C33.27843725 40.76436772 31.69619351 36.54869398 30.25878906 32.16113281 C28.49723895 34.28782217 27.31317265 35.99798206 26.43457031 38.63378906 C25.39442145 41.75423566 24.48238678 43.70678439 22.25878906 46.16113281 C17.22935553 48.26517183 11.63960627 48.66794393 6.25878906 49.16113281 C7.17001634 50.26979266 8.08805565 51.372855 9.00878906 52.47363281 C9.51925781 53.08851562 10.02972656 53.70339844 10.55566406 54.33691406 C12.17449255 56.27370858 12.17449255 56.27370858 14.47753906 57.31738281 C16.25878906 59.16113281 16.25878906 59.16113281 16.48242188 61.08178711 C16.42772987 63.52254663 16.24556657 65.91884455 16.00878906 68.34863281 C15.91017578 69.61610352 15.91017578 69.61610352 15.80957031 70.90917969 C15.64461217 72.99472191 15.45412881 75.07821661 15.25878906 77.16113281 C16.17917969 76.84402344 17.09957031 76.52691406 18.04785156 76.20019531 C21.1253743 75.20430596 24.06220153 74.61778817 27.25878906 74.16113281 C27.25878906 75.15113281 27.25878906 76.14113281 27.25878906 77.16113281 C16.88521275 82.90145172 16.88521275 82.90145172 11.25878906 82.16113281 C9.94204485 77.65149903 10.78435176 74.43087746 11.82128906 69.91113281 C12.33990287 67.12354129 12.39858624 65.06648292 12.25878906 62.16113281 C10.00529399 59.162185 7.64404927 57.30528168 4.62207031 55.10253906 C2.12150154 53.04835281 1.50283362 52.24489406 1.13378906 49.09863281 C1.17503906 48.12925781 1.21628906 47.15988281 1.25878906 46.16113281 C2.37253906 45.87238281 3.48628906 45.58363281 4.63378906 45.28613281 C5.72626953 44.98835938 5.72626953 44.98835938 6.84082031 44.68457031 C9.25878906 44.16113281 9.25878906 44.16113281 11.77050781 44.07519531 C14.75769831 43.88306361 16.61334673 43.60528412 19.25878906 42.16113281 C21.06180216 39.18869556 22.47898249 36.38360656 23.66333008 33.12695312 C24.39550781 31.25097656 24.39550781 31.25097656 26.25878906 28.16113281 C29.96582031 27.22753906 29.96582031 27.22753906 33.25878906 27.16113281 C34.29247788 29.66895178 35.24691148 32.11911427 35.98535156 34.73144531 C37.07118962 37.81900222 38.00704325 39.75423878 40.25878906 42.16113281 C44.25270678 43.77548385 48.48794349 44.18612009 52.73925781 44.66113281 C56.25878906 45.16113281 56.25878906 45.16113281 58.25878906 47.16113281 C58.32128906 49.91113281 58.32128906 49.91113281 57.25878906 53.16113281 C55.52861966 54.91613718 53.72701835 56.38813222 51.75488281 57.86425781 C51.26117188 58.29222656 50.76746094 58.72019531 50.25878906 59.16113281 C50.25878906 59.82113281 50.25878906 60.48113281 50.25878906 61.16113281 C51.89298676 62.49981556 53.59383177 63.75767027 55.32128906 64.97363281 C56.26101562 65.64007813 57.20074219 66.30652344 58.16894531 66.99316406 C59.18859375 67.70859375 60.20824219 68.42402344 61.25878906 69.16113281 C62.49065406 70.03847271 63.7216804 70.91699125 64.9519043 71.79663086 C65.88461403 72.46338081 65.88461403 72.46338081 66.83616638 73.14360046 C67.30563187 73.47938614 67.77509735 73.81517181 68.25878906 74.16113281 C68.30510659 65.73801161 68.34065524 57.31494352 68.36241341 48.89172649 C68.37285564 44.9805877 68.38702278 41.06953596 68.40966797 37.15844727 C68.43138079 33.38482112 68.44335438 29.61127877 68.44854355 25.83759499 C68.45224212 24.39706922 68.45946443 22.95654811 68.47024918 21.51605797 C68.48474253 19.50025267 68.4856058 17.48436325 68.48583984 15.46850586 C68.49028107 14.32044968 68.49472229 13.17239349 68.4992981 11.98954773 C68.47030495 8.95601449 68.47030495 8.95601449 66.25878906 6.16113281 C63.60449082 5.6476461 63.60449082 5.6476461 60.43457031 5.64941406 C59.19610352 5.61364258 57.95763672 5.57787109 56.68164062 5.54101562 C55.31153865 5.51764519 53.94142045 5.49521032 52.57128906 5.47363281 C51.16079438 5.44263925 49.75031264 5.41105208 48.33984375 5.37890625 C44.48139917 5.29772153 40.62296018 5.24197566 36.76397705 5.19366455 C34.17215262 5.16000777 31.58048681 5.11965549 28.98876953 5.07861328 C24.53338618 5.01097693 20.0781838 4.9639974 15.62240601 4.93197632 C13.88328957 4.91810692 12.14420298 4.89975228 10.40518188 4.87686157 C7.95515979 4.84713036 5.50584605 4.83735852 3.05566406 4.83300781 C2.32472031 4.8207164 1.59377655 4.80842499 0.84068298 4.79576111 C-3.37319392 4.81406042 -5.90678457 4.94762034 -8.74121094 8.16113281 Z M-21.61621094 33.78613281 C-23.05535618 36.82432833 -23.16575634 38.83552713 -22.74121094 42.16113281 C-20.48673522 45.5428464 -17.57080821 46.24633418 -13.74121094 48.16113281 C-13.74121094 42.88113281 -13.74121094 37.60113281 -13.74121094 32.16113281 C-19.39905171 31.50620227 -19.39905171 31.50620227 -21.61621094 33.78613281 Z M-26.92871094 51.22363281 C-29.60870745 54.08845667 -29.74121094 56.3004772 -29.74121094 60.16113281 C-26.89310151 63.73509537 -23.56987039 65.4604696 -19.49121094 67.41113281 C-17.59371094 68.31863281 -15.69621094 69.22613281 -13.74121094 70.16113281 C-13.74121094 64.88113281 -13.74121094 59.60113281 -13.74121094 54.16113281 C-15.34996094 53.17113281 -15.34996094 53.17113281 -16.99121094 52.16113281 C-17.89613281 51.60425781 -17.89613281 51.60425781 -18.81933594 51.03613281 C-21.7806126 49.68790929 -24.03379285 49.81752974 -26.92871094 51.22363281 Z M73.25878906 59.16113281 C73.11267593 62.53567179 73.02445439 65.90939047 72.94628906 69.28613281 C72.88344727 70.713125 72.88344727 70.713125 72.81933594 72.16894531 C72.68025496 78.41263124 72.68025496 78.41263124 75.71582031 83.65332031 C76.555 84.48089844 77.39417969 85.30847656 78.25878906 86.16113281 C78.75378906 87.64613281 78.75378906 87.64613281 79.25878906 89.16113281 C76.44738177 88.81804582 74.72510884 88.59844706 72.62597656 86.62988281 C72.13355469 85.98019531 71.64113281 85.33050781 71.13378906 84.66113281 C64.73137391 76.87780458 55.36022416 68.71185036 46.25878906 64.16113281 C41.59833148 63.70843774 41.59833148 63.70843774 37.38378906 65.34863281 C35.89061968 67.7542946 35.39128646 69.31243866 35.25878906 72.16113281 C37.1778831 76.71427749 39.61332861 79.91472628 43.00878906 83.47363281 C56.54210843 97.99767834 56.54210843 97.99767834 56.49707031 108.35644531 C56.24818103 111.28599453 55.5842651 113.73840011 54.69628906 116.53613281 C52.84549264 122.58149121 53.36221461 127.97152849 54.25878906 134.16113281 C52.27878906 134.65613281 52.27878906 134.65613281 50.25878906 135.16113281 C49.92878906 133.18113281 49.59878906 131.20113281 49.25878906 129.16113281 C43.97878906 129.16113281 38.69878906 129.16113281 33.25878906 129.16113281 C34.37253906 130.91425781 35.48628906 132.66738281 36.63378906 134.47363281 C37.57351562 135.95283203 37.57351562 135.95283203 38.53222656 137.46191406 C39.7553052 139.37401211 40.99972466 141.27253621 42.25878906 143.16113281 C45.5715496 142.56199739 48.62262584 141.75934145 51.77368164 140.57885742 C52.63419632 140.25860092 53.494711 139.93834442 54.38130188 139.60838318 C55.29606796 139.263564 56.21083405 138.91874481 57.15332031 138.56347656 C58.10067551 138.20975479 59.0480307 137.85603302 60.02409363 137.49159241 C63.04116712 136.36415485 66.05623901 135.23146898 69.07128906 134.09863281 C71.1199873 133.33200958 73.168814 132.56572953 75.21777344 131.79980469 C80.23294115 129.92422299 85.24647697 128.04433164 90.25878906 126.16113281 C89.92556641 125.18007568 89.59234375 124.19901855 89.24902344 123.18823242 C88.04152402 118.9430258 88.17035065 115.09909854 88.41503906 110.72753906 C88.46962681 109.091553 88.52162385 107.45547884 88.57128906 105.81933594 C88.65699744 103.2833907 88.75713964 100.7522163 88.90332031 98.21899414 C89.61654895 85.25224282 87.40385796 77.22961412 79.5144043 66.84103394 C78.26720601 65.17239394 77.12605344 63.4630691 75.99511719 61.71411133 C75.42212891 60.87162842 74.84914062 60.02914551 74.25878906 59.16113281 C73.92878906 59.16113281 73.59878906 59.16113281 73.25878906 59.16113281 Z M-16.74121094 75.16113281 C-18.86619163 76.78393589 -18.86619163 76.78393589 -20.74121094 79.16113281 C-21.07569353 82.32896481 -21.07569353 82.32896481 -20.74121094 85.16113281 C-17.27621094 87.14113281 -17.27621094 87.14113281 -13.74121094 89.16113281 C-13.74121094 84.87113281 -13.74121094 80.58113281 -13.74121094 76.16113281 C-14.73121094 75.83113281 -15.72121094 75.50113281 -16.74121094 75.16113281 Z M-8.74121094 109.16113281 C-8.74121094 113.45113281 -8.74121094 117.74113281 -8.74121094 122.16113281 C-5.39504202 124.39191209 -4.34527135 124.40637319 -0.44360352 124.38818359 C0.6372905 124.38803253 1.71818451 124.38788147 2.83183289 124.38772583 C3.99753311 124.37740326 5.16323334 124.36708069 6.36425781 124.35644531 C7.5585994 124.35361542 8.75294098 124.35078552 9.98347473 124.34786987 C13.80451403 124.33665958 17.6253204 124.31155318 21.44628906 124.28613281 C24.03417631 124.27610412 26.62206722 124.26697808 29.20996094 124.25878906 C35.55965002 124.23671746 41.9092029 124.20323142 48.25878906 124.16113281 C48.763146 122.22476241 49.2621278 120.28699135 49.75878906 118.34863281 C50.03722656 117.2696875 50.31566406 116.19074219 50.60253906 115.07910156 C51.36849854 112.17179306 51.36849854 112.17179306 51.25878906 109.16113281 C31.45878906 109.16113281 11.65878906 109.16113281 -8.74121094 109.16113281 Z M84.78735352 132.72167969 C83.88664627 133.06203247 82.98593903 133.40238525 82.05793762 133.75305176 C81.08910141 134.12490601 80.1202652 134.49676025 79.12207031 134.87988281 C78.12749313 135.25721558 77.13291595 135.63454834 76.10820007 136.02331543 C72.9274443 137.2312855 69.74936104 138.44612167 66.57128906 139.66113281 C64.41716448 140.48059629 62.26286966 141.2996124 60.10839844 142.11816406 C54.82320783 144.12729721 49.540196 146.14207601 44.25878906 148.16113281 C44.71125 149.26328125 45.16371094 150.36542969 45.62988281 151.50097656 C45.88439209 152.12093506 46.13890137 152.74089355 46.40112305 153.37963867 C47.87956736 156.45057752 49.1946528 157.66166188 52.25878906 159.16113281 C60.3453722 158.65436835 68.11648171 155.1934653 75.57128906 152.28613281 C76.72435547 151.85171875 77.87742188 151.41730469 79.06542969 150.96972656 C80.17724609 150.54046875 81.2890625 150.11121094 82.43457031 149.66894531 C83.43399658 149.28367676 84.43342285 148.8984082 85.46313477 148.50146484 C88.50525645 147.04296794 90.7987124 145.46822439 93.25878906 143.16113281 C94.53542864 138.48012104 94.48453573 135.48169988 92.25878906 131.16113281 C89.21440318 131.16113281 87.59644001 131.6515515 84.78735352 132.72167969 Z "
                                    fill="currentColor" transform="translate(67.7412109375,17.8388671875)" />
                            </svg>

                        </i>
                        <span class="item-name">Rating<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{route('admin.reviews.grouped-list-optimized')}}">
                        <i class="icon">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                <path fill="currentColor" d="M16 2c7.732 0 14 6.268 14 14s-6.268 14-14 14-14-6.268-14-14 6.268-14 14-14zM16 4c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zM16 6c5.523 0 10 4.477 10 10s-4.477 10-10 10-10-4.477-10-10 4.477-10 10-10zM15.5 8.5l-1.5 3-3 1.5 3 1.5 1.5 3 1.5-3 3-1.5-3-1.5-1.5-3z"/>
                            </svg>
                        </i>
                        <span class="item-name">Grouped Reviews<span
                                class="badge rounded-pill bg-info item-name"></span></span>
                    </a>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Employee Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.roles.add')}}">
                        <i class="icon">
                            <svg class="icon-20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 31.192 31.192" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path style="fill:currentColor;" d="M10.898,10.787c0.314,2.613,2.564,5.318,4.604,5.318c2.341,0,4.571-2.845,4.919-5.319 c0.129-0.095,0.348-0.322,0.428-0.838c0,0,0.502-1.792-0.164-1.6c0.232-0.69,1-3.379-0.485-5.052c0,0-0.697-0.947-2.386-1.452 c-0.059-0.051-0.12-0.1-0.189-0.148c0,0,0.037,0.043,0.094,0.12c-0.098-0.027-0.195-0.052-0.301-0.077 c-0.092-0.096-0.193-0.194-0.311-0.301c0,0,0.104,0.106,0.225,0.282c-0.044-0.01-0.086-0.02-0.134-0.03 c-0.076-0.118-0.17-0.237-0.288-0.358c0,0,0.049,0.091,0.117,0.24c-0.312-0.228-0.938-0.758-0.938-1.349 c0,0-0.394,0.184-0.623,0.517c0.092-0.276,0.242-0.531,0.488-0.74c0,0-0.26,0.133-0.496,0.419 c-0.184,0.102-0.604,0.391-0.747,0.903l-0.133-0.066c0.065-0.148,0.158-0.299,0.282-0.455c0,0-0.182,0.164-0.342,0.425 l-0.271-0.138c0.08-0.151,0.188-0.304,0.331-0.459c0,0-0.144,0.113-0.302,0.303c0.045-0.176,0.036-0.377-0.511,0.222 c0,0-2.466,1.071-3.183,3.288c0,0-0.422,1,0.137,3.944c-0.792-0.374-0.251,1.562-0.251,1.562 C10.55,10.466,10.767,10.691,10.898,10.787z M10.851,9.738c0,0.002,0,0.002,0,0.003C10.851,9.74,10.851,9.74,10.851,9.738z M15.384,0.517c-0.118,0.167-0.224,0.376-0.273,0.631l-0.088-0.035C15.091,0.898,15.204,0.694,15.384,0.517z"></path> <path style="fill:currentColor;" d="M25.876,19.226c-0.645-1.43-4.577-2.669-4.577-2.669c-2.095-0.738-2.109-1.476-2.109-1.476 c-4.121,8.125-7.253,0.022-7.253,0.022c-0.286,1.097-4.525,2.381-4.525,2.381c-1.24,0.478-1.765,1.192-1.765,1.192 c-1.834,2.719-2.049,8.769-2.049,8.769c0.024,1.383,0.618,1.525,0.618,1.525c4.218,1.882,10.831,2.215,10.831,2.215 c6.792,0.144,11.733-1.929,11.733-1.929c0.718-0.454,0.738-0.812,0.738-0.812C28.019,24.108,25.876,19.226,25.876,19.226z M17.976,26.946h-4.759V25.14h4.759V26.946z"></path> </g> </g></svg>
                        </i>
                        <span class="item-name">Employee Role<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.administration.roles.permissions')}}">
                        <i class="icon">
                            <svg fill="currentColor" width="20px" viewBox="0 0 1920 1920"
                                xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M1600 1066.667c117.653 0 213.333 95.68 213.333 213.333v106.667H1920V1760c0 88.213-71.787 160-160 160h-320c-88.213 0-160-71.787-160-160v-373.333h106.667V1280c0-117.653 95.68-213.333 213.333-213.333ZM800 0c90.667 0 179.2 25.6 254.933 73.6 29.867 18.133 58.667 40.533 84.267 66.133 49.067 49.067 84.8 106.88 108.053 169.814 11.307 30.4 20.8 61.44 25.814 94.08l2.24 14.613 3.626 20.16-.533.32v.213l-52.693 32.427c-44.694 28.907-95.467 61.547-193.067 61.867-.427 0-.747.106-1.173.106-24.534 0-46.08-2.133-65.28-5.653-.64-.107-1.067-.32-1.707-.427-56.32-10.773-93.013-34.24-126.293-55.68-9.6-6.293-18.774-12.16-28.16-17.6-27.947-16-57.92-27.306-108.16-27.306h-.32c-57.814.106-88.747 15.893-121.387 36.266-4.48 2.88-8.853 5.44-13.44 8.427-3.093 2.027-6.72 4.16-9.92 6.187-6.293 4.053-12.693 8.106-19.627 12.16-4.48 2.666-9.493 5.013-14.293 7.573-6.933 3.627-13.973 7.147-21.76 10.453-6.613 2.987-13.76 5.547-21.12 8.107-6.933 2.347-14.507 4.267-22.187 6.293-8.96 2.347-17.813 4.587-27.84 6.187-1.173.213-2.133.533-3.306.747v57.6c0 17.066 1.066 34.133 4.266 50.133C454.4 819.2 611.2 960 800 960c195.2 0 356.267-151.467 371.2-342.4 48-14.933 82.133-37.333 108.8-54.4v23.467c0 165.546-84.373 311.786-212.373 398.08h4.906a1641.19 1641.19 0 0 1 294.08 77.76C1313.28 1119.68 1280 1195.733 1280 1280h-106.667v480c0 1.387.427 2.667.427 4.16-142.933 37.547-272.427 49.173-373.76 49.173-345.493 0-612.053-120.32-774.827-221.333L0 1576.32v-196.373c0-140.054 85.867-263.04 218.667-313.28 100.373-38.08 204.586-64.96 310.186-82.347h4.8C419.52 907.413 339.2 783.787 323.2 640c-2.133-17.067-3.2-35.2-3.2-53.333V480c0-56.533 9.6-109.867 27.733-160C413.867 133.333 592 0 800 0Zm800 1173.333c-58.773 0-106.667 47.894-106.667 106.667v106.667h213.334V1280c0-58.773-47.894-106.667-106.667-106.667Z" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </i>
                        <span class="item-name">Permission<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#employee" role="button"
                        aria-expanded="false" aria-controls="employee">
                        <i class="icon">
                            <svg class="icon-20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 31.192 31.192" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path style="fill:currentColor;" d="M10.898,10.787c0.314,2.613,2.564,5.318,4.604,5.318c2.341,0,4.571-2.845,4.919-5.319 c0.129-0.095,0.348-0.322,0.428-0.838c0,0,0.502-1.792-0.164-1.6c0.232-0.69,1-3.379-0.485-5.052c0,0-0.697-0.947-2.386-1.452 c-0.059-0.051-0.12-0.1-0.189-0.148c0,0,0.037,0.043,0.094,0.12c-0.098-0.027-0.195-0.052-0.301-0.077 c-0.092-0.096-0.193-0.194-0.311-0.301c0,0,0.104,0.106,0.225,0.282c-0.044-0.01-0.086-0.02-0.134-0.03 c-0.076-0.118-0.17-0.237-0.288-0.358c0,0,0.049,0.091,0.117,0.24c-0.312-0.228-0.938-0.758-0.938-1.349 c0,0-0.394,0.184-0.623,0.517c0.092-0.276,0.242-0.531,0.488-0.74c0,0-0.26,0.133-0.496,0.419 c-0.184,0.102-0.604,0.391-0.747,0.903l-0.133-0.066c0.065-0.148,0.158-0.299,0.282-0.455c0,0-0.182,0.164-0.342,0.425 l-0.271-0.138c0.08-0.151,0.188-0.304,0.331-0.459c0,0-0.144,0.113-0.302,0.303c0.045-0.176,0.036-0.377-0.511,0.222 c0,0-2.466,1.071-3.183,3.288c0,0-0.422,1,0.137,3.944c-0.792-0.374-0.251,1.562-0.251,1.562 C10.55,10.466,10.767,10.691,10.898,10.787z M10.851,9.738c0,0.002,0,0.002,0,0.003C10.851,9.74,10.851,9.74,10.851,9.738z M15.384,0.517c-0.118,0.167-0.224,0.376-0.273,0.631l-0.088-0.035C15.091,0.898,15.204,0.694,15.384,0.517z"></path> <path style="fill:currentColor;" d="M25.876,19.226c-0.645-1.43-4.577-2.669-4.577-2.669c-2.095-0.738-2.109-1.476-2.109-1.476 c-4.121,8.125-7.253,0.022-7.253,0.022c-0.286,1.097-4.525,2.381-4.525,2.381c-1.24,0.478-1.765,1.192-1.765,1.192 c-1.834,2.719-2.049,8.769-2.049,8.769c0.024,1.383,0.618,1.525,0.618,1.525c4.218,1.882,10.831,2.215,10.831,2.215 c6.792,0.144,11.733-1.929,11.733-1.929c0.718-0.454,0.738-0.812,0.738-0.812C28.019,24.108,25.876,19.226,25.876,19.226z M17.976,26.946h-4.759V25.14h4.759V26.946z"></path> </g> </g></svg>
                        </i>
                        <span class="item-name">Employee  </span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="employee" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.employee.add-new')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name"> Add </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.employee.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> L </i>
                                <span class="item-name"> List </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Report Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.report.order')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="icon-20" width="20">
                                <path
                                    d="M57.64423,53.41349l-4.995-34.96809a5.79886,5.79886,0,0,0-5.71139-4.953h-4.6807l-.9398-2.821C38.35577,1.78071,25.64643,1.77149,22.682,10.672L21.7422,13.4924H17.06151a5.79885,5.79885,0,0,0-5.71139,4.953l-4.995,34.96809a5.80619,5.80619,0,0,0,5.71146,6.585l39.86629.00006A5.8063,5.8063,0,0,0,57.64423,53.41349ZM24.57994,11.3047C26.9,4.233,37.10036,4.23455,39.41939,11.30433l.72907,2.18807H23.85093ZM54.77876,56.69822a3.76656,3.76656,0,0,1-2.84592,1.29885H12.06648a3.7913,3.7913,0,0,1-3.72934-4.30022l4.995-34.968a3.787,3.787,0,0,1,3.72941-3.23489c.00391-.0025,24.47393.00177,24.47772,0h5.39859a3.78623,3.78623,0,0,1,3.72941,3.23489l4.995,34.96809A3.765,3.765,0,0,1,54.77876,56.69822Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M37.54686,32.79815,31.3048,39.0412l-2.76774-2.76774A1.00083,1.00083,0,0,0,27.122,37.6886l3.47525,3.47531a1.00637,1.00637,0,0,0,1.41514,0L38.962,34.21329A1.00085,1.00085,0,0,0,37.54686,32.79815Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M31.99966,23.47072A13.5254,13.5254,0,0,0,18.48935,36.981c.74154,17.92334,26.28184,17.91815,27.02062-.00012A13.52537,13.52537,0,0,0,31.99966,23.47072Zm0,25.01909A11.5222,11.5222,0,0,1,20.49088,36.981c.63226-15.26794,22.38769-15.26354,23.01756.00006A11.52218,11.52218,0,0,1,31.99966,48.48981Z"
                                    fill="currentColor" ></path>
                            </svg>

                        </i>
                        <span class="item-name">Order Report<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.report.product')}}">
                        <i class="icon">

                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-20" width="20" viewBox="0 0 64 64" fill="currentColor">
                                <path
                                    d="m60.93 41.62-4-10c-.19-.23-.25-.57-.93-.63H40a1 1 0 1 0 0 2h.23l-4.8 8H5.77l4.8-8H24a1 1 0 0 0 0-2H10a1.14 1.14 0 0 0-.86.49l-6 10A1 1 0 0 0 4 43h5v17a1 1 0 0 0 1 1h46a1 1 0 0 0 1-1V43h3a1 1 0 0 0 .83-.44 1 1 0 0 0 .1-.94ZM11 52h12v3H11Zm0 5h13a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H11v-7h25a1 1 0 0 0 .86-.48L41 35.6V59H11Zm44 2H43V37.19l2.07 5.18A1 1 0 0 0 46 43h9Zm-8.32-18-3.2-8h11.84l3.2 8Z" />
                                <path
                                    d="M28 51a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0v-4a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0v-4a1 1 0 0 0-1-1zM20.82 29.05a1.13 1.13 0 0 0 1.11-.4s1.57-2.25 3.5-2.39a3.42 3.42 0 0 1 2.75 1.34 1 1 0 0 0 .76.39h6.12a1 1 0 0 0 .75-.39 3.52 3.52 0 0 1 2.75-1.34c1.95.13 3.5 2.37 3.51 2.39a1.1 1.1 0 0 0 1.11.4 1 1 0 0 0 .72-.93c.16-6.38-3.18-8.33-5-8.92a14 14 0 0 0 .1-1.71c0-7.77-6-13.94-6.29-14.2a1 1 0 0 0-1.42 0C31 3.55 25 9.72 25 17.49a14 14 0 0 0 .11 1.71c-1.83.59-5.17 2.54-5 8.92a1 1 0 0 0 .71.93zm20.89-3.58a5.85 5.85 0 0 0-3-1.21 4.36 4.36 0 0 0-1.2.07 19.79 19.79 0 0 0 1-3.13c1.15.42 2.66 1.5 3.2 4.27zM32 5.5c1.53 1.82 5 6.56 5 12a17.42 17.42 0 0 1-2.54 8.5h-4.92A17.42 17.42 0 0 1 27 17.49c0-5.43 3.47-10.17 5-11.99zm-6.56 15.7a19.79 19.79 0 0 0 1 3.13 4.36 4.36 0 0 0-1.2-.07 5.85 5.85 0 0 0-3 1.21c.59-2.77 2.1-3.85 3.2-4.27z" />
                                <path
                                    d="M34 19a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1zm-3-4h2v2h-2zm0 16v5a1 1 0 1 0 2 0v-5a1 1 0 1 0-2 0zm4-1v3a1 1 0 1 0 2 0v-3a1 1 0 1 0-2 0zm-7-1a1 1 0 0 0-1 1v3a1 1 0 0 0 2 0v-3a1 1 0 0 0-1-1z" />
                            </svg>

                        </i>
                        <span class="item-name">Product Report<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.report.tax')}}">
                        <i class="icon">
                            <svg fill="currentColor" class="icon-20" width="20" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M482.728,147.271c-24.16-28.793-59.946-44.65-100.766-44.65c-40.728,0-81.742,15.798-115.487,44.484 c-33.716,28.662-56.232,66.84-63.403,107.503c-7.339,41.623,2.201,80.731,26.861,110.121c24.16,28.793,59.946,44.65,100.766,44.65 c40.728,0,81.742-15.798,115.487-44.484c33.716-28.662,56.232-66.84,63.403-107.502 C516.927,215.769,507.389,176.661,482.728,147.271z M493.8,254.608c-13.489,76.501-86.656,138.74-163.1,138.74 c-35.998,0-67.422-13.823-88.483-38.924c-21.562-25.696-29.856-60.156-23.353-97.03c13.489-76.501,86.656-138.74,163.1-138.74 c35.998,0,67.422,13.823,88.483,38.924C492.007,183.274,500.301,217.734,493.8,254.608z"></path> </g> </g>
                                <g> <g> <path d="M454.432,171.643c-18.841-22.453-46.719-34.819-78.5-34.819c-31.592,0-63.39,12.24-89.535,34.467 c-26.117,22.201-43.561,51.791-49.121,83.317c-5.711,32.388,1.73,62.842,20.954,85.75c18.839,22.453,46.718,34.819,78.5,34.819 c31.592,0,63.39-12.24,89.535-34.467c26.117-22.201,43.561-51.791,49.121-83.317C481.096,225.005,473.655,194.553,454.432,171.643 z M459.597,254.608c-10.164,57.642-65.281,104.536-122.866,104.536c-26.961,0-50.478-10.331-66.219-29.092 c-16.124-19.216-22.32-45.02-17.446-72.659c10.164-57.642,65.281-104.536,122.866-104.536c26.961,0,50.477,10.331,66.219,29.092 C458.274,201.164,464.471,226.969,459.597,254.608z"></path> </g> </g>
                                <g> <g> <path d="M398.233,209.557c-5.176-6.17-13.59-10.429-23.441-12.093l1.495-8.478c0.768-4.361-2.143-8.519-6.503-9.286 c-4.356-0.773-8.518,2.143-9.286,6.502l-1.914,10.857c-20.031,2.217-36.675,14.02-39.262,28.69 c-3.791,21.505,13.698,30.466,27.923,35.615l-6.596,37.404c-7.003-1.131-11.69-3.948-13.941-6.631 c-1.564-1.864-2.144-3.789-1.774-5.886c0.768-4.361-2.143-8.519-6.503-9.286c-4.356-0.774-8.518,2.142-9.286,6.502 c-1.192,6.757,0.685,13.497,5.282,18.976c5.177,6.172,13.591,10.43,23.442,12.093l-1.495,8.478 c-0.768,4.361,2.143,8.519,6.503,9.286c0.47,0.083,0.938,0.123,1.401,0.123c3.817,0,7.2-2.737,7.885-6.625l1.914-10.857 c20.032-2.217,36.675-14.02,39.262-28.69c3.792-21.505-13.698-30.466-27.924-35.615l6.596-37.404 c7.003,1.131,11.69,3.948,13.941,6.631c1.564,1.864,2.144,3.789,1.774,5.886c-0.768,4.361,2.143,8.519,6.503,9.286 c4.359,0.776,8.518-2.143,9.286-6.502C404.707,221.776,402.83,215.036,398.233,209.557z M350.089,245.231 c-14.093-5.63-16.151-10.043-14.978-16.698c1.04-5.901,9.192-12.329,20.529-14.786L350.089,245.231z M377.551,283.467 c-1.04,5.901-9.192,12.329-20.53,14.786l5.552-31.485C376.666,272.4,378.724,276.812,377.551,283.467z"></path> </g> </g>
                                <g> <g> <path d="M170.481,247.984H8.016c-4.427,0-8.016,3.588-8.016,8.016s3.589,8.016,8.016,8.016h162.465 c4.427,0,8.016-3.589,8.016-8.016C178.497,251.573,174.908,247.984,170.481,247.984z"></path> </g> </g>
                                <g> <g> <path d="M187.583,205.231H59.321c-4.427,0-8.016,3.588-8.016,8.016c0,4.428,3.589,8.016,8.016,8.016h128.262 c4.427,0,8.016-3.588,8.016-8.016C195.599,208.819,192.01,205.231,187.583,205.231z"></path> </g> </g>
                                <g> <g> <path d="M213.235,162.477h-85.508c-4.427,0-8.016,3.588-8.016,8.016s3.589,8.016,8.016,8.016h85.508 c4.427,0,8.016-3.588,8.016-8.016S217.662,162.477,213.235,162.477z"></path> </g> </g>
                                <g> <g> <path d="M238.887,128.274h-25.652c-4.427,0-8.016,3.588-8.016,8.016s3.589,8.016,8.016,8.016h25.652 c4.427,0,8.016-3.588,8.016-8.016C246.904,131.862,243.314,128.274,238.887,128.274z"></path> </g> </g>
                                <g> <g> <path d="M204.684,367.695h-17.102c-4.427,0-8.016,3.588-8.016,8.016s3.589,8.016,8.016,8.016h17.102 c4.427,0,8.016-3.588,8.016-8.016S209.111,367.695,204.684,367.695z"></path> </g> </g>
                                <g> <g> <path d="M187.583,333.492h-59.855c-4.427,0-8.016,3.588-8.016,8.016c0,4.428,3.589,8.016,8.016,8.016h59.855 c4.427,0,8.016-3.589,8.016-8.016C195.599,337.08,192.01,333.492,187.583,333.492z"></path> </g> </g>
                                <g> <g> <path d="M170.481,290.738H59.321c-4.427,0-8.016,3.588-8.016,8.016s3.589,8.016,8.016,8.016h111.16 c4.427,0,8.016-3.589,8.016-8.016C178.497,294.326,174.908,290.738,170.481,290.738z"></path> </g> </g> </g>
                            </svg>
                        </i>
                        <span class="item-name">Tax Report<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Transaction Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.earning.deliveryman')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="icon-20" width="20">
                                <path
                                    d="M57.64423,53.41349l-4.995-34.96809a5.79886,5.79886,0,0,0-5.71139-4.953h-4.6807l-.9398-2.821C38.35577,1.78071,25.64643,1.77149,22.682,10.672L21.7422,13.4924H17.06151a5.79885,5.79885,0,0,0-5.71139,4.953l-4.995,34.96809a5.80619,5.80619,0,0,0,5.71146,6.585l39.86629.00006A5.8063,5.8063,0,0,0,57.64423,53.41349ZM24.57994,11.3047C26.9,4.233,37.10036,4.23455,39.41939,11.30433l.72907,2.18807H23.85093ZM54.77876,56.69822a3.76656,3.76656,0,0,1-2.84592,1.29885H12.06648a3.7913,3.7913,0,0,1-3.72934-4.30022l4.995-34.968a3.787,3.787,0,0,1,3.72941-3.23489c.00391-.0025,24.47393.00177,24.47772,0h5.39859a3.78623,3.78623,0,0,1,3.72941,3.23489l4.995,34.96809A3.765,3.765,0,0,1,54.77876,56.69822Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M37.54686,32.79815,31.3048,39.0412l-2.76774-2.76774A1.00083,1.00083,0,0,0,27.122,37.6886l3.47525,3.47531a1.00637,1.00637,0,0,0,1.41514,0L38.962,34.21329A1.00085,1.00085,0,0,0,37.54686,32.79815Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M31.99966,23.47072A13.5254,13.5254,0,0,0,18.48935,36.981c.74154,17.92334,26.28184,17.91815,27.02062-.00012A13.52537,13.52537,0,0,0,31.99966,23.47072Zm0,25.01909A11.5222,11.5222,0,0,1,20.49088,36.981c.63226-15.26794,22.38769-15.26354,23.01756.00006A11.52218,11.52218,0,0,1,31.99966,48.48981Z"
                                    fill="currentColor" ></path>
                            </svg>

                        </i>
                        <span class="item-name">Deliveryman Payments<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Payments </span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.payments.list')}}">
                        <i class="icon">
                            <svg fill="currentColor"  width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.64 483.64" xml:space="preserve" stroke="#7d5454"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M312.4,238.886l-29.9-5.1c-2.9-11.5-7.4-22.4-13.3-32.4l17.7-24.7c3.3-4.6,2.8-10.8-1.2-14.8l-10.5-10.6l-10.5-10.6 c-2.2-2.2-5.2-3.4-8.1-3.4c-2.3,0-4.6,0.7-6.6,2.1l-24.9,17.4c-10.1-6.1-21.2-10.8-33.1-13.7l-4.9-29.7c-0.9-5.6-5.7-9.6-11.3-9.7 l-15-0.1l-15-0.1l0,0c-5.5,0-10.4,4-11.4,9.6l-5.3,30.2c-11.5,2.9-22.4,7.6-32.3,13.4l-24.3-17.8c-2-1.4-4.4-2.2-6.7-2.2 c-2.9,0-5.9,1.1-8.1,3.3l-10.6,10.5l-10.6,10.5c-3.9,3.9-4.5,10.1-1.3,14.8l17.7,25c-5.9,10-10.4,20.8-13.3,32.3l-29.7,4.9 c-5.6,0.9-9.6,5.7-9.7,11.3l-0.1,15l-0.1,15c0,5.6,4,10.5,9.6,11.4l30.2,5.3c2.9,11.5,7.6,22.4,13.4,32.3l-17.6,24.5 c-3.3,4.6-2.8,10.8,1.2,14.8l10.5,10.6l10.5,10.9c2.2,2.2,5.2,3.4,8.1,3.4c2.3,0,4.6-0.7,6.6-2.1l25-17.7 c9.7,5.7,20.4,10.2,31.5,13.1l4.9,30c0.9,5.6,5.7,9.6,11.3,9.7l15,0.1l15,0.1l0,0c5.5,0,10.4-4,11.4-9.6l5.1-29.9 c11.5-2.9,22.4-7.4,32.4-13.3l24.7,17.7c2,1.4,4.4,2.2,6.7,2.2c2.9,0,5.9-1.1,8.1-3.3l10.6-10.5l10.6-10.5 c3.9-3.9,4.5-10.1,1.3-14.8l-17.5-24.9c6-10,10.5-20.8,13.5-32.3l30-4.9c5.6-0.9,9.6-5.7,9.7-11.3l0.1-15l0.1-15 C321.9,244.586,317.9,239.886,312.4,238.886z M182.7,359.186c-7.3,1.7-14.6,2.5-21.8,2.5c-44.2,0-84.2-30.4-94.6-75.4 c-12-52.3,20.6-104.4,72.9-116.4c7.3-1.7,14.6-2.5,21.8-2.5c44.2,0,84.2,30.4,94.6,75.4 C267.6,295.086,234.9,347.186,182.7,359.186z"></path> <path d="M364,229.486l8,1l0,0c2.9,0.3,5.8-1.5,6.6-4.4l4.6-15.5c6.3-0.8,12.3-2.5,18-5l12,10.9c1,0.9,2.2,1.4,3.4,1.6 c1.6,0.2,3.2-0.2,4.5-1.2l6.3-4.9l6.3-4.9c2.3-1.8,3-5.1,1.6-7.7l-7.7-14.3c3.8-4.9,6.9-10.4,9.2-16.3l16.2-0.7 c3-0.1,5.5-2.4,5.8-5.4l1-8l1-8c0.2-2.9-1.6-5.7-4.4-6.5l-15.5-4.6c-0.8-6.3-2.5-12.3-5-18l10.9-12c2-2.2,2.1-5.6,0.3-7.9 l-4.9-6.3l-4.9-6.3c-1-1.3-2.5-2.1-4.1-2.3c-1.2-0.1-2.5,0.1-3.6,0.7l-14.3,7.7c-5-3.9-10.6-7-16.7-9.3l-0.8-16.1 c-0.1-3-2.4-5.5-5.4-5.8l-8-1l-8-1l0,0c-2.9-0.3-5.8,1.5-6.6,4.4l-4.7,15.7c-6.3,0.8-12.3,2.6-18,5.1l-11.8-11 c-1-0.9-2.2-1.4-3.4-1.6c-1.6-0.2-3.2,0.2-4.5,1.2l-6.3,4.9l-6.3,4.9c-2.3,1.8-3,5.1-1.6,7.7l7.8,14.4c-3.7,4.9-6.8,10.4-9.1,16.3 l-16,0.7c-3,0.1-5.5,2.4-5.8,5.4l-1,8l-1,8c-0.4,2.9,1.5,5.8,4.4,6.7l15.7,4.7c0.8,6.3,2.6,12.3,5.1,18l-10.8,11.9 c-2,2.2-2.1,5.6-0.3,7.9l4.9,6.3l4.9,6.3c1,1.3,2.5,2.1,4.1,2.3c1.2,0.1,2.5-0.1,3.6-0.7l14.4-7.8c4.8,3.6,10.2,6.7,15.9,8.9 l0.7,16.2c0.1,3,2.4,5.5,5.4,5.8L364,229.486z M339.4,142.886c0.7-19.4,17-34.5,36.4-33.8s34.5,17,33.8,36.4 c-0.7,19.4-17,34.5-36.4,33.8C353.8,178.586,338.7,162.286,339.4,142.886z"></path> <path d="M483.6,293.386l-0.7-6l-0.7-6c-0.4-2.1-2.2-3.8-4.4-4l-12.1-0.6c-1.7-4.4-4-8.5-6.8-12.2l5.8-10.6c1.1-2,0.6-4.4-1.2-5.8 l-4.7-3.7l-4.7-3.7c-1-0.8-2.2-1.1-3.4-1c-0.9,0.1-1.8,0.5-2.5,1.1l-9,8.1c-4.3-2-8.9-3.3-13.7-3.9l-3.3-11.5 c-0.6-2.2-2.7-3.5-4.9-3.3l-6,0.7l-6,0.7l0,0c-2.2,0.3-3.9,2.1-4,4.3l-0.7,12.2c-4.4,1.7-8.5,4.1-12.1,6.8l-10.5-5.9 c-0.9-0.5-1.8-0.6-2.8-0.5c-1.2,0.1-2.3,0.7-3,1.7l-3.7,4.7l-3.7,4.7c-1.4,1.7-1.3,4.2,0.2,5.9l8.2,9.1c-1.9,4.2-3.1,8.7-3.7,13.4 l-11.5,3.3c-2.2,0.6-3.5,2.7-3.3,4.9l0.7,6l0.7,6c0.3,2.2,2.1,3.9,4.3,4.1l12.2,0.7c1.7,4.4,4.1,8.5,6.8,12.1l-5.8,10.5 c-1.1,2-0.6,4.4,1.2,5.8l4.7,3.7l4.7,3.7c1,0.8,2.2,1.1,3.4,1c0.9-0.1,1.8-0.5,2.5-1.1l9.1-8.2c4.1,1.8,8.6,3.1,13.1,3.7l3.3,11.6 c0.6,2.2,2.7,3.5,4.9,3.3l6-0.7l6-0.7l0,0c2.2-0.3,3.9-2.1,4-4.3l0.6-12.1c4.4-1.7,8.5-4,12.2-6.8l10.6,5.8 c0.9,0.5,1.8,0.6,2.8,0.5c1.2-0.1,2.3-0.7,3-1.7l3.7-4.7l3.7-4.7c1.4-1.7,1.3-4.2-0.2-5.9l-8.1-9c1.9-4.2,3.2-8.7,3.8-13.4 l11.6-3.3C482.5,297.686,483.9,295.586,483.6,293.386z M424.4,320.486c-14.2,2.9-28-6.3-30.9-20.5s6.3-28,20.5-30.9 s28,6.3,30.9,20.5S438.6,317.586,424.4,320.486z"></path> <path d="M216.7,294.486c-17.3-4.4-31.3-14.3-31.3-14.3l-11,34.7l-2.1,6.5v-0.1l-1.8,5.5l-5.8-16.4c14.6-20.4-3.9-19.6-3.9-19.6 s-18.5-0.8-3.9,19.6l-5.8,16.5l-1.8-5.6l-13-41.2c0,0-14.1,9.9-31.3,14.3c-8.6,2.2-11.7,9.6-12.7,16.4 c15.5,21.3,40.5,34.7,68.4,34.7c6.4,0,12.8-0.7,19.1-2.2c20.2-4.6,37.7-16.3,49.7-32.9C228.3,303.786,225.1,296.686,216.7,294.486 z"></path> <path d="M131.8,257.286c1.5,9.7,8.9,22,21.2,26.3c5,1.8,10.5,1.8,15.6,0c12.1-4.3,19.8-16.6,21.3-26.2c1.6-0.1,3.8-2.4,6.1-10.5 c3.1-11.1-0.2-12.8-3-12.5c0.5-1.5,0.9-3.1,1.2-4.6c4.8-28.8-9.4-29.8-9.4-29.8s-2.3-4.5-8.5-7.9c-4.1-2.5-9.9-4.4-17.5-3.7 c-2.5,0.1-4.8,0.6-7,1.3l0,0c-2.8,0.9-5.4,2.3-7.7,3.9c-2.8,1.8-5.5,4-7.9,6.5c-3.8,3.8-7.1,8.8-8.5,15c-1.2,4.6-0.9,9.5,0.1,14.6 l0,0c0.3,1.5,0.7,3,1.2,4.6c-2.8-0.3-6.2,1.4-3,12.5C128,254.786,130.2,257.086,131.8,257.286z"></path> </g> </g> </g></svg>
                        </i>
                        <span class="item-name">Requests
                            <span class="badge rounded-pill bg-success item-name"> {{App\Models\PaymentRequest::where('payment_status', 'pending')->count()}}</span>
                        </span>
                    </a>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">KYC & Docs</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.doc.kyc-table')}}">
                        <i class="icon">
                            <svg fill="currentColor" width="20px" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 512.001 512.001" xml:space="preserve" stroke="#680303"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g>
                                <path d="M463.996,126.864L340.192,3.061C338.231,1.101,335.574,0,332.803,0H95.726C67.724,0,44.944,22.782,44.944,50.784v410.434 c0,28.001,22.781,50.783,50.783,50.783h320.547c28.002,0,50.783-22.781,50.783-50.783V134.253 C467.056,131.482,465.955,128.824,463.996,126.864z M343.255,35.679l88.127,88.126H373.14c-7.984,0-15.49-3.109-21.134-8.753 c-5.643-5.643-8.752-13.148-8.751-21.131V35.679z M446.158,461.217c0,16.479-13.406,29.885-29.884,29.885H95.726 c-16.479,0-29.885-13.406-29.885-29.885V50.784c0.001-16.479,13.407-29.886,29.885-29.886h226.631v73.021 c-0.002,13.565,5.28,26.318,14.871,35.909c9.592,9.592,22.345,14.874,35.911,14.874h73.018V461.217z"></path> </g> </g> <g> <g>
                                <path d="M275.092,351.492h-4.678c-5.77,0-10.449,4.678-10.449,10.449s4.679,10.449,10.449,10.449h4.678 c5.77,0,10.449-4.678,10.449-10.449S280.862,351.492,275.092,351.492z"></path> </g> </g> <g> <g>
                                <path d="M236.61,351.492H135.118c-5.77,0-10.449,4.678-10.449,10.449s4.679,10.449,10.449,10.449H236.61 c5.77,0,10.449-4.678,10.449-10.449S242.381,351.492,236.61,351.492z"></path> </g> </g> <g> <g>
                                <path d="M376.882,303.747H135.119c-5.77,0-10.449,4.678-10.449,10.449c0,5.771,4.679,10.449,10.449,10.449h241.763 c5.77,0,10.449-4.678,10.449-10.449C387.331,308.425,382.652,303.747,376.882,303.747z"></path> </g> </g> <g> <g>
                                <path d="M376.882,256H135.119c-5.77,0-10.449,4.678-10.449,10.449c0,5.771,4.679,10.449,10.449,10.449h241.763 c5.77,0,10.449-4.678,10.449-10.449C387.331,260.678,382.652,256,376.882,256z"></path> </g> </g> <g> <g>
                                <path d="M376.882,208.255H135.119c-5.77,0-10.449,4.678-10.449,10.449c0,5.771,4.679,10.449,10.449,10.449h241.763 c5.77,0,10.449-4.678,10.449-10.449S382.652,208.255,376.882,208.255z"></path> </g> </g> </g>
                            </svg>
                            {{-- <svg fill="currentColor"  width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.64 483.64" xml:space="preserve" stroke="#7d5454"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M312.4,238.886l-29.9-5.1c-2.9-11.5-7.4-22.4-13.3-32.4l17.7-24.7c3.3-4.6,2.8-10.8-1.2-14.8l-10.5-10.6l-10.5-10.6 c-2.2-2.2-5.2-3.4-8.1-3.4c-2.3,0-4.6,0.7-6.6,2.1l-24.9,17.4c-10.1-6.1-21.2-10.8-33.1-13.7l-4.9-29.7c-0.9-5.6-5.7-9.6-11.3-9.7 l-15-0.1l-15-0.1l0,0c-5.5,0-10.4,4-11.4,9.6l-5.3,30.2c-11.5,2.9-22.4,7.6-32.3,13.4l-24.3-17.8c-2-1.4-4.4-2.2-6.7-2.2 c-2.9,0-5.9,1.1-8.1,3.3l-10.6,10.5l-10.6,10.5c-3.9,3.9-4.5,10.1-1.3,14.8l17.7,25c-5.9,10-10.4,20.8-13.3,32.3l-29.7,4.9 c-5.6,0.9-9.6,5.7-9.7,11.3l-0.1,15l-0.1,15c0,5.6,4,10.5,9.6,11.4l30.2,5.3c2.9,11.5,7.6,22.4,13.4,32.3l-17.6,24.5 c-3.3,4.6-2.8,10.8,1.2,14.8l10.5,10.6l10.5,10.9c2.2,2.2,5.2,3.4,8.1,3.4c2.3,0,4.6-0.7,6.6-2.1l25-17.7 c9.7,5.7,20.4,10.2,31.5,13.1l4.9,30c0.9,5.6,5.7,9.6,11.3,9.7l15,0.1l15,0.1l0,0c5.5,0,10.4-4,11.4-9.6l5.1-29.9 c11.5-2.9,22.4-7.4,32.4-13.3l24.7,17.7c2,1.4,4.4,2.2,6.7,2.2c2.9,0,5.9-1.1,8.1-3.3l10.6-10.5l10.6-10.5 c3.9-3.9,4.5-10.1,1.3-14.8l-17.5-24.9c6-10,10.5-20.8,13.5-32.3l30-4.9c5.6-0.9,9.6-5.7,9.7-11.3l0.1-15l0.1-15 C321.9,244.586,317.9,239.886,312.4,238.886z M182.7,359.186c-7.3,1.7-14.6,2.5-21.8,2.5c-44.2,0-84.2-30.4-94.6-75.4 c-12-52.3,20.6-104.4,72.9-116.4c7.3-1.7,14.6-2.5,21.8-2.5c44.2,0,84.2,30.4,94.6,75.4 C267.6,295.086,234.9,347.186,182.7,359.186z"></path> <path d="M364,229.486l8,1l0,0c2.9,0.3,5.8-1.5,6.6-4.4l4.6-15.5c6.3-0.8,12.3-2.5,18-5l12,10.9c1,0.9,2.2,1.4,3.4,1.6 c1.6,0.2,3.2-0.2,4.5-1.2l6.3-4.9l6.3-4.9c2.3-1.8,3-5.1,1.6-7.7l-7.7-14.3c3.8-4.9,6.9-10.4,9.2-16.3l16.2-0.7 c3-0.1,5.5-2.4,5.8-5.4l1-8l1-8c0.2-2.9-1.6-5.7-4.4-6.5l-15.5-4.6c-0.8-6.3-2.5-12.3-5-18l10.9-12c2-2.2,2.1-5.6,0.3-7.9 l-4.9-6.3l-4.9-6.3c-1-1.3-2.5-2.1-4.1-2.3c-1.2-0.1-2.5,0.1-3.6,0.7l-14.3,7.7c-5-3.9-10.6-7-16.7-9.3l-0.8-16.1 c-0.1-3-2.4-5.5-5.4-5.8l-8-1l-8-1l0,0c-2.9-0.3-5.8,1.5-6.6,4.4l-4.7,15.7c-6.3,0.8-12.3,2.6-18,5.1l-11.8-11 c-1-0.9-2.2-1.4-3.4-1.6c-1.6-0.2-3.2,0.2-4.5,1.2l-6.3,4.9l-6.3,4.9c-2.3,1.8-3,5.1-1.6,7.7l7.8,14.4c-3.7,4.9-6.8,10.4-9.1,16.3 l-16,0.7c-3,0.1-5.5,2.4-5.8,5.4l-1,8l-1,8c-0.4,2.9,1.5,5.8,4.4,6.7l15.7,4.7c0.8,6.3,2.6,12.3,5.1,18l-10.8,11.9 c-2,2.2-2.1,5.6-0.3,7.9l4.9,6.3l4.9,6.3c1,1.3,2.5,2.1,4.1,2.3c1.2,0.1,2.5-0.1,3.6-0.7l14.4-7.8c4.8,3.6,10.2,6.7,15.9,8.9 l0.7,16.2c0.1,3,2.4,5.5,5.4,5.8L364,229.486z M339.4,142.886c0.7-19.4,17-34.5,36.4-33.8s34.5,17,33.8,36.4 c-0.7,19.4-17,34.5-36.4,33.8C353.8,178.586,338.7,162.286,339.4,142.886z"></path> <path d="M483.6,293.386l-0.7-6l-0.7-6c-0.4-2.1-2.2-3.8-4.4-4l-12.1-0.6c-1.7-4.4-4-8.5-6.8-12.2l5.8-10.6c1.1-2,0.6-4.4-1.2-5.8 l-4.7-3.7l-4.7-3.7c-1-0.8-2.2-1.1-3.4-1c-0.9,0.1-1.8,0.5-2.5,1.1l-9,8.1c-4.3-2-8.9-3.3-13.7-3.9l-3.3-11.5 c-0.6-2.2-2.7-3.5-4.9-3.3l-6,0.7l-6,0.7l0,0c-2.2,0.3-3.9,2.1-4,4.3l-0.7,12.2c-4.4,1.7-8.5,4.1-12.1,6.8l-10.5-5.9 c-0.9-0.5-1.8-0.6-2.8-0.5c-1.2,0.1-2.3,0.7-3,1.7l-3.7,4.7l-3.7,4.7c-1.4,1.7-1.3,4.2,0.2,5.9l8.2,9.1c-1.9,4.2-3.1,8.7-3.7,13.4 l-11.5,3.3c-2.2,0.6-3.5,2.7-3.3,4.9l0.7,6l0.7,6c0.3,2.2,2.1,3.9,4.3,4.1l12.2,0.7c1.7,4.4,4.1,8.5,6.8,12.1l-5.8,10.5 c-1.1,2-0.6,4.4,1.2,5.8l4.7,3.7l4.7,3.7c1,0.8,2.2,1.1,3.4,1c0.9-0.1,1.8-0.5,2.5-1.1l9.1-8.2c4.1,1.8,8.6,3.1,13.1,3.7l3.3,11.6 c0.6,2.2,2.7,3.5,4.9,3.3l6-0.7l6-0.7l0,0c2.2-0.3,3.9-2.1,4-4.3l0.6-12.1c4.4-1.7,8.5-4,12.2-6.8l10.6,5.8 c0.9,0.5,1.8,0.6,2.8,0.5c1.2-0.1,2.3-0.7,3-1.7l3.7-4.7l3.7-4.7c1.4-1.7,1.3-4.2-0.2-5.9l-8.1-9c1.9-4.2,3.2-8.7,3.8-13.4 l11.6-3.3C482.5,297.686,483.9,295.586,483.6,293.386z M424.4,320.486c-14.2,2.9-28-6.3-30.9-20.5s6.3-28,20.5-30.9 s28,6.3,30.9,20.5S438.6,317.586,424.4,320.486z"></path> <path d="M216.7,294.486c-17.3-4.4-31.3-14.3-31.3-14.3l-11,34.7l-2.1,6.5v-0.1l-1.8,5.5l-5.8-16.4c14.6-20.4-3.9-19.6-3.9-19.6 s-18.5-0.8-3.9,19.6l-5.8,16.5l-1.8-5.6l-13-41.2c0,0-14.1,9.9-31.3,14.3c-8.6,2.2-11.7,9.6-12.7,16.4 c15.5,21.3,40.5,34.7,68.4,34.7c6.4,0,12.8-0.7,19.1-2.2c20.2-4.6,37.7-16.3,49.7-32.9C228.3,303.786,225.1,296.686,216.7,294.486 z"></path> <path d="M131.8,257.286c1.5,9.7,8.9,22,21.2,26.3c5,1.8,10.5,1.8,15.6,0c12.1-4.3,19.8-16.6,21.3-26.2c1.6-0.1,3.8-2.4,6.1-10.5 c3.1-11.1-0.2-12.8-3-12.5c0.5-1.5,0.9-3.1,1.2-4.6c4.8-28.8-9.4-29.8-9.4-29.8s-2.3-4.5-8.5-7.9c-4.1-2.5-9.9-4.4-17.5-3.7 c-2.5,0.1-4.8,0.6-7,1.3l0,0c-2.8,0.9-5.4,2.3-7.7,3.9c-2.8,1.8-5.5,4-7.9,6.5c-3.8,3.8-7.1,8.8-8.5,15c-1.2,4.6-0.9,9.5,0.1,14.6 l0,0c0.3,1.5,0.7,3,1.2,4.6c-2.8-0.3-6.2,1.4-3,12.5C128,254.786,130.2,257.086,131.8,257.286z"></path> </g> </g> </g></svg> --}}
                        </i>
                        <span class="item-name">Docs
                            {{-- <span class="badge rounded-pill bg-success item-name"> {{App\Models\PaymentRequest::where('payment_status', 'pending')->count()}}</span> --}}
                        </span>
                    </a>
                    <a class="nav-link" aria-current="page" href="{{route('admin.joinas.restaurant')}}">
                        <i class="icon">
                            {{-- <svg fill="currentColor"  width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.64 483.64" xml:space="preserve" stroke="#7d5454"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M312.4,238.886l-29.9-5.1c-2.9-11.5-7.4-22.4-13.3-32.4l17.7-24.7c3.3-4.6,2.8-10.8-1.2-14.8l-10.5-10.6l-10.5-10.6 c-2.2-2.2-5.2-3.4-8.1-3.4c-2.3,0-4.6,0.7-6.6,2.1l-24.9,17.4c-10.1-6.1-21.2-10.8-33.1-13.7l-4.9-29.7c-0.9-5.6-5.7-9.6-11.3-9.7 l-15-0.1l-15-0.1l0,0c-5.5,0-10.4,4-11.4,9.6l-5.3,30.2c-11.5,2.9-22.4,7.6-32.3,13.4l-24.3-17.8c-2-1.4-4.4-2.2-6.7-2.2 c-2.9,0-5.9,1.1-8.1,3.3l-10.6,10.5l-10.6,10.5c-3.9,3.9-4.5,10.1-1.3,14.8l17.7,25c-5.9,10-10.4,20.8-13.3,32.3l-29.7,4.9 c-5.6,0.9-9.6,5.7-9.7,11.3l-0.1,15l-0.1,15c0,5.6,4,10.5,9.6,11.4l30.2,5.3c2.9,11.5,7.6,22.4,13.4,32.3l-17.6,24.5 c-3.3,4.6-2.8,10.8,1.2,14.8l10.5,10.6l10.5,10.9c2.2,2.2,5.2,3.4,8.1,3.4c2.3,0,4.6-0.7,6.6-2.1l25-17.7 c9.7,5.7,20.4,10.2,31.5,13.1l4.9,30c0.9,5.6,5.7,9.6,11.3,9.7l15,0.1l15,0.1l0,0c5.5,0,10.4-4,11.4-9.6l5.1-29.9 c11.5-2.9,22.4-7.4,32.4-13.3l24.7,17.7c2,1.4,4.4,2.2,6.7,2.2c2.9,0,5.9-1.1,8.1-3.3l10.6-10.5l10.6-10.5 c3.9-3.9,4.5-10.1,1.3-14.8l-17.5-24.9c6-10,10.5-20.8,13.5-32.3l30-4.9c5.6-0.9,9.6-5.7,9.7-11.3l0.1-15l0.1-15 C321.9,244.586,317.9,239.886,312.4,238.886z M182.7,359.186c-7.3,1.7-14.6,2.5-21.8,2.5c-44.2,0-84.2-30.4-94.6-75.4 c-12-52.3,20.6-104.4,72.9-116.4c7.3-1.7,14.6-2.5,21.8-2.5c44.2,0,84.2,30.4,94.6,75.4 C267.6,295.086,234.9,347.186,182.7,359.186z"></path> <path d="M364,229.486l8,1l0,0c2.9,0.3,5.8-1.5,6.6-4.4l4.6-15.5c6.3-0.8,12.3-2.5,18-5l12,10.9c1,0.9,2.2,1.4,3.4,1.6 c1.6,0.2,3.2-0.2,4.5-1.2l6.3-4.9l6.3-4.9c2.3-1.8,3-5.1,1.6-7.7l-7.7-14.3c3.8-4.9,6.9-10.4,9.2-16.3l16.2-0.7 c3-0.1,5.5-2.4,5.8-5.4l1-8l1-8c0.2-2.9-1.6-5.7-4.4-6.5l-15.5-4.6c-0.8-6.3-2.5-12.3-5-18l10.9-12c2-2.2,2.1-5.6,0.3-7.9 l-4.9-6.3l-4.9-6.3c-1-1.3-2.5-2.1-4.1-2.3c-1.2-0.1-2.5,0.1-3.6,0.7l-14.3,7.7c-5-3.9-10.6-7-16.7-9.3l-0.8-16.1 c-0.1-3-2.4-5.5-5.4-5.8l-8-1l-8-1l0,0c-2.9-0.3-5.8,1.5-6.6,4.4l-4.7,15.7c-6.3,0.8-12.3,2.6-18,5.1l-11.8-11 c-1-0.9-2.2-1.4-3.4-1.6c-1.6-0.2-3.2,0.2-4.5,1.2l-6.3,4.9l-6.3,4.9c-2.3,1.8-3,5.1-1.6,7.7l7.8,14.4c-3.7,4.9-6.8,10.4-9.1,16.3 l-16,0.7c-3,0.1-5.5,2.4-5.8,5.4l-1,8l-1,8c-0.4,2.9,1.5,5.8,4.4,6.7l15.7,4.7c0.8,6.3,2.6,12.3,5.1,18l-10.8,11.9 c-2,2.2-2.1,5.6-0.3,7.9l4.9,6.3l4.9,6.3c1,1.3,2.5,2.1,4.1,2.3c1.2,0.1,2.5-0.1,3.6-0.7l14.4-7.8c4.8,3.6,10.2,6.7,15.9,8.9 l0.7,16.2c0.1,3,2.4,5.5,5.4,5.8L364,229.486z M339.4,142.886c0.7-19.4,17-34.5,36.4-33.8s34.5,17,33.8,36.4 c-0.7,19.4-17,34.5-36.4,33.8C353.8,178.586,338.7,162.286,339.4,142.886z"></path> <path d="M483.6,293.386l-0.7-6l-0.7-6c-0.4-2.1-2.2-3.8-4.4-4l-12.1-0.6c-1.7-4.4-4-8.5-6.8-12.2l5.8-10.6c1.1-2,0.6-4.4-1.2-5.8 l-4.7-3.7l-4.7-3.7c-1-0.8-2.2-1.1-3.4-1c-0.9,0.1-1.8,0.5-2.5,1.1l-9,8.1c-4.3-2-8.9-3.3-13.7-3.9l-3.3-11.5 c-0.6-2.2-2.7-3.5-4.9-3.3l-6,0.7l-6,0.7l0,0c-2.2,0.3-3.9,2.1-4,4.3l-0.7,12.2c-4.4,1.7-8.5,4.1-12.1,6.8l-10.5-5.9 c-0.9-0.5-1.8-0.6-2.8-0.5c-1.2,0.1-2.3,0.7-3,1.7l-3.7,4.7l-3.7,4.7c-1.4,1.7-1.3,4.2,0.2,5.9l8.2,9.1c-1.9,4.2-3.1,8.7-3.7,13.4 l-11.5,3.3c-2.2,0.6-3.5,2.7-3.3,4.9l0.7,6l0.7,6c0.3,2.2,2.1,3.9,4.3,4.1l12.2,0.7c1.7,4.4,4.1,8.5,6.8,12.1l-5.8,10.5 c-1.1,2-0.6,4.4,1.2,5.8l4.7,3.7l4.7,3.7c1,0.8,2.2,1.1,3.4,1c0.9-0.1,1.8-0.5,2.5-1.1l9.1-8.2c4.1,1.8,8.6,3.1,13.1,3.7l3.3,11.6 c0.6,2.2,2.7,3.5,4.9,3.3l6-0.7l6-0.7l0,0c2.2-0.3,3.9-2.1,4-4.3l0.6-12.1c4.4-1.7,8.5-4,12.2-6.8l10.6,5.8 c0.9,0.5,1.8,0.6,2.8,0.5c1.2-0.1,2.3-0.7,3-1.7l3.7-4.7l3.7-4.7c1.4-1.7,1.3-4.2-0.2-5.9l-8.1-9c1.9-4.2,3.2-8.7,3.8-13.4 l11.6-3.3C482.5,297.686,483.9,295.586,483.6,293.386z M424.4,320.486c-14.2,2.9-28-6.3-30.9-20.5s6.3-28,20.5-30.9 s28,6.3,30.9,20.5S438.6,317.586,424.4,320.486z"></path> <path d="M216.7,294.486c-17.3-4.4-31.3-14.3-31.3-14.3l-11,34.7l-2.1,6.5v-0.1l-1.8,5.5l-5.8-16.4c14.6-20.4-3.9-19.6-3.9-19.6 s-18.5-0.8-3.9,19.6l-5.8,16.5l-1.8-5.6l-13-41.2c0,0-14.1,9.9-31.3,14.3c-8.6,2.2-11.7,9.6-12.7,16.4 c15.5,21.3,40.5,34.7,68.4,34.7c6.4,0,12.8-0.7,19.1-2.2c20.2-4.6,37.7-16.3,49.7-32.9C228.3,303.786,225.1,296.686,216.7,294.486 z"></path> <path d="M131.8,257.286c1.5,9.7,8.9,22,21.2,26.3c5,1.8,10.5,1.8,15.6,0c12.1-4.3,19.8-16.6,21.3-26.2c1.6-0.1,3.8-2.4,6.1-10.5 c3.1-11.1-0.2-12.8-3-12.5c0.5-1.5,0.9-3.1,1.2-4.6c4.8-28.8-9.4-29.8-9.4-29.8s-2.3-4.5-8.5-7.9c-4.1-2.5-9.9-4.4-17.5-3.7 c-2.5,0.1-4.8,0.6-7,1.3l0,0c-2.8,0.9-5.4,2.3-7.7,3.9c-2.8,1.8-5.5,4-7.9,6.5c-3.8,3.8-7.1,8.8-8.5,15c-1.2,4.6-0.9,9.5,0.1,14.6 l0,0c0.3,1.5,0.7,3,1.2,4.6c-2.8-0.3-6.2,1.4-3,12.5C128,254.786,130.2,257.086,131.8,257.286z"></path> </g> </g> </g></svg> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" shape-rendering="geometricPrecision"
                                text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd"
                                clip-rule="evenodd" viewBox="0 0 457 511.68">
                                <path fill="currentColor"
                                    d="M227.72 0c85.2 54.01 162.19 79.57 228.25 73.52 5.2 105.28-9.49 191.11-41.19 258.9a114.368 114.368 0 0 0-26.79-16.38c25.61-56.77 37.39-128.15 33.08-215.27-55.93 5.11-121.1-16.52-193.24-62.25C165.42 87.01 101 101.21 35.34 97.77c-2.91 110.9 16.58 194.39 54.22 255.13 27.83-23.27 75.23-19.75 97.67-50.61 1.61-2.39 2.36-3.67 2.35-4.73-.02-.54-24.36-30.39-26.54-33.87-5.74-9.12-16.48-21.5-16.48-32.19 0-6.03 4.76-13.9 11.57-15.65-.53-9.04-.89-18.21-.89-27.29 0-5.37.11-10.8.3-16.12.3-3.37.92-4.86 1.81-8.11a57.457 57.457 0 0 1 25.62-32.54c4.35-2.75 9.08-4.88 13.92-6.63 8.79-3.21 4.53-17.1 14.18-17.31 22.54-.58 59.63 19.13 74.08 34.78 9.21 10.19 14.46 21.51 14.78 35.23l-.92 39.49c4 .98 8.49 4.12 9.47 8.12 3.09 12.47-9.85 27.99-15.86 37.89-5.54 9.15-26.73 34.12-26.75 34.32-.1 1.08.45 2.42 1.9 4.61 5.46 7.49 12.38 12.95 20.13 17.27a114.783 114.783 0 0 0-27.85 20.47c-20.62 20.62-33.38 49.13-33.38 80.61 0 26.89 9.36 51.63 24.99 71.14-8.14 3.74-16.49 7.24-25.06 10.46C81.11 448.4-6.13 316.51.34 69.98 77.89 74.05 153.99 57.28 227.72 0z" />
                                <path fill="currentColor"
                                    d="M342.67 329.6c50.27 0 91.03 40.76 91.03 91.04 0 50.28-40.76 91.04-91.03 91.04-50.28 0-91.04-40.76-91.04-91.04 0-50.28 40.76-91.04 91.04-91.04zm-22.3 94.31a83.14 83.14 0 0 1 7.82 7.57c7.65-12.31 15.81-23.62 24.43-34.02 24.36-29.42 13.32-23.75 47.11-23.75l-4.7 5.22c-14.42 16.03-19.29 24.37-31.12 41.46a486.113 486.113 0 0 0-31.81 52.92l-2.92 5.65-2.7-5.76c-4.95-10.65-10.9-20.42-17.99-29.16-7.08-8.73-13.28-14.43-22.89-21.03 4.41-14.46 25.37-7.06 34.77.9z" />
                            </svg>
                        </i>
                        <span class="item-name">Join as Restaurant
                            {{-- <span class="badge rounded-pill bg-success item-name"> {{App\Models\PaymentRequest::where('payment_status', 'pending')->count()}}</span> --}}
                        </span>
                    </a>
                    <a class="nav-link" aria-current="page" href="{{route('admin.joinas.deliveryman')}}">
                        <i class="icon">
                            {{-- <svg fill="currentColor"  width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.64 483.64" xml:space="preserve" stroke="#7d5454"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M312.4,238.886l-29.9-5.1c-2.9-11.5-7.4-22.4-13.3-32.4l17.7-24.7c3.3-4.6,2.8-10.8-1.2-14.8l-10.5-10.6l-10.5-10.6 c-2.2-2.2-5.2-3.4-8.1-3.4c-2.3,0-4.6,0.7-6.6,2.1l-24.9,17.4c-10.1-6.1-21.2-10.8-33.1-13.7l-4.9-29.7c-0.9-5.6-5.7-9.6-11.3-9.7 l-15-0.1l-15-0.1l0,0c-5.5,0-10.4,4-11.4,9.6l-5.3,30.2c-11.5,2.9-22.4,7.6-32.3,13.4l-24.3-17.8c-2-1.4-4.4-2.2-6.7-2.2 c-2.9,0-5.9,1.1-8.1,3.3l-10.6,10.5l-10.6,10.5c-3.9,3.9-4.5,10.1-1.3,14.8l17.7,25c-5.9,10-10.4,20.8-13.3,32.3l-29.7,4.9 c-5.6,0.9-9.6,5.7-9.7,11.3l-0.1,15l-0.1,15c0,5.6,4,10.5,9.6,11.4l30.2,5.3c2.9,11.5,7.6,22.4,13.4,32.3l-17.6,24.5 c-3.3,4.6-2.8,10.8,1.2,14.8l10.5,10.6l10.5,10.9c2.2,2.2,5.2,3.4,8.1,3.4c2.3,0,4.6-0.7,6.6-2.1l25-17.7 c9.7,5.7,20.4,10.2,31.5,13.1l4.9,30c0.9,5.6,5.7,9.6,11.3,9.7l15,0.1l15,0.1l0,0c5.5,0,10.4-4,11.4-9.6l5.1-29.9 c11.5-2.9,22.4-7.4,32.4-13.3l24.7,17.7c2,1.4,4.4,2.2,6.7,2.2c2.9,0,5.9-1.1,8.1-3.3l10.6-10.5l10.6-10.5 c3.9-3.9,4.5-10.1,1.3-14.8l-17.5-24.9c6-10,10.5-20.8,13.5-32.3l30-4.9c5.6-0.9,9.6-5.7,9.7-11.3l0.1-15l0.1-15 C321.9,244.586,317.9,239.886,312.4,238.886z M182.7,359.186c-7.3,1.7-14.6,2.5-21.8,2.5c-44.2,0-84.2-30.4-94.6-75.4 c-12-52.3,20.6-104.4,72.9-116.4c7.3-1.7,14.6-2.5,21.8-2.5c44.2,0,84.2,30.4,94.6,75.4 C267.6,295.086,234.9,347.186,182.7,359.186z"></path> <path d="M364,229.486l8,1l0,0c2.9,0.3,5.8-1.5,6.6-4.4l4.6-15.5c6.3-0.8,12.3-2.5,18-5l12,10.9c1,0.9,2.2,1.4,3.4,1.6 c1.6,0.2,3.2-0.2,4.5-1.2l6.3-4.9l6.3-4.9c2.3-1.8,3-5.1,1.6-7.7l-7.7-14.3c3.8-4.9,6.9-10.4,9.2-16.3l16.2-0.7 c3-0.1,5.5-2.4,5.8-5.4l1-8l1-8c0.2-2.9-1.6-5.7-4.4-6.5l-15.5-4.6c-0.8-6.3-2.5-12.3-5-18l10.9-12c2-2.2,2.1-5.6,0.3-7.9 l-4.9-6.3l-4.9-6.3c-1-1.3-2.5-2.1-4.1-2.3c-1.2-0.1-2.5,0.1-3.6,0.7l-14.3,7.7c-5-3.9-10.6-7-16.7-9.3l-0.8-16.1 c-0.1-3-2.4-5.5-5.4-5.8l-8-1l-8-1l0,0c-2.9-0.3-5.8,1.5-6.6,4.4l-4.7,15.7c-6.3,0.8-12.3,2.6-18,5.1l-11.8-11 c-1-0.9-2.2-1.4-3.4-1.6c-1.6-0.2-3.2,0.2-4.5,1.2l-6.3,4.9l-6.3,4.9c-2.3,1.8-3,5.1-1.6,7.7l7.8,14.4c-3.7,4.9-6.8,10.4-9.1,16.3 l-16,0.7c-3,0.1-5.5,2.4-5.8,5.4l-1,8l-1,8c-0.4,2.9,1.5,5.8,4.4,6.7l15.7,4.7c0.8,6.3,2.6,12.3,5.1,18l-10.8,11.9 c-2,2.2-2.1,5.6-0.3,7.9l4.9,6.3l4.9,6.3c1,1.3,2.5,2.1,4.1,2.3c1.2,0.1,2.5-0.1,3.6-0.7l14.4-7.8c4.8,3.6,10.2,6.7,15.9,8.9 l0.7,16.2c0.1,3,2.4,5.5,5.4,5.8L364,229.486z M339.4,142.886c0.7-19.4,17-34.5,36.4-33.8s34.5,17,33.8,36.4 c-0.7,19.4-17,34.5-36.4,33.8C353.8,178.586,338.7,162.286,339.4,142.886z"></path> <path d="M483.6,293.386l-0.7-6l-0.7-6c-0.4-2.1-2.2-3.8-4.4-4l-12.1-0.6c-1.7-4.4-4-8.5-6.8-12.2l5.8-10.6c1.1-2,0.6-4.4-1.2-5.8 l-4.7-3.7l-4.7-3.7c-1-0.8-2.2-1.1-3.4-1c-0.9,0.1-1.8,0.5-2.5,1.1l-9,8.1c-4.3-2-8.9-3.3-13.7-3.9l-3.3-11.5 c-0.6-2.2-2.7-3.5-4.9-3.3l-6,0.7l-6,0.7l0,0c-2.2,0.3-3.9,2.1-4,4.3l-0.7,12.2c-4.4,1.7-8.5,4.1-12.1,6.8l-10.5-5.9 c-0.9-0.5-1.8-0.6-2.8-0.5c-1.2,0.1-2.3,0.7-3,1.7l-3.7,4.7l-3.7,4.7c-1.4,1.7-1.3,4.2,0.2,5.9l8.2,9.1c-1.9,4.2-3.1,8.7-3.7,13.4 l-11.5,3.3c-2.2,0.6-3.5,2.7-3.3,4.9l0.7,6l0.7,6c0.3,2.2,2.1,3.9,4.3,4.1l12.2,0.7c1.7,4.4,4.1,8.5,6.8,12.1l-5.8,10.5 c-1.1,2-0.6,4.4,1.2,5.8l4.7,3.7l4.7,3.7c1,0.8,2.2,1.1,3.4,1c0.9-0.1,1.8-0.5,2.5-1.1l9.1-8.2c4.1,1.8,8.6,3.1,13.1,3.7l3.3,11.6 c0.6,2.2,2.7,3.5,4.9,3.3l6-0.7l6-0.7l0,0c2.2-0.3,3.9-2.1,4-4.3l0.6-12.1c4.4-1.7,8.5-4,12.2-6.8l10.6,5.8 c0.9,0.5,1.8,0.6,2.8,0.5c1.2-0.1,2.3-0.7,3-1.7l3.7-4.7l3.7-4.7c1.4-1.7,1.3-4.2-0.2-5.9l-8.1-9c1.9-4.2,3.2-8.7,3.8-13.4 l11.6-3.3C482.5,297.686,483.9,295.586,483.6,293.386z M424.4,320.486c-14.2,2.9-28-6.3-30.9-20.5s6.3-28,20.5-30.9 s28,6.3,30.9,20.5S438.6,317.586,424.4,320.486z"></path> <path d="M216.7,294.486c-17.3-4.4-31.3-14.3-31.3-14.3l-11,34.7l-2.1,6.5v-0.1l-1.8,5.5l-5.8-16.4c14.6-20.4-3.9-19.6-3.9-19.6 s-18.5-0.8-3.9,19.6l-5.8,16.5l-1.8-5.6l-13-41.2c0,0-14.1,9.9-31.3,14.3c-8.6,2.2-11.7,9.6-12.7,16.4 c15.5,21.3,40.5,34.7,68.4,34.7c6.4,0,12.8-0.7,19.1-2.2c20.2-4.6,37.7-16.3,49.7-32.9C228.3,303.786,225.1,296.686,216.7,294.486 z"></path> <path d="M131.8,257.286c1.5,9.7,8.9,22,21.2,26.3c5,1.8,10.5,1.8,15.6,0c12.1-4.3,19.8-16.6,21.3-26.2c1.6-0.1,3.8-2.4,6.1-10.5 c3.1-11.1-0.2-12.8-3-12.5c0.5-1.5,0.9-3.1,1.2-4.6c4.8-28.8-9.4-29.8-9.4-29.8s-2.3-4.5-8.5-7.9c-4.1-2.5-9.9-4.4-17.5-3.7 c-2.5,0.1-4.8,0.6-7,1.3l0,0c-2.8,0.9-5.4,2.3-7.7,3.9c-2.8,1.8-5.5,4-7.9,6.5c-3.8,3.8-7.1,8.8-8.5,15c-1.2,4.6-0.9,9.5,0.1,14.6 l0,0c0.3,1.5,0.7,3,1.2,4.6c-2.8-0.3-6.2,1.4-3,12.5C128,254.786,130.2,257.086,131.8,257.286z"></path> </g> </g> </g></svg> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" shape-rendering="geometricPrecision"
                                text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd"
                                clip-rule="evenodd" viewBox="0 0 457 511.68">
                                <path fill="currentColor"
                                    d="M227.72 0c85.2 54.01 162.19 79.57 228.25 73.52 5.2 105.28-9.49 191.11-41.19 258.9a114.368 114.368 0 0 0-26.79-16.38c25.61-56.77 37.39-128.15 33.08-215.27-55.93 5.11-121.1-16.52-193.24-62.25C165.42 87.01 101 101.21 35.34 97.77c-2.91 110.9 16.58 194.39 54.22 255.13 27.83-23.27 75.23-19.75 97.67-50.61 1.61-2.39 2.36-3.67 2.35-4.73-.02-.54-24.36-30.39-26.54-33.87-5.74-9.12-16.48-21.5-16.48-32.19 0-6.03 4.76-13.9 11.57-15.65-.53-9.04-.89-18.21-.89-27.29 0-5.37.11-10.8.3-16.12.3-3.37.92-4.86 1.81-8.11a57.457 57.457 0 0 1 25.62-32.54c4.35-2.75 9.08-4.88 13.92-6.63 8.79-3.21 4.53-17.1 14.18-17.31 22.54-.58 59.63 19.13 74.08 34.78 9.21 10.19 14.46 21.51 14.78 35.23l-.92 39.49c4 .98 8.49 4.12 9.47 8.12 3.09 12.47-9.85 27.99-15.86 37.89-5.54 9.15-26.73 34.12-26.75 34.32-.1 1.08.45 2.42 1.9 4.61 5.46 7.49 12.38 12.95 20.13 17.27a114.783 114.783 0 0 0-27.85 20.47c-20.62 20.62-33.38 49.13-33.38 80.61 0 26.89 9.36 51.63 24.99 71.14-8.14 3.74-16.49 7.24-25.06 10.46C81.11 448.4-6.13 316.51.34 69.98 77.89 74.05 153.99 57.28 227.72 0z" />
                                <path fill="currentColor"
                                    d="M342.67 329.6c50.27 0 91.03 40.76 91.03 91.04 0 50.28-40.76 91.04-91.03 91.04-50.28 0-91.04-40.76-91.04-91.04 0-50.28 40.76-91.04 91.04-91.04zm-22.3 94.31a83.14 83.14 0 0 1 7.82 7.57c7.65-12.31 15.81-23.62 24.43-34.02 24.36-29.42 13.32-23.75 47.11-23.75l-4.7 5.22c-14.42 16.03-19.29 24.37-31.12 41.46a486.113 486.113 0 0 0-31.81 52.92l-2.92 5.65-2.7-5.76c-4.95-10.65-10.9-20.42-17.99-29.16-7.08-8.73-13.28-14.43-22.89-21.03 4.41-14.46 25.37-7.06 34.77.9z" />
                            </svg>
                        </i>
                        <span class="item-name">Join as Deliveryman
                            {{-- <span class="badge rounded-pill bg-success item-name"> {{App\Models\PaymentRequest::where('payment_status', 'pending')->count()}}</span> --}}
                        </span>
                    </a>
                </li>

                

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Business Setting</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.business-settings.business-setup')}}">
                        <i class="icon">
                            <svg fill="currentColor"  width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.64 483.64" xml:space="preserve" stroke="#7d5454"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M312.4,238.886l-29.9-5.1c-2.9-11.5-7.4-22.4-13.3-32.4l17.7-24.7c3.3-4.6,2.8-10.8-1.2-14.8l-10.5-10.6l-10.5-10.6 c-2.2-2.2-5.2-3.4-8.1-3.4c-2.3,0-4.6,0.7-6.6,2.1l-24.9,17.4c-10.1-6.1-21.2-10.8-33.1-13.7l-4.9-29.7c-0.9-5.6-5.7-9.6-11.3-9.7 l-15-0.1l-15-0.1l0,0c-5.5,0-10.4,4-11.4,9.6l-5.3,30.2c-11.5,2.9-22.4,7.6-32.3,13.4l-24.3-17.8c-2-1.4-4.4-2.2-6.7-2.2 c-2.9,0-5.9,1.1-8.1,3.3l-10.6,10.5l-10.6,10.5c-3.9,3.9-4.5,10.1-1.3,14.8l17.7,25c-5.9,10-10.4,20.8-13.3,32.3l-29.7,4.9 c-5.6,0.9-9.6,5.7-9.7,11.3l-0.1,15l-0.1,15c0,5.6,4,10.5,9.6,11.4l30.2,5.3c2.9,11.5,7.6,22.4,13.4,32.3l-17.6,24.5 c-3.3,4.6-2.8,10.8,1.2,14.8l10.5,10.6l10.5,10.9c2.2,2.2,5.2,3.4,8.1,3.4c2.3,0,4.6-0.7,6.6-2.1l25-17.7 c9.7,5.7,20.4,10.2,31.5,13.1l4.9,30c0.9,5.6,5.7,9.6,11.3,9.7l15,0.1l15,0.1l0,0c5.5,0,10.4-4,11.4-9.6l5.1-29.9 c11.5-2.9,22.4-7.4,32.4-13.3l24.7,17.7c2,1.4,4.4,2.2,6.7,2.2c2.9,0,5.9-1.1,8.1-3.3l10.6-10.5l10.6-10.5 c3.9-3.9,4.5-10.1,1.3-14.8l-17.5-24.9c6-10,10.5-20.8,13.5-32.3l30-4.9c5.6-0.9,9.6-5.7,9.7-11.3l0.1-15l0.1-15 C321.9,244.586,317.9,239.886,312.4,238.886z M182.7,359.186c-7.3,1.7-14.6,2.5-21.8,2.5c-44.2,0-84.2-30.4-94.6-75.4 c-12-52.3,20.6-104.4,72.9-116.4c7.3-1.7,14.6-2.5,21.8-2.5c44.2,0,84.2,30.4,94.6,75.4 C267.6,295.086,234.9,347.186,182.7,359.186z"></path> <path d="M364,229.486l8,1l0,0c2.9,0.3,5.8-1.5,6.6-4.4l4.6-15.5c6.3-0.8,12.3-2.5,18-5l12,10.9c1,0.9,2.2,1.4,3.4,1.6 c1.6,0.2,3.2-0.2,4.5-1.2l6.3-4.9l6.3-4.9c2.3-1.8,3-5.1,1.6-7.7l-7.7-14.3c3.8-4.9,6.9-10.4,9.2-16.3l16.2-0.7 c3-0.1,5.5-2.4,5.8-5.4l1-8l1-8c0.2-2.9-1.6-5.7-4.4-6.5l-15.5-4.6c-0.8-6.3-2.5-12.3-5-18l10.9-12c2-2.2,2.1-5.6,0.3-7.9 l-4.9-6.3l-4.9-6.3c-1-1.3-2.5-2.1-4.1-2.3c-1.2-0.1-2.5,0.1-3.6,0.7l-14.3,7.7c-5-3.9-10.6-7-16.7-9.3l-0.8-16.1 c-0.1-3-2.4-5.5-5.4-5.8l-8-1l-8-1l0,0c-2.9-0.3-5.8,1.5-6.6,4.4l-4.7,15.7c-6.3,0.8-12.3,2.6-18,5.1l-11.8-11 c-1-0.9-2.2-1.4-3.4-1.6c-1.6-0.2-3.2,0.2-4.5,1.2l-6.3,4.9l-6.3,4.9c-2.3,1.8-3,5.1-1.6,7.7l7.8,14.4c-3.7,4.9-6.8,10.4-9.1,16.3 l-16,0.7c-3,0.1-5.5,2.4-5.8,5.4l-1,8l-1,8c-0.4,2.9,1.5,5.8,4.4,6.7l15.7,4.7c0.8,6.3,2.6,12.3,5.1,18l-10.8,11.9 c-2,2.2-2.1,5.6-0.3,7.9l4.9,6.3l4.9,6.3c1,1.3,2.5,2.1,4.1,2.3c1.2,0.1,2.5-0.1,3.6-0.7l14.4-7.8c4.8,3.6,10.2,6.7,15.9,8.9 l0.7,16.2c0.1,3,2.4,5.5,5.4,5.8L364,229.486z M339.4,142.886c0.7-19.4,17-34.5,36.4-33.8s34.5,17,33.8,36.4 c-0.7,19.4-17,34.5-36.4,33.8C353.8,178.586,338.7,162.286,339.4,142.886z"></path> <path d="M483.6,293.386l-0.7-6l-0.7-6c-0.4-2.1-2.2-3.8-4.4-4l-12.1-0.6c-1.7-4.4-4-8.5-6.8-12.2l5.8-10.6c1.1-2,0.6-4.4-1.2-5.8 l-4.7-3.7l-4.7-3.7c-1-0.8-2.2-1.1-3.4-1c-0.9,0.1-1.8,0.5-2.5,1.1l-9,8.1c-4.3-2-8.9-3.3-13.7-3.9l-3.3-11.5 c-0.6-2.2-2.7-3.5-4.9-3.3l-6,0.7l-6,0.7l0,0c-2.2,0.3-3.9,2.1-4,4.3l-0.7,12.2c-4.4,1.7-8.5,4.1-12.1,6.8l-10.5-5.9 c-0.9-0.5-1.8-0.6-2.8-0.5c-1.2,0.1-2.3,0.7-3,1.7l-3.7,4.7l-3.7,4.7c-1.4,1.7-1.3,4.2,0.2,5.9l8.2,9.1c-1.9,4.2-3.1,8.7-3.7,13.4 l-11.5,3.3c-2.2,0.6-3.5,2.7-3.3,4.9l0.7,6l0.7,6c0.3,2.2,2.1,3.9,4.3,4.1l12.2,0.7c1.7,4.4,4.1,8.5,6.8,12.1l-5.8,10.5 c-1.1,2-0.6,4.4,1.2,5.8l4.7,3.7l4.7,3.7c1,0.8,2.2,1.1,3.4,1c0.9-0.1,1.8-0.5,2.5-1.1l9.1-8.2c4.1,1.8,8.6,3.1,13.1,3.7l3.3,11.6 c0.6,2.2,2.7,3.5,4.9,3.3l6-0.7l6-0.7l0,0c2.2-0.3,3.9-2.1,4-4.3l0.6-12.1c4.4-1.7,8.5-4,12.2-6.8l10.6,5.8 c0.9,0.5,1.8,0.6,2.8,0.5c1.2-0.1,2.3-0.7,3-1.7l3.7-4.7l3.7-4.7c1.4-1.7,1.3-4.2-0.2-5.9l-8.1-9c1.9-4.2,3.2-8.7,3.8-13.4 l11.6-3.3C482.5,297.686,483.9,295.586,483.6,293.386z M424.4,320.486c-14.2,2.9-28-6.3-30.9-20.5s6.3-28,20.5-30.9 s28,6.3,30.9,20.5S438.6,317.586,424.4,320.486z"></path> <path d="M216.7,294.486c-17.3-4.4-31.3-14.3-31.3-14.3l-11,34.7l-2.1,6.5v-0.1l-1.8,5.5l-5.8-16.4c14.6-20.4-3.9-19.6-3.9-19.6 s-18.5-0.8-3.9,19.6l-5.8,16.5l-1.8-5.6l-13-41.2c0,0-14.1,9.9-31.3,14.3c-8.6,2.2-11.7,9.6-12.7,16.4 c15.5,21.3,40.5,34.7,68.4,34.7c6.4,0,12.8-0.7,19.1-2.2c20.2-4.6,37.7-16.3,49.7-32.9C228.3,303.786,225.1,296.686,216.7,294.486 z"></path> <path d="M131.8,257.286c1.5,9.7,8.9,22,21.2,26.3c5,1.8,10.5,1.8,15.6,0c12.1-4.3,19.8-16.6,21.3-26.2c1.6-0.1,3.8-2.4,6.1-10.5 c3.1-11.1-0.2-12.8-3-12.5c0.5-1.5,0.9-3.1,1.2-4.6c4.8-28.8-9.4-29.8-9.4-29.8s-2.3-4.5-8.5-7.9c-4.1-2.5-9.9-4.4-17.5-3.7 c-2.5,0.1-4.8,0.6-7,1.3l0,0c-2.8,0.9-5.4,2.3-7.7,3.9c-2.8,1.8-5.5,4-7.9,6.5c-3.8,3.8-7.1,8.8-8.5,15c-1.2,4.6-0.9,9.5,0.1,14.6 l0,0c0.3,1.5,0.7,3,1.2,4.6c-2.8-0.3-6.2,1.4-3,12.5C128,254.786,130.2,257.086,131.8,257.286z"></path> </g> </g> </g></svg>
                        </i>
                        <span class="item-name">Business Setup<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#bussiness-pages" role="button"
                        aria-expanded="false" aria-controls="bussiness-pages">
                        <i class="icon">
                            <svg fill="currentColor"  width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.64 483.64" xml:space="preserve" stroke="#7d5454"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M312.4,238.886l-29.9-5.1c-2.9-11.5-7.4-22.4-13.3-32.4l17.7-24.7c3.3-4.6,2.8-10.8-1.2-14.8l-10.5-10.6l-10.5-10.6 c-2.2-2.2-5.2-3.4-8.1-3.4c-2.3,0-4.6,0.7-6.6,2.1l-24.9,17.4c-10.1-6.1-21.2-10.8-33.1-13.7l-4.9-29.7c-0.9-5.6-5.7-9.6-11.3-9.7 l-15-0.1l-15-0.1l0,0c-5.5,0-10.4,4-11.4,9.6l-5.3,30.2c-11.5,2.9-22.4,7.6-32.3,13.4l-24.3-17.8c-2-1.4-4.4-2.2-6.7-2.2 c-2.9,0-5.9,1.1-8.1,3.3l-10.6,10.5l-10.6,10.5c-3.9,3.9-4.5,10.1-1.3,14.8l17.7,25c-5.9,10-10.4,20.8-13.3,32.3l-29.7,4.9 c-5.6,0.9-9.6,5.7-9.7,11.3l-0.1,15l-0.1,15c0,5.6,4,10.5,9.6,11.4l30.2,5.3c2.9,11.5,7.6,22.4,13.4,32.3l-17.6,24.5 c-3.3,4.6-2.8,10.8,1.2,14.8l10.5,10.6l10.5,10.9c2.2,2.2,5.2,3.4,8.1,3.4c2.3,0,4.6-0.7,6.6-2.1l25-17.7 c9.7,5.7,20.4,10.2,31.5,13.1l4.9,30c0.9,5.6,5.7,9.6,11.3,9.7l15,0.1l15,0.1l0,0c5.5,0,10.4-4,11.4-9.6l5.1-29.9 c11.5-2.9,22.4-7.4,32.4-13.3l24.7,17.7c2,1.4,4.4,2.2,6.7,2.2c2.9,0,5.9-1.1,8.1-3.3l10.6-10.5l10.6-10.5 c3.9-3.9,4.5-10.1,1.3-14.8l-17.5-24.9c6-10,10.5-20.8,13.5-32.3l30-4.9c5.6-0.9,9.6-5.7,9.7-11.3l0.1-15l0.1-15 C321.9,244.586,317.9,239.886,312.4,238.886z M182.7,359.186c-7.3,1.7-14.6,2.5-21.8,2.5c-44.2,0-84.2-30.4-94.6-75.4 c-12-52.3,20.6-104.4,72.9-116.4c7.3-1.7,14.6-2.5,21.8-2.5c44.2,0,84.2,30.4,94.6,75.4 C267.6,295.086,234.9,347.186,182.7,359.186z"></path> <path d="M364,229.486l8,1l0,0c2.9,0.3,5.8-1.5,6.6-4.4l4.6-15.5c6.3-0.8,12.3-2.5,18-5l12,10.9c1,0.9,2.2,1.4,3.4,1.6 c1.6,0.2,3.2-0.2,4.5-1.2l6.3-4.9l6.3-4.9c2.3-1.8,3-5.1,1.6-7.7l-7.7-14.3c3.8-4.9,6.9-10.4,9.2-16.3l16.2-0.7 c3-0.1,5.5-2.4,5.8-5.4l1-8l1-8c0.2-2.9-1.6-5.7-4.4-6.5l-15.5-4.6c-0.8-6.3-2.5-12.3-5-18l10.9-12c2-2.2,2.1-5.6,0.3-7.9 l-4.9-6.3l-4.9-6.3c-1-1.3-2.5-2.1-4.1-2.3c-1.2-0.1-2.5,0.1-3.6,0.7l-14.3,7.7c-5-3.9-10.6-7-16.7-9.3l-0.8-16.1 c-0.1-3-2.4-5.5-5.4-5.8l-8-1l-8-1l0,0c-2.9-0.3-5.8,1.5-6.6,4.4l-4.7,15.7c-6.3,0.8-12.3,2.6-18,5.1l-11.8-11 c-1-0.9-2.2-1.4-3.4-1.6c-1.6-0.2-3.2,0.2-4.5,1.2l-6.3,4.9l-6.3,4.9c-2.3,1.8-3,5.1-1.6,7.7l7.8,14.4c-3.7,4.9-6.8,10.4-9.1,16.3 l-16,0.7c-3,0.1-5.5,2.4-5.8,5.4l-1,8l-1,8c-0.4,2.9,1.5,5.8,4.4,6.7l15.7,4.7c0.8,6.3,2.6,12.3,5.1,18l-10.8,11.9 c-2,2.2-2.1,5.6-0.3,7.9l4.9,6.3l4.9,6.3c1,1.3,2.5,2.1,4.1,2.3c1.2,0.1,2.5-0.1,3.6-0.7l14.4-7.8c4.8,3.6,10.2,6.7,15.9,8.9 l0.7,16.2c0.1,3,2.4,5.5,5.4,5.8L364,229.486z M339.4,142.886c0.7-19.4,17-34.5,36.4-33.8s34.5,17,33.8,36.4 c-0.7,19.4-17,34.5-36.4,33.8C353.8,178.586,338.7,162.286,339.4,142.886z"></path> <path d="M483.6,293.386l-0.7-6l-0.7-6c-0.4-2.1-2.2-3.8-4.4-4l-12.1-0.6c-1.7-4.4-4-8.5-6.8-12.2l5.8-10.6c1.1-2,0.6-4.4-1.2-5.8 l-4.7-3.7l-4.7-3.7c-1-0.8-2.2-1.1-3.4-1c-0.9,0.1-1.8,0.5-2.5,1.1l-9,8.1c-4.3-2-8.9-3.3-13.7-3.9l-3.3-11.5 c-0.6-2.2-2.7-3.5-4.9-3.3l-6,0.7l-6,0.7l0,0c-2.2,0.3-3.9,2.1-4,4.3l-0.7,12.2c-4.4,1.7-8.5,4.1-12.1,6.8l-10.5-5.9 c-0.9-0.5-1.8-0.6-2.8-0.5c-1.2,0.1-2.3,0.7-3,1.7l-3.7,4.7l-3.7,4.7c-1.4,1.7-1.3,4.2,0.2,5.9l8.2,9.1c-1.9,4.2-3.1,8.7-3.7,13.4 l-11.5,3.3c-2.2,0.6-3.5,2.7-3.3,4.9l0.7,6l0.7,6c0.3,2.2,2.1,3.9,4.3,4.1l12.2,0.7c1.7,4.4,4.1,8.5,6.8,12.1l-5.8,10.5 c-1.1,2-0.6,4.4,1.2,5.8l4.7,3.7l4.7,3.7c1,0.8,2.2,1.1,3.4,1c0.9-0.1,1.8-0.5,2.5-1.1l9.1-8.2c4.1,1.8,8.6,3.1,13.1,3.7l3.3,11.6 c0.6,2.2,2.7,3.5,4.9,3.3l6-0.7l6-0.7l0,0c2.2-0.3,3.9-2.1,4-4.3l0.6-12.1c4.4-1.7,8.5-4,12.2-6.8l10.6,5.8 c0.9,0.5,1.8,0.6,2.8,0.5c1.2-0.1,2.3-0.7,3-1.7l3.7-4.7l3.7-4.7c1.4-1.7,1.3-4.2-0.2-5.9l-8.1-9c1.9-4.2,3.2-8.7,3.8-13.4 l11.6-3.3C482.5,297.686,483.9,295.586,483.6,293.386z M424.4,320.486c-14.2,2.9-28-6.3-30.9-20.5s6.3-28,20.5-30.9 s28,6.3,30.9,20.5S438.6,317.586,424.4,320.486z"></path> <path d="M216.7,294.486c-17.3-4.4-31.3-14.3-31.3-14.3l-11,34.7l-2.1,6.5v-0.1l-1.8,5.5l-5.8-16.4c14.6-20.4-3.9-19.6-3.9-19.6 s-18.5-0.8-3.9,19.6l-5.8,16.5l-1.8-5.6l-13-41.2c0,0-14.1,9.9-31.3,14.3c-8.6,2.2-11.7,9.6-12.7,16.4 c15.5,21.3,40.5,34.7,68.4,34.7c6.4,0,12.8-0.7,19.1-2.2c20.2-4.6,37.7-16.3,49.7-32.9C228.3,303.786,225.1,296.686,216.7,294.486 z"></path> <path d="M131.8,257.286c1.5,9.7,8.9,22,21.2,26.3c5,1.8,10.5,1.8,15.6,0c12.1-4.3,19.8-16.6,21.3-26.2c1.6-0.1,3.8-2.4,6.1-10.5 c3.1-11.1-0.2-12.8-3-12.5c0.5-1.5,0.9-3.1,1.2-4.6c4.8-28.8-9.4-29.8-9.4-29.8s-2.3-4.5-8.5-7.9c-4.1-2.5-9.9-4.4-17.5-3.7 c-2.5,0.1-4.8,0.6-7,1.3l0,0c-2.8,0.9-5.4,2.3-7.7,3.9c-2.8,1.8-5.5,4-7.9,6.5c-3.8,3.8-7.1,8.8-8.5,15c-1.2,4.6-0.9,9.5,0.1,14.6 l0,0c0.3,1.5,0.7,3,1.2,4.6c-2.8-0.3-6.2,1.4-3,12.5C128,254.786,130.2,257.086,131.8,257.286z"></path> </g> </g> </g></svg>
                        </i>
                        <span class="item-name">Business Pages </span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="bussiness-pages" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.business-settings.terms-and-conditions')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name">Terms &amp; Conditions  </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.business-settings.privacy-policy')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name">Privacy policy</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.business-settings.about-us')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name">About us</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.business-settings.refund-policy')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name">Refund Policy</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.business-settings.shipping-policy')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name">Shipping Policy</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('admin.business-settings.cancellation-policy')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name">Cancellation Policy</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('admin.referral.index')}}">
                        <i class="icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 8C18 12.4183 14.4183 16 10 16C5.58172 16 2 12.4183 2 8C2 3.58172 5.58172 0 10 0C14.4183 0 18 3.58172 18 8Z" fill="currentColor"/>
                                <path d="M22 16C22 18.2091 20.2091 20 18 20C15.7909 20 14 18.2091 14 16C14 13.7909 15.7909 12 18 12C20.2091 12 22 13.7909 22 16Z" fill="currentColor"/>
                                <path d="M8 24C10.2091 24 12 22.2091 12 20C12 17.7909 10.2091 16 8 16C5.79086 16 4 17.7909 4 20C4 22.2091 5.79086 24 8 24Z" fill="currentColor"/>
                                <path d="M10 8L14 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M6 20L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </i>
                        <span class="item-name">Referral System<span
                                class="badge rounded-pill bg-primary item-name"></span></span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#employee" role="button"
                        aria-expanded="false" aria-controls="employee">
                        <i class="icon">

                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="icon-20">
                                <path opacity="0.4"
                                    d="M10.0833 15.958H3.50777C2.67555 15.958 2 16.6217 2 17.4393C2 18.2559 2.67555 18.9207 3.50777 18.9207H10.0833C10.9155 18.9207 11.5911 18.2559 11.5911 17.4393C11.5911 16.6217 10.9155 15.958 10.0833 15.958Z"
                                    fill="currentColor"></path>
                                <path opacity="0.4"
                                    d="M22.0001 6.37867C22.0001 5.56214 21.3246 4.89844 20.4934 4.89844H13.9179C13.0857 4.89844 12.4102 5.56214 12.4102 6.37867C12.4102 7.1963 13.0857 7.86 13.9179 7.86H20.4934C21.3246 7.86 22.0001 7.1963 22.0001 6.37867Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M8.87774 6.37856C8.87774 8.24523 7.33886 9.75821 5.43887 9.75821C3.53999 9.75821 2 8.24523 2 6.37856C2 4.51298 3.53999 3 5.43887 3C7.33886 3 8.87774 4.51298 8.87774 6.37856Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M21.9998 17.3992C21.9998 19.2648 20.4609 20.7777 18.5609 20.7777C16.6621 20.7777 15.1221 19.2648 15.1221 17.3992C15.1221 15.5325 16.6621 14.0195 18.5609 14.0195C20.4609 14.0195 21.9998 15.5325 21.9998 17.3992Z"
                                    fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Employee  </span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="employee" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="dashboard/index-horizontal.html">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> H </i>
                                <span class="item-name"> Horizontal </span>
                            </a>
                        </li>
                    </ul>
                </li>




            </ul>
            <!-- Sidebar Menu End -->
        </div>
    </div>
    <div class="sidebar-footer"></div>
</aside>
