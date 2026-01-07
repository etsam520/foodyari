@extends('user-views.restaurant.layouts.main')

@push('sub-header')
    @include('user-views.restaurant.layouts.sub-header')
@endpush

@push('slider')
    @include('user-views.restaurant.layouts.slider')
@endpush



@section('containt')


    <!-- All Restaurant -->
    <div class="container">
        <div class="pt-3 pb-3 title d-flex align-items-center justify-content-center" style="border-bottom: 2px dashed #dee2e6; border-top: 2px dashed #dee2e6;">
            <h2 class="m-0 fw-bolder d-flex align-items-center">
                <span id="favoriteName">Restaurants</span>

            </h2>

        </div>
        <div class="pt-3 px-3 pb-3 title d-flex align-items-center justify-content-between bg-white mt-3 rounded-4 mx-1 shadow-sm">


            <div class="d-flex">
                <div>
                    <input type="radio" class="btn-check" name="myfavorite" value="myFavoriteRestaurants" id="success-outlined"  onchange="showMyFavorite(this)" checked>
                    <label class="btn btn-outline-warning" for="success-outlined" style="border-radius: 5px 0px 0px 5px;">Restaurants</label>
                </div>
                <div>
                    <input type="radio" class="btn-check" name="myfavorite" value="myFavoriteFoods" id="danger-outlined"  onchange="showMyFavorite(this)" autocomplete="off">
                    <label class="btn btn-outline-warning" for="danger-outlined" style="border-radius: 0px 5px 5px 0px;">Foods</label>
                </div>
            </div>

        </div>
        {{-- <div class="text-end mb-2">
            <a class="fw-bolder ms-auto" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#filters" style="font-size: 19px;">Filters <i class="feather-chevrons-right"></i></a>
        </div> --}}
        <div class="most_sale" id="restaurantFrame">
            <div class="row" data-view="restaurants">

            </div>
        </div>
        <div class="most_sale d-none mt-3" id="foodFrame">
            <div class="row" data-products="all">

            </div>
        </div>
    </div>

@endsection


@push('modal')

    <div class="offcanvas offcanvas-end" tabindex="-1" id="custom_item" aria-labelledby="customizeCartLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="customizeCartLabel">Customize Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" data-products="single">
            <p>Some placeholder content for the customize cart offcanvas.</p>
        </div>
    </div>


    <!-- Sticky Foot cart -->
    @php($cart = App\Http\Controllers\User\Restaurant\CartHelper::getCart())
    <div class="container sticky-bottom rounded {{ count($cart) == 0 ? 'd-none' : null }}  mb-3" id="view-cart" onclick="location.href='{{ route('user.restaurant.check-out') }}'">
        <div class="d-flex justify-content-between py-3 rounded-4 px-3" style="background-color:#ff810a;">
            <a href="javascript:void(0)" class="align-self-center">
                <h6 class="text-white fw-bolder mb-0 align-self-center">{{ count($cart) }} Item Added</h6>
            </a>
            <div class="d-flex">
                <h5 class="text-white fw-bolder mb-0 align-self-center">View Cart<i class="fas fa-arrow-right ms-2"></i>
                </h5>
            </div>
        </div>
    </div>
@endpush

@push('javascript')
    <script>
        async function getRestaurants(filter = "all", options = {}) {
            let url = `{{ route('user.restaurant.favorite.restaurants') }}`;
            try {
                const resp = await fetch(url);
                if (!resp.ok) {
                    const error = await resp.json();
                    throw new Error(error.message);
                }
                const result = await resp.json();
                document.querySelector('[data-view="restaurants"]').innerHTML = result.view;
                // document.querySelector('[data-filter=all]').textContent = result.count;
            } catch (error) {
                console.error('Error:', error);
                toastr.warning(error.message || 'An error occurred while fetching restaurants.');
            }
        }

        document.querySelectorAll('[data-filter]').forEach(element => {
            element.addEventListener('click', () => {
                let options = {}
                if (element.dataset.categoryId) {
                    options.category_id = element.dataset.categoryId;
                }
                getRestaurants(element.dataset.filter, options);
            });
        });

        getRestaurants();

        document.querySelector("#food-search-container input").addEventListener('keyup', () => {
            getRestaurants(event.target.value, {})
        });
    </script>

    <script>
        /*====================// Fet Food //=====================*/
        async function getFoods(filter = null) {
            let url = "{{ route('user.restaurant.favorite.foods') }}";

            try {
                const resp = await fetch(url);
                if (!resp.ok) {
                    const error = await resp.json();
                    toastr.error(error.message);
                }
                const result = await resp.json();
                document.querySelector('[data-products=all]').innerHTML = result.view;
                changer();
                viewSingleFood();


            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }
        getFoods();

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

        var customizeSingelFood = new bootstrap.Offcanvas(document.getElementById('custom_item'));

        function viewSingleFood() {
            document.querySelectorAll('[data-customize]').forEach(element => {
                element.addEventListener('click', async () => {
                    const foodId = element.dataset.foodId;
                    let url = `{{ route('user.restaurant.get-food') }}?food_id=${foodId}`;

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


        /*============//Add to cart//=================*/
        async function addToCart(product_id, qty = 1, price, options = Null) {
            const url = "{{ route('user.restaurant.add-to-cart') }}"
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

                if (resp.status == 201) {
                    const warning = await resp.json();
                    Swal.fire(warning.message);
                    return true;
                }else if (!resp.ok) {
                    const error = await resp.json();

                    throw new Error(error.message);
                }
                const result = await resp.json();
                // $('#custom_item').modal('hide');
                customizeSingelFood.hide();
                const viewCart = document.getElementById('view-cart');
                if(result.message){
                    // toastr.success(result.message)
                    viewCart.querySelector('h6').textContent = result.message;
                }
                if(result.confirm){
                    Swal.fire({
                    title: "Are you sure?",
                    text: result.confirm,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes"
                    }).then((sw_resp) => {
                        if (sw_resp.isConfirmed) {
                            addToCart(PRODUCT_ID, QTY, PRICE, OPTIONS  );
                        }else{
                            location.reload();
                        }

                    });
                }
                if (viewCart.classList.contains('d-none')) {
                    viewCart.classList.remove('d-none');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    title: error.message,
                    icon: "info"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }
        }


        function customizeCart() {
            document.querySelectorAll('[customizeCart]').forEach(element => {
                element.addEventListener('click', async () => {
                    const foodId = element.getAttribute('food-id');

                    let url = "{{ route('user.restaurant.get-foods') }}?food_id=" + foodId; //
                    try {
                        const resp = await fetch(url);
                        if (!resp.ok) {
                            const error = await resp.json();
                            toastr.error(error.message);
                        }
                        const result = await resp.json();
                        document.querySelector('[data-products=all]').innerHTML = result.view;
                        changer();
                        viewSingleFood();
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }

                });
            });
        }



        document.querySelector("#food-search-container input").addEventListener('keyup', () => {
            getFoods(event.target.value);
        });
    </script>

    <script>
        function showMyFavorite(item) {
            const foodFrame = document.getElementById('foodFrame');
            const restaurantFrame = document.getElementById('restaurantFrame');
            const favoriteName = document.getElementById('favoriteName');

            if (item.value == "myFavoriteFoods") {
                if (foodFrame.classList.contains('d-none')) {
                    foodFrame.classList.remove('d-none');
                }
                if (!restaurantFrame.classList.contains('d-none')) {
                    restaurantFrame.classList.add('d-none');
                }
                favoriteName.textContent = "Foods";
            } else if (item.value == "myFavoriteRestaurants") {
                if (!foodFrame.classList.contains('d-none')) {
                    foodFrame.classList.add('d-none');
                }
                if (restaurantFrame.classList.contains('d-none')) {
                    restaurantFrame.classList.remove('d-none');
                }
                favoriteName.textContent = "Restaurants";

            }
        }
        function favoriteRestaurant(item) {
            fetch(`{{route('user.restaurant.favorite.restaurant')}}?restaurant_id=${item.dataset.id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json())
            .then(data => {
                console.log(data)
                // item.parentElement.innerHTML = `<img src="{{asset('assets/user/img/favourite.png')}}" onclick="unfavoriteRestaurant(this)" data-id="${item.dataset.id}}" class="img-fluid" style="width: 30px" alt="non-fav-food">`
                item.parentElement.innerHTML = `<span style="font-size: 25px;" onclick="unfavoriteRestaurant(this)" data-id="${item.dataset.id}"><i class="fas fa-heart text-danger bg-white"></i></span>`

            });
        }

        function unfavoriteRestaurant(item) {
            fetch(`{{route('user.restaurant.unfavorite.restaurant')}}?restaurant_id=${item.dataset.id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json())
            .then(data =>{
            console.log(data)
            // item.parentElement.innerHTML = ` <img src="{{asset('assets/user/img/non_favourite.png')}}" onclick="favoriteRestaurant(this)" data-id="${item.dataset.id}}" class="img-fluid" style="width: 30px" alt="fav-food">`
            item.parentElement.innerHTML = ` <span style="font-size: 25px;" onclick="favoriteRestaurant(this)" data-id="${item.dataset.id}"><i class="feather-heart text-muted"></i></span>`
            });
        }

        function favoriteFood(item) {
            fetch(`{{route('user.restaurant.favorite.food')}}?food_id=${item.dataset.id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json())
            .then(data => {
                console.log(data)
                // item.parentElement.innerHTML = `<img src="{{asset('assets/user/img/favourite.png')}}" onclick="unfavoriteFood(this)" data-id="${item.dataset.id}}" class="img-fluid" style="width: 30px" alt="non-fav-food">`
                item.parentElement.innerHTML = `<span style="font-size: 25px;" onclick="unfavoriteFood(this)" data-id="${item.dataset.id}"><i class="fas fa-heart text-danger bg-white"></i></span>`
            });
        }

        function unfavoriteFood(item) {
            fetch(`{{route('user.restaurant.unfavorite.food')}}?food_id=${item.dataset.id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json())
            .then(data =>{
               console.log(data)
            //    item.parentElement.innerHTML = ` <img src="{{asset('assets/user/img/non_favourite.png')}}" onclick="favoriteFood(this)" data-id="${item.dataset.id}}" class="img-fluid" style="width: 30px" alt="fav-food">`
               item.parentElement.innerHTML = ` <span style="font-size: 25px;"  onclick="favoriteFood(this)" data-id="${item.dataset.id}"><i class="feather-heart text-muted"></i></span>`
            });
        }
    </script>
@endpush
