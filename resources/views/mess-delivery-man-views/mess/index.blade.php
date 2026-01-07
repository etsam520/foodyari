@extends('user-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        @if(!empty($meesMunu))
        <div class="col-md-12 card p-4">
            <div class="d-flex justify-content-around">
                <div class="f-item ">
                    <h3>Today Menu</h3>
                </div>
                <div class="f-item">
                    <button 
                        class="btn {{$userInfo->diet_status === 0?'btn-outline-success'  :'btn-outline-danger'}} " data-hold-diet="{{$userInfo->diet_status === 0 ? 1:0}}">
                        {{$userInfo->diet_status === 0 ? 'Active Your Diet':'Hold Your Diet'}}
                    </button>
                </div>
            </div>
        </div>
        @php ($defaultMessIcons = [
            asset('assets/images/icons/breakfast.png'),asset('assets/images/icons/lunch-box.png'),asset('assets/images/icons/dinner.png')
        ])
        @foreach ($meesMunu as $menu)
        @php($last_updated = Carbon\Carbon::now()->diff(Carbon\Carbon::parse($menu->created_at)))
        @php($service = $menu->messServices[0])
        {{-- @dd($menu) --}}
         <div class="col-md-4">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-5 p-2">
                        <img class="bd-placeholder-img" src="{{$menu->image ?asset("MessMenu/$mess->image") :$defaultMessIcons[$loop->index] }}"  width="150px" height="auto" alt="">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <h5 class="card-title">{{$menu->name}}</h5>
                            <p class="card-text">{{$menu->description}}</p>
                            <p class="card-text">
                                <small class="text-muted">Last updated {{ $last_updated->format('%h hrs %I mins') }} ago</small><br>
                                <small class="text-info">{{$service->name}}</small><br><span data-cancel-for="{{$menu->name}}" data-menu-id="{{$menu->id}}" class="badge bg-danger mx-2 p-1">Cancel</span><span data-addons-for="{{$menu->name}}" data-menu-id="{{$menu->id}}" class="badge bg-info mx-2 p-1">Addons</span>
                            </p>
                        </div>
                    </div>
                    @php($addons = App\Models\MessAddonModel::whereIn('id',json_decode($menu->addons))->get())

                    {{-- @if(!empty($addons))
                        <div class="col-md-12 px-3">
                            <p class="card-text"> Addons
                                <dl class="row pricing">
                                    @foreach ($addons as  $addon)
                                     <dt class="col-6">{{$addon->name}} <i>[@ {{$addon->price}}]</i></dt>
                                        <dd class="col-6 text-right">
                                            <div class="btn-group mr-2 shadow-sm" role="group">
                                                <button type="button" class="btn btn-sm btn-danger change_tiffin" data-type="dinner" data-value="-1">+</button>
                                                <button type="button" class="btn btn-sm btn-light border count_tiffin px-3" data-type="dinner">1</button>
                                                <button type="button" class="btn btn-sm btn-success change_tiffin" data-type="dinner" data-value="1">-</button>
                                              </div>
                                        </dd>   
                                    @endforeach
                                    <dt class="col-8">Extra Cost:</dt>
                                     <dd class="col-4 text-right">0 ₹</dd>  
                                </dl>
                            </p>
                        </div> 
                    @endif --}}
                </div>
            </div>
        </div>   
        @endforeach
    </div>
    @endif

    <div class="row">
        <div class="col-md-12 col-lg-12">

            <div class="row row-cols-1">
               <div class="card p-3">
                 <h5>NEAREST MESS</h5>       
                </div> 
                <div class="overflow-hidden d-slider1 ">
                    
                    <ul  class="p-0 m-0 mb-2 swiper-wrapper list-inline">
                        @foreach ($messList as $mess)
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                           <a href="{{route('user.mess.subscriptions', $mess->id)}}">
                               <div class="card-body">
                                   <div class="progress-widget">
                                       <div class="progress-detail">
                                           <h4 class="counter">{{$mess->name}}</h4>
                                           <p  class="mb-2">{{$mess->address}}</p>
                                       </div>
                                   </div>
                               </div>
                            </a> 
                        </li>
                        @endforeach
                       
                        
                    </ul>
                    <div class="swiper-button swiper-button-next"></div>
                    <div class="swiper-button swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@push('javascript')
<script src="{{asset('assets/vendor/sweetalert/sweetalert.min.js')}}"></script>
<script>
    /**
     * ==============================// Events for Add Addons  start //=========================
    */
    document.querySelectorAll('[data-addons-for]').forEach(element => {
        element.style.cursor = "pointer";
        element.addEventListener('click',async function(){
            try{
                const resp = await fetch("{{route('user.mess.addons')}}?menu_id="+element.dataset.menuId);
                const result =await resp.json();
                /**
                 * ===================// Intializing a object to calculate price //=========================
                */
                const addonObj = new Object({
                        setattrubute: function(key, value) {
                            this[key] = {price : value.price , quantity : value.quantity};
                        },
                        sumUP: function() {
                            let sum = 0;
                            for (let key in this) {
                                if (typeof this[key] === 'object') {
                                    if(typeof this[key].price === 'number' &&  typeof this[key].quantity === 'number'){
                                        sum += (this[key].price * this[key].quantity);
                                    }
                                }
                            }
                            return sum;
                        }
                    });

                if(result.error){
                    throw new Error(result.error);
                }
                /**
                 *=============================// seting this to append addons with selected menu//=================================
                */
                let ElemToappend = `<div class="col-md-12 px-3" data-group="${element.dataset.addonsFor}">
                        <p class="card-text"> Addons
                            <dl class="row pricing">`;
                ;
                if (Array.isArray(result)){
                    ElemToappend += result.map(item => {
                        addonObj.setattrubute(item.name, {price : item.price, quantity : 0, id : item.id})
                        return `<dt class="col-6">${item.name} <i>[${item.price}]</i></dt>
                                    <dd class="col-6 text-right">
                                        <div class="btn-group mr-2 shadow-sm" role="group">
                                            <button type="button" class="btn btn-sm btn-danger" data-name="${item.name}"  data-plus="0">-</button>
                                            <button type="button" class="btn btn-sm btn-light border px-3" data-quantity="true">0</button>
                                            <button type="button" class="btn btn-sm btn-success " data-name="${item.name}" data-plus="1">+</button>
                                        </div>
                                    </dd>`;
                    }).join('\n');
                }else{
                   
                    throw new Error('Failed to Load the data')
                }

                    ElemToappend += `<dt class="col-8">Extra Cost:</dt>
                                    <dd class="col-4 text-right" data-sum="0">${addonObj.sumUP()} ₹</dd> 
                                    <dt class="col-8">Did You Confirm ?</dt>
                                    <dd class="col-4 text-right"> <button type="button" class="btn btn-sm btn-success "  data-confirm="0" >I do.</button></dd> 

                                </dl>
                            </p>
                        </div>`;
                    let checkElemAlreadyExists = document.querySelector(`[data-group="${element.dataset.addonsFor}"]`);
                    if (!checkElemAlreadyExists) {
                        element.closest('.row').insertAdjacentHTML('beforeend', ElemToappend);
                    }
                    /**
                     * =======================// increasing and decreasing qunatity value //======================
                    */
                    element.closest('.row').querySelectorAll('[data-plus]').forEach(item => {
                        item.addEventListener('click',() =>{      
                            if (item.dataset.plus == "0") {
                                if (addonObj[item.dataset.name].quantity > 0) {
                                    addonObj[item.dataset.name].quantity--;
                                }
                            } else {
                                addonObj[item.dataset.name].quantity++;
                            }
                            item.closest('div').querySelector('[data-quantity]').textContent = addonObj[item.dataset.name].quantity;
                            item.closest('div').querySelector('[data-quantity]').dataset.quantity = addonObj[item.dataset.name].quantity;
                            item.closest('.row').querySelector('[data-sum]').dataset.quantity = addonObj.sumUP(); 
                            item.closest('.row').querySelector('[data-sum]').textContent = addonObj.sumUP() +' ₹'; 
                        })
                    }); 
                    /**
                     * Making request to store addons
                    */
                    element.closest('.row').querySelector('[data-confirm]').addEventListener('click',async () =>{ 
                        const currentELEM = event.target;
                        const res2 = await fetch("{{route('user.mess.addons')}}", {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            body: JSON.stringify({addons : addonObj, menu_id : element.dataset.menuId })
                        });
                        const result2 = await res2.json();
                        if(result2.success){
                            toastr.success(result2.success);
                        }else if(result2.error){
                            toastr.error(result2.error);
                        }
                    })     
            }catch(error){
                toastr.error(error.message);
                console.error(error)
            }
        })  
    });
    /**
     *======================================// Events for Add Addons  end //==========================
    */
</script>
<script>
    /***
     * ========================// cancelling Diet //==============
     */
     document.querySelectorAll('[data-cancel-for]').forEach(element => {
        element.style.cursor = "pointer";
        element.addEventListener('click', async ()=> {
            const currentELEM = event.target;
            const willCancel = await swal({title: "Are you sure?",icon: "warning",buttons: true,dangerMode: true,})
            if(willCancel){
                const res = await fetch("{{route('user.mess.dietCancel')}}?menu_id="+element.dataset.menuId);
                const result = await res.json();
                if(result.success){
                    toastr.success(result.success);
                }else if(result.error){
                    toastr.error(result.error);
                }
            }else{
                swal("Your Diet ain't Cancelled !");
            }
        })
     })
     

     /***@argument
      * 
      * =================// holding Diet //======================
      */
     document.querySelectorAll('[data-hold-diet]').forEach(element => {
        element.style.cursor = "pointer";
        element.addEventListener('click', async ()=> {
            const currentELEM = event.target;
            const willCancel = await swal({title: "Are you sure?",icon: "warning",buttons: true,dangerMode: true,})
            if(willCancel){
                const res = await fetch("{{route('user.mess.hold-diet')}}");
                const result = await res.json();
                if(result.success){
                    toastr.success(result.success);
                    element.dataset.holdDiet = result.holdDietIndex;
                    element.classList.toggle('btn-outline-danger');
                    element.classList.toggle('btn-outline-success');
                    element.textContent = result.textContent;
                }else if(result.error){
                    toastr.error(result.error);
                }
            }else{
                swal("Your Diet ain't Cancelled !");
            }
        })
     })
</script>
    
@endpush
