@php
if($timing){
    $deleveryTiming = json_decode($timing->delivery);
    $dineInTiming = json_decode($timing->dine_in);
}
@endphp

@extends('mess-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title"> Mess Timing</h4>
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
                            
                          <form class="row g-3 needs-validation" method="POST" enctype="multipart/form-data"  action="{{route('mess.profile.timing')}}">
                            @csrf
                            <div class="col-12">
                                <p class="text-muted text-center fs-5">Delivery Timing</p>
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="delivery_breakfast">Breakfast</label>
                                    <input id="delivery_breakfast" type="time" name="delivery_breakfast"
                                        class="form-control h--45px" 
                                        value="{{$deleveryTiming?$deleveryTiming->breakfast:null}}" >
                                </div> 
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="delivery_lunch">
                                        Lunch</label>
                                    <input id="delivery_lunch" type="time" name="delivery_lunch"
                                        class="form-control h--45px" 
                                        value="{{$deleveryTiming?$deleveryTiming->lunch:null}}" >
                                </div> 
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="delivery_dinner">Dinner</label>
                                    <input id="delivery_dinner" type="time" name="delivery_dinner"
                                        class="form-control h--45px" 
                                        value="{{$deleveryTiming?$deleveryTiming->dinner:null}}" >
                                </div> 
                            </div>

                            <div class="col-12">
                                <h3 class="text-muted text-center fs-5">Dine In Timing</h3>
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="dinein_breakfast">Breakfast</label>
                                    <input id="dinein_breakfast" type="time" name="dinein_breakfast"
                                        class="form-control h--45px" 
                                        value="{{$dineInTiming?$dineInTiming->breakfast:null}}" >
                                </div> 
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="dinein_lunch">Lunch</label>
                                    <input id="dinein_lunch" type="time" name="dinein_lunch"
                                        class="form-control h--45px" 
                                        value="{{$dineInTiming?$dineInTiming->lunch:null}}" >
                                </div> 
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="dinein_dinner">Dinner</label>
                                    <input id="dinein_dinner" type="time" name="dinein_dinner"
                                        class="form-control h--45px" 
                                        value="{{$dineInTiming?$dineInTiming->dinner:null}}" >
                                </div> 
                            </div>
                             <div class="col-md-6 ">
                                <button class="btn btn-primary" type="submit">Save</button>
                             </div>
                          </form>
                       </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
@endsection
