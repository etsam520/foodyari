@extends('vendor-views.layouts.dashboard-main')
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush
@php($isAdmin = auth('admin')->check())

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group mb-3" role="group" aria-label="Filter Tabs">
                    {{-- @dd($data['counts']) --}}
                    <a href=""
                        class="btn btn-outline-primary {{ request('filter') == 'all' || !request('filter') ? 'active' : '' }}">
                        All ({{$data['counts']['food_count']}})
                    </a>
                    <a href="" class="btn btn-outline-success {{ request('filter') == 'active' ? 'active' : '' }}">
                        Active ({{$data['counts']['active_count']}})
                    </a>
                    <a href="" class="btn btn-outline-danger {{ request('filter') == 'inactive' ? 'active' : '' }}">
                        Inactive ({{$data['counts']['inactive_count']}})
                    </a>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center rounded-2">
                        <div class="header-title">
                            <h5 class="page-header-title">Food List <div class="badge bg-soft-primary ms-2">
                                    {{-- <small>44</small> --}}
                                </div>
                            </h5>
                        </div>
                        <a href="{{ route('vendor.food.add') }}" type="button"
                            class="btn btn-primary align-self-end">Create</a>
                    </div>
                </div>
                @foreach ($data['foods'] as $food)
                <div class="card mb-1">
                    <div class="card-body">
                        <div class="d-lg-flex justify-content-between">
                            <div class="d-flex">
                                <div class="">
                                    <img src="{{ Helpers::getUploadFile($food->image??null, 'product') }}" alt=""
                                        style="width:50px;height:50px;border-radius:10px;">
                                </div>
                                <div class="mx-3 w-100">
                                    <div class="d-flex justify-content-between">
                                        <h6>{{Str::ucfirst($food->name)}}</h6>
                                        {{-- @dd($food) --}}
                                        <span class="text-success fw-bolder ms-4">{{ $food->isCustomize == true ? 'Customized' : Helpers::format_currency($food->restaurant_price)}}</span>
                                    </div>
                                    <div class="mt-2 d-flex">
                                        {{-- Starter 1 | Snacks | Snacks --}}
                                        <div class="border-end pe-2">
                                            <div class="text-center text-success text-decoration-underline"
                                                style="font-size: 10px">Menu</div>
                                            <div>{{isset($food->menu->name) ? Str::ucfirst($food->menu->name) : null}}</div>
                                        </div>
                                        <div class="border-end px-2">
                                            <div class="text-center text-success text-decoration-underline"
                                                style="font-size: 10px">Sub Menu</div>
                                            <div>{{isset($food->submenu->name) ? Str::ucfirst($food->submenu->name) : null}}</div>
                                        </div>
                                        <div class="px-2">
                                            <div class="text-center text-success text-decoration-underline"
                                                style="font-size: 10px">Category</div>
                                            <div>{{isset($food->category) ? $food->category->name : null}}</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="d-lg-flex flex-column align-items-end">
                                <div class="d-lg-block d-none">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input type="checkbox" data-toggle="toggle" name="free_delivery"
                                            data-status="update"
                                            {{$food->status ? 'checked' : ''}} 
                                            data-food-id="{{$food->id}}" class="form-check-input form-control fs-4"
                                            id="free_delivery">
                                            
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 border-top pt-2">
                                    <div>
                                        @if($isAdmin)
                                        <a class="btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Delete"
                                            onclick="confirmDeletion('{{route('vendor.food.delete', ['id' => $food->id])}}')"
                                            href="javascript:void(0)">
                                            <span class="btn-inner">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                    <path
                                                        d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path d="M20.708 6.23975H3.75" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path
                                                        d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                            </span>
                                            Delete
                                        </a>

                                        <a class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Edit" data-original-title="Edit"
                                            href="{{route('vendor.food.edit', ['id' => $food->id])}}">
                                            <span class="btn-inner">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                            </span>
                                            Edit
                                        </a>
                                        @endif
                                    </div>
                                    <div class="d-lg-none d-block">
                                        <div class="form-check form-switch mb-2" style="min-height: 0px;">
                                            <input type="checkbox" data-toggle="toggle" name="free_delivery" 
                                                {{$food->status ? 'checked' : ''}}
                                                data-status="update" data-food-id="57"
                                                class="form-check-input form-control fs-4" id="free_delivery">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $data['foods']->links() }}
                    </div>
            </div>
        </div>
    </div>
    </div>
@endsection
<!-- End Table -->
@push('javascript')
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-status=update]').forEach(async element => {
                element.addEventListener('change', async () => {
                    try {
                        const resp = await fetch('{{ route('vendor.food.status') }}?status=' +(element.checked?1:0)+ '&food_id=' + element.dataset.foodId);
                        if (!resp.ok) {
                            const error = await resp.json();
                            throw new Error(error.message);
                        }
                        const result = await resp.json();
                        toastr.success(result.message);
                    } catch (error) {
                        toastr.error(error.message);
                        console.error('Error fetching data:', error);
                    }
                });
            });
        });

        function confirmDeletion(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // Redirect to the intended URL
                }
            });
        }
    </script>
    <script>
        $.extend(true, $.fn.DataTable.defaults, {
            responsive: true
        });

        $(document).ready(function () {
            $('#example-data-table').DataTable({
                responsive: {
                    details: true // Enables row details view when columns collapse
                },
                columnDefs: [
                    { responsivePriority: 1, targets: 0 }, // Name (Always visible)
                    { responsivePriority: 2, targets: 1 }, // Office (Higher priority)
                    { responsivePriority: 3, targets: 6 }, // Position
                ]
            });
        });
    </script>

@endpush
