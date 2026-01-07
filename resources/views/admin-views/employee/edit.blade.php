@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
       <div class="row">
          <div class="col-xl-3 col-lg-4">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                      <h4 class="card-title">Edit Employee</h4>
                   </div>
                </div>
                <div class="card-body">
                   <form action="{{route('admin.employee.update', ['id'=>$e['id']])}}" method="POST" enctype="multipart/form-data">
                    @csrf
                      <div class="form-group">
                         <div class="profile-img-edit position-relative"> 
                            <img src="{{$e->image != null ? asset("admin/$e->image"): asset('assets/images/avatars/01.png')}}"  alt="profile-pic" id="avatar-img" class="theme-color-default-img profile-pic rounded avatar-100">
                            <div class="upload-icone bg-primary">
                              <label for="avatar"><svg class="upload-button icon-14" width="14"  viewBox="0 0 24 24">
                                  <path fill="#ffffff" d="M14.06,9L15,9.94L5.92,19H5V18.08L14.06,9M17.66,3C17.41,3 17.15,3.1 16.96,3.29L15.13,5.12L18.88,8.87L20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18.17,3.09 17.92,3 17.66,3M14.06,6.19L3,17.25V21H6.75L17.81,9.94L14.06,6.19Z" />
                               </svg></label>
                               <input class="file-upload" id="avatar" name="image" onchange="readImage(this,'#avatar-img')" type="file" accept="image/*">
                            </div>
                         </div>
                         <div class="img-extension mt-3">
                            <div class="d-inline-block align-items-center">
                               <span>Only</span>
                               <a href="javascript:void();">.jpg</a>
                               <a href="javascript:void();">.png</a>
                               <a href="javascript:void();">.jpeg</a>
                               <span>allowed</span>
                            </div>
                         </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">User Role:</label>
                        <select name="role_id" class="selectpicker form-control" data-style="py-0">
                           <option value="">Select</option>
                           @isset($rls)
                           @foreach ($rls as $role)
                               <option value="{{$role->id}}" {{$e->roles->first()?->id == $role->id ? 'selected' : ''}}>{{$role->name}}</option>
                           @endforeach

                           @endisset
                        </select>
                     </div>
                      <div class="form-group">
                         <label class="form-label">Zone:</label>
                         <select name="zone_id" class="selectpicker form-control" data-style="py-0">
                            <option value="">Select</option>
                            @isset($zns)
                            @foreach ($zns as $zone)
                                <option value="{{$zone->id}}" {{$e->zone_id == $zone->id ? 'selected' : ''}}>{{$zone->name}}</option>
                            @endforeach

                            @endisset
                         </select>
                      </div>


                </div>
             </div>
          </div>
          <div class="col-xl-9 col-lg-8">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                      <h4 class="card-title">Update Employee Information</h4>
                   </div>
                </div>
                <div class="card-body">
                   <div class="new-user-info">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                         <div class="row">
                            <div class="form-group col-md-6">
                               <label class="form-label" for="fname">First Name:</label>
                               <input type="text" class="form-control" value="{{old('f_name') ?? $e->f_name}}" name="f_name" id="fname" placeholder="First Name">
                            </div>
                            <div class="form-group col-md-6">
                               <label class="form-label" for="lname">Last Name:</label>
                               <input type="text" class="form-control" value="{{old('l_name') ?? $e->l_name}}" name="l_name" id="lname" placeholder="Last Name">
                            </div>
                            {{-- @dd($e) --}}
                            <div class="form-group col-md-12 d-none">
                               <label class="form-label" for="add1">Street Address :</label>
                               <input type="text" class="form-control" name="street" id="add1" placeholder="Street Address ">
                            </div>
                            <div class="form-group col-md-6">
                               <label class="form-label" for="mobno">Mobile Number:</label>
                               <input type="text" class="form-control" id="mobno" name="phone" value="{{old('phone') ?? $e->phone}}" placeholder="Mobile Number">
                            </div>
                            <div class="form-group col-md-6">
                               <label class="form-label" for="email">Email:</label>
                               <input type="email" class="form-control" name="email" id="email" value="{{old('email') ?? $e->email}}" placeholder="Email">
                            </div>
                            <div class="form-group col-md-6 d-none">
                               <label class="form-label" for="pno">Pin Code:</label>
                               <input type="text" class="form-control" name="pincode" id="pno" placeholder="Pin Code">
                            </div>
                            <div class="form-group col-md-6 d-none">
                               <label class="form-label" for="city">Town/City:</label>
                               <input type="text" class="form-control" id="city" name="city" placeholder="Town/City">
                            </div>
                         </div>
                         <hr>
                         <h5 class="mb-3">Security</h5>
                         <div class="row">
                            <div class="form-group col-md-6">
                               <label class="form-label" for="pass">Password:</label>
                               <input type="password" class="form-control" name="password" id="pass" placeholder="Password">
                            </div>
                            <div class="form-group col-md-6">
                               <label class="form-label" for="rpass">Repeat Password:</label>
                               <input type="password" class="form-control" id="rpass" name="c_password" placeholder="Repeat Password ">
                            </div>
                         </div>

                         <button type="submit" class="btn btn-primary">Update</button>
                      </form>
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
function readImage(input,selector) {
    try{
        const reader = new FileReader();
        reader.onload = function (e) {
            const imgSrc = e.target.result;
            document.querySelector(selector).src = imgSrc;
        };
        reader.readAsDataURL(input.files[0]);
    }catch(error){
        console.error(error);
    }

}
</script>
@endpush
