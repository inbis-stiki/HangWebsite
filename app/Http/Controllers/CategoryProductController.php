<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    public function index(){
        $data['title']      = "Kategori Produk";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "category-product";
        return view('master.category_product', $data);
    }
}
