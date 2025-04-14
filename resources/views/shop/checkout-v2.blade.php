<x-app-layout>

    @section('title', __('template.checkout'))

    <style>
        #autocomplete-address{
            width: 100%;
            list-style: none;
            margin: 0;
            padding: 0;
            position: absolute;
            z-index: 1;
            background: #ffffff;
            border: 1px solid #e4e4e4;
            -webkit-box-shadow: 0 2px 28px 0 rgba(0, 0, 0, 0.06);
            box-shadow: 0 2px 28px 0 rgba(0, 0, 0, 0.06);
        }
        #autocomplete-address li{
            display: block;
            width: 100%;
            padding: 6px 12px;
        }
        #autocomplete-address li:hover{
            background: #f9f9f9;
            cursor: pointer;
        }
        .spinner-border{
            position: absolute;
            right: 5px;
            bottom: 12px;
        }
    </style>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-4 ltn__breadcrumb-color-white---">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner text-center">
                        <h1 class="ltn__page-title">{{__('template.checkout')}}</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{\App\Models\Navigation\Menu::getHomePageLink()}}">{{__('template.home')}}</a></li>
                                <li><a href="{{route('shop.home')}}">{{__('template.shop')}}</a></li>
                                <li><a href="{{route('cart.view')}}">{{__('template.cart')}}</a></li>
                                <li>{{__('template.checkout')}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- CHECKOUT AREA START -->
    <div class="ltn__checkout-area mb-100">
        <div class="container">
            <div class="row">
                @if(null !== $cart && $cart->items->count() > 0)
                    <div class="col-lg-6">
                        <div class="ltn__checkout-inner">
                            <div class="ltn__checkout-single-content mt-50">
                                <h4 class="title-2">{{__('template.shipping_details')}}</h4>
                                <div class="ltn__checkout-single-content-info">
                                    <form action="#" >
                                        {{-- Do not remove input with csrf token --}}
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <h6>Personal Information</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-item input-item-name ltn__custom-icon">
                                                    <input  type="text" 
                                                            placeholder="{{__('template.first_name')}}"
                                                            name="customer[fname]" 
                                                            class="form-control" 
                                                            value="{{null !== $cart->order ? $cart->order->customer->fname : ''}}" 
                                                            required 
                                                        />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-item input-item-name ltn__custom-icon">
                                                    <input  type="text" 
                                                            placeholder="{{__('template.last_name')}}"
                                                            name="customer[lname]" 
                                                            class="form-control" 
                                                            value="{{null !== $cart->order ? $cart->order->customer->lname : ''}}" 
                                                            required 
                                                        />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-item input-item-phone ltn__custom-icon">
                                                    <input  type="text" 
                                                            placeholder="{{__('template.phone')}}"
                                                            name="customer[phone]" 
                                                            class="form-control" 
                                                            value="{{null !== $cart->order ? $cart->order->customer->phone : ''}}" 
                                                            required 
                                                        />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-item input-item-email ltn__custom-icon">
                                                    <input  type="email" 
                                                            placeholder="{{__('template.email')}}"
                                                            name="customer[email]" 
                                                            class="form-control" 
                                                            value="{{null !== $cart->order ? $cart->order->customer->email : ''}}" 
                                                            required 
                                                        />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>{{__('template.country')}}</h6>
                                                <div class="input-item">
                                                    <select name="address[country]" class="nice-select" required >
                                                        <option value="NONE">{{__('template.select')}}</option>
                                                        @foreach($countries as $country)
                                                            <option 
                                                                value="{{$country->iso3}}"
                                                                {{null !== $cart->order && $cart->order->address->country === $country->iso3 ? 'selected' : ''}}
                                                            >
                                                                {{$country->name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>{{__('template.locality')}}</h6>
                                                <div class="input-item">
                                                    <select name="address[locality]" class="nice-select" required >
                                                        <option value="NONE">{{__('template.select')}}</option>
                                                        @foreach($cities as $city)
                                                            <option 
                                                                value="{{$city->code}}"
                                                                {{null !== $cart->order && $cart->order->address->locality === $city->code ? 'selected' : ''}}
                                                            >
                                                                {{$city->name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 d-none">
                                                <h6>{{__('template.other_country')}}</h6>
                                                <div class="input-item">
                                                    <input  type="text" 
                                                            name="address[other_country]" 
                                                            class="form-control" 
                                                            value="{{null !== $cart->order && !empty($cart->order->address->other_country) ? $cart->order->address->other_country : ''}}" 
                                                        />
                                                </div>
                                            </div>
                                            <div class="col-md-6 d-none">
                                                <h6>{{__('template.other_locality')}}</h6>
                                                <div class="input-item">
                                                    <input  type="text" 
                                                            name="address[other_locality]" 
                                                            data-action="autocomplete-address"
                                                            class="form-control" 
                                                            value="{{null !== $cart->order && !empty($cart->order->address->other_locality) ? $cart->order->address->other_locality : ''}}" 
                                                        />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6>{{__('template.address')}}</h6>
                                                <div class="input-item">
                                                    <input  type="text" 
                                                            name="address[address]" 
                                                            data-action="autocomplete-address"
                                                            class="form-control" 
                                                            value="{{null !== $cart->order ? $cart->order->address->address : ''}}" 
                                                            required 
                                                        />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h6>{{__('template.post_code')}}</h6>
                                                <div class="input-item">
                                                    <input  type="text" 
                                                            name="address[post_code]" 
                                                            class="form-control" 
                                                            value="{{null !== $cart->order ? $cart->order->address->post_code : ''}}" 
                                                            required 
                                                        />
                                                </div>
                                            </div>
                                        </div>
                                        <h6>{{__('template.notes')}} (optional)</h6>
                                        <div class="input-item input-item-textarea ltn__custom-icon">
                                            <textarea 
                                                name="order[notes]" 
                                                rows="12"
                                            >
                                                {{null !== $cart->order ? $cart->order->notes : ''}}
                                            </textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mt-50 mb-50">
                            <h4 class="title-2">{{__('template.order_details')}}</h4>
                            <table class="table">
                                <tbody>
                                    @foreach($cart->items as $item)
                                        <tr>
                                            <td>
                                                @if($item->product->type === \App\Models\Shop\Product::VARIABLE)
                                                    {{$item->variation->name}}
                                                @else
                                                    {{$item->product->name}}
                                                @endif
                                                <strong>× {{$item->qty}}</strong>
                                            </td>
                                            <td>{{$item->subtotal}} MDL</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>{{__('template.shipping')}}</td>
                                        <td id="shipping-cost">
                                            {{null !== $cart->order ? $cart->order->shipping . ' MDL' : '--'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{__('template.order_total')}}</strong></td>
                                        <td>
                                            <strong id="order-total">
                                                {{null !== $cart->order ? $cart->order->total : $cart->total_price}} MDL
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="ltn__checkout-payment-method mt-50">
                            <h4 class="title-2">{{__('template.shipping_method')}}</h4>
                            <div class="mb-5">
                                @foreach($shippingMethods as $method)
                                    <!-- card -->
                                    <div class="card">
                                        <input  type="radio" 
                                                id="{{$method->code}}" 
                                                name="shipping_method" 
                                                value="{{$method->code}}" 
                                                {{null !== $cart->order && $cart->order->shippingMethod->code === $method->code ? 'checked' : ($method->is_default ? 'checked' : '') }}
                                            >
                                        <label for="{{$method->code}}">{{$method->name}}</label>
                                        <div class="card-body d-none">
                                            {{strip_tags($method->description)}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <h4 class="title-2">{{__('template.payment_method')}}</h4>
                            <div class="mb-5">
                                @foreach($paymentMethods as $method)
                                    <!-- card -->
                                    <div class="card">
                                        <input  type="radio" 
                                                id="{{$method->code}}" 
                                                name="payment_method" 
                                                value="{{$method->code}}" 
                                                {{null !== $cart->order && $cart->order->paymentMethod->code === $method->code ? 'checked' : ($method->is_default ? 'checked' : '') }}
                                            >
                                        <label for="{{$method->code}}">{{$method->name}}</label>
                                        <div class="card-body d-none">
                                            {{strip_tags($method->description)}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-right">
                                <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit">
                                    {{__('template.place_order')}}
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    @include('includes.empty-cart')
                @endif
            </div>
        </div>
    </div>
    <!-- CHECKOUT AREA END -->
    
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.rawgit.com/hayeswise/Leaflet.PointInPolygon/v1.0.0/wise-leaflet-pip.js"></script>
    <script type="module">
        function getCountry(){
            var country = $('select[name="address[country]"]').val();
            if(country === 'NONE'){
                console.log('You should specify your country!');
                return null;
            }
            return country;
        }

        function getLocality(){
            var locality = $('select[name="address[locality]"]').val();
            if(locality === 'NONE'){
                console.log('You should specify your locality!');
                return null;
            }
            return locality;
        }

        /**
         * Get address coordinates if exists
         * Just address[coordinates] matter, because only coordinates affects the shipping
         * Using address[address] we can not calculate the shipping
         */
        function getCoordinates(){
            var $addressCoordinates = $('input[name="address[coordinates]"]');
            if($addressCoordinates.length){
                return JSON.parse($addressCoordinates.val());
            }
            return null;
        }

        function parseAddress(address){
            var result = address.city;
            if(address.road){
                result += `, ${address.road}`;
            }
            if(address.house_number){
                result += `, ${address.house_number}`;
            }

            return result;
        }

        /**
         * Calculate distance between two points (addresses) in km
         */
        function calculateDistance(finish){
            /**
             * Initial point
             * The distance will be calculated starting from this point
             * The point coordinates are hardcoded for the moment
             * But can be used from some settings from backend if needed
             */
            const start = {lat: 47.02643, lng: 28.83251}; // Chisinau flowers market

            const params = `${start.lat},${start.lng};${finish.lat},${finish.lng}`;

            const url = `https://router.project-osrm.org/route/v1/driving/${params}?overview=false`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var $input = $('input[autocomplete="true"]');
                    // Access the distance in meters from the API response
                    const distanceM = data.routes[0].distance;
                    
                    // Convert distance to kilometers
                    const distanceKM = (distanceM / 1000).toFixed(2);

                    var $addressDistance = $('input[name="address[distance]"]');
                    if($addressDistance.length){
                        $addressDistance.val(distanceKM);
                    } else {
                        $addressDistance = $("<input>").attr('type', 'hidden').attr('name', 'address[distance]').val(distanceKM);
                        $input.next('ul').after($addressDistance);
                    }

                    calculateShipping([], distanceKM);

                    console.log('Distance between points:', distanceKM + ' km');
                })
                .catch(error => console.error('Error:', error));
        }

        function detectShippingZone(params){
            $.ajax({
                url: '/checkout/detect-shipping-zone',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: params,
                success: function(response) {
                    console.log('Zones: ', response);
                    if(response.status === 200){
                        // Collect zones codes
                        let zones = new Array()
                        response.zones.map((zone) => {
                            if(zone.on_map && null !== zone.area){
                                const point = L.latLng(params.coordinates.lat, params.coordinates.lng)
                                if(L.polygon(zone.area).contains(point)){
                                    console.log('Contains: ', zone.code);
                                    zones.push(zone.code)
                                }
                            } else {
                                zones.push(zone.code)
                            }   
                        })
                        /**
                         * If we found the zone, chosen address belong to, 
                         * calculate the shipping for detected zone
                         */
                        if(zones.length){
                            calculateShipping(zones);
                        } else {
                            /**
                             * Try to calculate distance to the point (address coordinates)
                             */
                            if(params.coordinates){
                                calculateDistance(params.coordinates)
                            }
                            
                        }
                        
                    } else {
                        $('#shipping-cost').html(@js(__('template.not_available')));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                    // Handle error response
                }
            });
        }

        function calculateShipping(zones, distance = 0){
            /**
             * Method can be or can not to be provided
             * Depends of how many shipping methods will be supported
             * In case there is only one shipping method, pass just conditions
             */
            var method = $('input[name="shipping_method"]:checked').val();
            // Perform the AJAX POST request to calculate shipping
            $.ajax({
                url: '/checkout/calculate-shipping',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                    method,
                    zones, 
                    distance
                },
                success: function(response) {
                    if(response.status === 200){
                        if(response.shipping.available){
                            $('#shipping-cost').html(response.shipping.price + ' MDL');
                            $('button[type="submit"]').prop('disabled', false);
                        } else {
                            $('#shipping-cost').html(@js(__('template.not_available')));
                            /**
                             * Disable button to prevent submitting the form while selected unavailable shipping
                             */
                            $('button[type="submit"]').prop('disabled', true);
                        }
                            
                        $('#order-total').html(response.orderTotal + ' MDL');
                    }   
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                    // Handle error response
                }
            }); 
        }

        /*function calculateShipping(country, locality){
            var method = $('input[name="shipping_method"]:checked').val();
            if(country){
                // Perform the AJAX POST request to calculate shipping
                $.ajax({
                    url: '/checkout/calculate-shipping',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data: {
                        method,
                        country,
                        locality
                    },
                    success: function(response) {
                        if(response.status === 200){
                            if(response.shipping.available){
                                $('#shipping-cost').html(response.shipping.price + ' MDL');
                                $('button[type="submit"]').prop('disabled', false);
                            } else {
                                $('#shipping-cost').html('Not available');
                                /**
                                 * Disable button to prevent submitting the form while selected unavailable shipping
                                 *
                                $('button[type="submit"]').prop('disabled', true);
                            }
                            
                            $('#order-total').html(response.orderTotal + ' MDL');
                        }   
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr, status, error);
                        // Handle error response
                    }
                });
            }
        }*/

        $('select[name="address[country]"]').on('change', function(){
            var country = $(this).val(),
                locality = getLocality(),
                $dependentSelect = $('select[name="address[locality]"]'),
                $dependentInputs = $('input[name="address[other_country]"], input[name="address[other_locality]"]');

            detectShippingZone({
                country, 
                locality
            });
            //calculateShipping(country, locality);

            /**
             * If other country is selected, 
             * give the possibility to input the country and locallity manually
             */
            if(country === 'OTH'){
                $dependentSelect.closest('div[class*="col"]').addClass('d-none');
                $dependentInputs.prop('required', true).closest('div[class*="col"]').removeClass('d-none');
            } else {
                $dependentSelect.closest('div[class*="col"]').removeClass('d-none');
                $dependentInputs.prop('required', false).closest('div[class*="col"]').addClass('d-none');
                $dependentInputs.val('');
            }
        });

        $('select[name="address[locality]"]').on('change', function(){
            var country = getCountry(),
                locality = $(this).val(),
                $dependentInput = $('input[name="address[other_locality]"]');
            
            detectShippingZone({
                country, 
                locality
            });
            //calculateShipping(country, locality);

            /**
             * If other locality is selected, 
             * give the possibility to input the locallity manually
             */
            if(locality === 'OTH'){
                $dependentInput.prop('required', true).closest('div[class*="col"]').removeClass('d-none');
            } else {
                $dependentInput.prop('required', false).closest('div[class*="col"]').addClass('d-none');
                $dependentInput.val('');
            }
        });

        $('input[name="shipping_method"]').on('change', function(){
            var country = getCountry(),
                locality = getLocality(),
                coordinates = getCoordinates();
            
            if(coordinates){
                detectShippingZone({
                    coordinates
                });
            } else {
                detectShippingZone({
                    country, 
                    locality
                });
            }
        })

        /**
         * Autocomplete the address
         */
        function selectResult($input, result){
            console.log('Selected result: ', result);
            $input.val(parseAddress(result.address));
            const coordinates = {
                    lat: result.lat,
                    lng: result.lon
                };
            var $addressCoordinates = $('input[name="address[coordinates]"]');
            if($addressCoordinates.length){
                $addressCoordinates.val(JSON.stringify(coordinates));
            } else {
                $addressCoordinates = $("<input>").attr('type', 'hidden').attr('name', 'address[coordinates]').val(JSON.stringify(coordinates));
                $input.next('ul').after($addressCoordinates);
            }
            $input.next('ul').empty(); // Clear the results list
            detectShippingZone({coordinates})
        }

		function displayResults(results) {
            var $input = $('input[autocomplete="true"]'),
                $resultsList = $('#autocomplete-address');
            if($resultsList.length){
                $resultsList.empty().hide();
            } else {
                $resultsList = $("<ul>").attr('id', 'autocomplete-address');
            }
            
            if(results.length){
                results.forEach((result) => {
                    const li = $("<li>")
                    li.text(parseAddress(result.address))
                    li.on('click', function(){
                        selectResult($input, result);
                    })
                    $resultsList.append(li);
                })
            } else {
                const li = $("<li>")
                li.text(@js(__('template.address_not_found')))
                $resultsList.append(li);
            }
			
            $input.after($resultsList);
            $resultsList.show();
            // Remove the spinner after results were shown
            $('.spinner-border').remove();
		}

        /* 
         * Perform api request with given params
         */
		function autocompleteAddress(address) {
			var url = new URL('https://nominatim.openstreetmap.org/search'),
                params = {
                    'q': address,
                    'countrycodes': ['MD'], // Specify country code(s)
                    'addressdetails': 1,
                    //extratags: 'highway',
                    'format': 'json',
                    'accept-language': @js(app()->getLocale()) // Specify the language code
                }
            
			url.search = new URLSearchParams(params).toString()

			fetch(url)
				.then((response) => response.json())
				.then((data) => {
					console.log('Request result: ', data)
					displayResults(data)
				})
				.catch((error) => console.log('Error: ', error))
		}

        /**
         * Add a debounce timer to wait while user will stop typing
         */
        var debounceTimer
		const debounceInterval = 1000 // 1 second

        /**
         * Handle input and trigger autocomplete
         */
        function handleAutocompletedInput($input) {
			clearTimeout(debounceTimer)
            const address = $input.val();
			if (address.trim() !== '') {
                $input.attr('autocomplete', true);
                /**
                 * Bootstrap spinner is used, can be used any other instead
                 */
                var $spinner = $('.spinner-border');
                if(!$spinner.length){
                    $spinner = $('<div>').addClass('spinner-border').attr('type', 'status');
                    $input.before($spinner);
                }

				debounceTimer = setTimeout(() => {
					autocompleteAddress(address)
				}, debounceInterval)
			}
		}

        $('input[data-action="autocomplete-address"]').on('input', function(){
            handleAutocompletedInput($(this));
        })

        /**
         * Hide results if clicked outside
         */
        $(document).on('click', function(event){
            if(event.target !== $('input[data-action="autocomplete-address"]')){
                $('#autocomplete-address').empty().hide();
            }
        })

    </script>
</x-app-layout>