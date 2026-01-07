@extends('mess-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('Mess Tiffins') }}
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

                          <form class="row g-3 needs-validation" method="POST" enctype="multipart/form-data"  action="{{route('mess.tiffin.store')}}">
                            @csrf
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="name">Title</label>
                                    <input id="name" type="text" name="title"
                                        class="form-control h--45px" placeholder="Ex. ABC"
                                        value="{{old('title')}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="code">Tiffin Code</label>
                                    <input id="code" type="text" name="code"
                                        class="form-control h--45px" placeholder="Ex. FDs35"
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
                            <h4 class="card-title">Tiffin Table</h4>
                         </div>
                      </div>
                      <div class="card-body p-0">
                         <div class="table-responsive mt-4">
                            <table id="datatable" data-toggle="data-table" class="table  mb-0" data-toggle="data-table" role="grid">
                               <thead>
                                  <tr>
                                     <th>S.I</th>
                                     <th>Title</th>
                                     <th>Tiffin No.</th>
                                     <th>action</th>
                                  </tr>
                               </thead>
                               <tbody>
                                 @foreach ($tiffins as $tiffin)
                                    <tr>
                                     <td>{{$loop->index + 1}}</td>
                                     <td>{{$tiffin->title??"NA"}}</td>
                                     <td>{{$tiffin->no}}</td>
                                     <td><span class="text-danger fas fa-trash">   </span></td>
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
