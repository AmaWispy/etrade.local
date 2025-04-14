<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://cdn.rawgit.com/hayeswise/Leaflet.PointInPolygon/v1.0.0/wise-leaflet-pip.js"></script>

<script type="module">
    const florarCoordinat = {lat: 47.02643, lng: 28.83251}
    /**
     * Copy personal data, in case the customer will receive order by himself
     */
    $('input[name="details[myself]"]').on('change', function(){
        var $section = $('#contact-person'),
            $inputContactPerson = $('input[name="details[contact_person]"]'),
            $inputPhone = $('input[name="details[phone]"]');

        if($(this).is(':checked')){
            $section.hide();
            var name = $('input[name="customer[name]"]').val(),
                phone = $('input[name="customer[phone]"]').val();

            $inputContactPerson.val(name );
            $inputPhone.val(phone);
        } else {
            $section.show();
            $inputContactPerson.val("");
            $inputPhone.val("");
        }
    });

    /**
     * Copy personal data
     */
    $('input[name="customer[name]').on('change', function(){
        if($('input[name="details[myself]"]').is(':checked')){
            var name = $('input[name="customer[name]"]').val();
            $('input[name="details[contact_person]"]').val(name);
        }
    });
    $('input[name="customer[phone]"]').on('change', function(){
        if($('input[name="details[myself]"]').is(':checked')){
            var phone = $('input[name="customer[phone]"]').val();
            $('input[name="details[phone]"]').val(phone);
        }
    });

    /**
     * Datepicker
     */
    $('.datepicker').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        minDate: @js(date('Y-m-d')),
        lang: @js(app()->getLocale())
    });

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
    // function getCoordinates(){
    //     var $addressCoordinates = $('input[name="address[coordinates]"]');
    //     if($addressCoordinates.length){
    //         return JSON.parse($addressCoordinates.val());
    //     }
    //     return null;
    // }

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


    function calculateFixedTime(){
        const fixedTime = $('input[name="details[fixed_time]"]:checked').val();;
        const currentTotal = parseFloat($('#order-total').text().replace(/\s/g, '').replace(/[^\d.]/g, '')) || 0;
        
        const dTimeBlock = $('#dTime');
        if (dTimeBlock.hasClass('hidden')) {
            dTimeBlock.removeClass('hidden').addClass('flex'); // Показываем блок
        } else {
            dTimeBlock.removeClass('flex').addClass('hidden'); // Скрываем блок
        }
    
        $.ajax({
            url: '/checkout/calculate-fixed-time',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: {
                fixedTime,
                currentTotal
            },
            success: function(response) {
                if(response.status === 200){
                    $('#order-total').html(response.orderTotal);
                    // console.log(response)
                }   
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
                // Handle error response
            }
        }); 
    }

    function calculateDistance(finish){
        /**
         * Initial point
         * The distance will be calculated starting from this point
         * The point coordinates are hardcoded for the moment
         * But can be used from some settings from backend if needed
         */
        const params = `${florarCoordinat.lat},${florarCoordinat.lng};${finish.lat},${finish.lng ?? finish.lon}`;

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

    function getCoordinates(Name){
        const url = `https://nominatim.openstreetmap.org/search?city=${Name}&countrycodes=MD&format=json&addressdetails=1&limit=1`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if(data && data.length > 0) {
                    const lat = data[0].lat;
                    const lon = data[0].lon;
                    calculateDistance(data[0])
                }
            })
            .catch( 
                error => console.error(error)
            );
    }

    $('#raion').on('change', function(){
        const $select = $(this),
            raion = $select.find('option:selected').data('raion'),
            raionDefault = $select.find('option:selected').data('null');
        
        if(raionDefault === 'NONE'){
            calculateShipping([], 0)
        }
        if(raion){
            detectShippingZone({raion});
        }
    })

    $('#locality').on('change', function(){
        var $select = $(this),
            locality = $select.val(),
            city = $select.find('option:selected').data('city'),
            localityDefault = $select.find('option:selected').data('null'),
            code = $select.find('option:selected').data('code');

        if(localityDefault === 'NONE' ){
            calculateShipping([], 0)
        }
        if(code === 'CHS'){
            // $('.shipping-cost').html('')
            $('.raion').prop('selectedIndex', 0);
        }
        
        if(code === 'CHS'){
            $('#raion').prop('disabled', false)
        } else if (city) {
            $('#raion').prop('disabled', true)
            getCoordinates(city)
        } else {
            detectShippingZone({locality})
            $('#raion').prop('disabled', true)
        }
        
        $('#raion').niceSelect('update');
    });

    function haversineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Радиус Земли в километрах
        const dLat = (lat2 - lat1) * (Math.PI / 180);
        const dLon = (lon2 - lon1) * (Math.PI / 180);
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // Расстояние в километрах
    }

    function findClosest(store, locations) {
        let closest = null;
        let minDistance = Infinity;

        locations.forEach(location => {
            const distance = haversineDistance(
                store.lat, store.lng, location.lat, location.lng
            );

            if (distance < minDistance) {
                minDistance = distance;
                closest = location;
            }
        });

        return closest;
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
                            const closestLocation = findClosest(florarCoordinat, zone.area[0]);

                            calculateDistance(closestLocation)
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
                    $('.shipping-cost').html(@js(__('template.not_available')));
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
        const currentTotal = parseFloat($('#order-total').text().replace(/\s/g, '').replace(/[^\d.]/g, '')) || 0;

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
                distance,
                currentTotal
            },
            success: function(response) {
                if(response.status === 200){
                    if(response.shipping.available){
                        $('.shipping-cost').html(response.shipping.price_formatted);
                        $('button[type="submit"]').prop('disabled', false);
                    } else {
                        $('.shipping-cost').html(@js(__('template.not_available')));
                        /**
                         * Disable button to prevent submitting the form while selected unavailable shipping
                         */
                        $('button[type="submit"]').prop('disabled', true);
                    }
                    $('#order-total').html(response.orderTotal);
                }   
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
                // Handle error response
            }
        }); 
    }

    $('#exact_time').on('click', function(){
        calculateFixedTime()
    })


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

    $(document).ready(function(){
        var $submitButton = $('button[data-action="place-order"]'),
            action = @JS(route('checkout.place-order')),
            $form = $('form[action="'+action+'"]'); 

        $submitButton.on('click', function(e){
            console.log($form);
            e.preventDefault();
            $form.submit();
        })
    })

    //Save Data to back cart 
    $('#back-to-cart').on('click', function(){
        
        let checkoutData = $('.checkout-data').serializeArray();
        const totalCost =  parseFloat($('#order-total').text().replace(/\s/g, '').replace(/[^\d.]/g, '')) || 0;

        let formattedData = {};
        $.each(checkoutData, function(_, field) {
            formattedData[field.name] = field.value;
        });
        formattedData['totalCost'] = totalCost
        console.log(formattedData)

        $.ajax({
            url: '/checkout/save',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: formattedData,
            success: function(response) {
                console.log(response)
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
            }
        })
    })

</script>