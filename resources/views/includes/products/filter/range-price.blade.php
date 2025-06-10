<div class="flex gap-3 flex-col range-price-container">
    <h1>{{ __('template.price') }} ({{ session()->has('currency') ? session()->get('currency')['iso_alpha'] : '' }})</h1>
    <div class="flex flex-col gap-2 !items-center">
        <div class="flex w-100 items-center h-10 mb-1">
            <input type="text" name="min" class="price-min w-50 border !h-full !rounded-l-lg !m-0 !p-0 !pl-4" value="{{ request('min', '') }}">
            <input type="text" name="max" class="price-max w-50 border !h-full !rounded-r-lg !m-0 !p-0 !pl-4" value="{{ request('max', '') }}">
        </div>
        <div class="slider-range w-full"></div>
    </div>
</div>


<style>
    .ui-slider {
        background: #4D4D4D;
        border: 1px solid #ccc; 
        height: 7px; 
        border-radius: 5px; 
    }

    .ui-slider-handle {
        background: white !important; 
        border: 3px solid #3b82f6 !important; 
        width: 15px !important; 
        height: 15px !important;
        border-radius: 50% !important; 
        cursor: pointer !important; 
    }

    /* Выбранный диапазон */
    .ui-slider-range {
        background: #3b82f6; /* Цвет диапазона */
        border-radius: 5px; /* Скругленные края диапазона */
    }
</style>