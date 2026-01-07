@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="page-header-title">{{ __('messages.add-addons') }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="row g-3 needs-validation" method="POST" enctype="multipart/form-data"
                                action="{{route('vendor.addon.store')}}">
                                @csrf
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label" for="name">Name</label>
                                        <input id="name" type="text" name="name" class="form-control h--45px"
                                            placeholder="Ex. Water" value="{{old('name')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label" for="name">Price</label>
                                        <input id="price" type="number" name="price" class="form-control h--45px"
                                            placeholder="Ex. 20" value="{{old('price')}}">
                                    </div>
                                </div>
                                <hr style="border: 1px solid #cecbcb;">
                                <div class="text-end mt-0">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title d-flex align-items-center">
                                <h5 class="page-header-title">Addon Table</h5>
                                <span class="badge bg-primary ms-2 py-1" id="itemCount">{{ count($addons) }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" data-toggle="data-table" class="table table-striped mb-0" role="grid">
                                    <thead>
                                        <tr>
                                            <th>S.I</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($addons as $addon)
                                            <tr>
                                                <td>{{$loop->index + 1}}</td>
                                                <td>{{$addon->name}}</td>
                                                <td>{{$addon->price}}</td>
                                                <td>
                                                    <div class="form-check w-100 form-switch px-3">
                                                        <input type="checkbox" data-toggle="toggle"
                                                            name="addon-status-{{$addon->id}}"
                                                            {{$addon->status == 1 ? 'checked' : ''}}
                                                            onchange="location.href='{{ route('vendor.addon.status')}}?id={{$addon->id}}&status='+this.checked"
                                                            class="form-check-input mx-auto form-control fs-4"
                                                            id="addon-status-{{$addon->id}}">
                                                    </div>
                                                </td>
                                                <td><a class="btn btn-sm btn-icon btn-warning"
                                                        href="{{route('vendor.addon.edit', $addon->id)}}">
                                                        <span class="btn-inner">
                                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round"></path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                    <a class="btn" href="{{route('vendor.addon.delete', $addon['id'])}}"
                                                        onclick="form_alert(this,'{{__('Want to delete this Addon')}}')"
                                                        title="{{__('messages.delete')}} {{__('messages.marquee')}}">
                                                        <i class="fa fa-trash text-danger"></i>
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
