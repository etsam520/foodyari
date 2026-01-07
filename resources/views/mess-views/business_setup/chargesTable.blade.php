@extends('mess-views.layouts.dashboard-main')
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
@endpush
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
        <form  method="POST" id="cusomer-form" action="{{route('mess.business-setup.charges')}}">
            @csrf
       <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Mess Charges Information</h4>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="new-user-info">
                            <div class="row">
                                
                                <div class="form-group col-md-6">
                                <label class="form-label" for="gst">GST :</label>
                                <input type="number" class="form-control" name="GST" id="gst" value="{{$charges && $charges->GST?$charges->GST:old('GST')}}" placeholder="GST IN %">
                                @if($errors->has('GST'))
                                    <span class="text-danger">{{$errors->first('GST')}}</span>
                                @endif
                                </div>	

                                <div class="form-group col-md-6">
                                <label class="form-label" for="mess-charge">Mess Charge:</label>
                                <input type="text" class="form-control" name="mess_charge" id="mess-charge" value="{{$charges && $charges->mess_charge?$charges->mess_charge:old('mess_charge')}}" placeholder="0">
                                @if($errors->has('mess_charge'))
                                    <span class="text-danger">{{$errors->first('mess_charge')}}</span>
                                @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="admin-charge">Admin Charge:</label>
                                    <input type="text" class="form-control" name="admin_charge" id="admin-charge" value="{{$charges && $charges->admin_charge?$charges->admin_charge:old('admin_charge')}}" placeholder="0">
                                    @if($errors->has('admin_charge'))
                                        <span class="text-danger">{{$errors->first('admin_charge')}}</span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                <label class="form-label" for="mess-charge-pf">Mess Charge(Percent/Fixed) :</label>
                                <select name="mess_charge_type" id="mess-charge-pf" class="form-control">
                                    @php
                                        $m_selected['P'] = ($charges && Str::ucfirst($charges->mess_charge_type[0]) == "P") ? 'selected' : null;
                                        $m_selected['F'] = ($charges && Str::ucfirst($charges->mess_charge_type[0]) == "F") ? 'selected' : null;
                                    @endphp
                                    <option value="">Select One</option>
                                    <option value="F" {{ $m_selected['F'] }}>Fixed</option>
                                    <option value="P" {{ $m_selected['P'] }}>Percent</option>
                                </select>
                                @if($errors->has('mess_charge_type'))
                                    <span class="text-danger">{{$errors->first('mess_charge_type')}}</span>
                                @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label" for="admin-charge-pf"> Admin Charge(Percent/Fixed) :</label>
                                    <select name="admin_charge_type" id="admin-charge-pf" class="form-control" value="{{$charges && $charges->admin_charge_type?$charges->admin_charge_type:old('admin_charge_type')}}">
                                        @php
                                        $a_selected['P'] = ($charges && Str::ucfirst($charges->admin_charge_type[0]) == "P") ? 'selected' : null;
                                        $a_selected['F'] = ($charges && Str::ucfirst($charges->admin_charge_type[0]) == "F") ? 'selected' : null;
                                        @endphp
                                        <option value="">Select One</option>
                                        <option value="F" {{ $a_selected['F'] }}>Fixed</option>
                                        <option value="P" {{ $a_selected['P'] }}>Percent</option>
                                    </select>
                                    @if($errors->has('admin_charge_type'))
                                        <span class="text-danger">{{$errors->first('admin_charge_type')}}</span>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label" for="delivery-charge">Delivery Charge:</label>
                                    <input type="text" class="form-control" name="delivery_man_charge" value="{{$charges && $charges->delivery_man_charge?$charges->delivery_man_charge:old('delivery_man_charge')}}" id="delivery-charge" placeholder="0">
                                    @if($errors->has('delivery_man_charge'))
                                        <span class="text-danger">{{$errors->first('delivery_man_charge')}}</span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="delivery-charge-pf"> Delivery Charge(Percent/Fixed) :</label>
                                    <select name="delivery_man_charge_type" id="delivery-charge-pf" class="form-control" value="{{$charges && $charges->delivery_man_charge_type?$charges->delivery_man_charge_type:old('delivery_man_charge_type')}}">
                                        @php
                                        $d_selected['P'] = ($charges && Str::ucfirst($charges->delivery_man_charge_type[0]) == "P") ? 'selected' : null;
                                        $d_selected['F'] = ($charges && Str::ucfirst($charges->delivery_man_charge_type[0]) == "F") ? 'selected' : null;
                                        @endphp
                                        <option value="">Select One</option>
                                        <option value="F" {{ $d_selected['F'] }}>Fixed</option>
                                        <option value="P" {{ $d_selected['P'] }}>Percent</option>
                                    </select>
                                    @if($errors->has('delivery_man_charge_type'))
                                        <span class="text-danger">{{$errors->first('delivery_man_charge_type')}}</span>
                                    @endif
                                </div>
                               
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
 </div>
@endsection


