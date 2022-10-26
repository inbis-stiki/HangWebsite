<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryProductController extends Controller
{
    public function index(){
        $data['title']      = "Kategori Produk";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "category-product";
        $data['category_product']      = CategoryProduct::all();
        $data['total_persen']   =   CategoryProduct::where('deleted_at', null)->sum('PERCENTAGE_PC');

        return view('master.product.category_product', $data);
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'category_product'      => 'required',
            'percentage_product'    => 'required',
            'status'                => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/category-product')->withErrors($validator);
        }

        

        $data['total_persen']   =   CategoryProduct::where('deleted_at', null)->sum('PERCENTAGE_PC');
        $total  = $data['total_persen']+$req->input('percentage_product');

        if ($data['total_persen'] == 100) {
            return redirect('master/category-product')->with('err_msg', 'Persentase kategori produk sudah 100%!');
        }else{
            if ($total > 100) {
                return redirect('master/category-product')->with('err_msg', 'Persentase kategori produk melebihi persentase, mohon kurangi persentase saat input!');
            }else{
                $category_product                   = new CategoryProduct();
                $category_product->NAME_PC          = $req->input('category_product');
                $category_product->PERCENTAGE_PC    = $req->input('percentage_product');
                $category_product->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');    
                try {
                    $category_product->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    $errorCode = $e->errorInfo[1];
                    if($errorCode == '1062'){
                        return redirect('master/category-product')->with('err_msg', 'Kategori Produk tidak boleh sama!');
                    }
                }
                return redirect('master/category-product')->with('succ_msg', 'Berhasil menambah data kategori produk!');
            }    
        }
    }

    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'category_product'      => 'required',
            'percentage_product'    => 'required',
            'status'                => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/category-product')->withErrors($validator);
        }

        
        $total   =   CategoryProduct::where('deleted_at', null)
        ->whereNotIn('ID_PC', [$req->input('id')])
        ->sum('PERCENTAGE_PC');
        
        $cek = $total+$req->input('percentage_product');
        if ($cek > 100) {
            return redirect('master/category-product')->with('err_msg', 'Persentase kategori produk melebihi persentase, mohon kurangi persentase saat input!');
        }else{
            $category_product = CategoryProduct::find($req->input('id'));
            $category_product->NAME_PC          = $req->input('category_product');
            $category_product->PERCENTAGE_PC    = $req->input('percentage_product');
            $category_product->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
            $category_product->save();

            return redirect('master/category-product')->with('succ_msg', 'Berhasil mengubah data kategori produk!');
        }
    }

    public function destroy(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/category-product')->withErrors($validator);
        }

        $category_product = CategoryProduct::find($req->input('id'));
        $category_product->delete();

        return redirect('master/category-product')->with('succ_msg', 'Berhasil menghapus data kategori produk!');
    }

}
