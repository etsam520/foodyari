@extends('mess-views.layouts.dashboard-main')
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/image-uploader/dist/image-uploader.min.css')}}">

@endpush
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
        <form action="{{route('mess.subscription.add')}}"  method="POST" id="cusomer-form" enctype="multipart/form-data">
            @csrf
       <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">New Subscription Pakage</h4>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="new-user-info">
                        <div class="row">
                            @if ($errors->any())
                            <div class="col-md-12">
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="title">Title:</label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Title" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Type</label>
                                    <select name="item_type"  class="selectpicker form-control" data-style="py-0">
                                        <option disabled selected value="">Select One</option>
                                        <option value="V">Veg</option>
                                        <option value="N">Non Veg</option>
                                        <option value="B">Both</option>
                                     </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="sdate">Validity:</label>
                                    <input type="text" name="validity" id="sdate" required class="form-control " placeholder="No. of days">  
                                </div>
                                {{-- <div class="form-group">
                                    <label class="form-label" for="exdate">Expity Date:</label>
                                    <input type="text" name="expiry" id="exdate" required class="form-control date_flatpicker" placeholder="Pick Expiry Day">  
                                </div> --}}
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">Product
                                        Image</label>
                                    <div class="input-images"></div>

                                </div>
                                <div class="form-group ">
                                    <label class="form-label" for="add1">Description :</label>
                                    <textarea name="description" class="form-control"id="add1"  cols="10" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-6"> 
                                <label class="form-label" for="price">Price:</label>
                                <input type="text" class="form-control" required name="price" id="price" placeholder="Ex. 20">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="speciality">Speciality:</label>
                                <div class="form-group d-flex " id="speciality">
                                    <div class="form-check d-block">
                                        <input class="form-check-input" type="radio" required name="speciality"
                                            value="1" id="special-diet">
                                        <label class="form-check-label" for="special-diet">
                                            Special
                                        </label>
                                    </div>
                                    <div class="form-check d-block mx-3">
                                        <input class="form-check-input" type="radio"  required name="speciality"
                                            value="0" id="normal-diet" checked>
                                        <label class="form-check-label" for="normal-diet">
                                            Normal
                                        </label>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"
                                        for="exampleFormControlInput1">Discount
                                        Type

                                    </label>
                                    <select name="discount_type" class="form-control js-select2-custom">
                                        <option value="">Select One</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="amount">Amount ($)</option>
                                    </select> 
                                </div>
                                <div class="form-group ">
                                    <label class="form-label"
                                        for="exampleFormControlInput1">Discount
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="Currently you need to manage discount with the Restaurant.">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                    </label>
                                    <input type="number" min="0" max="9999999999999999999999"
                                        name="discount" class="form-control" value=""
                                        placeholder="Enter Discount">
                                </div>
                            </div>
                            <div class=" col-md-6">
                                <div class="form-group d-none" id="Special-Diet">
                                    <label class="form-label" for="pno">No. of Special Diets:</label>
                                    <input type="text" class="form-control" data-diet="special" name="no_diet_special" id="pno" placeholder="Ex. 30">
                                </div>
                                <label class="form-label"  >No. of Diets(<i data-diet-count="0">0</i>):</label>
                                <div class="d-flex"  >
                                    <div class="form-group">
                                        <label class="form-label" for="breakfast">Breakfast:</label>
                                        <input type="text" class="form-control" data-diet="breakfast" name="no_d_breakfast" id="breakfast" required placeholder="Ex. 30">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="lunch">Luch:</label>
                                        <input type="text" class="form-control" data-diet="lunch" name="no_d_lunch" id="lunch" required placeholder="Ex. 30">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="dinner">Dinner:</label>
                                        <input type="text" class="form-control" data-diet="dinner" name="no_d_dinner" id="dinner" required placeholder="Ex. 30">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
 </div>
@endsection

@push('javascript')
{{-- <script src="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.js')}}"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js" ></script> --}}
<script src="{{asset('assets/vendor/image-uploader/dist/image-uploader.min.js')}}"></script>
<script>
    document.querySelector("#special-diet").addEventListener('click', () => {
        document.querySelector("#Special-Diet").classList.toggle('d-none');
    });
    document.querySelector("#normal-diet").addEventListener('click', () => {
        document.querySelector("#Special-Diet").classList.toggle('d-none');
    });

    // order of expiry calculation [1=month;2=halfmonth;3=week;4=day;5=onetime;]

   /* const ExpiryDate = {
    startDay: "2024-03-10", 
    date: moment(this.startDay),

    month: function() {
        return moment(this.date).add(1, 'month').format('YYYY-MM-DD');
    },

    halfmonth: function() {
        return moment(this.startDay).add(15, 'days').format('YYYY-MM-DD');
    },

    week: function() {
        return moment(this.startDay).add(7, 'days').format('YYYY-MM-DD');
    },

    day: function() {
        return this.startDay;
    },

    endDayCase: function(value = 1) {
        switch (value) {
            case 1:
                return this.month();
            case 2:
                return this.halfmonth();
            case 3:
                return this.week();
            case 4:
                return this.day();
            default:
                return 0000-00-00;
        }
    }
};*/
</script>
<script>
  
    const dietNumbers = {
        breakfast: 0,
        lunch: 0,
        dinner: 0,
        special : 0,
        dietCount: function(item) {
            item.textContent = parseInt(this.breakfast) + parseInt(this.lunch) + parseInt(this.dinner) + parseInt(this.special); 
            return true;
        }
    };

    (function() {
        document.querySelectorAll("[data-diet]").forEach(element => {
            element.addEventListener('input', () => {
                dietNumbers[element.dataset.diet] = element.value;
                dietNumbers.dietCount(document.querySelector('[data-diet-count]')); 
            });
        });
    }());
    
</script>

<script>
$('.input-images').imageUploader({
  extensions: ['.jpg', '.jpeg', '.png', '.gif', '.svg'],
  mimes: ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
  maxSize: undefined,
  maxFiles: undefined,
  imagesInputName: 'product_images',
  preloadedInputName: 'preloaded',
  label: 'Drag & Drop files here or click to browse'
});
/*
* =================// customer form submission starts//============================
*/
// const CustomerForm = document.querySelector('#cusomer-form');
// CustomerForm.addEventListener('submit', async function(event) {
//     event.preventDefault();
//     try {
//         const url = '{{route('mess.customer.add')}}';
//         const formData = new FormData(CustomerForm);
//         formData.append('pricing', JSON.stringify(pricing));
//         if (!formData) {
//             throw new Error('Please fill the form properly');
//         }
//         const res = await fetch(url, {
//             method: 'POST',
//             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//             body: formData
//         });
//         if (!res.ok) {
//             const errorMessage = await res.json(); 
//             throw new Error(handleError(errorMessage)); 
//         }
//         const result = await res.json();
//         // console.log(result);
//         if(result.success){
//             toastr.success(result.success);
//             CustomerForm.reset();
//             // window.location.href     = "{{route('mess.customer.list')}}";
//         }
//     } catch (error) {
//         console.error(error)
//         toastr.error(error.message);
//     }
// });

// function handleError(errorResponse) {
//     if (errorResponse && errorResponse.errors) {
//         if (Array.isArray(errorResponse.errors)) {
//             return errorResponse.errors.join(', ');
//         }
//         if (typeof errorResponse.errors === 'string') {
//             return errorResponse.errors;
//         }
//         if (typeof errorResponse.errors === 'object') {
//             const errorMessages = Object.values(errorResponse.errors);
//             const errorList = errorMessages.map(item => `<li>${item}</li>`);
//             return `<ul>${errorList.join('')}</ul>`;
//         }
//     }
//     return JSON.stringify(errorResponse);
// }
/*
* =================// customer form submission ends//============================
*/
</script>

@endpush
