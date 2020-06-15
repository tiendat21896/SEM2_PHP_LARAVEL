<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Order;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class MailToUserAfterOrderCreated
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
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event -> order;
        // Lay Email
        $user = User::find($order->__get("user_id"));
        try{
            Mail::to($user->__get("email"))->send(new \App\Mail\MailToUserAfterOrderCreated($user));
        }catch (Exception $exception){

        }
    }
}
