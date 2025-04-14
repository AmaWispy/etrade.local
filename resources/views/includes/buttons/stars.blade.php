<div class="flex gap-2" x-data='{ rating: 1 }'>
    <!-- Скрытое поле для рейтинга -->
    <input type="hidden" name="rating" id='rating' x-model="rating">

    <!-- Звезды -->
    @for ($i = 1; $i <= 5; $i++)
        <li>
            <button type="button" x-on:click="rating = {{ $i }}">
                <i x-show="rating < {{ $i }}" class="bi bi-star"></i>
                <i x-show="rating >= {{ $i }}" class="bi bi-star-fill text-yellow-400"></i>
            </button>
        </li>
    @endfor
</div>
