<form action="" class="flex items-center gap-3">
    <h1>{{ __('template.price') }} ({{ session()->has('currency') ? session()->get('currency')['iso_alpha'] : '' }})</h1>
    <div class="flex flex-col gap-2 !items-center">
        <div class="flex w-44 items-center h-10">
            <input type="text" class="border !h-full !rounded-l-lg !m-0 !p-0 !pl-4" id="price-min" name="" value="">
            <input type="text" class="border !h-full !rounded-r-lg !m-0 !p-0 !pl-4" id="price-max" name="" value="">
        </div>
        <div id="slider-range" class="w-full"></div>
    </div>
</form>


<script type="module">
    $(document).ready(function(){
        
        $( "#slider-range" ).slider({
            range: true,
            min: {{ $minPrice }},
            max: {{ $maxPrice }},
            values: [ {{ $minPriceChanged }}, {{ $maxPriceChanged }} ],
            slide: function( event, ui ) {
                $( "#price-min" ).val( ui.values[ 0 ] );
                $( "#price-max" ).val( ui.values[ 1 ] );

                var minPrice = ui.values[0];
                var maxPrice = ui.values[1];

                // Get current url and delete old params (min, max, sorting)
                var currentUrl = window.location.href.replace(/[?&](min-price|min|max-price|max|sorting|color|size)=[^&]+/g, '');

                // Check params
                var separator = currentUrl.includes('?') ? '&' : '?';

                // Create new params to  min, max and save sorting if there is one
                var sorting = window.location.href.match(/[?&]sorting=[^&]+/);
                var color = window.location.href.match(/[?&]color=[^&]+/);
                var size = window.location.href.match(/[?&]size=[^&]+/);
                var newUrl = currentUrl + separator + 'min=' + minPrice + '&max=' + maxPrice;

                // If the sort parameter exists, add it to the URL
                if (sorting) {
                    newUrl += '&' + sorting[0].slice(1); 
                }
                if (color){
                    newUrl += '&' + color[0].slice(1); 

                }
            

                // Redirect to the new URL
                window.location.href = newUrl;
            }
            });
            $( "#price-min" ).val( $( "#slider-range" ).slider( "values", 0 ));
            $( "#price-max" ).val( $( "#slider-range" ).slider( "values", 1 ));
    })
</script>
<style>
    .ui-slider {
        background: #4D4D4D;
        border: 1px solid #ccc; 
        height: 7px; 
        border-radius: 5px; 
    }

    .ui-slider-handle {
        background: white !important; 
        border: 3px solid #FF4176 !important; 
        width: 15px !important; 
        height: 15px !important;
        border-radius: 50% !important; 
        cursor: pointer !important; 
    }

    /* Выбранный диапазон */
    .ui-slider-range {
        background: #FF4176; /* Цвет диапазона */
        border-radius: 5px; /* Скругленные края диапазона */
    }
</style>