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
        $categories = Category::orderBy("created_at","ASC")->get();
        $featureds = Product::orderBy("updated_at","DESC")->limit(8)->get();
        $latest_1 = Product::orderBy("created_at","DESC")->limit(3)->get();
        $latest_2 = Product::orderBy("created_at","DESC")->offset(3)->limit(3)->get();
        return view("frontend.home",[
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
        $relativeProducts = Product::with("Category")->paginate(4);
        return view("frontend.product",[
            "product"=>$product,
            "relativeProducts"=>$relativeProducts
        ]);
    }
}
