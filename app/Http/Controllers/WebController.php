<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Product;
use Carbon\Carbon;
use DemeterChain\C;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebController extends Controller
{
    public function register()
    {
        return view("register");
    }

    public function login()
    {
        return view("login");
    }


    public function index()
    {
        return view("home");
    }

    public function dashboard(){
        return view("dashboard");
    }

    public function listCategory()
    {
        //lay tat ca
        $category = Category::withCount("Products")->paginate(20);
        //show validation theo ten D%
        //  $category =Category::where ("category_name", "LIKE", "D%")->get();
        return view("category.list", ["categories" => $category]);
        //
    }

    public function newCategory()
    {
        return view("category.new");
    }

    public function saveCategory(Request $request)
    {
        //validate du lieu
        $request->validate([
            "category_name" => "required|string|min:6|unique:categories"
        ]);
        try {
            $categoryImage = null;
            // xử lý để đưa ảnh lên media trong public sau đó lấy nguồn file cho vào biến $product
            if($request->hasFile("category_image")){ // nếu request gửi lên có file product_image là inputname
                $file = $request->file("category_image"); // trả về 1 đối tượng lấy từ request của input
                // lấy tên file
                // thêm time() để thay đổi thời gian upload ảnh lên để không bị trùng ảnh với nhau
                $allow = ["png","jpg","jpeg","gif"];
                $extName = $file->getClientOriginalExtension();
                if(in_array($extName,$allow)){ // nếu đuôi file gửi lên nằm trong array
                    $fileName = time().$file->getClientOriginalName(); //  lấy tên gốc original của file gửi lên từ client
                    $file->move(public_path("media"),$fileName); // đẩy file vào thư mục media với tên là fileName
                    //convert string to ProductImage
                    $categoryImage = "media/".$fileName; // lấy nguồn file
                }
            }
//tự động cập nhật thời gian cho category
            Category::create([
                "category_name" => $request->get("category_name"),
                "category_image" =>$categoryImage
            ]);

            // "updated_at"=>Carbon::now(),
            //            DB::table("categories") ->insert([
//                "category_name" =>$request->get("category_name"),
//                "created_at"=>Carbon::now(),
//
        } catch (\Exception $exception) {
            return redirect()->back();
        }
        return redirect()->to("/admin/list-category");
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
//        if (is_null($category))
//            abort(404); =findOrFail
        return view("category.edit", ["category" => $category]);
    }

    public function updateCategory($id, Request $request)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            "category_name" => "required|min:6|unique:categories,category_name,{$id}"
        ]);
        // die("loi");
        //      dd($request->all());
        try {
            $categoryImage = $category->get("category_image");
            // xử lý để đưa ảnh lên media trong public sau đó lấy nguồn file cho vào biến $product
            if($request->hasFile("category_image")){ // nếu request gửi lên có file product_image là inputname
                $file = $request->file("category_image"); // trả về 1 đối tượng lấy từ request của input
                // lấy tên file
                // thêm time() để thay đổi thời gian upload ảnh lên để không bị trùng ảnh với nhau
                $allow = ["png","jpg","jpeg","gif"];
                $extName = $file->getClientOriginalExtension();
                if(in_array($extName,$allow)){ // nếu đuôi file gửi lên nằm trong array
                    $fileName = time().$file->getClientOriginalName(); //  lấy tên gốc original của file gửi lên từ client
                    $file->move(public_path("media"),$fileName); // đẩy file vào thư mục media với tên là fileName
                    //convert string to brandImage
                    $categoryImage = "media/".$fileName; // lấy nguồn file
//                    dd($brandImage);
                }
            }
            $category->update([
                "category_name" => $request->get("category_name"),
                "category_image" =>$categoryImage
            ]);
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return redirect()->back();
        }
        return redirect()->to("/admin/list-category");
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        try {
            $category->delete();
        } catch (\Exception $exception) {
            return redirect()->back();
        }
        return redirect()->to("/admin/list-category");
    }


    public function listBrand()
    {
        //lay tat ca
        $brand = Brand::paginate(20);
        //show validation theo ten D%
        //  $category =Category::where ("category_name", "LIKE", "D%")->get();
        return view("brand.list", ["brands" => $brand]);
        //
    }

    public function newBrand()
    {
        return view("brand.new");
    }

    public function saveBrand(Request $request)
    {
        //validate du lieu
        $request->validate([
            "brand_name" => "required|string|min:6|unique:brands"
        ]);
        try {
            // bắt lỗi nếu không có = null
            $brandImage = null;
            // xử lý để đưa ảnh lên media trong public sau đó lấy nguồn file cho vào biến $product
            if($request->hasFile("brand_image")){ // nếu request gửi lên có file product_image là inputname
                $file = $request->file("brand_image"); // trả về 1 đối tượng lấy từ request của input
                // lấy tên file
                // thêm time() để thay đổi thời gian upload ảnh lên để không bị trùng ảnh với nhau
                $allow = ["png","jpg","jpeg","gif"];
                $extName = $file->getClientOriginalExtension();
                if(in_array($extName,$allow)){ // nếu đuôi file gửi lên nằm trong array
                    $fileName = time().$file->getClientOriginalName(); //  lấy tên gốc original của file gửi lên từ client
                    $file->move(public_path("media"),$fileName); // đẩy file vào thư mục media với tên là fileName
                    //convert string to ProductImage
                    $brandImage = "media/".$fileName; // lấy nguồn file
                }
            }
//tự động cập nhật thời gian cho category
            Brand::create([
                "brand_name" => $request->get("brand_name"),
                "brand_image" =>$brandImage
            ]);

            // "updated_at"=>Carbon::now(),
            //            DB::table("categories") ->insert([
//                "category_name" =>$request->get("category_name"),
//                "created_at"=>Carbon::now(),
//
        } catch (\Exception $exception) {
            return redirect()->back();
        }
        return redirect()->to("/admin/list-brand");
    }

    public function editBrand($id)
    {
        $brand = Brand::findOrFail($id);
//        if (is_null($brand))
//            abort(404); =findOrFail
        return view("brand.edit", ["brand" => $brand]);
    }

    public function updateBrand($id, Request $request)
    {
        $brand = Brand::findOrFail($id);
        $request->validate([
            "brand_name" => "required|min:6|unique:brands,brand_name,{$id}"
        ]);
//         die("loi");
//              dd($brand);
        try {
            // bắt lỗi nếu không có = null
//            $brandImage = $request->get("brand_image");
            $brandImage = $brand->get("brand_image");
            // xử lý để đưa ảnh lên media trong public sau đó lấy nguồn file cho vào biến $product
            if($request->hasFile("brand_image")){ // nếu request gửi lên có file product_image là inputname
                $file = $request->file("brand_image"); // trả về 1 đối tượng lấy từ request của input
                // lấy tên file
                // thêm time() để thay đổi thời gian upload ảnh lên để không bị trùng ảnh với nhau
                $allow = ["png","jpg","jpeg","gif"];
                $extName = $file->getClientOriginalExtension();
                if(in_array($extName,$allow)){ // nếu đuôi file gửi lên nằm trong array
                    $fileName = time().$file->getClientOriginalName(); //  lấy tên gốc original của file gửi lên từ client
                    $file->move(public_path("media"),$fileName); // đẩy file vào thư mục media với tên là fileName
                    //convert string to brandImage
                    $brandImage = "media/".$fileName; // lấy nguồn file
//                    dd($brandImage);
                }
            }
            $brand->update([
                "brand_name" => $request->get("brand_name"),
                "brand_image" => $brandImage
            ]);
        } catch (\Exception $exception) {
//            dd($exception->getMessage());
            return redirect()->back();
        }
        return redirect()->to("/admin/list-brand");
    }

    public function deleteBrand($id)
    {
        $brand = Brand::findOrFail($id);
        try {
            $brand->delete();
        } catch (\Exception $exception) {
            return redirect()->back();
        }
        return redirect()->to("/admin/list-brand");
    }

    public function listProduct(){
//            $product = Product::paginate(20);
//        $product = Product::leftjoin("categories","categories.id","=","products.category_id")
//            ->leftjoin("brands","brands.id","=","products.brand_id")
//            ->select("products.*","categories.category_name","brands.brand_name")->paginate(20);
        $product = Product::with("Category")->with("Brand")->paginate(20);
//        dd($product);
        return view("product.list",["products"=>$product]); // string la mang cac product bien duoc gui sang lam bien dau tien cua forech

    }
    public function newProduct(){
        // phai lay du lieu tu cac bang phu
        $category = Category::all();
        $brand = Brand::all();
        return view("product.new",[
                "categories"=>$category,
                "brands" => $brand,
            ]
        );
    }
    public function saveProduct(Request $request){ // tạo biến request lưu dữ liệu người dùng gửi lên ở body
        // đầu tiên phải validate dữ liệu cả bên html và bên sever
        // cách validate
        $request->validate([
            "product_name" => "required",
            "product_desc" => "required",
            "price" => "required|numeric|min:0",
            "qty" => "required|numeric|min:1",
            "category_id" => "required",
            "brand_id" => "required",
        ]);
        try {
            // bắt lỗi nếu không có = null
            $productImage = null;
            // xử lý để đưa ảnh lên media trong public sau đó lấy nguồn file cho vào biến $product
            if($request->hasFile("product_image")){ // nếu request gửi lên có file product_image là inputname
                $file = $request->file("product_image"); // trả về 1 đối tượng lấy từ request của input
                // lấy tên file
                // thêm time() để thay đổi thời gian upload ảnh lên để không bị trùng ảnh với nhau
                $allow = ["png","jpg","jpeg","gif"];
                $extName = $file->getClientOriginalExtension();
                if(in_array($extName,$allow)){ // nếu đuôi file gửi lên nằm trong array
                    $fileName = time().$file->getClientOriginalName(); //  lấy tên gốc original của file gửi lên từ client
                    $file->move(public_path("media"),$fileName); // đẩy file vào thư mục media với tên là fileName
                    //convert string to ProductImage
                    $productImage = "media/".$fileName; // lấy nguồn file
                }
            }
            Product::create([
                "product_name" => $request->get("product_name"),
                "product_image" =>$productImage,
                "product_desc" => $request->get("product_desc"),
                "price" => $request->get("price"),
                "qty" => $request->get("qty"),
                "category_id" => $request->get("category_id"),
                "brand_id" => $request->get("brand_id"),
            ]);
        }catch (\Exception $exception){
            return redirect()->back();
        }
        return redirect()->to("/admin/list-product");
    }

    public function editProduct($id, Request $request){
        $category = Category::all();
        $brand = Brand::all();
        $product = Product::findOrFail($id);
        return view("product.edit",[
            "categories"=>$category,
            "brands" => $brand,
            "product" => $product]);
    }
    public function deleteProduct($id){
        $product = Product::findorFail($id);
        try {
            $product->delete();
        }catch (\Exception $exception){
            return redirect()->back();
        }
        return redirect()->to("/admin/list-product");
    }
    public function updateProduct($id,Request $request){
        $product = Product::findOrFail($id);
        $request->validate([ // unique voi categories(table) category_name(truong muon unique), (id khong muon bi unique)
            "product_name" => "required|min:3|unique:products,product_name,{$id}",
            "product_desc" => "required",
            "price" => "required|numeric|min:0",
            "qty" => "required|numeric|min:1",
            "category_id" => "required",
            "brand_id" => "required",
        ]);
        try{
            $productImage = $product->get("product_image");
            // xử lý để đưa ảnh lên media trong public sau đó lấy nguồn file cho vào biến $product
            if($request->hasFile("product_image")){ // nếu request gửi lên có file product_image là inputname
                $file = $request->file("product_image"); // trả về 1 đối tượng lấy từ request của input
                // lấy tên file
                // thêm time() để thay đổi thời gian upload ảnh lên để không bị trùng ảnh với nhau
                $allow = ["png","jpg","jpeg","gif"];
                $extName = $file->getClientOriginalExtension();
                if(in_array($extName,$allow)){ // nếu đuôi file gửi lên nằm trong array
                    $fileName = time().$file->getClientOriginalName(); //  lấy tên gốc original của file gửi lên từ client
                    $file->move(public_path("media"),$fileName); // đẩy file vào thư mục media với tên là fileName
                    //convert string to ProductImage
                    $productImage = "media/".$fileName; // lấy nguồn file
                }
            }
            $product->update([
                "product_name" => $request->get("product_name"),
                "product_image" => $productImage,
                "product_desc" => $request->get("product_desc"),
                "price" => $request->get("price"),
                "qty" => $request->get("qty"),
                "category_id" => $request->get("category_id"),
                "brand_id" => $request->get("brand_id"),
            ]);
        }catch(\Exception $exception){
            return redirect()->back();
        }
        return redirect()->to("/admin/list-product");
    }

}
