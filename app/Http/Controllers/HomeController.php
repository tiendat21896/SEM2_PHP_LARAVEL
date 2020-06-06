<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
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
        $most_view = Product::orderBy("viewer_count","DESC")->limit(8)->get();
        $categories = Category::orderBy("created_at","ASC")->get();
        $featureds = Product::orderBy("updated_at","DESC")->limit(8)->get();
        $latest_1 = Product::orderBy("created_at","DESC")->limit(3)->get();
        $latest_2 = Product::orderBy("created_at","DESC")->offset(3)->limit(3)->get();
        return view("frontend.home",[
            "most_view"=>$most_view,
            "categories"=>$categories,
            "featureds" =>$featureds,
            "latest_1" => $latest_1,
            "latest_2" => $latest_2,
        ]);
    }

    public function category(Category $category){
//        $products = Product::where("category_id",$category->__get("id"))->paginate(12);
        $products = $category->Products()->paginate(12);
        // dung trong model de lay tat ca\
        return view("frontend.category",[
            "category"=>$category,
//            "categories"=>$categories// tra ve category trong front end
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
        $myCart = session()->has("my_cart") && is_array(session("my_cart"))?session("my_cart"):[];
        $contain = false;
        foreach ($myCart as $key=>$item){
            if ($item["product_id"] == $product->__get("id")){
                $myCart["$key"]["qty"] += $qty;
                $contain = true;
                break;
            }
        }
        if (!$contain){
            $myCart[] = [
                "product_id" =>$product->__get("id"),
                "qty" => $qty
            ];
        }
        session(["my_cart"=>$myCart]);
        return redirect()->to("/shopping-cart");
        // return redirect va trang truoc
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
        return view("frontend.checkout");
    }
}
