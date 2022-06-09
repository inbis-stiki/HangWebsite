<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    public function index(){
        $data['title']      = "Kategori Produk";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "category-product";
        $data['category_product']      = CategoryProduct::all();
        return view('master.category_product', $data);
    }
}
