@extends('mess-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Order Allotment </h4>
                    </div>
                </div>
                <div class="card-body px-0">
                <form action="javascript:void(0)" method="POST" id="order-allotment-form">
                    <div class="row mx-3" >
                        <div class="col-md-4">
                            <div class="form-group">
                                {{-- @dd($messdeliverman) --}}
                                <label class="form-label">Select Delivery Man:</label>
                                <select name="delivery_man"  class="selectpicker select-2  form-control" >
                                    <option value="">Choose One</option>
                                    {{-- '','services' --}}
                                    @foreach ($messdeliverman as $dman)
                                        <option value="{{$dman->id}}">{{$dman->f_name}} {{$dman->l_name}} ({{$dman->phone}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">  
                            @php($services = App\CentralLogics\Helpers::getService())      
                            <div class="form-group">
                                <label class="form-label">Set For:</label>
                                <select name="setFor"  class="selectpicker form-control" data-options="service">
                                   <option value="">Choose One</option>
                                    @foreach ($services as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Customers</label>
                                <select name="coupon_id"  class="selectpicker form-control select-2" data-options="customer">
                                    <option value="">Choose Set For First</option>
                                </select>
                            </div>  
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Tiffin No</label>
                                <select name="tiffin"  class="selectpicker form-control select-2" data-options="tiffin">
                                    <option value="">Choose Customer First</option>
                                </select>
                            </div>   
                        </div>
                        <div class="col-md-4 ">
                                <button type="submit" class="btn btn-outline-primary my-3 ">Allot</button>
                        </div>
                        
                        
                    
                    </div>
                </form>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Order Alloted </h4>
                    </div>
                </div>
                <div class="card-body px-0">
                    <nav>
                        <div class="nav nav-pills mb-3" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-breakfast-tab" data-bs-toggle="tab" data-bs-target="#nav-breakfast" type="button" role="tab" aria-controls="nav-breakfast" aria-selected="true">Breakfast</button>
                            <button class="nav-link" id="nav-lunch-tab" data-bs-toggle="tab" data-bs-target="#nav-lunch" type="button" role="tab" aria-controls="nav-lunch" aria-selected="false">Lunch</button>
                            <button class="nav-link" id="nav-dinner-tab" data-bs-toggle="tab" data-bs-target="#nav-dinner" type="button" role="tab" aria-controls="nav-dinner" aria-selected="false">Dinner</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-breakfast" role="tabpanel" aria-labelledby="nav-breakfast-tab">
                            <div class="table-responsive">
                                <table id="user-list-table" class="table" role="grid" data-table-for="breakfast" data-bs-toggle="data-table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>S.I.</th>
                                            <th>Customer</th>
                                            <th>Delivery Man</th>
                                            <th>Quantity</th>
                                            <th>Cash On Delivery</th>
                                            <th>Status</th>
                                            <th>Tiffin NO</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-lunch" role="tabpanel" aria-labelledby="nav-lunch-tab">
                            <div class="table-responsive">
                                <table id="user-list-table" class="table" role="grid" data-table-for="lunch" data-bs-toggle="data-table"  style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>S.I.</th>
                                            <th>Customer</th>
                                            <th>Delivery Man</th>
                                            <th>Quantity</th>
                                            <th>Cash On Delivery</th>
                                            <th>Status</th>
                                            <th>Tiffin NO</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-dinner" role="tabpanel" aria-labelledby="nav-dinner-tab">
                            <div class="table-responsive">
                                <table id="user-list-table" class="table" role="grid" data-table-for="dinner" data-bs-toggle="data-table"  style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>S.I.</th>
                                            <th>Customer</th>
                                            <th>Delivery Man</th>
                                            <th>Quantity</th>
                                            <th>Cash On Delivery</th>
                                            <th>Status</th>
                                            <th>Tiffin NO</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
    const table_load = {
    url: "{{ route('mess.diet-order.listOfOrderToDeliveryMany') }}",
    getCustomerByIdURL: "{{ route('mess.diet-order.getCustomerById') }}", 
    getCustomerTiffinNoURL: "{{ route('mess.diet-order.getTiffinById') }}", 
    loadData: async function() {
        try {
            const resp = await fetch(this.url);
            const result = await resp.json();
            if (result.error) {
                throw new Error(handleError(result.error));
            }
            console.log(result);
            return result;
        } catch (error) {
            toastr.error(error.message);
            console.error(error);
        }
    },
    getCustomerById: async function(id) {
        try {
            const resp = await fetch(`${this.getCustomerByIdURL}?customer_id=${id}`); // Corrected URL
            const result = await resp.json();
            if (result.error) {
                throw new Error(handleError(result.error));
            }
            console.log(result);
            return result;
        } catch (error) {
            toastr.error(error.message);
            console.error(error);
        }
    },
    getCustomerTiffinNo: async function(id) {
        try {
            const resp = await fetch(`${this.getCustomerTiffinNoURL}?tiffin_id=${id}`); // Corrected URL
            const result = await resp.json();
            return result;
        } catch (error) {
            toastr.error(error.message);
            console.error(error);
        }
    },
    showTable: async function(selector, service_id) {
    const table = document.querySelector(selector);
    const desiredObject = [];
    let result = await this.loadData();

    for (let x of result) {
        if (parseInt(x.service_id) === parseInt(service_id)) {
            desiredObject.push(x);
            break;
        }
    }
    let sr = 0;
    const contentsToAppend = await Promise.all(desiredObject.map(async item => {
        sr++;
        const customerId = item.allot_todelivery_men[0].pivot.customer_id;
        const customer = await this.getCustomerById(customerId);
        const customerAdderess = JSON.parse(customer.address);
        const deliveryMan = item.allot_todelivery_men[0];
        const deliveryManaddress = JSON.parse(deliveryMan.address);
        let tiffin = "NA"
        if(deliveryMan.pivot.tiffin_id){
             tiffin = await this.getCustomerTiffinNo(deliveryMan.pivot.tiffin_id);
        }
        
        return {
            "": sr,
            "Customer": `${customer.user.f_name} ${customer.user.l_name} <br>${customer.user.phone} <br>${customerAdderess.street} <br>${customerAdderess.city}-${customerAdderess.pincode}`,
            "Delivery Man": `${deliveryMan.f_name} ${deliveryMan.l_name} <br>${deliveryMan.phone} <br>${deliveryManaddress.street} <br>${deliveryManaddress.city}-${deliveryManaddress.pincode}`,
            "Quantity": item.quantity,
            "Cash On Delivery": deliveryMan.pivot.cash_to_collect,
            "Status": deliveryMan.pivot.status,
            "Tiffin NO": tiffin ? tiffin : "NA"
        };
    }));

    $(selector).DataTable({
        data: contentsToAppend,
        columns: [

          
            { data: '' },
            { data: 'Customer' },
            { data: 'Delivery Man' },
            { data: 'Quantity' },
            { data: 'Cash On Delivery' },
            { data: 'Status' },
            { data: 'Tiffin NO' }
        ]
    });
},
   
};

// return `<tr>
//             <td>${sr}</td>
//             <td>${customer.user.f_name} ${customer.user.l_name} <br>${customer.user.phone} <br>${customerAdderess['street']} ${customerAdderess['city']}- ${customerAdderess['pincode']}</td>
//             <td>${deliveryMan.f_name} ${deliveryMan.l_name} <br>${deliveryMan.phone} <br>${deliveryManaddress['street']} ${deliveryManaddress['city']}- ${deliveryManaddress['pincode']}</td>
//             <td>${item.quantity}</td>
//             <td>${deliveryMan.pivot.cash_to_collect}</td>
//             <td>${deliveryMan.pivot.status}</td>
//             <td>${tiffin}</td>

//         </tr>`;
</script>

<script>
   $('[data-options=service]').select2().on('change',async function (e) {
    try{
        const arr = Array({id :"all", text: "Select All"});

        const reps = await fetch("{{ route('mess.diet-order.getOrderedCustomers') }}?service_name="+ $(this).val())
        if(!reps.ok){
            const error = await reps.json();
            toastr.error(error.message)
        }else{
            const result = await reps.json();

            result.coupons.forEach(coupon => {
           
                const customer = coupon.customer_subscription_txns.customer
                const arrayToappned = {id : coupon.id , text :` ${customer.f_name} ${customer.l_name} (${customer.phone}) ` };
                arr.push(arrayToappned);
            })
        }
        
        $("[data-options='customer']").empty().select2({
            data: arr
        }).on('change', async function() {
            const reps2 = await fetch("{{ route('mess.diet-order.getTiffinNo') }}")
            const result2 = await reps2.json();
            const arr2 = Array({id :"", text: "Select One"});
            result2.forEach(item => {
                const arrayToappned = {id : item.id , text : item.no };
                arr2.push(arrayToappned);
            })
            $("[data-options='tiffin']").empty().select2({
            data: arr2
        })
        });
    }catch(error){
        toastr.error(error.message);
    }
});
</script>

<script>
    const ordralltmentForm = document.querySelector('#order-allotment-form');
    ordralltmentForm.addEventListener('submit', async (event) => { 
    event.preventDefault(); 
    try {
        const url = '{{route("mess.diet-order.allot")}}';
        const formData = new FormData(ordralltmentForm);
        if (!formData) {
            throw new Error('Please fill the form properly');
        }
        const res = await fetch(url, {
            method: 'POST',
            body : formData,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        if (!res.ok) {
            const error = await res.json();
            throw new Error(handleError(error.message));
        }
        const result = await res.json();
        if (result) {
            toastr.success(result.message);
            setTimeout(() => {
                location.reload()
            }, 3000);
        }
    } catch (error) {
        toastr.error(error.message);
        console.error(error);
    }
});
/***@argument
 * ===================//intitalization of table//=================
 */
table_load.showTable('[data-table-for=breakfast]',1);
table_load.showTable('[data-table-for=lunch]',2);
table_load.showTable('[data-table-for=dinner]',3);
/***@argument
 * ===================// intitalization of table end//=================
 */

function handleError(errorResponse) {
    if (errorResponse && errorResponse.errors) {
        if (Array.isArray(errorResponse.errors)) {
            return errorResponse.errors.join(', ');
        }
        if (typeof errorResponse.errors === 'string') {
            return errorResponse.errors;
        }
        if (typeof errorResponse.errors === 'object') {
            const errorMessages = Object.values(errorResponse.errors);
            const errorList = errorMessages.map(item => `<li>${item}</li>`);
            return `<ul>${errorList.join('')}</ul>`;
        }
    }
    return JSON.stringify(errorResponse);
}
</script>


    
@endpush