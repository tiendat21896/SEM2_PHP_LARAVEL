<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Category;
use App\Events\OrderCreated;
use App\Order;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use function GuzzleHttp\Psr7\str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::all();
        foreach ($products as $p){
            $slug = \Illuminate\Support\Str::slug($p->__get("product_name"));
            $p->slug = $slug.$p->__get("id");// luu lai vao DB
            $p->save();
            // tuong duong $p->update(["slug"=>$slug.$p->__get("id")]);
        }
        if (!Cache::has("home_page")){
            $categories = Category::all();
            $most_view = Product::orderBy("viewer_count","DESC")->limit(8)->get();
            $featureds = Product::orderBy("updated_at","DESC")->limit(8)->get();
            $latest_1 = Product::orderBy("created_at","DESC")->limit(3)->get();
            $latest_2 = Product::orderBy("created_at","DESC")->offset(3)->limit(3)->get();
            $view =  view("frontend.home",[
                "categories" => $categories,
                "most_view"=>$most_view,
                "featureds" =>$featureds,
                "latest_1" => $latest_1,
                "latest_2" => $latest_2,
            ])->render();
            $now = Carbon::now();
            Cache::put("home_page",$view,$now->addMinute(20));
        }
        return Cache::get("home_page");
    }

    public function category(Category $category){
        $products = $category->Products()->paginate(12);
        // dung trong model de lay tat ca\
        return view("frontend.category",[
            "category"=>$category,
            "products"=>$products
        ]);
    }

    public function product(Product $product){
        if(!session()->has("viewer_count_{$product->__get("id")}"))
        {
            $product->increment("viewer_count");
            session(["viewer_count_{$product->__get("id")}"=>true]);
        }
        // Tang them 1 moi khi nguoi dung vao xem sp
        $relativeProducts = Product::with("Category")->paginate(4);
        return view("frontend.product",[
            "product"=>$product,
            "relativeProducts"=>$relativeProducts
        ]);
    }
    public function addToCart(Product $product,Request $request){
        $qty = $request->has("qty")&& (int)$request->get("qty")>0?(int)$request->get("qty"):1;
        $myCart = session()->has("my_cart")&& is_array(session("my_cart"))?session("my_cart"):[];
        $contain = false;
        if(Auth::check()){
            if(Cart::where("user_id",Auth::id())->where("is_checkout",true)->exists()){
                $cart = Cart::where("user_id",Auth::id())->where("is_checkout",true)->first();
            }else{
                $cart = Cart::create([
                    "user_id"=> Auth::id(),
                    "is_checkout"=>true
                ]);
            }
        }
        foreach ($myCart as $key=>$item){
            if($item["product_id"] == $product->__get("id")){
                $myCart[$key]["qty"] += $qty;
                $contain = true;
                if(Auth::check()) {
                    DB::table("cart_product")->where("cart_id", $cart->__get("id"))
                        ->where("product_id", $item["product_id"])
                        ->update(["qty" => $myCart[$key]["qty"]]);
                }
                break;
            }
        }
        if(!$contain){
            $myCart[] = [
                "product_id" => $product->__get("id"),
                "qty" => $qty
            ];
            if(Auth::check()) {
                DB::table("cart_product")->insert([
                    "qty" => $qty,
                    "cart_id" => $cart->__get("id"),
                    "product_id" => $product->__get("id")
                ]);
            }
        }
        session(["my_cart"=>$myCart]);

        return redirect()->to("/shopping-cart");
    }

    public function shoppingCart(){
        $myCart = session()->has("my_cart") && is_array(session("my_cart"))?session("my_cart"):[];
        $productIds = [];
        foreach ($myCart as $item){
            $productIds[] = $item["product_id"];
        }
        $grandTotal = 0;
        $products = \App\Product::find($productIds);
        foreach ($products as $p){
            foreach ($myCart as $item){
                if($p->__get("id") == $item["product_id"]){
                    $grandTotal += ($p->__get("price")*$item["qty"]);
                    $p->cart_qty = $item["qty"];
                }
            }
        }
        return view("frontend.cart",[
           "products"=>$products,
           "grandTotal"=>$grandTotal
        ]);
    }
    public function checkout(){
        $cart = Cart::where("user_id",Auth::id())
            ->where("is_checkout",true)
            ->with("getItems")
            ->firstOrFail();
        return view("frontend.checkout",[
            "cart"=>$cart
        ]);
    }
    public function placeOrder(Request $request){
        $request->validate([
            "username"=>"required",
            "address"=>"required",
            "telephone"=>"required",
        ]);
        $cart = Cart::where("user_id",Auth::id())
            ->where("is_checkout",true)
            ->with("getItems")
            ->firstOrFail();
        $grandTotal = 0;
        foreach ($cart->getItems as $item){
            $grandTotal+= $item->pivot->__get("qty")*$item->__get("price");
        }
        try{
            $order = Order::create([
                "user_id"=>Auth::id(),
                "username"=>$request->get("username"),
                "address"=>$request->get("address"),
                "telephone"=>$request->get("telephone"),
                "note"=>$request->get("note"),
                "grand_total"=>$grandTotal,
                "status"=> Order::PENDING
            ]);
            foreach ($cart->getItems as $item){
                DB::table("orders_products")->insert([
                    "order_id"=>$order->__get("id"),
                    "product_id"=>$item->__get("id"),
                    "price" => $item->__get("price"),
                    "qty"=> $item->pivot->__get("qty")
                ]);
            }
            event(new OrderCreated($order));

        }catch (\Exception $exception){

        }
        return redirect()->to("/");
    }
}
