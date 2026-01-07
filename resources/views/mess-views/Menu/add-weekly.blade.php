@extends('mess-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
       <div class="row">
          <div class="col-xl-4 col-lg-4">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                      <h4 class="card-title">Add Menu Item Weekly</h4>
                   </div>
                </div>
                <div class="card-body">
                   <form action="{{route('mess.menu.add.weekly')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                      <div class="form-group">
                        <div class="card-body ">
                           <img class="initial-57-2" id="item-image"
                               src="{{asset('assets/images/icons/food.svg')}}"
                               alt="delivery-man image">

                           <div class="form-group mb-0">
                               <label class="input-label">Image<small class="text-danger">
                                       (Ratio 1:1)</small></label>
                               <div class="custom-file">
                                   <input type="file" name="image" value="{{old('image')}}" id="customFileEg1" onchange="readImage(this, '#item-image')" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                               </div>
                           </div>
                        </div>
                      </div>
                      {{-- set day --}}
                      @php($days = App\CentralLogics\Helpers::getDayname())
                      <div class="form-group">
                        <label class="form-label">Day:</label>
                        <select name="day"  class="selectpicker form-control" data-style="py-0">
                           <option disabled selected value="">Select One</option>
                           @if ($days)
                               @foreach ($days as $key => $value)
                                   <option value="{{ $key }}" class="text-uppercase">{{Str::upper($value) }}</option>
                               @endforeach
                           @endif
                        </select>
                     </div>
                      {{-- set servce --}}
                      @php($services = App\CentralLogics\Helpers::getService())
                      
                      <div class="form-group">
                         <label class="form-label">Set to:</label>
                         <select name="setTo"  class="selectpicker form-control" data-style="py-0">
                            <option disabled selected value="">Select One</option>
                            @if ($services)
                                @foreach ($services as $key => $value)
                                    <option value="{{ $key }}" class="text-uppercase">{{ Str::upper($value)}}</option>
                                @endforeach
                            @endif
                         </select>
                      </div>
                      <div class="form-group">
                        <label class="form-label"> Item type</label>
                        <select name="item_type"  class="selectpicker form-control" data-style="py-0">
                           <option disabled selected value="">Select One</option>
                           <option value="V">Veg</option>
                           <option value="N">Non Veg</option>
                           <option value="B">Both</option>
                        </select>
                     </div>
                      
                  
                </div>
             </div>
          </div>
          <div class="col-xl-8 col-lg-8">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                      <h4 class="card-title">Menu Details :</h4>
                   </div> 
                </div>
                @if ($errors->any())
                     <div class="alert alert-danger m-2">
                        <ul>
                           @foreach ($errors->all() as $error)
                                 <li class="mx-4 float-start" >{{ $error }}</li>
                           @endforeach
                        </ul>
                     </div>
               @endif
                <div class="card-body">
                   <div class="new-user-info">
                        <div class="row">
                            <div class="form-group col-md-5">
                               <label class="form-label" for="description">Description:</label>
                               <textarea name="description" id="description" class="form-control" cols="20" rows="10">{{old('description')}}</textarea>
                            </div>
                            <div class="col-md-7">
                              <div class="form-group ">
                                 <label class="form-label" for="lname"> Name:</label>
                                 <input type="text" class="form-control" name="name" value="{{old('name')}}" id="name" placeholder=" Name">
                              </div>
                              <h5 class="card-title">
                                 <span>Food Variations</span>
                              </h5>
                              <div class="form-group ">
                                 <label class="form-label" for="lname"> Name:</label>
                                 <select type="text" class="form-control select-2" multiple name="addons[]"  id="addon" placeholder=" Name">
                                    @foreach (App\Models\MessAddonModel::all() as $addon)
                                      <option value="{{$addon->id}}">{{$addon->name}} [@ {{App\CentralLogics\Helpers::format_currency($addon->price)}}]</option> 
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                        </div>
                        
                         
                        <button type="reset" class="btn btn-secondary">Reset</button>
                         <button type="submit" class="btn btn-primary">Save</button>
                      </form>
                   </div>
                   {{-- @dd(Session::get('vendor')) --}}
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
