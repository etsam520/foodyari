<aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all ">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="{{route('mess.dashboard')}}" class="navbar-brand">
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

      @if(Cookie::has('active_store'))
        <?php
            $vendor =  App\Models\Vendor::with(['restaurants','messes'])->find(Session::get('restaurant')->vendor_id);
            $stores = [];
            foreach ($vendor->messes as  $mess) {
                $stores[] = [
                    'name' => $mess->name,
                    'type' => 'mess',
                    'id' => $mess->id
                ];
            }
            foreach ($vendor->restaurants as  $restaurant) {
                $stores[] = [
                    'name' => $restaurant->name,
                    'type' => 'restaurant',
                    'id' => $restaurant->id
                ];
            }
        ?>  <div class="sidebar-li        st">
            <div class="ms-2 w-100">
                <label class="visually-hidden" for="autoSizingInputGroup">Username</label>
                <div class="input-group">
                  <div class="input-group-text"><i class="fa-solid fa-store"></i></div>
                  <select type="text" class="form-control form-control-sm" onchange="location.href= this.value" id="autoSizingInputGroup">
                    @foreach ($stores as $store)
                    <option value="{{route('mess.dashboard-changer',['name'=>$store['name'],'type'=> $store['type'],'id'=>$store['id'] ])}}"
                        {{$store['type']=='mess'? $store['id']== Session::get('mess')->id ? 'selected' :null : null }}
                        >
                        {{Str::ucfirst($store['name'])}}
                    </option>
                    @endforeach
                    {{-- <option value="ABC Restfffaurant">Abc Resfftaurnt</option> --}}
                  </select>
                </div>
            </div>
        </div>
        @endif
        <div class="sidebar-list">
            <!-- Sidebar Menu Start -->
            <ul class="navbar-nav iq-main-menu mb-5" id="sidebar-menu">
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Home</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{route('mess.dashboard')}}">
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
                    <a class="nav-lin               k" aria-current="page" href="{{route('mess.mywallet')}}"
                        target="_blank">
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
                        <span class="item-name">My Wallet<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('mess.diet-calander.view')}}">
                        <i class="icon">
                            <svg class="icon-20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340 340" width="20">
                                <path
                                    d="M292.375 84.437c-.009-.042-.029-.079-.04-.12-.031-.122-.076-.237-.118-.355a3.953 3.953 0 0 0-.176-.43c-.052-.1-.112-.2-.172-.3a3.949 3.949 0 0 0-.274-.4c-.035-.043-.057-.093-.093-.135s-.094-.082-.137-.127a3.843 3.843 0 0 0-.343-.32 3.746 3.746 0 0 0-.283-.219 3.656 3.656 0 0 0-.391-.232 3.51 3.51 0 0 0-.318-.161 3.9 3.9 0 0 0-.454-.152c-.106-.031-.209-.068-.318-.09a3.979 3.979 0 0 0-.547-.06c-.08-.005-.158-.026-.238-.026h-42.346a4 4 0 0 0 0 8h37.709L255.36 281.386H14.637L43.112 89.314H71.1c.528 6.622 2.246 12.6 4.991 17.065a4 4 0 0 0 6.814-4.192c-2.937-4.774-4.389-12.412-3.886-20.428.42-6.7 2.2-13.1 4.882-17.572 2.172-3.623 4.761-5.706 7-5.564 3.136.2 6.3 4.758 7.881 11.352a4 4 0 0 0 7.781-1.864c-2.515-10.5-8.183-17.035-15.161-17.472-5.4-.337-10.506 3.01-14.359 9.432-3.369 5.615-5.5 13.139-6.007 21.186v.057H39.662a4 4 0 0 0-3.957 3.414L6.043 284.8A4 4 0 0 0 10 289.386h248.811a4 4 0 0 0 3.957-3.413l26.513-178.838L325 265.936h-44.3a4 4 0 0 0 0 8H330a4 4 0 0 0 3.9-4.878Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M73.942 209.788a4 4 0 1 0 0-8H57.31a4 4 0 0 0-3.957 3.413l-5.133 34.621a4 4 0 0 0 3.957 4.587H88.47A4 4 0 0 0 92.427 241l5.1-34.376 4.377-4.377a4 4 0 0 0-5.657-5.656l-22.58 22.572-1.218-1.217a4 4 0 1 0-5.657 5.657l4.046 4.045a4 4 0 0 0 5.657 0l11.533-11.533-3.008 20.294H56.814l3.946-26.621zm32.119-69.397a4 4 0 0 0-3.029-1.391H66.738a4 4 0 0 0-3.956 3.413l-5.133 34.621a4 4 0 0 0 3.957 4.587H97.9a4 4 0 0 0 3.957-3.413l5.132-34.621a4 4 0 0 0-.928-3.196zm-11.613 33.234H66.242L70.189 147H98.4zm27.437 70.534h36.294a4 4 0 0 0 3.956-3.413l5.133-34.621a4 4 0 0 0-3.956-4.587h-36.294a4 4 0 0 0-3.956 3.413l-5.133 34.621a4 4 0 0 0 3.956 4.587zm8.584-34.621h28.206l-3.947 26.621h-28.206zm41.095-31.576 5.133-34.621a4 4 0 0 0-3.957-4.587h-36.293a4 4 0 0 0-3.957 3.413l-5.133 34.621a4 4 0 0 0 3.957 4.587h36.293a4 4 0 0 0 3.957-3.413zm-35.613-4.587 3.946-26.621H168.1l-3.947 26.621zm60.775 28.413a4 4 0 0 0-3.956 3.413l-5.133 34.621a4 4 0 0 0 3.957 4.587h36.293a4 4 0 0 0 3.957-3.409l5.133-34.621a4 4 0 0 0-3.957-4.587zm27.711 34.621h-28.206l3.946-26.621h28.206zm16.835-58.197 4.9-33.069 6.4-6.4a4 4 0 0 0-5.657-5.656l-6.478 6.478a3.983 3.983 0 0 0-1.454 1.454l-14.643 14.645-1.217-1.217a4 4 0 0 0-5.657 5.657l4.046 4.045a4 4 0 0 0 5.657 0l9.511-9.511-2.815 18.987h-28.206L209.606 147h13.979a4 4 0 0 0 0-8h-17.43a4 4 0 0 0-3.957 3.413l-5.132 34.621a4 4 0 0 0 3.956 4.587h36.294a4 4 0 0 0 3.956-3.409zm-87.579-71.833a4 4 0 0 0 6.815-4.192c-2.937-4.774-4.39-12.412-3.887-20.428.42-6.7 2.2-13.1 4.882-17.572 2.173-3.622 4.763-5.666 7-5.564 3.136.2 6.3 4.758 7.882 11.352a4 4 0 0 0 7.78-1.864c-2.515-10.5-8.183-17.035-15.161-17.472-5.41-.347-10.507 3.009-14.359 9.432-3.368 5.615-5.5 13.139-6.006 21.186v.057H92.151a4 4 0 1 0 0 8H148.7c.531 6.622 2.248 12.601 4.993 17.065z"
                                    fill="currentColor"></path>
                                <path
                                    d="M253.988 69.975a4 4 0 0 0 7.781-1.864c-2.516-10.5-8.184-17.035-15.162-17.472-5.4-.337-10.506 3.01-14.359 9.432-3.368 5.615-5.5 13.139-6.006 21.186v.057h-58.215a4 4 0 0 0 0 8h58.28c.529 6.622 2.247 12.6 4.991 17.065a4 4 0 0 0 6.815-4.192c-2.937-4.774-4.389-12.412-3.886-20.428.42-6.7 2.2-13.1 4.882-17.572 2.172-3.623 4.763-5.706 7-5.564 3.133.196 6.301 4.758 7.879 11.352Z"
                                    fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Weekly Calander<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
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
                    <a class="nav-link" data-bs-toggle="collapse" href="#customers-s" role="button"
                        aria-expanded="false" aria-controls="customers-s">
                        <i class="icon">
                            <svg fill="currentColor" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-3.2 -3.2 38.40 38.40" xml:space="preserve" width="20" height="20" stroke="#a02727" stroke-width="0.00032" transform="rotate(0)">
                                <g id="SVGRepo_bgCarrier" stroke-width="0" transform="translate(0,0), scale(1)">
                                    <path transform="translate(-3.2, -3.2), scale(2.4)" fill="#b8d6e5" d="M9.166.33a2.25 2.25 0 00-2.332 0l-5.25 3.182A2.25 2.25 0 00.5 5.436v5.128a2.25 2.25 0 001.084 1.924l5.25 3.182a2.25 2.25 0 002.332 0l5.25-3.182a2.25 2.25 0 001.084-1.924V5.436a2.25 2.25 0 00-1.084-1.924L9.166.33z" strokewidth="0"></path></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.064"></g><g id="SVGRepo_iconCarrier"> <path id="key--users_1_" d="M24.36,31h-0.72v-7.5c0-3.552-2.414-6.604-5.872-7.424c-0.15-0.036-0.261-0.163-0.275-0.316 c-0.015-0.154,0.071-0.3,0.212-0.363c1.517-0.675,2.496-2.181,2.496-3.836c0-2.316-1.884-4.201-4.2-4.201s-4.2,1.884-4.2,4.201 c0,1.656,0.98,3.162,2.496,3.836c0.141,0.063,0.226,0.209,0.212,0.363c-0.014,0.153-0.125,0.281-0.275,0.316 c-3.458,0.82-5.873,3.872-5.873,7.424V31H7.64v-7.5c0-3.592,2.257-6.718,5.586-7.879c-1.326-0.907-2.146-2.421-2.146-4.06 c0-1.964,1.157-3.663,2.824-4.452C14.093,6.626,14.2,6.048,14.2,5.5c0-2.321-1.845-4.14-4.2-4.14S5.799,3.179,5.799,5.5 c0,1.666,1.003,3.232,2.496,3.897C8.437,9.46,8.522,9.606,8.507,9.76c-0.014,0.153-0.125,0.281-0.275,0.316 C4.774,10.896,2.36,13.948,2.36,17.5V25H1.64v-7.5c0-3.594,2.259-6.721,5.591-7.881C5.917,8.705,5.08,7.144,5.08,5.5 c0-2.68,2.207-4.86,4.92-4.86s4.92,2.18,4.92,4.86c0,0.437-0.056,0.881-0.162,1.299c0.794-0.207,1.689-0.207,2.484,0 C17.136,6.38,17.079,5.937,17.079,5.5c0-2.68,2.208-4.86,4.921-4.86s4.921,2.18,4.921,4.86c0,1.644-0.839,3.205-2.152,4.119 c3.332,1.16,5.592,4.287,5.592,7.881V25H29.64v-7.5c0-3.552-2.414-6.604-5.872-7.424c-0.15-0.036-0.261-0.163-0.275-0.316 c-0.015-0.154,0.071-0.3,0.212-0.363C25.197,8.732,26.2,7.166,26.2,5.5c0-2.321-1.845-4.14-4.2-4.14s-4.2,1.819-4.2,4.14 c0,0.547,0.106,1.125,0.296,1.609c1.668,0.789,2.825,2.487,2.825,4.452c0,1.64-0.82,3.154-2.146,4.061 c3.329,1.161,5.586,4.287,5.586,7.879L24.36,31L24.36,31z M18.926,29.36c-0.074,0-0.148-0.023-0.211-0.069L16,27.323l-2.714,1.968 c-0.126,0.092-0.297,0.092-0.423,0s-0.179-0.254-0.13-0.402l1.037-3.186l-2.714-1.973c-0.126-0.092-0.179-0.254-0.131-0.402 s0.187-0.249,0.342-0.249h3.355l1.037-3.19C15.706,19.74,15.844,19.64,16,19.64l0,0c0.156,0,0.294,0.101,0.342,0.249l1.037,3.19 h3.355c0.156,0,0.294,0.101,0.343,0.249c0.048,0.148-0.005,0.311-0.131,0.402l-2.715,1.973l1.037,3.186 c0.048,0.148-0.005,0.311-0.131,0.402C19.074,29.337,19,29.36,18.926,29.36z M16,26.519c0.074,0,0.148,0.023,0.211,0.069l2.03,1.471 l-0.775-2.382c-0.048-0.148,0.005-0.311,0.131-0.402l2.03-1.475h-2.51c-0.156,0-0.294-0.101-0.342-0.249L16,21.165l-0.775,2.386 c-0.048,0.148-0.187,0.249-0.342,0.249h-2.509l2.03,1.475c0.126,0.092,0.179,0.254,0.131,0.402l-0.775,2.382l2.029-1.471 C15.852,26.542,15.926,26.519,16,26.519z"></path>
                                    <rect id="_Transparent_Rectangle" style="fill:none;" width="32" height="32"></rect>
                                </g>
                            </svg>
                        </i>
                        <span class="item-name">Customers</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="customers-s" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.customer.add')}}">
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
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.customer.list')}}">
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
                    <a class="nav-link" data-bs-toggle="collapse" href="#attendance-v" role="button"
                        aria-expanded="false" aria-controls="attendance-v">
                        <i class="icon">
                            <svg fill="currentColor"lass="icon-20" width="20" viewBox="0 0 32 32" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" stroke="#944747" stroke-width="0.00032"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <defs> <style> .cls-1 { fill: none; } </style> </defs>
                                <path d="M18,23H16V21a3.0033,3.0033,0,0,0-3-3H9a3.0033,3.0033,0,0,0-3,3v2H4V21a5.0059,5.0059,0,0,1,5-5h4a5.0059,5.0059,0,0,1,5,5Z" transform="translate(0 0)"></path>
                                <path d="M11,6A3,3,0,1,1,8,9a3,3,0,0,1,3-3m0-2a5,5,0,1,0,5,5A5,5,0,0,0,11,4Z" transform="translate(0 0)"></path>
                                <rect  x="2" y="26.0001" width="28" height="2"></rect> <polygon points="30 8 28 8 28 6 26 6 26 4 30 4 30 8"></polygon> <polygon points="19 4 23 4 23 6 2 1 6 21 8 19 8 19 4"></polygon> <rect x="28" y="13.0001" width="2" height="2"></rect> <rect x="26" y="11.0001" width="2" height="2"></rect> <polygon points="19 11 21 11 21 13 23 13 23 15 19 15 19 11"></polygon> <rect id="_Transparent_Rectangle_" data-name="<Transparent Rectangle>" class="cls-1" width="32" height="32"></rect> </g>
                            </svg>
                        </i>
                        <span class="item-name">Attedance</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="attendance-v" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.customer.attaindance.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> V </i>
                                <span class="item-name">View</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#order-allot" role="button"
                        aria-expanded="false" aria-controls="order-allot">
                        <i class="icon">
                            <svg fill="currentColor"lass="icon-20" width="20" viewBox="0 0 32 32" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" stroke="#944747" stroke-width="0.00032"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <defs> <style> .cls-1 { fill: none; } </style> </defs>
                                <path d="M18,23H16V21a3.0033,3.0033,0,0,0-3-3H9a3.0033,3.0033,0,0,0-3,3v2H4V21a5.0059,5.0059,0,0,1,5-5h4a5.0059,5.0059,0,0,1,5,5Z" transform="translate(0 0)"></path>
                                <path d="M11,6A3,3,0,1,1,8,9a3,3,0,0,1,3-3m0-2a5,5,0,1,0,5,5A5,5,0,0,0,11,4Z" transform="translate(0 0)"></path>
                                <rect x="2" y="26.0001" width="28" height= "2"></rect> <polygon points="30 8 28 8 28 6 26 6 26 4 30 4 30 8"></polygon> <polygon points="19 4 23 4 23 6 21 6 21 8 19 8 19 4"></polygon> <rect  x="28" y="13.0001" width="2" height="2"></rect> <rect x="26" y="11.0001" width="2" height="2"></rect> <polygon points="19 11 21 11 21 13 23 13 23 15 19 15 19 11"></polygon> <rect id="_Transparent_Rectangle_" data-name="<Transparent Rectangle>" class="cls-1" width="32" height="32"></rect> </g>
                            </svg>
                        </i>
                        <span class="item-name">Food Allot</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="order-allot" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.diet-order.allot')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> V </i>
                                <span class="item-name">View</span>
                            </a>
                        </li>
                    </ul>
                </li>

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
                            <a class="nav-link " href="{{route('mess.order.list', 'all')}}">
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
                            <a class="nav-link " href="{{route('mess.order.list', 'pending')}}">
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
                            <a class="nav-link " href="{{route('mess.order.list', 'confirmed')}}">
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
                            <a class="nav-link " href="{{route('mess.order.list', 'cancelled')}}">
                                <i class="icon">
                                    <svg class="ico                   n-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Cancelled</span>
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
                        <span cl               as                s="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#messes" role="button"
                        aria-expanded="false" aria-controls="messes">
                        <i class="icon">
                            <svg fill="currentColor"class="icon-20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 490 490" xml:space="preserve" stroke="#7f3434"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <ellipse cx="244.997" cy="245.029" rx="23.847" ry="23.875"></ellipse> <path d="M193.776,245.03c0,28.27,22.976,51.268,51.224,51.268c28.24,0,51.216-22.999,51.216-51.268S273.24,193.761,245,193.761 C216.753,193.761,193.776,216.76,193.776,245.03z M280.904,245.03c0,19.828-16.105,35.956-35.904,35.956 s-35.911-16.128-35.911-35.956s16.113-35.956,35.911-35.956S280.904,225.201,280.904,245.03z"></path> <path d="M459.457,140.055c0-17.383-14.131-31.53-31.507-31.53c-8.991,0-17.063,3.837-22.807,9.906L274.324,42.813 c1.355-3.514,2.176-7.296,2.176-11.283C276.5,14.146,262.369,0,245,0c-17.376,0-31.507,14.146-31.507,31.53 c0,3.988,0.821,7.771,2.177,11.286L84.854,118.429c-5.746-6.067-13.818-9.903-22.812-9.903c-17.369,0-31.5,14.146-31.5,31.53 c0,14.727,10.184,27.031,23.844,30.478v149.68c-13.66,3.448-23.844,15.752-23.844,30.478c0,17.384,14.131,31.53,31.5,31.53 c8.454,0,16.104-3.391,21.768-8.832l131.218,75.847c-0.907,2.938-1.536,5.995-1.536,9.226C213.493,475.854,227.624,490,245,490 c17.369,0,31.5-14.146,31.5-31.537c0-3.23-0.629-6.287-1.536-9.223l131.222-75.851c5.663,5.443,13.312,8.833,21.764,8.833 c17.376,0,31.507-14.146,31.507-31.53c0-14.728-10.185-27.033-23.851-30.479V170.535 C449.272,167.088,459.457,154.783,459.457,140.055z M397.682,131.808c-0.722,2.646-1.232,5.376-1.232,8.248 c0,3.035,0.567,5.913,1.371,8.693l-56.455,32.898l-88.71-51.282V62.008c4.795-1.21,9.108-3.53,12.75-6.66L397.682,131.808z M333.727,194.92V297.51L245,348.793l-88.735-51.283V194.92L245,143.63L333.727,194.92z M245,15.313 c8.927,0,16.187,7.275,16.187,16.217S253.927,47.747,245,47.747c-8.927,0-16.195-7.275-16.195-16.217S236.073,15.313,245,15.313z M92.317,131.805L224.59,55.349c3.643,3.13,7.957,5.45,12.754,6.66v68.356l-88.717,51.282l-56.449-32.895 c0.804-2.781,1.372-5.659,1.372-8.696C93.55,137.183,93.039,134.452,92.317,131.805z M45.855,140.055 c0-8.942,7.26-16.217,16.187-16.217s16.195,7.275,16.195,16.217c0,8.942-7.268,16.217-16.195,16.217S45.855,148.998,45.855,140.055 z M62.042,366.909c-8.927,0-16.187-7.275-16.187-16.217c0-8.942,7.26-16.217,16.187-16.217s16.195,7.275,16.195,16.217 C78.237,359.634,70.97,366.909,62.042,366.909z M69.699,320.213V170.535c5.729-1.445,10.856-4.388,14.859-8.493l56.395,32.863 v102.549l-56.105,31.605C80.799,324.785,75.58,321.696,69.699,320.213z M91.876,360.362c0.997-3.066,1.674-6.276,1.674-9.67 c0-2.878-0.512-5.613-1.237-8.264l56.248-31.684l88.784,51.314v65.925c-5.545,1.398-10.541,4.185-14.484,8.089L91.876,360.362z M245,474.688c-8.927,0-16.195-7.275-16.195-16.225c0-8.942,7.268-16.217,16.195-16.217c8.927,0,16.187,7.275,16.187,16.217 C261.187,467.413,253.927,474.688,245,474.688z M267.136,436.074c-3.942-3.905-8.937-6.691-14.48-8.09v-65.927l88.777-51.314 l56.253,31.688c-0.724,2.65-1.236,5.384-1.236,8.26c0,3.393,0.678,6.602,1.673,9.667L267.136,436.074z M405.149,329.06 l-56.109-31.607V194.905l56.399-32.866c4.002,4.106,9.127,7.049,14.854,8.495v149.68 C414.415,321.698,409.197,324.787,405.149,329.06z M444.145,350.692c0,8.942-7.267,16.217-16.195,16.217 c-8.927,0-16.187-7.275-16.187-16.217c0-8.942,7.26-16.217,16.187-16.217C436.877,334.475,444.145,341.75,444.145,350.692z M427.95,156.273c-8.927,0-16.187-7.275-16.187-16.217c0-8.942,7.26-16.217,16.187-16.217c8.927,0,16.195,7.275,16.195,16.217 C444.145,148.998,436.877,156.273,427.95,156.273z"></path> </g> </g></svg>
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
                    <ul class="sub-nav collapse" id="messes" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.subscription.add')}}">
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
                            <a class="nav-link " href="{{route('mess.subscription.list')}}">
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
                        <span class="default-icon">Diet Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#category" role="button"
                        aria-expanded="false" aria-controls="category">
                        <i class="icon">
                            <svg fill="currentColor" class="icon-20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 296.195 296.195" xml:space="preserve" stroke="currentColor"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M158.216,49.633c-54.294,0-98.466,44.171-98.466,98.465s44.172,98.465,98.466,98.465c54.293,0,98.464-44.171,98.464-98.465 S212.509,49.633,158.216,49.633z M158.216,215.636c-37.241,0-67.539-30.298-67.539-67.538s30.298-67.538,67.539-67.538 c37.24,0,67.537,30.298,67.537,67.538S195.456,215.636,158.216,215.636z"></path> <path d="M293.939,96.877c-3.65-15.548-10.662-26.685-10.962-27.151c-1.921-2.984-5.705-4.347-9.108-3.346 c-3.405,1.001-5.869,4.126-5.869,7.675v148.043c0,4.418,3.582,8,8,8s8-3.582,8-8v-70.542 C296,136.346,298.777,117.483,293.939,96.877z"></path> <path d="M62,84.235V74.098c0-4.418-3.582-8-8-8s-8,3.582-8,8v10.138c0,5.227-2,9.815-7,12.426V74.098c0-4.418-3.582-8-8-8 s-8,3.582-8,8v22.564c-4-2.611-7-7.199-7-12.427V74.098c0-4.418-3.582-8-8-8s-8,3.582-8,8v10.138c0,14.196,10,26.169,23,29.674 v109.188c0,4.418,3.582,8,8,8s8-3.582,8-8V113.908C53,110.403,62,98.431,62,84.235z"></path> <path d="M186.331,117.574c-3.879-2.114-8.738-0.687-10.854,3.192l-23.662,43.381l-11.658-17.487 c-2.449-3.676-7.418-4.671-11.094-2.219c-3.677,2.45-4.67,7.418-2.219,11.094l19,28.5c1.487,2.231,3.989,3.563,6.655,3.563 c0.118,0,0.236-0.003,0.355-0.008c2.798-0.124,5.327-1.702,6.669-4.161l30-55C191.639,124.55,190.21,119.69,186.331,117.574z"></path> </g> </g></svg>
                        </i>
                        <span class="item-name">Menu</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="category" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.menu.add')}}">
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
                                <span class="item-name"> Add Daily </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.menu.add.weekly')}}">
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
                                <span class="item-name"> Add Weekly </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.menu.add')}}">
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
                                <span class="item-name"> Menu Records Daily </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.menu.list.weekly')}}">
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
                                <span class="item-name"> Menu Records Weekly </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#Addons" role="button"
                        aria-expanded="false" aria-controls="Addons">
                        <i class="icon">
                            <svg class="icon-20" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="m 7.015625 0 c -1.109375 0 -2 0.890625 -2 2 v 1 h -3.015625 c -1.109375 0 -2 0.890625 -2 2 v 2 h 1.015625 c 1.105469 0 2 0.890625 2 2 s -0.894531 2 -2 2 h -1.015625 v 2.988281 c 0 1.105469 0.890625 2 2 2 h 3.015625 v -0.988281 c 0 -1.109375 0.890625 -2 2 -2 c 1.105469 0 2 0.890625 2 2 v 0.988281 h 2 c 1.105469 0 2 -0.894531 2 -2 v -2.988281 h 1 c 1.105469 0 2 -0.890625 2 -2 s -0.894531 -2 -2 -2 h -1 v -2 c 0 -1.109375 -0.894531 -2 -2 -2 h -2 v -1 c 0 -1.109375 -0.894531 -2 -2 -2 z m 0 0" fill="currentColor"></path> </g></svg>
                        </i>
                        <span class="item-name">Addons</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="Addons" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.addon.add')}}">
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
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{route('mess.tiffin.add')}}">
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
                        <span class="item-name">Tiffins</span>
                    </a>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Staff Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#add-staff" role="button                "
                        aria-expanded="false" aria-controls="add-staff">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 75 75" viewBox="0 0 75 75"
                                width="20">
                                <path
                                    d="M39.33,15.1c0.64,0,1.48-0.01,1.38-0.01c2.08,0,1.21,3.77,1.39,5.5c0,1.32,2,1.32,2,0c-0.19-2.74,0.59-4.84-1.14-6.55
                                    c-1.12-1.11-2.22-0.95-3.63-0.94c-5.08,0.01-12.03,0.01-15.22,6.05c-0.62,1.17,1.15,2.11,1.77,0.94
                                    C28.5,15.11,34.5,15.1,39.33,15.1z"
                                    fill="currentColor"></path>
                                <path
                                    d="M48.6,46.88l-6.9-3.9V38.1c1.31-1.7,7.56-10.35,5.65-18.9V7.93c0-1.89-1.67-3.42-3.73-3.42H30.28
                                    c-5.87,0-10.65,4.28-10.65,9.54v5.15c-1.91,8.56,4.84,17.2,6.27,18.92v4.85l-7.74,4.37c-7.52,4.25-5.15,12.61-5.58,15.25
                                    c0,1.92,1.77,0.58,27.17,1c5.37,11.58,22.7,7.67,22.7-4.99C62.45,51.41,56.01,45.65,48.6,46.88z M21.63,19.22v-5.17
                                    c0-4.16,3.88-7.54,8.65-7.54h13.33c0.96,0,1.73,0.64,1.73,1.42c0,12.73-0.01,11.46,0.02,11.6c1.82,7.77-4.31,16.13-5.3,17.41
                                    c-4.22,1.88-8.33,1.88-12.55-0.01C19.12,26.92,21.67,19.69,21.63,19.22z M32.8,61.6H20.65c-0.31-5.48,0.74-7.98-1-7.98
                                    c-1.74,0-0.69,2.48-1,7.98h-4.06c0.36-2.25-1.61-9.02,4.56-12.51l5-2.82c5.33,5.19,6.06,6.7,7.09,5.72l1.57-1.48V61.6z M27.9,40.87
                                    v-1.61c3.92,1.45,7.88,1.45,11.8,0v1.61l-5.9,5.9L27.9,40.87z M39.04,61.6H34.8V50.5l1.57,1.48c1.05,1,1.87-0.63,7.09-5.72
                                    l2.44,1.38C40.63,49.89,37.51,55.7,39.04,61.6z M51.55,59.61c-0.21,2.14,0.61,4.53-1,4.53c-1.61,0-0.79-2.39-1-4.53
                                    c-2.14-0.21-4.53,0.61-4.53-1c0-1.61,2.39-0.79,4.53-1c0.21-2.14-0.61-4.53,1-4.53c1.61,0,0.79,2.39,1,4.53
                                    c2.14,0.21,4.53-0.61,4.53,1C56.08,60.21,53.69,59.39,51.55,59.61z"
                                    fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Staff </span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="add-staff" data-bs-parent="#sidebar-menu">
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
                                <span class="item-name"> Add </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#vehicles" role="button"
                        aria-expanded="false" aria-controls="vehicles">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 48 48" width="20"><path d="M44.22,16.12a.75.75,0,0,0,.75-.74V10.93A1.47,1.47,0,0,0,43.5,9.46H31.24a1.47,1.47,0,0,0-1.47,1.47v3.84c-1.2-1.35-2.62-2.75-3.15-3.27l-.16-.16a5.29,5.29,0,0,0-1-.77A5.49,5.49,0,0,0,27.78,6V5.09l2.86-.48a.74.74,0,0,0,.62-.86.75.75,0,0,0-.86-.62l-2.71.45-.08-.72A3.23,3.23,0,0,0,24.05,0L20.78.38a3.23,3.23,0,0,0-2.84,3.56l.21,1.92h0v0l.13,1a5.05,5.05,0,0,0,2.38,3.73,2.32,2.32,0,0,0-.4.3,3,3,0,0,0-1,2.21c0,.69.24,2.63.75,6H17a.75.75,0,0,0,0,1.5h5.76a.75.75,0,0,0,0-1.5H21.56c-.51-3.37-.77-5.41-.77-6a1.5,1.5,0,0,1,.5-1.12,2.66,2.66,0,0,1,1.94-.64A4.32,4.32,0,0,1,25.4,12.4l.17.17c.54.53,2,2,3.24,3.38l-1.59,1.71L25.6,16.22a.76.76,0,0,0-1.06.06.75.75,0,0,0,.06,1.06l3.3,2.94.2,2.95-4.05.41a.74.74,0,0,0-.67.82.75.75,0,0,0,.74.68h.08l4-.41.07,1.14a.76.76,0,0,0,.39.61l7,3.76a1.11,1.11,0,0,1,.57.78l2,11.66-1.15.21a.75.75,0,1,0,.28,1.48l2.41-.45a.37.37,0,0,1,.46.27l.1.68-4.38.8-2.4-12.91a1.33,1.33,0,0,0-1-1c-2.6-.53-5.75-1.35-6.79-2.08-2.31-1.61-3.08-3.22-3.17-3.92,0-.26-.11-.73-.24-1.53l-.31-2a.75.75,0,0,0-1.48.23l.31,2c.1.67.17,1.1.21,1.37l-1.93,8.74a.25.25,0,0,1-.08.14l-7.25,5.73h0l-1.68,1.13a.39.39,0,0,0-.1.08,1.3,1.3,0,0,0-.17,1.7L13,47.69a.77.77,0,0,0,.61.31.74.74,0,0,0,.42-.13l1.19-.8a1.83,1.83,0,0,0,.53-2.44l8.15-6.51a1.7,1.7,0,0,0,.54-.76l1.87-4.6a.75.75,0,1,0-1.39-.57L23,36.84A.28.28,0,0,1,23,37l-8.69,6.94-.08.1-.1.11-.05.14s0,.09,0,.13a.76.76,0,0,0,0,.15.66.66,0,0,0,0,.14.84.84,0,0,0,.06.14l0,.12.28.38a.37.37,0,0,1,0,.54l-.57.38-2.6-3.62L12,42l.76,1.05A.75.75,0,1,0,14,42.21l-.77-1.06L20,35.84a1.79,1.79,0,0,0,.62-1l1.47-6.69a11.6,11.6,0,0,0,2.81,2.7c1.47,1,5.45,1.93,7.23,2.3l2.44,13.12a1.32,1.32,0,0,0,1.25.91l.22,0,5.31-1a.74.74,0,0,0,.6-.85l-.21-1.42a1.84,1.84,0,0,0-2-1.52l-2-11.67a2.55,2.55,0,0,0-1.33-1.84l-6.62-3.57L29.5,21.7l.16.13a.48.48,0,0,0,.11.09v1.24a1.47,1.47,0,0,0,1.47,1.47h13a.75.75,0,0,0,0-1.5l-12.95,0v-.78c3.17-.15,7.83-.37,8.47-.38l1.18.21a.34.34,0,0,0,.14,0l3.17,0a.75.75,0,0,0,.74-.75V17.69a.73.73,0,0,0-.23-.54.72.72,0,0,0-.55-.21l-2.7.09a.65.65,0,0,0-.36.1l-.94.55-7.95-.27a.47.47,0,0,1-.29-.14l-.69-.68s0,0,0-.06l0-5.57h3.6v2.78a.75.75,0,0,0,.75.75h3.52a.75.75,0,0,0,.75-.75v-2.8h3.61v4.45A.75.75,0,0,0,44.22,16.12ZM21,1.87l3.27-.36h.19A1.74,1.74,0,0,1,26.12,3l.09.79L19.56,4.91l-.13-1.14A1.73,1.73,0,0,1,21,1.87ZM19.78,6.66l0-.26,6.53-1.07v.6c-.21,3.63-2.81,3.88-3.22,3.89A3.55,3.55,0,0,1,19.78,6.66ZM32.14,18.9l8.21.29a.72.72,0,0,0,.41-.1l1-.57,1.74-.06V20.7l-2.35,0-1.18-.21H39.8c-.48,0-7.87.35-8.69.38a.73.73,0,0,1-.45-.16l-1.51-1.34-.07-.06-.74-.66,1.38-1.48,1.18,1.16A2,2,0,0,0,32.14,18.9ZM38.36,13h-2V11h2Z" fill="currentColor"></path><path d="M11.29 27.11a.75.75 0 0 0 0 1.5h4.17a.75.75 0 0 0 0-1.5zM14.44 23.87a.74.74 0 0 0-.75-.75H3.78a.75.75 0 0 0 0 1.5h9.91A.75.75 0 0 0 14.44 23.87zM8.79 16.63H17a.75.75 0 0 0 0-1.5H8.79a.75.75 0 1 0 0 1.5z" fill="currentColor" ></path></svg>
                        </i>
                        <span class="item-name">Delivery Man</span>
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
                            <a class="nav-link" href="{{route('mess.delivery-man.add')}}">
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
                            <a class="nav-link" href="{{route('mess.delivery-man.list')}}">
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
                                <span class="item-name"> List </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('mess.delivery-man.wallet')}}">
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
                                <span class="item-name"> Delivery Man Wallet </span>
                            </a>
                        </li>
                    </ul>
                </li>



                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Business Setup</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#employee-role" role="button"
                        aria-expanded="false" aria-controls="employee-role">
                        <i class="icon">

                                                         <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 128 128" width="20">
                                <path fill="currentColor"
                                    d="M122.756 40.797a31.218 31.218 0 0 0-1.921-10.827q2.922-1.948 5.818-3.943l-4.82-8.363q-3.184 1.506-6.335 3.06a31.312 31.312 0 0 0-18.8-10.867c-.148-2.337-.322-4.68-.51-7.017h-9.654c-.188 2.337-.362 4.68-.51 7.017a31.372 31.372 0 0 0-18.807 10.86q-3.154-1.546-6.327-3.053l-4.821 8.363a418.826 418.826 0 0 0 5.812 3.936 31.498 31.498 0 0 0 0 21.668 420.803 420.803 0 0 0-5.812 3.937l4.82 8.362q3.174-1.506 6.328-3.053a31.373 31.373 0 0 0 18.808 10.86c.147 2.337.321 4.68.509 7.017h9.654c.188-2.336.362-4.68.51-7.017a31.313 31.313 0 0 0 18.8-10.866q3.154 1.556 6.334 3.06l4.821-8.363q-2.892-2-5.818-3.944a31.219 31.219 0 0 0 1.921-10.827Zm-13.043 4.27a18.973 18.973 0 1 1 .487-4.27 18.738 18.738 0 0 1-.487 4.27Z"></path>
                                <path fill="currentColor"
                                    d="M98.903 33.3a2.821 2.821 0 0 0-1.951.785l-.595.594-7.122 7.123-2.26-1.937-1.371-1.173a2.741 2.741 0 0 0-2.01-.646 2.71 2.71 0 0 0-1.87.954 2.755 2.755 0 0 0 .315 3.88l5.494 4.688.051.044a1317.067 1317.067 0 0 1 .36.256 1.775 1.775 0 0 0 .315.162l.14.073a2.069 2.069 0 0 0 .227.074l.264.058a2.617 2.617 0 0 0 .506.059 2.914 2.914 0 0 0 .543-.059 1.054 1.054 0 0 0 .176-.051 2.475 2.475 0 0 0 .352-.125 1.402 1.402 0 0 0 .264-.132 1.077 1.077 0 0 0 .213-.132 2.293 2.293 0 0 0 .286-.227l4.614-4.607 4.995-4.988a2.728 2.728 0 0 0 0-3.888 2.734 2.734 0 0 0-1.936-.785zm21.805 54.31c-5.31 1.057-10.658 2.346-16.046 3.828a10.448 10.448 0 0 1 .444 4.522 10.683 10.683 0 0 1-10.713 9.186H61.515a1.996 1.996 0 0 1-2-1.6 1.925 1.925 0 0 1 1.897-2.248h33.323a6.602 6.602 0 0 0 6.6-6.609 6.34 6.34 0 0 0-.482-2.453 6.06 6.06 0 0 0-.74-1.366 2.948 2.948 0 0 0-.203-.279c-.096-.115-.182-.22-.288-.327a4.09 4.09 0 0 0-.28-.298 6.558 6.558 0 0 0-.788-.664 2.798 2.798 0 0 0-.279-.183 1.551 1.551 0 0 0-.27-.163 5.836 5.836 0 0 0-1.327-.577 3.91 3.91 0 0 0-.577-.154.409.409 0 0 0-.106-.02 5.773 5.773 0 0 0-.587-.086 5.166 5.166 0 0 0-.673-.038H63.278c-8.26-9.764-19.48-12.447-33.236-9.085a35.48 35.48 0 0 0-8.147 3.15l-18.79 9.94a3.304 3.304 0 0 0-1.758 2.92V116.7a3.304 3.304 0 0 0 3.23 3.302l17.087.38 40.2 4.586a26.646 26.646 0 0 0 4.514.155 28.933 28.933 0 0 0 11.402-2.764c.605-.305 46.391-25.5 46.857-25.845a4.99 4.99 0 0 0-3.929-8.906z"></path>
                            </svg>
                        </i>
                        <span class="item-name">Primary Setup </span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="employee-role" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.business-setup.charges')}}">
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
                                <span class="item-name"> Charges Table </span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="employee-role" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('mess.profile.timing')}}">
                                <i class="icon">
                                    <svg fill="currentColor" width="15px" version="1.1" id="Layer_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 512 512" xml:space="preserve">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g> <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <g> <g> <path d="M437.019,74.98C388.667,26.628,324.381,0,256,0C187.62,0,123.333,26.628,74.98,74.98C26.628,123.333,0,187.62,0,256 s26.628,132.667,74.98,181.02C123.333,485.372,187.62,512,256,512c68.381,0,132.667-26.628,181.019-74.98 C485.372,388.667,512,324.38,512,256S485.372,123.333,437.019,74.98z M256,491.602c-129.911,0-235.602-105.69-235.602-235.602 S126.089,20.398,256,20.398S491.602,126.089,491.602,256S385.911,491.602,256,491.602z"></path> </g> </g>
                                            <g> <g> <path d="M256,48.514C141.591,48.514,48.514,141.591,48.514,256S141.591,463.486,256,463.486S463.487,370.409,463.487,256 S370.409,48.514,256,48.514z M256,443.088c-103.161,0-187.088-83.927-1 87.088-187.088S152.839,68.912,256,68.912 S443.089,152.839,443.089,256S359.161,443.088,256,443.088z"></path> </g> </g>
                                            <g> <g> <path d="M403.517,244.456c-5.632,0-10.199,4.566-10.199,10.199c0,4.857-0.257,9.754-0.763,14.552 c-0.592,5.602,3.471,10.622,9.072,11.214c0.365,0.038,0.725,0.057,1.083,0.057c5.152,0,9.577-3.891,10.13-9.129 c0.581-5.508,0.876-11.124,0.876-16.694C413.716,249.022,409.149,244.456,403.517,244.456z"></path> </g> </g>
                                             <g> <g> <path d="M346.558,245.801h-58.211c-3.322-10.512-11.635-18.826-22.148-22.148V95.367c0-5.633-4.567-10.199-10.199-10.199 c-5.633,0-10.199,4.566-10.199,10.199v128.286c-13.733,4.34-23.717,17.198-23.717,32.347c0,18.702,15.215,33.917,33.916,33.917 c15.149,0,28.007-9.985,32.347-23.717h58.211c5.632,0,10.199-4.566,10.199-10.199C356.75 7,250.367,352.19,245.801,346.558,245.801 z M256,269.518c-7.455,0-13.518-6.064-13.518-13.518s6.063-13.518,13.518-13.518s13.518,6.064,13.518,13.518 S263.455,269.518,256,269.518z"></path> </g> </g>
                                            <g> <g> <path d="M396.577,300.608c-5.217-2.122-11.167,0.39-13.289,5.607c-21.1,51.899-70.935,85.434-126.964,85.434 c-75.538,0 -136.994-61.455-136.994-136.994c0-45.116,22.526-87.467,59.398-112.893l0.373,0.881 c3.246,7.671,13.579,8.951,18.598,2.304l14.187-18.786c5.019-6.646,0.963-16.233-7.303-17.258l-23.362-2.897 c-8.266-1.025-14.542,7.283-11.296,14.954l0.71,1.677c-20.48,13.322-37.714,31.3-50.112,52.406 c-14.126,24.046-21.593,51.575-21.593,79.612c0,86.786,70.606,157.392,157.392,157.392c31.924,0,62.666-9.508,88.901-27.495 c25.608-17.558,45.305-41.99,56.958-70.655C404.306,308.679,401.796,302.73,396.577,300.608z"></path> </g> </g>
                                        </g>
                                     </svg>
                                </i>
                                <i class="sidenav-mini-icon"> T </i>
                                <span class="item-name"> Timing </span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li>
                    <hr class="hr-horizontal">
                </li>

            </ul>
            <!-- Sidebar Menu End -->
        </div>
    </div>
    <div class="sidebar-footer"></div>
</aside>
