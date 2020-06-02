<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";
    //khóa chính là id thì ko cần phải viết lại
    //loc cac trường còn lại của bảng
    //Model (ORM)
    public $fillable = [
        "category_name",
        "category_image"
    ];
    public function getImage(){
        if (is_null($this->__get("category_image"))){
            return asset("media/category.jpeg");
        }
        return asset($this->__get("category_image"));
    }

//    public function get($key)
//    {
//        if (is_null($this->get($key)))
//            return "default value";
//        return $this->get($key);
//    }

    public function Products(){
            return $this ->hasMany("\App\Product"); //tra ve 1 collection

    }
    //version 5.8
    public function getRouteKeyName()
    {
        return "slug";
    }

    public function getCategoryUrl(){
        return url("/category/{$this->__get("slug")}");
    }
}
