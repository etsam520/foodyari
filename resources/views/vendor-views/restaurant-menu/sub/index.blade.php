@extends('vendor-views.layouts.dashboard-main')



@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">

                <div class="col-sm-12">
                    <div class="fs-3 fw-bolder mb-3">Sub Menu List</div>
                    <div class="btn-group mb-3" role="group" aria-label="Filter Tabs">
                        <a href="{{ route('vendor.restaurant-sub-menu.index', ['filter' => 'all']) }}"
                            class="btn btn-outline-primary text-nowrap {{ request('filter') == 'all' || !request('filter') ? 'active' : '' }}">
                            All ({{ $submenus->count() }})
                        </a>
                        <a href="{{ route('vendor.restaurant-sub-menu.index', ['filter' => 'active']) }}"
                            class="btn btn-outline-success text-nowrap {{ request('filter') == 'active' ? 'active' : '' }}">
                            Active ({{ $submenus->where('status', 1)->count() }})
                        </a>
                        <a href="{{ route('vendor.restaurant-sub-menu.index', ['filter' => 'inactive']) }}"
                            class="btn btn-outline-danger text-nowrap {{ request('filter') == 'inactive' ? 'active' : '' }}">
                            Inactive ({{ $submenus->where('status', 0)->count() }})
                        </a>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <h5 class="page-header-title mb-0">List
                                    <a href="{{route('vendor.restaurant-sub-menu.sort')}}"
                                        class="badge bg-soft-primary ms-2"><small class="py-2">SORT</small></a>
                                </h5>
                                <a href="{{route('vendor.restaurant-sub-menu.create')}}" type="button"
                                    class="btn btn-primary align-self-end">Create</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" data-toggle="data-table" class="table table-striped mb-0" role="grid">
                                    <thead>


                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Sub <br>Menu No <a
                                                    href="{{ route('vendor.restaurant-sub-menu.custom-id-regenerate') }}"><i
                                                        class="fas fa-arrows-rotate" title="Regenerate ID"></i></a></th>
                                            <th>Menu</th>
                                            <th>Sub Menu</th>
                                            <th>Status</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $serialNumber = 1; @endphp
                                        @foreach ($submenus->filter(function ($smenu) {
                                            $filter = request('filter');
                                            if ($filter == 'active') {
                                                return $smenu->status == 1;
                                            } elseif ($filter == 'inactive') {
                                                return $smenu->status == 0;
                                            }
                                            return true; // Show all submenus if no filter or 'all' is selected
                                        }) as $smenu)
                                                <tr>
                                                    <td>{{ $serialNumber++ }}</td>
                                                    <td>{{$smenu->custom_id}}</td>
                                                    <td>{{Str::ucfirst($smenu->menu->name)}}</td>
                                                    <td>{{$smenu->name}}</td>
                                                    <td>
                                                        <div class="form-check w-100 form-switch px-3">
                                                            <input type="checkbox" data-toggle="toggle"
                                                                name="menu-status-{{$smenu->id}}" {{$smenu->status == 1 ? 'checked' : ''}}
                                                                onchange="location.href='{{ route('vendor.restaurant-sub-menu.status')}}?id={{$smenu->id}}&status='+this.checked"
                                                                class="form-check-input mx-auto form-control fs-4"
                                                                id="menu-status-{{$smenu->id}}">
                                                        </div>
                                                    </td>
                                                    <td><a href="{{route('vendor.restaurant-sub-menu.edit', ['id' => $smenu->id])}}"
                                                            class=" text-warning"> <i class="fa fa-edit"></i> Edit</a>
                                                        <a class="btn text-danger"
                                                            href="{{route('vendor.restaurant-sub-menu.destroy', $smenu['id'])}}"
                                                            onclick="form_alert(this,'{{__('Want to delete this Menu')}}')"
                                                            title="{{__('messages.delete')}} {{__('messages.marquee')}}">
                                                            <i class="fa fa-trash text-danger"></i>{{__('messages.delete')}}
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
