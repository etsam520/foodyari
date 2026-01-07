@extends('vendor-views.layouts.dashboard-main')



@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="fs-3 fw-bolder mb-3">Sub Menu List</div>
                    <div class="btn-group mb-3" role="group" aria-label="Filter Tabs">
                        <a href="{{ route('vendor.restaurant-menu.index', ['filter' => 'all']) }}"
                           class="btn btn-outline-primary {{ request('filter') == 'all' || !request('filter') ? 'active' : '' }}">
                            All ({{ $restaurantMenus->count() }})
                        </a>
                        <a href="{{ route('vendor.restaurant-menu.index', ['filter' => 'active']) }}"
                           class="btn btn-outline-success {{ request('filter') == 'active' ? 'active' : '' }}">
                            Active ({{ $restaurantMenus->where('status', 1)->count() }})
                        </a>
                        <a href="{{ route('vendor.restaurant-menu.index', ['filter' => 'inactive']) }}"
                           class="btn btn-outline-danger {{ request('filter') == 'inactive' ? 'active' : '' }}">
                            Inactive ({{ $restaurantMenus->where('status', 0)->count() }})
                        </a>
                    </div>
                    <div class="card">
                        <div class="card-header">

                            <div class="d-flex align-items-center justify-content-between w-100">
                                <h5 class="page-header-title mb-0">Menu Table
                                    <a href="{{route('vendor.restaurant-menu.sort')}}"
                                        class="badge bg-soft-primary ms-2"><small>SORT</small></a>
                                </h5>

                                <a href="{{route('vendor.restaurant-menu.create')}}" type="button"
                                    class="btn btn-primary align-self-end">Create</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" data-toggle="data-table" class="table table-striped mb-0" role="grid">
                                    <thead>
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Menu Id <a href="{{ route('vendor.menu-custom-id-regenerate') }}"><i
                                                        class="fas fa-arrows-rotate" title="Regenerate ID"></i></a></th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($restaurantMenus->filter(function ($menu) {
                                            $filter = request('filter');
                                            if ($filter == 'active') {
                                                return $menu->status == 1;
                                            } elseif ($filter == 'inactive') {
                                                return $menu->status == 0;
                                            }
                                            return true; // Show all menus if no filter or 'all' is selected
                                        }) as $menu)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$menu->custom_id}}</td>
                                                <td>{{$menu->name}}</td>
                                                <td>
                                                    <div class="form-check w-100 form-switch px-3">
                                                        <input type="checkbox" data-toggle="toggle"
                                                            name="menu-status-{{$menu->id}}" {{$menu->status == 1 ? 'checked' : ''}}
                                                            onchange="location.href='{{ route('vendor.restaurant-menu.status')}}?id={{$menu->id}}&status='+this.checked"
                                                            class="form-check-input mx-auto form-control fs-4"
                                                            id="menu-status-{{$menu->id}}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-end">
                                                        <a href="{{route('vendor.restaurant-menu.edit', $menu->id)}}"
                                                            class=" text-warning"> <i class="fa fa-edit"></i> Edit</a>
                                                        <a class="btn text-danger"
                                                            href="{{route('vendor.restaurant-menu.destroy', $menu['id'])}}"
                                                            onclick="form_alert(this,'{{__('Want to delete this Menu')}}')">
                                                            <i class="fa fa-trash text-danger"></i> {{__('messages.delete')}}
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
@endsection
@push('javascript')
    <script>
        function form_alert(item, message) {
            event.preventDefault();  // Prevent the default anchor action (e.g., navigating to href)

            Swal.fire({
                title: '{{ __('messages.Are you sure ?') }}',
                text: message,
                icon: 'warning',  // Correct SweetAlert syntax
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ __('messages.No') }}',
                confirmButtonText: '{{ __('messages.Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a dynamic form
                    const form = document.createElement('form');
                    form.method = 'POST'; // Use POST for compatibility
                    form.action = item.getAttribute('href'); // Set the URL from the href attribute

                    // Add a hidden _method input to simulate DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Add a CSRF token if required (for Laravel or other frameworks)
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                    }

                    // Append the form to the body and submit it
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
