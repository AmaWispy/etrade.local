<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://cdn.rawgit.com/hayeswise/Leaflet.PointInPolygon/v1.0.0/wise-leaflet-pip.js"></script>
<script type="module">
    const florarCoordinat = {lat: 47.02643, lng: 28.83251} // Chisinau florar coord

    function getCoordinates(Name){
        const url = `https://nominatim.openstreetmap.org/search?city=${Name}&countrycodes=MD&format=json&addressdetails=1&limit=1`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log(data)
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
        
            console.log(raionDefault)
        if(raionDefault === 'NONE'){
            $('.shipping-cost').html('')
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

        if(localityDefault === "NONE" || code === 'CHS'){
            $('.shipping-cost').html('')
            $('.raion').prop('selectedIndex', 0);
        }if(code === 'CHS'){
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
        console.log('finish:' , finish.lat) 

        const params = `${florarCoordinat.lat},${florarCoordinat.lng};${finish.lat},${finish.lng ?? finish.lon}`;

        const url = `https://router.project-osrm.org/route/v1/driving/${params}?overview=false`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                var $input = $('input[autocomplete="true"]');
                // Access the distance in meters from the API response
                const distanceM = data.routes[0].distance;
                console.log(data)
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
                        console.log(zone.area[0])
                        console.log(zone.area[0][0])

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
                        console.log(555)
                        calculateShipping(zones);
                    } else {
                        /**
                         * Try to calculate distance to the point (address coordinates)
                         */
                        if(params.coordinates){
                            console.log(12)
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

        console.log(method)
        // Perform the AJAX POST request to calculate shipping
        $.ajax({
            url: '/checkout/calculate-shipping-delivery',
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
                        $('.shipping-cost').html(response.shipping.price_formatted);
                    } else {
                        $('.shipping-cost').html(@js(__('template.not_available')));
                    }
                    $('#order-total').html(response.orderTotal);
                    console.log(response)
                }   
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
                // Handle error response
            }
        }); 
    }
</script>