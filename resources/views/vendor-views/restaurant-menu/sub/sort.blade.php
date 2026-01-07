@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header">

                        <div class="header-title d-flex justify-content-between">
                            <h5 class="page-header-title mb-0">Restaurant Sub Menu Sort</h5>
                            <a href="javascript:();">Drag & Drop for Positioning</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group" id="sortable">
                            @foreach ($menu as $index => $list)
                                <li class="list-group-item border-0 {{ $index % 2 == 0 ? 'bg-light' : '' }}"
                                    data-id="{{$list->id}}">
                                    <a href="javascript:();">
                                        <div class="d-flex align-items-center">
                                            <span class="me-3">{{ $index + 1 }}.</span>
                                            <img class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded"
                                                src="{{$list->image ? asset('Category/' . $list->image) : asset('assets/images/icons/food-default-image.png')}}"
                                                alt="{{Str::ucfirst($list->name)}}">
                                            <p class="ms-3 pt-1 text-muted mb-0">{{Str::ucfirst($list->name)}}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                            {{-- @foreach ($menu as $list)

                            <li class="list-group-item" data-id="{{$list->id}}">
                                <div class="d-flex">
                                    <img class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded"
                                        src="{{$list->image?asset('Category/'.$list->image): asset('assets/images/icons/food-default-image.png')}}"
                                        alt="{{Str::ucfirst($list->name)}}">
                                    <p class="ms-3 pt-1 text-muted">{{Str::ucfirst($list->name)}}</p>
                                </div>

                            </li>
                            @endforeach --}}

                        </ul>
                    </div>
                </div>
            </div>
        </div>
@endsection

    @push('javascript')
        <script src="{{asset('assets/vendor/sortable/Sortable.min.js')}}"></script>
        <script>

            new Sortable(document.querySelector('#sortable'), {
                multiDrag: true,
                selectedClass: 'selected',
                fallbackTolerance: 3,
                animation: 150,
                onUpdate: function (eve) {
                    const sortedArray = {};
                    eve.target.querySelectorAll('.list-group-item').forEach((item, index) => {
                        sortedArray[index + 1] = JSON.parse(item.dataset.id);
                    });

                    fetch('{{route('vendor.restaurant-sub-menu.sort')}}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ sortedArray: sortedArray })
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Success: Updated');
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                }
            });
        </script>
    @endpush
    <!-- End Table -->
