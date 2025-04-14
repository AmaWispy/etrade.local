@php
    use App\Models\Shop\Follow;
    use Illuminate\Support\Facades\Cookie;
    use App\Models\Shop\FollowItems;
    
    $followCode = Cookie::get('follow');
    $follow = Follow::where('code', $followCode)->first();

    if(Follow::where('code', $followCode)->first()){
        $isFollow =  FollowItems::where('follow_id', $follow['id'])->where('shop_product_id', $product['id'])->first() ?? null;
    } else{
        $isFollow = null;
    }
@endphp
<div>
    @if($isFollow !==  null)
        <div 
            class=" text-center bg-white py-2 px-[12px] rounded-md flex justify-center items-center"
            x-on:click="follow = !follow">
            <a 
                @guest
                    href="{{ route('register') }}"
                @endguest
                @auth
                    href="#" 
                    data-action="add-to-follow"
                    data-product="{{$product->id}}"
                    data-type="{{$product->type}}"
                @endauth
                x-bind:class="follow ? 'bg-none text-black  xl:hover:text-red-500' : 'text-red-500 xl:hover:text-black  '"
                class="text-md">
                <i 
                    class="bi bi-heart-fill"></i>
            </a>
        </div>
    @else
        <div
            class="rounded-md py-2 px-[12px] bg-white text-center flex justify-center items-center"
            x-on:click="follow = !follow">
            <a 
                @guest
                    href="{{ route('register') }}"
                @endguest
                @auth
                    href="#" 
                    data-action="add-to-follow"
                    data-product="{{$product->id}}"
                    data-type="{{$product->type}}"
                @endauth
                x-bind:class="follow ? '!text-red-500 xl:hover:text-black' : ' text-black xl:hover:text-red-500'"
                class="text-md text-center">
                <i 
                class="bi bi-heart-fill"></i>
            </a>
        </div>
    @endif
</div>