<div id="rating_product" class="flex items-center gap-1.5">
    <div id="rating_product_stars">
        @for ($i = 1; $i <= 5; $i++)
            <i class="bi bi-star{{ $i <= floor($rating) ? '-fill' : '' }} text-yellow-400"></i>
        @endfor
    </div>
    <ul>
        <li class="text-neutral-500 flex items-center gap-1">
            <h1>{{ '(' }}</h1>
            <h1 id="qnty_rating_users">{{ $ratingQntyUsers }}</h1>
            <h1>{{ ' ' . __('template.customer_reviews') }}</h1>
            <h1>{{ ')' }}</h1>
        </li>
    </ul>
</div>