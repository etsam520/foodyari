@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <div class="header-title">
                                        <h4 class="card-title">Role List</h4>
                                    </div>
                                     <div class="header-button"> 
                                        <a href="javascript:void(0)" class=" text-center btn btn-primary btn-icon mt-lg-0 mt-md-0 mt-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop-1">
                                            <i class="btn-inner">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </i>
                                            <span>New Role</span>
                                        </a>
                                    </div> 
                                </div>
                                <div class="card-body px-0">
                                    <div class="table-responsive">
                                        <table id="datatable" class="table" role="grid" data-toggle="data-table">
                                            <thead>
                                                <tr class="ligth">
                                                    <th>SL</th>
                                                    <th>Role</th>
                                                    {{-- <th class="text-center">STATUS</th>
                                                    <th style="min-width: 100px">ACTION</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($roles as $key=>$role)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>
                                                        <span class="d-block text-body">{{Str::limit($role['name'],25, '...')}}
                                                        </span>
                                                    </td>
                                                    {{-- <td>
                                                        <label class="form-check form-check form-switch form-check-inline"
                                                            for="stocksCheckbox{{$role->id}}">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="stocksCheckbox{{$role->id}}" >
                                                            <span class="toggle-switch-label">
                                                                <span class="toggle-switch-indicator"></span>
                                                            </span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                    </td> --}}
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
        </div>
    </div>
</div>

<div class="modal fade" id="staticBackdrop-1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add new role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{route('admin.roles.add')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label">role title</label>
                        <input type="text" class="form-control" name="role" id="email" aria-describedby="email" placeholder="Role Title">
                    </div>
                    {{-- <div>
                        <span>status</span>
                        <div class="form-check">
                            <input class="form-check-input"  type="radio" name="status" id="exampleRadios2" value="1">
                            <label class="form-check-label" for="exampleRadios2">
                                yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio"  name="status" id="exampleRadios2" value="0">
                            <label class="form-check-label" for="exampleRadios2">
                                no
                            </label>
                        </div>
                    </div> --}}
                    <div class="text-start mt-2">
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-danger">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
