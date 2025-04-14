<?php

namespace App\Livewire;

use App\Models\Page\Page;
use Livewire\Component;
use App\Models\Shop\Cart;

class CartTotalHeader extends Component
{

    public $total;

    public function mount(){
        if(session()->has('cart')){
            $cart = Cart::where('code', session()->get('cart')['code'])->first();
            $total = $cart->getTotal();
            $this->total = $total;
        } else{
            $this->total = 0;
        }
        
    }
    public function render()
    {
        return view('livewire.cart-total-header');
    }
}
