<?php

namespace App\Http\Controllers;

use App\Product;
use App\logmd;
use App\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Session;

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
            'category_product'  => 'required',
            'status'            => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/product')->withErrors($validator);
        }

        $cek = Product::where('CODE_PRODUCT', '=', $req->input('code_product'))->exists();
        if ($cek == true) {
            return redirect('master/product')->with('err_msg', 'Kode produk sudah ada!');
        }else{
            $path = $req->file('image')->store('images', 'r2');

            $product                        = new Product();
            $product->NAME_PRODUCT          = $req->input('name_product');
            $product->CODE_PRODUCT          = $req->input('code_product');
            $product->ID_PC                 = $req->input('category_product');
            $product->ORDER_GROUPING         = $req->input('order_product');
            $product->IMAGE_PRODUCT         = Storage::disk('r2')->url($path);
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

        if (!empty($req->file('image'))) {
            $path = $req->file('image')->store('images', 'r2');
        }
        $product = Product::find($req->input('id'));

        $oldValues = $product->getOriginal();

        $cek = $product->CODE_PRODUCT == strtoupper($req->input('code_product')); 
        if ($cek == true) {
            $product->NAME_PRODUCT          = $req->input('name_product');
            $product->CODE_PRODUCT          = strtoupper($req->input('code_product'));
            $product->ID_PC                 = $req->input('category_product');
            $product->ORDER_GROUPING         = $req->input('order_product');
            if (!empty($req->file('image'))) {
                $product->IMAGE_PRODUCT     = Storage::disk('r2')->url($path);
            }
            $product->deleted_at            = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');

            $changedFields = array_keys($product->getDirty());
            $product->save();
    
            $newValues = [];
            foreach($changedFields as $field) {
                $newValues[$field] = $product->getAttribute($field);
            }
    
            $id_userU = SESSION::get('id_user');
    
            if (!empty($newValues)) {
                DB::table('log_md')->insert([
                    'UPDATED_BY' => $id_userU,
                    'DETAIL' => 'Updating Product ' . (string)$req->input('id'),
                    'OLD_VALUES' => json_encode(array_intersect_key($oldValues, $newValues)),
                    'NEW_VALUES' => json_encode($newValues),
                    'log_time' => now(),
                ]);
            } 

            return redirect('master/product')->with('succ_msg', 'Berhasil mengubah data produk!');
        }else{
            $cek_code = Product::where('CODE_PRODUCT', '=', $req->input('code_product'))->exists();
            if ($cek_code == true) {
                return redirect('master/product')->with('err_msg', 'Kode produk sudah ada!');
            }else{
                $product->NAME_PRODUCT          = $req->input('name_product');
                $product->CODE_PRODUCT          = strtoupper($req->input('code_product'));
                $product->ID_PC                 = $req->input('category_product');
                $product->ORDER_GROUPING         = $req->input('order_product');
                if (!empty($req->file('image'))) {
                    $product->IMAGE_PRODUCT     = Storage::disk('r2')->url($path);
                }
                $product->deleted_at            = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
                $product->save();

                $id_userU = SESSION::get('id_user');
                $log                    = new logmd();
                $log->UPDATED_BY        = $id_userU;
                $log->DETAIL            = 'Updating Product ' . (string)$req->input('id'); 
                $log->log_time          = now();
                $log->save();   

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

    public function UploadFileR2($fileData, $folder)
    {
        $extension = $fileData->getClientOriginalExtension();
        $fileName = $fileData->getClientOriginalName();

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        $path = $folder . '/' . hash('sha256', $fileName) . $randomString . '.' . $extension;

        $s3 = Storage::disk('r2')->getDriver()->getAdapter()->getClient();
        $bucket = config('filesystems.disks.r2.bucket');

        $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $path,
            'SourceFile' => $fileData->path(),
            'ACL' => 'public-read',
            'ContentType' => $fileData->getMimeType(),
            'ContentDisposition' => 'inline; filename="' . $fileName . '"',
        ]);
        
        return 'https://finna.yntkts.my.id/' . $path;
    }
}
