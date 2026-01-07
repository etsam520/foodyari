@extends('user-views.restaurant.layouts.main')
@section('containt')

<div class="osahan-home-page" id="content-wrapper">

    <div class="container position-relative">
      <div class="py-5">
        <div class="col-lg-4 col-md-6 col-sm-8 mx-auto">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-light text-center border-bottom-0 p-3">
              <h3 class="text-dark font-25 fw-bolder">Account Details</h3>
              <p class="text-50 mb-0 mt-2">Continue to delete</p>
            </div>
            <div class="card-body">
              <div class="text-center">
                <h6 class="mb-3"><strong>{{Str::ucfirst($user->f_name).' '.Str::ucfirst($user->l_name)}}</strong></h6>
                <h6>Email : {{$user->email}}</h6>
                <h6>Phone : {{$user->phone}}</h6>
              </div>
              <hr>
           
              <form action="{{route('user.auth.user.destroy', $user->id)}}" method="POST" >
                  @method('DELETE')
                @csrf
                <div id="account_config">
                  <div class="form-group mb-2 mt-3 text-center">
                    <input type="hidden" name="request_for_delete" value="{{$user->id}}" >
                    <button type="submit" class="btn btn-primary px-4">Yes, I want to delete!</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  
  </div>
@endsection