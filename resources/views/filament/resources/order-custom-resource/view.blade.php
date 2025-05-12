<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Товары в заказе</h2>
    <table class="table-auto w-full border mb-8">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Имя</th>
                <th class="px-4 py-2 text-left">Цена</th>
                <th class="px-4 py-2 text-left">Количество</th>
                <th class="px-4 py-2 text-left">Ссылка</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td class="border px-4 py-2">{{ $item->product->name ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $item->unit_price }}</td>
                    <td class="border px-4 py-2">{{ $item->qty }}</td>
                    <td class="border px-4 py-2">
                        @if($item->product)
                            <a href="{{ route('shop.card', ['slug' => $item->product->slug, 'id' => $item->product->id]) }}" target="_blank" class="text-primary-600 underline">Открыть</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-gray-400 py-4">Нет товаров в корзине</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mb-4">
        <strong>Статус заказа:</strong> {{ $record->status }}<br>
        <strong>Комментарии:</strong> {{ $record->comments }}<br>
        <strong>Сумма заказа:</strong> {{ $record->total }}
    </div>
</x-filament::page> 