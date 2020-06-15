<?php

namespace App\Listeners;

use App\Cart;
use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CleanCart
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event -> order;
        session()->forget(["my_cart"]);// xoa session
        Cart::where("user_id",$order->__get("user_id"))
            ->update([// chuyen checkout ve false
            "is_checkout"=>false
        ]);
    }
}
