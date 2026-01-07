@extends('mess-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('messages.add-addons') }}</h4>
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

                          <form class="row g-3 needs-validation" method="POST" enctype="multipart/form-data"  action="{{route('mess.addon.store')}}">
                            @csrf
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="name">Name</label>
                                    <input id="name" type="text" name="name"
                                        class="form-control h--45px" placeholder="Ex. Water"
                                        value="{{old('name')}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="name">Price</label>
                                    <input id="price" type="number" name="price"
                                        class="form-control h--45px" placeholder="Ex. 20"
                                        value="{{old('price')}}" >
                                </div>
                            </div>
                             <div class="col-md-6 ">
                                <button class="btn btn-primary" type="submit">Submit form</button>
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
                         <div class="header-title">
                            <h4 class="card-title">Addon Table</h4>
                         </div>
                      </div>
                      <div class="card-body p-0">
                         <div class="table-responsive mt-4">
                            <table id="datatable" data-toggle="data-table" class="table  mb-0" data-toggle="data-table" role="grid">
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
                                     <td>{{App\CentralLogics\Helpers::format_currency($addon->price)}}</td>
                                     <td>{{$addon->status ===1?'active':'deactive'}}</td>
                                     <td><button class="btn">Edit</button></td>
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
