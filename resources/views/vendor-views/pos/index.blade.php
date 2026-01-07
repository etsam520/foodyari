@php
    $restaurant = Session::get('restaurant');
@endphp
@extends('vendor-views.layouts.dashboard-main')
@push('css')

<style>
    dt {
        font-weight: 600;
    }
    .avatar-2{
        height: 178px;
        width: 100%;
        object-fit: cover;
        min-width: 80px;
        -webkit-border-radius: .25rem;
        border-radius: .25rem;
    }
    .gold-members .food-type {
        border: 1px solid;
        width: 15px;
        height: 15px;
        text-align: center;
        border-radius: 3px;
        font-size: 35px;
        line-height: 7px;
    }
    .package-img {
        height: 140px;
        width: 140px;
        border-radius: 20px;
    }
    .package-view {
        bottom: -21px;
        display: flex;
        justify-content: center;
        width: 100%;
    }
    .count-number .btn {
    padding: 2px 5px;
    font-size: 12px;
    border-radius: 0px;
    padding: 10px;
    }
    .count-number .item-decrement {
        border-radius: 0px 10px 10px 0px;
    }
    .count-number-input, .product-count-number-input {
        width: 51px;
        text-align: center;
        margin: 0 -4px;
        /* background: #6c757d; */
        border: none;
        color: #fff;
        height: 49px;
        font-size: 20px;
        font-weight: 900;
        border-radius: 0px;
        vertical-align: bottom;
    }

    .member-plan .item-img {
        height: 12px !important;
        background: white;
    }
    .product-count-number {
        border: 1px solid #ff810a !important;
        border-radius: 5px !important;
        font-weight: 900 !important;
        width: 98px !important;
        font-size: 18px;
        padding: 1px 8px;
        width: 117px;
    }
    .product-count-number .btn {
        font-size: 15px;
        padding: 1px 8px;
        font-weight: 900;
        color: #ff810a;
    }
    .product-count-number-input {
        width: 37px;
        color: #ff810a;
        height: 40px;
        font-weight: 700;
    }
    .pac-container{
        z-index: 99999;
    }
    .fullscreen {
        width: 100vw !important;
        height: 100vh !important;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
</style>
@endpush
@section('content')
<div class="conatiner-fluid content-inner mt-5 py-0">
  <div class="row">
    <div class="col-sm-12 col-lg-12">
      <div class="h-auto">
        <div class="card-header d-flex justify-content-between">
          <div class="header-title">
            {{-- <h4 class="card-title">{{ __('messages.add-category') }}</h4> --}}
          </div>
        </div>
        <div class="card-body">
          <div class="row">

            {{-- billing section --}}
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Billing Section</h4>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                        <div class="d-flex flex-wrap add-customer-btn">
                            <div class="form-group flex-grow-1">
                            <select id="cutomer" name="customer_id" data-placeholder="Select customer"
                                class="form-control"   tabindex="-1" aria-hidden="true">

                                <option value="walk-in" selected>Walk In</option>
                                @foreach ($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->phone}}</option>
                                @endforeach
                            </select>
                            </div>
                            <div class="form-group d-inline mx-2">
                            <a href="{{route('admin.customer.add')}}" class="btn btn-outline-primary"  title="Add Customer">
                                Add new customer
                            </a>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-12">
                        <div class="header-title">
                            <div class="pos-delivery-options">
                            <div class="d-flex justify-content-between ">
                                <h5 class="card-title " data-title="delivery-info">
                                    <span class="card-title-icon">
                                        <i class="tio-user"></i>
                                    </span>
                                    <span>Delivery Infomation</span>
                                </h5>

                            </div>
                            <div class="pos-delivery-options-info" id="del-add">
                                @include('vendor-views.pos._address')
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-12">
                        <div class="card-body p-0">
                            <div class="w-100 table-responsive" id="cart">
                            <table class="table" id="cart-table" style="width: 100%">
                                <thead class="thead-light border-0">
                                <tr>
                                    <th class="py-2" scope="col">S.I.</th>
                                    <th class="py-2" scope="col">Item</th>
                                    <th class="py-2" scope="col">Qty</th>
                                    <th class="py-2 text-center" scope="col">Amount</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <div class="box p-3" id="bill-box">
                                <dl class="row pricing">
                                </dl>
                                <!-- Static Data -->
                                <form action="javascript:void(0)" method='post' id="place-order-form">
                                @csrf
                                <input type="hidden" name="customer_id" value="walk-in" data-set-customer="1">
                                <div class="pos--payment-options mt-3 mb-3">
                                    <h5 class="mb-3">Payment Method</h5>
                                    <ul class="d-flex ps-0">
                                    <li>
                                        <label>
                                        <input type="radio" name="type" value="cash" hidden="" checked="">
                                        <span>Cash On Delivery</span>
                                        </label>
                                    </li>
                                    {{-- <li>
                                        <label>
                                        <input type="radio" name="type" value="wallet" hidden="">
                                        <span>Wallet</span>
                                        </label>
                                    </li> --}}
                                    </ul>
                                </div>
                                <!-- Static Data -->
                                <div class="row  mt-3 g-1 bg-white">
                                    <div class="col-sm-12">
                                    <button type="submit" class="btn btn-outline-primary btn-block">
                                        <label>
                                            <input type="radio" name="kot" value="0" hidden="">
                                            Place Order
                                        </label>
                                        Place
                                    </button>


                                    <button type="submit" class="btn btn-outline-primary btn-block">
                                        <label>
                                            <input type="radio" name="kot" value="1" hidden="">
                                            Place Order + Print Kot
                                        </label>
                                    </button>

                                    <a href="javascript:void(0)" class="btn btn-outline-gray btn-block" onclick="emptyCart()">Clear
                                        Cart</a>
                                    </div>
                                </div>
                                </form>
                            </div>
                            </div>
                        </div>
                        </div>
                        <!-- end billing section -->
                        <!-- Last Order section -->
                        <div class="col-12" id="showLastOrder">
                        @if(Session::has('last_order'))
                            <div class="card aos-init aos-animate" data-aos="fade-up" data-aos-delay="500">
                                <p class="py-2 ps-3 text-muted">Last Order</p>
                                @php($last_order = App\Models\Order::with(['customer'])->find(Session::get('last_order')))
                                <div class="text-center card-body d-flex justify-content-around">
                                    <div>

                                    <h3 class="mb-2">#{{$last_order->id}}</h3>
                                    <p class="mb-0 text-gray">{{$last_order->customer?$last_order->customer->f_name : 'Walk-in'}}</p>
                                    </div>
                                    <hr class="hr-vertial">
                                    <div>
                                    {{-- <h2 class="mb-2">7,500</h2> --}}
                                    <p class="mb-0 ">
                                        <a class="badge py-1 px-2 bg-soft-success"  href="{{route('vendor.order.generate-KOT',$last_order->id)}}"  type="button">Print KOT</a>
                                        <a class="badge py-1 px-2 bg-soft-warning" href="{{route('vendor.order.generate-invoice',$last_order->id)}}" type="button"><i class="fa fa-print"></i>Print Bill</a>
                                    </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        </div>

                    </div>
                    </div>
                </div>
            </div>

              {{-- food section  --}}
            <div class="col-md-7">
              <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <div class="header-title">
                    <h4 class="card-title">Food Section</h4>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-6">
                      <select name="menu_id" id="menu_id" class="form-control" onchange="get_options('{!! route('vendor.food.get-submenu-option').'?menu_id='!!}'+this.value,'#submenu_id','Select All')"
                        title="Select Category" >
                        <option value="">Select Menu</option>
                        {{$menu = \App\Models\RestaurantMenu::isActive(true)->where('restaurant_id',$restaurant->id)->latest()->get()}}
                        <option value="all">All Menu</option>
                        @foreach ($menu as $menuItem)
                          <option value="{{$menuItem->id}}">{{Str::upper($menuItem->name)}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                      <select name="submenu_id" id="submenu_id" class="form-control" onchange="getFoods()"
                        title="Select Subcategory" >
                        <option value="">Select Subcategory</option>
                      </select>
                    </div>
                    <div class="col-sm-6">
                      <form id="search-form" class="mw-100">
                        <!-- Search -->
                        <div class="input-group input-group-merge input-group-flush w-100">

                          <input id="datatableSearch" type="search" value="" data-search-food="all" name="search_food"
                            class="form-control flex-grow-1 pl-5 border rounded h--45x"
                            placeholder="Ex : Search Food Name" aria-label="Search here">
                        </div>
                        <!-- End Search -->
                      </form>
                    </div>
                  </div>
                </div>

                <div class="card-body d-block " id="items">
                  <div class="" data-products="all">

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



<!-- Custom Item popup -->

  <div class="offcanvas offcanvas-end" tabindex="-1" id="custom_item" aria-labelledby="customizeCartLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="customizeCartLabel">Customize Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" data-products="single">
        <p>Some placeholder content for the customize cart offcanvas.</p>
    </div>
  </div>

  <div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light border-bottom py-3">
                <h5 class="modal-title flex-grow-1 text-center">{{ __('Delivery Information') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <?php
              if (session()->has('address')) {
                  $old = session()->get('address');
              } else {
                  $old = null;
              }
              ?>
              <form id='delivery_address_store' method="post" onsubmit="event.preventDefault()">
                @csrf

                <div class="row g-2" id="delivery_address_field">
                    <div class="col-md-6">
                        <label class="input-label" for="">{{ __('messages.contact_person_name') }}<span class="input-label-secondary text-danger">*</span></label>
                        <input type="text" class="form-control" name="contact_person_name" value="{{ $old ? $old['contact_person_name'] : '' }}" placeholder="{{ __('messages.Ex :') }} Jhone">
                    </div>
                    <div class="col-md-6">
                        <label class="input-label" for="">{{ __('Contact Number') }}<span class="input-label-secondary text-danger">*</span></label>
                        <input type="tel" class="form-control" name="contact_person_number" value="{{ $old ? $old['contact_person_number'] : '' }}" placeholder="{{ __('messages.Ex :') }} +3264124565">
                    </div>

                    @php($position = $old ? (is_array($old['position']) ? $old['position'] : json_decode($old['position'], true)) : null)
                    {{-- @dd($position) --}}
                    <div class="col-md-6 d-none">
                        <label class="input-label" for="">{{ __('messages.longitude') }}<span class="input-label-secondary text-danger">*</span></label>
                        <input type="hidden"  class="form-control" id="longitude" name="longitude" value="{{ $old ? $position['lat'] : '' }}" >
                    </div>
                    <div class="col-md-6 d-none">
                        <label class="input-label" for="">{{ __('messages.latitude') }}<span class="input-label-secondary text-danger">*</span></label>
                        <input type="hidden" class="form-control" id="latitude" name="latitude" value="{{ $old ? $position['lon'] : '' }}" >
                    </div>
                    <div class="col-md-4">
                      <label for="" class="input-label">{{_('Landmark')}}<span class="input-label-secondary text-danger">*</span></label>
                      <input type="text"name="landmark"  data-user-address='address' class="form-control" placeholder="Landmark" onsubmit="event.preventDefault()" >
                    </div>

                    {{-- <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="text-primary">
                                {{ __('* pin the address in the map to calculate delivery fee') }}
                            </span>
                        </div>
                    </div> --}}
                    <div class="col-md-8 mx-auto">
                        <div class="form-group ">
                        <label class="input-label" for="search-address-input">{{ __('Address') }}<span class="input-label-secondary text-danger">*</span></label>

                            <input type="text" name="address" id="search-address-input" class="form-control rounded-0" placeholder="Enter Address or Place">
                        </div>
                    </div>
                    <div class="col-12">

                      <div id="map-canvas" style=" width:100%;height: 50vh"></div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="btn--container justify-content-end">
                        <button class="btn btn-sm btn-primary w-100" type="submit">
                            {{ __('Update') }} {{ __('messages.Delivery address') }}
                        </button>
                    </div>
                </div>
              </form>
            </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="add-discount" tabindex="-1">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">{{__('messages.update_discount')}}</h5>
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form action="{{route('vendor.pos.discount')}}" id="custom-discount" method="post" class="row">
                      @csrf
                      <div class="form-group col-sm-6">
                          <label for="">{{__('messages.discount')}}</label>
                          <input type="number" class="form-control" name="discount" min="0" id="discount_input"  max="1000000000">
                      </div>
                      <div class="form-group col-sm-6">
                          <label for="">{{__('messages.type')}}</label>
                          <select name="type" class="form-control" id="discount_input_type">
                              <option value="amount" >{{__('messages.amount')}}({{\App\CentralLogics\Helpers::currency_symbol()}})</option>
                              <option value="percent">{{__('messages.percent')}}(%)</option>
                          </select>
                      </div>
                      <div class="form-group col-sm-12">
                          <button class="btn btn-sm btn-primary" type="submit">{{__('messages.submit')}}</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="add-tax" tabindex="-1">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">{{__('messages.update_tax')}}</h5>
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form action="{{route('vendor.pos.tax')}}" method="POST" class="row" id="custom-tax">
                      @csrf
                      <div class="form-group col-12">
                          <label for="">{{__('messages.tax')}}(%)</label>
                          <input type="number" class="form-control" name="tax" min="0">
                      </div>

                      <div class="form-group col-sm-12">
                          <button class="btn btn-sm btn-primary" type="submit">{{__('messages.submit')}}</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>

  <!--  KOT OFFcanvas -->

  <div class="offcanvas offcanvas-bottom fullscreen non-printable" tabindex="-1" id="KOT_OFFCANVAS" aria-labelledby="offcanvasBottomLabel">
    <i class="position-absolute top-0 end-0 text-primary me-2 mt-2 fa fa-close fa-2x" style="z-index: 1;" data-bs-dismiss="offcanvas"></i>
    <div class="offcanvas-body m-1 p-1 ">
        <div class="mapouter m-0 p-0"id="kot_fragment">
        </div>
    </div>
  </div>


</div>


@endsection

@push('javascript')

<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places">
</script>
<script src="{{asset('assets/js/Helpers/mapHelper.js')}}"></script>

<script>
    async function get_options(url,element_id,optionName){
        try {
            const resp = await fetch(url);
            const result = await resp.json();
            if (resp.ok && result !== null) {
                console.log(result);
                const fragment = document.createDocumentFragment();
                result.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    fragment.appendChild(option);
                });
                const targetElement = document.querySelector(element_id);
                targetElement.innerHTML = '<option value="" desabled >Choose '+optionName+'</option>';
                targetElement.appendChild(fragment);
                await getFoods();
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    document.querySelector('[data-search-food]').addEventListener('keyup',function(){
      getFoods();
    });

</script>

<script>
const ASSET_URL = uri => "{{url('/public')}}/"+uri+"/";
// const BASE_URL = uri => "{{url('/')}}/"+uri;

/*=============//get foods  //===============*/
async function getFoods(url=null){
    let menu = document.querySelector('#menu_id');
    let submenu = document.querySelector('#submenu_id');
    let foodname = document.querySelector('[data-search-food]');
    if(url == null){
        url = "{{url('restaurant-panel/pos/foods')}}"; //
        url += menu.value? "?menu_id="+menu.value : '?menu_id=all';
    }else{
        url += menu.value? "&menu_id="+menu.value : '&menu_id=all';
    }
    url += submenu.value? "&submenu_id="+submenu.value : '';
    url += foodname.value? "&food_name="+foodname.value : '';
    try {
        const resp = await fetch(url);
        const result = await resp.json();
        if (resp.ok && result !== null) {
            document.querySelector('[data-products=all]').innerHTML = result.view;
            changer();
            viewSingleFood();
        }
        document.querySelectorAll('.pagination .page-item a').forEach(element => {
            element.addEventListener('click', function(e) {
                e.preventDefault();  // Prevent the default link behavior

                // Add your custom logic here, such as handling AJAX request
                const url = this.href; // Get the URL of the clicked link
                getFoods(url);

            });
        });
    } catch (error) {
        console.error('Error fetching data:', error);
    }

}
getFoods();

/*============//changer of uncustomized food [quantity increment decrement & add to cart]//=================*/
function changer() {
      document.querySelectorAll('[data-changer-target]').forEach(element => {
          element.addEventListener('click', () => {
              const targetSelector = `[data-changer="${element.dataset.changerTarget}"]`;
              const targetElement = document.querySelector(targetSelector);

              if (targetElement) {
                  targetElement.classList.remove('d-none');
              }
              const packageView = element.closest('.package-view');

              if (packageView) {
                  const quantityElement = targetElement.closest('.package-view').querySelector('[data-product-price]');
                  if (quantityElement.value > 0 && quantityElement.value < 2) {
                      const currentPrice = {
                          quantity: quantityElement.value,
                          price: parseFloat(quantityElement.dataset.productPrice),
                          priceCalculate: function() {
                              let tempPrice = parseFloat(this.price) * this.quantity;
                              return tempPrice;
                          },
                      };
                      // console.log(targetElement);
                      addToCart(quantityElement.dataset.foodId, currentPrice.quantity, currentPrice.priceCalculate(), {});
                  }
                  packageView.remove();

              }
          });
      });

      /*********** //food quantity increment decrement //****************/
      document.querySelectorAll('[data-food-increment]').forEach(item => {
          const currentPrice = {
              quantity: 1,
              price: parseFloat(item.closest('.package-view').querySelector('[data-product-price]').dataset.productPrice),
              priceCalculate: function() {
                  let tempPrice = parseFloat(this.price) * this.quantity;
                  return tempPrice;
              },

          };
          item.addEventListener('click', () => {
              try {
                  let quantityElement = item.closest('.package-view').querySelector('[data-product-qty]');
                  let currentQuantity = parseInt(quantityElement.value);

                  if (item.dataset.foodIncrement === "1") {
                      quantityElement.value = currentQuantity + 1;
                  } else {
                      if (currentQuantity <= 0) {
                          throw new Error("Product quantity should not be zero");
                      } else {
                          quantityElement.value = currentQuantity - 1;
                      }
                  }
                  currentPrice.quantity = parseInt(quantityElement.value);
                  addToCart(quantityElement.dataset.foodId, currentPrice.quantity, currentPrice.priceCalculate(), {})

              } catch (error) {
                  console.error('Error fetching data:', error);
                  toastr.error(error.message);
              }
          });
      });
  }
/*============//view single food//=================*/
var customizeSingelFood = new bootstrap.Offcanvas(document.getElementById('custom_item'));

function viewSingleFood() {
    document.querySelectorAll('[data-customize]').forEach(element => {
        element.addEventListener('click', async () => {
            const foodId = element.dataset.foodId;
            let url = `{{ route('vendor.pos.get-food-item-details') }}?food_id=${foodId}`;

            try {
                const resp = await fetch(url);
                if (!resp.ok) {
                    const error = await resp.json();
                    toastr.error(error.message);
                    return;
                }

                const result = await resp.json();
                document.querySelector('[data-products=single]').innerHTML = result.view;
                // $('#custom_item').modal('show');
                customizeSingelFood.show()

                const productAddon = [];
                const options = [];
                const currentPrice = {
                    quantity: 0,
                    priceElement: document.querySelector('[data-current-price]'),
                    price: parseFloat(document.querySelector('[data-current-price]').dataset.currentPrice),
                    priceCalculate: function() {
                        let tempPrice = 0;
                        for (let addon of productAddon) {
                            tempPrice += (parseFloat(addon.price) * addon.qty);
                        }
                        for (let option of options) {
                            for (let value of option.values) {
                                tempPrice += parseFloat(value.price) * value.qty;
                            }
                        }
                        return tempPrice;
                    },
                    priceChanger: function() {
                        const price = this.priceCalculate();
                        this.priceElement.textContent = currencySymbolsuffix(price);
                        this.priceElement.dataset.currentPrice = price;
                    }
                };



                /*********** //addon quantity increment decrement //****************/
                document.querySelectorAll('[data-addon-increment]').forEach(item => {

                    const addonId = item.dataset.addonId;
                    let quantityElement = document.querySelector(`input[data-addon-id="${addonId}"]`);
                    const addonPrice = parseFloat(quantityElement.dataset.price);
                    item.addEventListener('click', () => {
                        try {
                            if (item.dataset.addonIncrement == "1") {
                                quantityElement.value = parseInt(quantityElement.value) + 1;
                            } else {
                                if (quantityElement.value == 0) {
                                    throw new Error("Addon quantity can'\t be less than zero");
                                } else {
                                    quantityElement.value = parseInt(quantityElement.value) - 1;
                                }
                            }

                            if (quantityElement.value == 0) {
                                const index = productAddon.findIndex(addon => addon.id === addonId);
                                if (index !== -1) {
                                    productAddon.splice(index, 1);
                                }
                            } else {
                                const index = productAddon.findIndex(addon => addon.id === addonId);
                                if (index == -1) {
                                    productAddon.push({
                                        id: addonId,
                                        price: addonPrice,
                                        qty: quantityElement.value,
                                    });
                                } else {
                                    productAddon[index].qty = quantityElement.value;
                                }
                            }
                            currentPrice.priceChanger();
                        } catch (error) {
                            console.error('Error fetching data:', error);
                            toastr.error(error.message);
                        }
                    })
                    if (quantityElement.value == 0) {
                        const index = productAddon.findIndex(addon => addon.id === addonId);
                        if (index !== -1) {
                            productAddon.splice(index, 1);
                        }
                    } else {
                        const index = productAddon.findIndex(addon => addon.id === addonId);
                        if (index == -1) {
                            productAddon.push({
                                id: addonId,
                                price: addonPrice,
                                qty: quantityElement.value,
                            });
                        } else {
                            productAddon[index].qty = quantityElement.value;
                        }
                    }
                    currentPrice.priceChanger();
                });

                /*********** //option quantity increment decrement //****************/
                document.querySelectorAll('[data-variation-increment]').forEach(item => {
                    const optionLabel = item.dataset.optionLabel;
                    let quantityElement = document.querySelector(`input[name="${optionLabel}"]`);
                    const variationName = quantityElement.dataset.variationName;
                    const optionPrice = parseFloat(quantityElement.dataset.price);

                    item.addEventListener('click', () => {
                        try {

                            if (item.dataset.variationIncrement == "1") {
                                quantityElement.value = parseInt(quantityElement.value) + 1;
                            } else {
                                if (quantityElement.value == 0) {
                                    throw new Error(optionLabel + " quantity can'\t be less than zero");
                                } else {
                                    quantityElement.value = parseInt(quantityElement.value) - 1;
                                }
                            }

                            const variationIndex = options.findIndex(opt => opt.option === variationName);
                            if (quantityElement.value > 0) {
                                if (variationIndex === -1) {
                                    options.push({
                                        option: variationName,
                                        values: [{
                                            label: optionLabel,
                                            price: optionPrice,
                                            qty: quantityElement.value
                                        }]
                                    });
                                } else {

                                    const optionIndex = options[variationIndex].values.findIndex(val => val.label === optionLabel);

                                    if (optionIndex !== -1) {
                                        options[variationIndex].values[optionIndex].qty = quantityElement.value;
                                    } else {
                                        options[variationIndex].values.push({
                                            label: optionLabel,
                                            price: optionPrice,
                                            qty: quantityElement.value
                                        });
                                    }
                                }
                            } else {
                                if (variationIndex !== -1) {
                                    const optionIndex = options[variationIndex].values.findIndex(val => val.label === optionLabel);
                                    if (optionIndex !== -1) {
                                        options[variationIndex].values.splice(optionIndex, 1);

                                        if (options[variationIndex].values.length === 0) {
                                            options.splice(variationIndex, 1);
                                        }
                                    }
                                }
                            }

                            currentPrice.priceChanger();


                        } catch (error) {
                            console.error('Error fetching data:', error);
                            toastr.error(error.message);
                        }
                    })
                    const variationIndex = options.findIndex(opt => opt.option === variationName);
                    if (quantityElement.value > 0) {
                        if (variationIndex === -1) {
                            options.push({
                                option: variationName,
                                values: [{
                                    label: optionLabel,
                                    price: optionPrice,
                                    qty: quantityElement.value
                                }]
                            });
                        } else {

                            const optionIndex = options[variationIndex].values.findIndex(val => val.label === optionLabel);

                            if (optionIndex !== -1) {
                                options[variationIndex].values[optionIndex].qty = quantityElement.value;
                            } else {
                                options[variationIndex].values.push({
                                    label: optionLabel,
                                    price: optionPrice,
                                    qty: quantityElement.value
                                });
                            }
                        }
                    } else {
                        if (variationIndex !== -1) {
                            const optionIndex = options[variationIndex].values.findIndex(val => val.label === optionLabel);
                            if (optionIndex !== -1) {
                                options[variationIndex].values.splice(optionIndex, 1);

                                if (options[variationIndex].values.length === 0) {
                                    options.splice(variationIndex, 1);
                                }
                            }
                        }
                    }

                    currentPrice.priceChanger();
                });

                /*********** //addining to cart //****************/

                document.querySelector('[data-add-to-cart]').addEventListener('click', function() {
                    addToCart(foodId, currentPrice.quantity, currentPrice.priceCalculate(), {
                        addons: productAddon,
                        variation: options
                    })
                    element.textContent = "Added";
                })


            } catch (error) {
                console.error('Error fetching data:', error);
                toastr.error(error.message);
            }
        });
    });
}

/*============//helping function end//=================*/

/*============//Set Customer //=================*/

document.querySelector('#cutomer').addEventListener('change',async (event) => {
  const element = event.target
  document.querySelector('[data-set-customer]').value = element.value;
  if (!isNaN(parseInt(element.value))) {
     try {
        const res = await fetch("{{route('vendor.pos.customer-delivery-info')}}?customer_id="+element.value);
        const data = await res.json();
        if (data.success) {
          toastr.success(data.success, "Success!", {
            closeButton: true,
            tapToDismiss: false,
            progressBar: true
          });
          document.querySelector('#del-add').innerHTML = data.view;

        }
      } catch (error) {
        console.error(error);
      }
    }else{
      document.querySelector('#del-add').innerHTML = "";
    }
    setDeliveryAddress(element);
})

function setDeliveryAddress(element) {
    let addressModal = document.querySelector('#delivery_address');
    if (!addressModal && element.value) {
        const chld = `<span class=" text-primary" id="delivery_address" data-bs-toggle="modal" data-bs-target="#paymentModal"><i class="fa fa-edit"></i> </span>`;
        document.querySelector('[data-title="delivery-info"]').insertAdjacentHTML('beforeend', chld);
        addressModal = document.querySelector('#delivery_address');
    } else if (!element.value && addressModal) {
        addressModal.remove();
    }

    if (addressModal) {
        addressModal.addEventListener('click', (event) => {
            navigator.geolocation.getCurrentPosition((position) => {
                const currentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                myMap.CreateMap(currentLocation, {
                    selector: "#map-canvas", // corrected syntax for object properties
                    marker: {
                        location: currentLocation,
                        img: "http://localhost:8080/foodyari_etsam/public/assets/user/img/icons/marker-icon.png",
                        draggable: true
                    }
                });
                // search location
                var input = document.getElementById('search-address-input');
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.addListener('place_changed', function() {
                    var place = autocomplete.getPlace();

                    if (!place.geometry) {
                        console.log("No details available for input: '" + place.name + "'");
                        return;
                    }
                    myMap.map.setCenter(place.geometry.location.toJSON());
                    myMap.marker.setPosition(place.geometry.location.toJSON());
                    myMap.setElementsPosition(place.geometry.location.toJSON());
                })

                //drag marker to get posion
                google.maps.event.addListener(myMap.marker, 'dragend', function() {
                    myMap.map.setCenter(myMap.marker.getPosition().toJSON());
                    myMap.marker.setPosition(myMap.marker.getPosition().toJSON());
                    myMap.setElementsPosition(myMap.marker.getPosition().toJSON());
                });

            });
        });
    }
}



/*============//Add to cart//=================*/
async function addToCart(product_id, qty = 1,price, options = Null) {
    const url = "{{route('vendor.pos.add-to-cart')}}"
    var PRODUCT_ID = product_id;
    var QTY = qty;
    var PRICE = price;
    var OPTIONS = options;
    try {
        const resp = await fetch(url, {
            method: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                id: PRODUCT_ID,
                qty: QTY,
                price: PRICE,
                options: OPTIONS
            })
        });
        if (!resp.ok) {
            throw new Error("Something Going Wrong");
        }
        const result = await resp.json();

        if (resp.ok && result !== null) {

            // toastr.success(result.message)
            await getCarts()
        }
    } catch (error) {
        console.error('Error:', error);
        toastr.error(error);
    }
}

/*============//Get Cart Items //=================*/
async function getCarts() {
    const url = "{{route('vendor.pos.get-cart-items')}}"
    try {
        const resp = await fetch(url);

        const result = await resp.json();

        if (resp.ok && result !== null) {
            let data = Object.values(result.items)
            let counter = 0;
            const cartItems = [...data].map(item => {
                counter ++;
                return `<tr>
                            <td >${counter}</td>
                            <td >${item.name}</td>
                            <td><span class="product-quantity" data-productQty="${item.quantity}"> ${item.quantity}</span>

                              </td>

                            <td>${currencySymbolsuffix(parseFloat(item.amount))}</td>

                        </tr>`;
            }).join('\n');
            document.querySelector('#cart-table tbody').innerHTML = cartItems;
            let billig = {
                addons: 0,
                discount: 0,
                custom_discount : 0,
                tax : 0,
                amount: 0,
                variationPrice: 0,
                deliveryfee: 0,
                subtotal: function() {
                    return parseFloat(this.addons) + parseFloat(this.amount) + parseFloat(this.variationPrice);
                },
                total: function() {
                    return this.subtotal() + this.deliveryfee +(-1*this.discount) + (-1*this.custom_discount);
                },
                net_total : function(){
                  return this.total() + this.tax ;
                }
            };

            [...data].forEach(item => {
                console.log(billig.amount);
                billig.amount += parseFloat(item.amount);
            });
            console.log(result)
            console.log(billig)
            billig.discount += parseFloat(result?.discount ?? 0);
            if(result.custom_discount){
              const custom_discount = result.custom_discount;
                if (custom_discount.discount_type == 'percent') {
                  billig.custom_discount  = (billig.subtotal() / 100) * parseFloat(custom_discount.discount);
              } else {
                billig.custom_discount =parseFloat(custom_discount.discount);
              }
            }

            if (result.update_tax || result.update_tax !=0) {
              billig.tax = billig.total()  * (parseFloat(result.update_tax) / 100)
            }else if (result.tax){
              billig.tax = billig.total()  * (parseFloat(result.tax) / 100)
            }

            if(result.deliveryCharge && result.deliveryCharge !=0){
                billig.deliveryfee = parseFloat(result.deliveryCharge);
            }


            document.querySelector('#bill-box .pricing').innerHTML = `
                <dt class="col-8">Subtotal:</dt>
                <dd class="col-4 text-right">${currencySymbolsuffix(billig.subtotal().toFixed(2))} </dd>

                <dt class="col-8">Discount :</dt>
                <dd class="col-4 text-right">${currencySymbolsuffix(-1 *billig.discount)} </dd>

                <dt class="col-8">Delivery Fee :</dt>
                <dd class="col-4 text-right" id="delivery_price">${currencySymbolsuffix(billig.deliveryfee)}</dd>
                <dt class="col-8">Extra Discount :</dt>
                <dd class="col-4 text-right">-${currencySymbolsuffix(billig.custom_discount)}
                  <button class="btn btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#add-discount"><i class="fa fa-edit"></i></button>
                </dd>
                <dt class="col-8">Tax (GST) :</dt>
                <dd class="col-4 text-right">+${currencySymbolsuffix(billig.tax.toFixed(2))}
                  <button class="btn btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#add-tax"><i class="fa fa-edit"></i></button>
                </dd>
                <dt class="col-8 pr-0"><hr class="mt-0" /></dt>
                <dt class="col-4 pl-0"><hr class="mt-0" /></dt>
                <dt class="col-8">Total:</dt>
                <dd class="col-4 text-right"> ${currencySymbolsuffix(billig.total().toFixed(2))} </dd>
                <dt class="col-8">Net Price:</dt>
                <dd class="col-4 text-right h6"> ${currencySymbolsuffix(billig.net_total().toFixed(2))} </dd>
              `;
            // console.log(billig)

        }
    } catch (error) {
        console.error('Error :', error);
        toastr.error(error);
    }
}


/*============//Remove cart Product//=================*/

async function deleteProduct(cart_id) {
    const url = "{{route('vendor.pos.delete-cart-item')}}?cart_id="+cart_id;
    try {
        const resp = await fetch(url);
        if (!resp.ok) {
            throw new Error("Something Going Wrong");
        }
        const result = await resp.json();

        if (resp.ok && result !== null) {
            if (result.status == 'success') {
                toastr.success(result.message)
            }
            await getCarts()
        }
    } catch (error) {
        console.error('Error:', error);
        toastr.error(error);
    }
}

/*================// Clear Cart //================*/
async function emptyCart() {
    const url = "{{route('vendor.pos.clear-cart')}}";
    try {
        const resp = await fetch(url);
        if (!resp.ok) {
          const error = await resp.json();
          throw new Error(error.message);
        }
        const result = await resp.json();
        toastr.success(result.message)
        getCarts();
        getFoods();
    } catch (error) {
        console.error('Error:', error);
        toastr.error(error);
    }
}
/*============//custom discount//=================*/
document.querySelector('#custom-discount').addEventListener('submit', async e => {
  e.preventDefault();
  const element = e.target;
  try {
    const resp = await fetch(element.action, {
      method: 'POST',
      body: new FormData(element),
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
    });

    if (!resp.ok) {
      const error = await resp.json();
      throw new Error(error.message);
    }
    $('#add-discount').modal('hide');
    const result = await resp.json();
    await getCarts(); // Ensure getCarts() is defined elsewhere

  } catch (error) {
    console.error('Error:', error);
    toastr.error(error.message);
  }
});

/*============//custom tax//=================*/
document.querySelector('#custom-tax').addEventListener('submit', async e => {
  e.preventDefault();
  const element = e.target;
  try {
    const resp = await fetch(element.action, {
      method: 'POST',
      body: new FormData(element),
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
    });

    if (!resp.ok) {
      const error = await resp.json();
      throw new Error(error.message);
    }

    const result = await resp.json();
    $('#add-tax').modal('hide');

    await getCarts();

  } catch (error) {
    console.error('Error:', error);
    toastr.error(error.message);
  }
});

getCarts();
</script>
<script type="module">
  import { validateForm, escapeLiterals } from "{{ asset('assets/js/Helpers/helper.js') }}";
/*============//Address Store//=================*/

document.querySelector('#delivery_address_store').addEventListener('submit', async (e) => {
    e.preventDefault();
    const validator = validateForm('#delivery_address_store');
    if (validator) {
        const formData = new FormData(e.target);
        try {
            const res = await fetch("{{route('vendor.pos.add-delivery-info')}}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await res.json();
            if (data.success) {
                toastr.success(data.success, "Success!", {
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true
                });
                e.target.reset();
                $('#paymentModal').modal('hide');
                document.querySelector('#del-add').innerHTML = data.view;
            } else {
                toastr.error(`<ul>${data.errors.map(message => `<li>${message[0]}</li>`).join('')}</ul>`, "Error!", {
                    closeButton: true,
                    tapToDismiss: false,
                    progressBar: true
                });
            }
        } catch (error) {
            console.error(error);
            toastr.error('An error occurred while submitting the form.', "Error!", {
                closeButton: true,
                tapToDismiss: false,
                progressBar: true
            });
        }
    } else {
        console.error('Validation failed');
    }
});

/*============//Place Order //==================*/
  document.getElementById('place-order-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    try {
        const orderData = new FormData(e.target);
        const res = await fetch("{{route('vendor.pos.order')}}", {
          method: "POST",
          body: orderData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        const data = await res.json();
        if(data.error){
          throw new Error(data.error);
        }else if(data.success){
          toastr.success(data.success);
          e.target.reset();
          getCarts();
          getFoods();
          if(data?.last_order){
            document.getElementById('showLastOrder').innerHTML = data?.last_order;
          }
          if (data?.print_kot) {

            // Initialize Offcanvas
            const KOT_OFFCANVAS = new bootstrap.Offcanvas(document.getElementById('KOT_OFFCANVAS'));

            // Set the content inside the fragment
            document.getElementById('kot_fragment').innerHTML = data?.print_kot_view;

            // Show the Offcanvas
            KOT_OFFCANVAS.show();

            // Listen for when the offcanvas is hiddenc
            // KOT_OFFCANVAS.addEventListener('hidden.bs.offcanvas', function () {
            //     // Clear the content when the offcanvas is closed
            //     document.getElementById('kot_fragment').innerHTML = '';
            // });
          }
          console.log(data);
        }
    } catch (error) {
       toastr.error(error.message);
       console.error(error);
    }
});
</script>

@endpush
