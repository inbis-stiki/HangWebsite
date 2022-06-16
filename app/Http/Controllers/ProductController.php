<?php

namespace App\Http\Controllers;

use App\Product;
use App\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        $data['title']      = "Produk";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "category-product";
        $data['product']      = Product::join('md_product_category', 'md_product.ID_PC', '=', 'md_product_category.ID_PC')
        ->get(['md_product.*', 'md_product_category.NAME_PC']);
        $data['dropdown_category_product'] = CategoryProduct::all();
        return view('master.product.product', $data);
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'name_product'      => 'required',
            'code_product'      => 'required|unique:md_product,CODE_PRODUCT',
            'category_product'    => 'required',
            'status'                => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/product')->withErrors($validator);
        }

        date_default_timezone_set("Asia/Bangkok");

        $cek = Product::where('CODE_PRODUCT', '=', $req->input('code_product'))->exists();
        if ($cek == true) {
            return redirect('master/product')->with('err_msg', 'Kode produk sudah ada!');
        }else{
            $product                        = new Product();
            $product->NAME_PRODUCT          = $req->input('name_product');
            $product->CODE_PRODUCT          = $req->input('code_product');
            $product->ID_PC                 = $req->input('category_product');
            $product->deleted_at            = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
            $product->save();

            return redirect('master/product')->with('succ_msg', 'Berhasil menambah data produk!');
        }
    }

    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'name_product'      => 'required',
            'code_product'      => 'required',
            'category_product'    => 'required',
            'status'                => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/product')->withErrors($validator);
        }

        date_default_timezone_set("Asia/Bangkok");
        $product = Product::find($req->input('id'));
        $cek = $product->CODE_PRODUCT == strtoupper($req->input('code_product')); 
        if ($cek == true) {
            $product->NAME_PRODUCT          = $req->input('name_product');
            $product->CODE_PRODUCT          = strtoupper($req->input('code_product'));
            $product->ID_PC                 = $req->input('category_product');
            $product->deleted_at            = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
            $product->save();

            return redirect('master/product')->with('succ_msg', 'Berhasil mengubah data produk!');
        }else{
            $cek_code = Product::where('CODE_PRODUCT', '=', $req->input('code_product'))->exists();
            if ($cek_code == true) {
                return redirect('master/product')->with('err_msg', 'Kode produk sudah ada!');
            }else{
                $product->NAME_PRODUCT          = $req->input('name_product');
                $product->CODE_PRODUCT          = strtoupper($req->input('code_product'));
                $product->ID_PC                 = $req->input('category_product');
                $product->deleted_at            = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
                $product->save();

                return redirect('master/product')->with('succ_msg', 'Berhasil mengubah data produk!');
            }
        }
    }

    public function destroy(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/product')->withErrors($validator);
        }

        $product = Product::find($req->input('id'));
        $product->delete();

        return redirect('master/product')->with('succ_msg', 'Berhasil menghapus data produk!');
    }
}
