@extends('layouts.dashboard-main')
@push('css')

<style>
    dt {
    font-weight: 600;
}
</style>
@endpush
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
  <div class="row">
    <div class="col-sm-12 col-lg-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <div class="header-title">
            {{-- <h4 class="card-title">{{ __('messages.add-category') }}</h4> --}}
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <div class="header-title">
                    <h4 class="card-title">Food Section</h4>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    {{-- <div class="col-md-6">
                      <div class="form-group">
                        <select name="zone_id" id="zone_id" class="form-control" data-placeholder="Select Zone"
                          tabindex="-1" aria-hidden="true">
                          <option value="">Select Zone</option>
                          <option value="1">patna</option>
                        </select>
                      </div>
                    </div> --}}
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-select" name="restaurant_id"
                          onchange="get_options('{{ url('admin/category/get_categories')}}/'+this.value,'#category_id','Category')"
                          id="resturant-name" required="">
                          <option selected="" disabled="" value="">Choose Restaurant</option>
                          @foreach ($restaurants as $restaurant )
                          <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <select name="category_id" id="category_id" class="form-control" onchange="getFoods()"
                        title="Select Category" <option value="">Select Categories</option>
                        <option value="">All Categories</option>
                      </select>
                    </div>
                    <div class="col-sm-6">
                      <form id="search-form" class="mw-100">
                        <!-- Search -->
                        <div class="input-group input-group-merge input-group-flush w-100">

                          <input id="datatableSearch" type="search" value="" name="search"
                            class="form-control flex-grow-1 pl-5 border rounded h--45x"
                            placeholder="Ex : Search Food Name" aria-label="Search here">
                        </div>
                        <!-- End Search -->
                      </form>
                    </div>
                  </div>
                </div>

                <div class="card-body d-flex flex-column justify-content-center" id="items">
                  <div class="product-grid grid-1" data-products="all">

                  </div>
                </div>

              </div>

            </div>
            <div class="col-md-5">
              <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <div class="header-title">
                    <h4 class="card-title">Billing Section</h4>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col md-12">
                      <div class="d-flex flex-wrap  p-2 add-customer-btn">
                        <div class="form-group flex-grow-1">
                          <select id="customer" name="customer_id" data-placeholder="Select customer"
                            class="form-control" onchange="setCustomer(this.value)" tabindex="-1" aria-hidden="true">
                            <option value="">choose customer</option>
                            @foreach ($customers as $customer)
                            <option value="{{$customer->id}}">{{$customer->f_name}} {{$customer->l_name}}</option>
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
                        <h4 class="card-title">Delivery Information</h4>
                      </div>
                      <div class="card-body">
                        <div class="w-100" id="cart">
                          <div class="d-flex flex-row initial-47">
                            <table class="table table--vertical-middle" id="cart-table">
                              <thead class="thead-light border-0">
                                <tr>
                                  <th class="py-2" scope="col">Item</th>
                                  <th class="py-2" scope="col">Qty</th>
                                  <th class="py-2 text-center" scope="col">Price</th>
                                  <th class="py-2 text-center" scope="col">Delete</th>
                                </tr>
                              </thead>
                              <tbody>

                              </tbody>
                            </table>
                          </div>

                          <div class="box p-3" id="bill-box">
                            <dl class="row pricing">
                              {{-- <dt class="col-8">Addon:</dt>
                              <dd class="col-4 text-right">0 ₹</dd>

                              <dt class="col-8">Subtotal (TAX Included):</dt>
                              <dd class="col-4 text-right">0 ₹</dd>

                              <dt class="col-8">Discount :</dt>
                              <dd class="col-4 text-right">-0 ₹</dd>

                              <dt class="col-8">Delivery Fee :</dt>
                              <dd class="col-4 text-right" id="delivery_price">0 ₹</dd>
                              <dt class="col-8 pr-0">
                                <hr class="mt-0" />
                              </dt>
                              <dt class="col-4 pl-0">
                                <hr class="mt-0" />
                              </dt>
                              <dt class="col-8">Total:</dt>
                              <dd class="col-4 text-right h4 b">0 ₹</dd> --}}
                            </dl>
                            <!-- Static Data -->
                            <form action="javascript:void(0)" method='post'>
                              @csrf
                              <div class="pos--payment-options mt-3 mb-3">
                                <h5 class="mb-3">Payment Method</h5>
                                <ul class="d-flex ps-0">
                                  <li>
                                    <label>
                                      <input type="radio" name="type" value="cash" hidden="" checked="">
                                      <span>Cash On Delivery</span>
                                    </label>
                                  </li>
                                  <li>
                                    <label>
                                      <input type="radio" name="type" value="wallet" hidden="">
                                      <span>Wallet</span>
                                    </label>
                                  </li>
                                </ul>
                              </div>
                              <!-- Static Data -->
                              <div class="row  mt-3 g-1 bg-white">
                                <div class="col-sm-12">
                                  <button type="submit" class="btn btn-outline-primary btn-block">
                                    Place order
                                  </button>

                                  <a href="javascript:void(0)" class="btn btn-outline-gray btn-block" onclick="emptyCart()">Clear
                                    Cart</a>
                                </div>
                              </div>
                            </form>
                          </div>

                          <div class="modal fade" id="add-discount" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header border-bottom py-3 bg-light">
                                  <h5 class="modal-title">Update discount</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <form>

                                    <div class="form-group col-sm-6">
                                      <label for="">Type</label>
                                      <select name="type" class="form-control" id="discount_input_type"
                                        onchange="document.getElementById('discount_input').max=(this.value=='percent'?100:1000000000);">
                                        <option value="amount" selected="">Amount(₹)</option>
                                        <option value="percent">Percent(%)</option>
                                      </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                      <label for="">Discount</label>
                                      <input type="number" class="form-control" name="discount" min="0"
                                        id="discount_input" value="0" max="1000000000" />
                                    </div>
                                    <div class="form-group col-sm-12 text-right mb-0">
                                      <button class="btn btn-sm btn--primary" type="submit">
                                        Submit
                                      </button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="modal fade" id="add-tax" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header border-bottom py-3 bg-light">
                                  <h5 class="modal-title">Update tax</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <form action="http://localhost/foodyari/admin/pos/tax" method="POST" class="row"
                                    id="order_submit_form">
                                    <input type="hidden" name="_token"
                                      value="J9tF0zm9vbV1AmHGdgvl1Smpu3oSzgdsT7WfhghE" />
                                    <div class="form-group col-12">
                                      <label for="">TAX(%)</label>
                                      <input type="number" class="form-control" name="tax" min="0" />
                                    </div>

                                    <div class="form-group col-sm-12 text-right mb-0">
                                      <button class="btn btn-sm btn--primary" type="submit">
                                        Submit
                                      </button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="modal fade" id="paymentModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-header bg-light border-bottom py-3">
                                  <h5 class="modal-title flex-grow-1 text-center">
                                    Delivery Information
                                  </h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <form id="delivery_address_store">
                                    <input type="hidden" name="_token"
                                      value="J9tF0zm9vbV1AmHGdgvl1Smpu3oSzgdsT7WfhghE" />
                                    <div class="row g-2" id="delivery_address">
                                      {{-- <div class="col-md-6">
                                        <label class="input-label" fo>Contact person name class="input-label-secondary
                                          text-dange >*<>
                                            <>
                                              type="tex class="form-contro name="contact_person_nam valu placeholder="Ex
                                              : Jhon />
                                      </div> --}}
                                      <div class="col-md-6">
                                        <label class="input-label" for="">Contact Number<span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="contact_person_number" value=""
                                          placeholder="Ex : +3264124565" />
                                      </div>
                                      <div class="col-md-4">
                                        <label class="input-label" for="">Road<span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" class="form-control" name="road" value=""
                                          placeholder="Ex : 4th" />
                                      </div>
                                      <div class="col-md-4">
                                        <label class="input-label" for="">House<span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" class="form-control" name="house" value=""
                                          placeholder="Ex : 45/C" />
                                      </div>
                                      <div class="col-md-4">
                                        <label class="input-label" for="">Floor<span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" class="form-control" name="floor" value=""
                                          placeholder="Ex : 1A" />
                                      </div>
                                      <div class="col-md-6">
                                        <label class="input-label" for="">Longitude<span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" class="form-control" id="longitude" name="longitude" value=""
                                          readonly="" />
                                      </div>
                                      <div class="col-md-6">
                                        <label class="input-label" for="">Latitude<span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" class="form-control" id="latitude" name="latitude" value=""
                                          readonly="" />
                                      </div>
                                      <div class="col-md-12">
                                        <label class="input-label" for="">Address</label>
                                        <textarea name="address" class="form-control" cols="30" rows="3"
                                          placeholder="Ex : address"></textarea>
                                      </div>
                                      <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                          <span class="text-primary">
                                            * pin the address in the map to calculate delivery fee
                                          </span>
                                          <div>
                                            <input type="hidden" name="distance" id="distance" />
                                            <span>Delivery fee :</span>
                                            <input type="hidden" name="delivery_fee" id="delivery_fee" value="" />
                                            <strong>0 ₹</strong>
                                          </div>
                                        </div>
                                        <input id="pac-input" class="controls rounded initial-8 pac-target-input"
                                          title="Search your location here" type="text" placeholder="Search here"
                                          autocomplete="off" />
                                        <div class="mb-2 h-200px" id="map">
                                          <div style="height: 100%; width: 100%">
                                            <div style="overflow: hidden"></div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="btn--container justify-content-end">
                                        <button class="btn btn-sm btn--primary w-100" type="button"
                                          onclick="deliveryAdressStore()">
                                          Update Delivery address
                                        </button>
                                      </div>
                                    </div>
                                  </form>
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


    {{-- quick view modal --}}
    <div class="modal modal-lg fade " id="product-view" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content" id="quick-view-modal">
          <div class="modal-header py-3">
            <h4 class="modal-title">Product View</h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" data-food-item-details="0">
                
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
</script>  
<script>
const ASSET_URL = uri => "{{url('/public')}}/"+uri+"/";
// const BASE_URL = uri => "{{url('/')}}/"+uri;
    
async function getFoods(){
    let url = "{{url('admin/pos/foods')}}"; //
    let restaurant = document.querySelector('#resturant-name');
    let category = document.querySelector('#category_id');

    url += restaurant.value? "?restaurant_id="+restaurant.value : '';
    url += category.value? "&category_id="+category.value : '';
        try {
            const resp = await fetch(url);
            const result = await resp.json();
            if (resp.ok && result !== null) {
                console.log(result);
                let items = '<div class="row g-3 mb-auto">';
                items += result.map(item => {
                  console.log(item)
                    return `<div class="product-item">
                              <div class="product-single">
                                  <div class="product-img">
                                      <img src="${ASSET_URL('product')+item.image}" alt="Product Image"/>
                                      <div class="product-status">
                                          <span>New</span>
                                          <span>In Stock</span>
                                      </div>
                                      <div class="product-action">
                                          <a href=""><i class="fa fa-eye"></i></a>
                                          <a href=""><i class="fa fa-shopping-bag"></i></a>
                                          <a href="javascript:void(0)" data-selected-item="${item.id}"><i class="fa fa-shopping-cart"></i></a>
                                      </div>
                                  </div>
                                  <div class="product-content">
                                      <div class="product-title">
                                          <h2><a href="javascript:void(0)">${item.name}</a></h2>
                                      </div>
                                      {{-- <div class="product-ratting">
                                      </div> --}}
                                      <div class="product-price">
                                          <h5>${currencySymbolsuffix(product_discount(item.price, item.discount, item.discount_type))}</h5>
                                          <h5>${currencySymbolsuffix(item.price)}</h5>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        `;
                }).join('\n')
                items += '</div>';
                document.querySelector('[data-products=all]').innerHTML = items;
                document.querySelectorAll('[data-selected-item]').forEach(item => {
                    item.addEventListener('click', () => {
                        viewSigleFoodDetails(item.dataset.selectedItem);
                    });
                });
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
} 

async function viewSigleFoodDetails(foodId) {
    const resp = await fetch("{{route('admin.pos.get-food-item-details')}}?food_id=" + foodId);
    const result = await resp.text();
    document.querySelector('[data-food-item-details]').innerHTML = result;
    $('#product-view').modal('show');

    const productAddon = [];
    const options = [];
    const currentPrice = {
        quantity : 1,
        priceElement: document.querySelector('[data-current-price]'),
        price: parseFloat(document.querySelector('[data-current-price]').dataset.currentPrice),
        priceCalculate: function() {
            let tempPrice = this.price;
            for (let addon of productAddon) {
                tempPrice += parseFloat(addon.price);
            }
            for (let option of options) {
                for (let value of option.values) {
                    tempPrice += parseFloat(value.price);
                }
            }
            return tempPrice * parseFloat(this.quantity) ;
        },
        priceChanger: function() {
            const price = this.priceCalculate();
            this.priceElement.textContent = currencySymbolsuffix(price);
            this.priceElement.dataset.currentPrice = price;
        }
    };

    document.querySelectorAll('[data-addon-checkbox]').forEach(item => {
        item.addEventListener('change', (event) => {
            const addonId = item.dataset.addonCheckbox;
            const addonPrice = parseFloat(item.dataset.price);

            if (item.checked) {
                productAddon.push({
                    id: addonId,
                    price: addonPrice
                });
            } else {
                const index = productAddon.findIndex(addon => addon.id === addonId);
                if (index !== -1) {
                    productAddon.splice(index, 1);
                }
            }
            currentPrice.priceChanger();
        });
    });

    document.querySelectorAll('[data-option-label]').forEach(item => {

        item.addEventListener('change', () => {
            const variationName = item.dataset.variationName;
            const optionLabel = item.dataset.optionLabel;
            const optionPrice = item.dataset.optionPrice;
            const variationIndex = options.findIndex(opt => opt.option === variationName);

            if (item.checked) {
                if (variationIndex === -1) {
                    options.push({
                        option: variationName,
                        values: [{
                            label: optionLabel,
                            price: optionPrice
                        }]
                    });
                } else {
                    options[variationIndex].values.push({
                        label: optionLabel,
                        price: optionPrice
                    });
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
    });

    document.querySelectorAll('[ data-product-increment]').forEach(item => {
      item.addEventListener('click',()=> {
        try {
            let quantityElement = document.querySelector('[data-product-qty]');
            if (item.dataset.productIncrement == "1") {
                quantityElement.value = parseInt(quantityElement.value)+ 1 ;
            } else {
                if (quantityElement.value <= 0) {
                    throw new Error("Product quantity should not be zero");
                } else {
                    quantityElement.value = parseInt(quantityElement.value) -1;
                }
            }
            currentPrice.quantity = parseInt(quantityElement.value);
            currentPrice.priceChanger();

        } catch (error) {
            console.error('Error fetching data:', error);
            toastr.error(error.message);
        }  
      })
    })

    quantityElement = document.querySelector('[data-product-qty]');


    document.querySelector('[data-add-to-cart]').addEventListener('click', function(){
      addToCart(foodId,quantityElement.value,  currentPrice.priceCalculate(),{addons : productAddon, variation : options})
    })

}
// /helping function
function product_discount(price, discount, d_type = 'amount') {
    if (d_type === 'percent') {
        return parseInt(price) - (parseInt(price) * parseInt(discount) / 100);
    } else {
        return parseInt(price) - parseInt(discount);
    }
}

function currencySymbolsuffix(amount, symbol = 'INR') {
    let icon = { 'USD': '$', 'INR': '₹' };
    return icon[symbol] + ' ' + amount;
}
// /helping function end

let setCustomer = (id) => {
    document.querySelector('#customer_id').value = id;
    document.querySelector('#restaurant-id').value = document.querySelector('#resturant-name').value;
    return null;
}


function addNewCustomer() {
    $('#add-customer').modal('show');
}


async function addToCart(product_id, qty = 1,price, options = Null) {
    const url = "{{route('admin.pos.add-to-cart')}}"
    try {
        const resp = await fetch(url, {
            method: "post",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                id: product_id,
                qty: qty,
                price : price,
                options: options
            })
        });
        if (!resp.ok) {
            throw new Error("Something Going Wrong");
        }
        const result = await resp.json();

        if (resp.ok && result !== null) {
            // console.log(result);
            toastr.success(result.message)
            await getCarts()
        }
    } catch (error) {
        console.error('Error:', error);
        toastr.error(error);
    }
}

async function getCarts() {
    const url = "{{route('admin.pos.get-cart-items')}}"
    try {
        const resp = await fetch(url);

        const result = await resp.json();

        if (resp.ok && result !== null) {
            console.log(result.items);
            let data = Object.values(result.items)

            const cartItems = [...data].map(item => {
                // console.log(item);
                return `<tr>
                            <td ><img class="" src="${ASSET_URL('product/'+item.image)}" style="width:50px;height:50px;border-radius:50%;"><br>${item.name}</td>
                            <td><div class="product-item"><span class="plus" ></span>

                              <span class="product-quantity" data-productQty="${item.quantity}"> ${item.quantity}</span>
                              <span class="product-minus" onclick="decreaseItem(${item.id})"></span>
                              </div> 
                              </td>
                            <td>${parseFloat(item.price)* item.quantity}</td>
                            <td><span class="btn fa fa-trash text-danger" onclick="deleteProduct('${item.cart_id}')"></span></td>
                        </tr>`;
            }).join('\n');
            document.querySelector('#cart-table tbody').innerHTML = cartItems;
            let billig = {
                addons: 0,
                discount: 0,
                price: 0,
                variationPrice: 0,
                deliveryfee: 0,
                subtotal: function() {
                    return parseInt(this.addons) + parseInt(this.price) + parseInt(this.variationPrice);
                },
                total: function() {
                    return this.subtotal() + this.deliveryfee - this.discount;
                }
            };

            [...data].forEach(item => {
              
                billig.addons += (parseInt(item.addon_price) * parseInt(item.quantity));
                billig.discount += (parseInt(item.discount)  * parseInt(item.quantity));
                billig.price += (parseInt(item.price)  * parseInt(item.quantity));
                billig.variationPrice += (parseInt(item.variation_price)  * parseInt(item.quantity));
                billig.discount +=  (parseInt(item.discount) * item.quantity ) ;
            });
          // console.log(billig.discount)
            document.querySelector('#bill-box .pricing').innerHTML = `
                <dt class="col-8">Addon:</dt>
                <dd class="col-4 text-right">${billig.addons} ₹</dd>
                <dt class="col-8">Variation:</dt>
                <dd class="col-4 text-right">${billig.variationPrice} ₹</dd>
          
                <dt class="col-8">Subtotal (TAX Included):</dt>
                <dd class="col-4 text-right">${billig.subtotal()} ₹</dd>
          
                <dt class="col-8">Discount :</dt>
                <dd class="col-4 text-right">${billig.discount} ₹</dd>
          
                <dt class="col-8">Delivery Fee :</dt>
                <dd class="col-4 text-right" id="delivery_price">${billig.deliveryfee} ₹</dd>
                <dt class="col-8 pr-0"><hr class="mt-0" /></dt>
                <dt class="col-4 pl-0"><hr class="mt-0" /></dt>
                <dt class="col-8">Total:</dt>
                <dd class="col-4 text-right h4 b"> ${billig.total()} ₹</dd>
              `;
            // console.log(billig)

        }
    } catch (error) {
        console.error('Error :', error);
        toastr.error(error);
    }
}



async function deleteProduct(cart_id) {
    const url = "{{route('admin.pos.delete-cart-item')}}?cart_id="+cart_id;
    try {
        const resp = await fetch(url);
        if (!resp.ok) {
            throw new Error("Something Going Wrong");
        }
        const result = await resp.json();

        if (resp.ok && result !== null) {
            // console.log(result);
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

getCarts();
</script>

@endpush