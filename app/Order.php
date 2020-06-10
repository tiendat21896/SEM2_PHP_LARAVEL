<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $table = "orders";

   protected  $fillable = [
       "user_id".
       "grand_total"
   ];
   public const PENDING = 0;
   public const PROCESS = 1;
   public const SNIPPING = 2;
   public const COMPLETE = 3;
   public const CANCEL = 4;
}