@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title d-flex align-items-center">
                                <h5 class="page-header-title">Category Table</h5>
                                <span class="badge bg-primary ms-2 py-1" id="itemCount">{{$categories->count()}}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" data-toggle="data-table" class="table table-striped mb-0" role="grid">
                                    <thead>
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Category Name</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            {{-- @dd($category) --}}
                                            <tr>
                                                <td>{{$loop->index + 1}}</td>
                                                <td>{{$category->id}}</td>
                                                <td><img src="{{asset("Category/$category->image")}}"
                                                        onerror="this.src='{{asset('assets/images/icons/food-default-image.png')}}'"
                                                        alt="" style="width:75px;border-radius:5px;"></td>
                                                <td>{{$category->name}}</td>
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
