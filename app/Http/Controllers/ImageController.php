<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImageController extends Controller
{
    public function create(){
        return view('images.create');
    }

    public function store(Request $req){
        $path = $req->file('image')->store('images', 's3');

        $image = Image::create([
            'filename' => basename($path),
            'url'      => Storage::disk('s3')->url($path)
        ]);

        return $image;
    }

    public function excel(){      
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->getActiveSheet();
        $ObjSheet->setTitle("GENERAL");

        $fileName = 'Testing';
        $new_name = time().md5($fileName);
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $new_name . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        Storage::disk('s3')->put('images/'.$new_name.'.xlsx', $content);
        Image::create([
            'url'      => 'https://finna.is3.cloudhost.id/images/'.$new_name.'.xlsx'
        ]);
        ob_end_clean();
        
        // Storage::disk('local')->put("Testing.xlsx", $content); 
    
        
    }

    public function show(Image $img){
        
    }
}
