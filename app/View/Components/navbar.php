<?php

namespace App\View\Components;

use App\Models\Cart;
use App\Models\Whilist;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class navbar extends Component
{
    public $num_carts = 0;

    public $num_whilists = 0;

    public $sum_all_carts = 0;

    public function __construct()
    {
        if (! Auth::guard('ecomm')->check()) {
            return;
        }

        $user_id = Auth::guard('ecomm')->user()->id;

        // cart
        $this->num_carts = Cart::where('user_id', $user_id)->count();

        $cart_user = Cart::where('user_id', $user_id)->with('product')->get();
        foreach ($cart_user as $cart) {
            if ($cart->product) {
                $this->sum_all_carts += $cart->product->price * $cart->count;
            }
        }

        // whilist
        $this->num_whilists = Whilist::where('user_id', $user_id)->count();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navbar');
    }
}
