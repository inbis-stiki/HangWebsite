<?php

namespace App\Http\Controllers;

use App\Imports\RegionalPriceImport;
use App\Product;
use App\Regional;
use App\RegionalPrice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RegionalPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title']              = "Harga Regional";
        $data['sidebar']            = "master";
        $data['sidebar2']           = "regionalprice";

        $data['regional_prices']    = RegionalPrice::all();
        $data['products']           = Product::all();
        $data['regionals']          = Regional::all();

        return view('master.regionalprice.regionalprice', $data);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'file_excel_template'       => 'required'
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);
        if ($validator->fails()) {
            return redirect('master/regionalprice')->withErrors($validator);
        }
        $file = $request->file('file_excel_template');
        // dd($file);
        $sheet      = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
        $highestRow = $sheet->getActiveSheet()->getHighestRow();
        $highestColumn = $sheet->getActiveSheet()->getHighestColumn();
        $arrSheet   = $sheet->getActiveSheet()->rangeToArray(
            'A9:' . $highestColumn . $highestRow
        );
        // dd($arrSheet);
        $arrs = [];
        foreach ($arrSheet as $arr) {
            if ($arr[1] == null) break;
            array_push($arrs, $arr);
        }
        // dd($arrs);
        foreach ($arrs as $arr) {
            $regional_price = new RegionalPrice();
            $product = Product::where('NAME_PRODUCT', '=', $arr[1])->first();
            $regional = Regional::where('NAME_REGIONAL', '=', $arr[3])->first();
            $regional_price->ID_PRODUCT      = $product->ID_PRODUCT;
            $regional_price->ID_REGIONAL     = $regional->ID_REGIONAL;
            $regional_price->PRICE_PP        = 200000;
            $regional_price->TARGET_PP       = 1;
            $regional_price->START_PP        = date('Y-m-d');
            $regional_price->END_PP        = date('Y-m-d');
            $regional_price->save();
        }
        // Excel::import(new RegionalPriceImport, $file->getRealPath());

        return redirect('master/regionalprice')->with('succ_msg', 'Berhasil menambahkan data harga regional!');

        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id'             => 'required',
            'product_edit'   => 'required',
            'regional_edit'  => 'required',
            'harga'          => 'required',
            'status'         => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);
        if ($validator->fails()) {
            return redirect('master/regionalprice')->withErrors($validator);
        }
        date_default_timezone_set("Asia/Bangkok");
        $regional_price = RegionalPrice::find($request->input('id'));
        $regional_price->ID_PRODUCT         = $request->input('product_edit');
        $regional_price->ID_REGIONAL        = $request->input('regional_edit');
        $regional_price->PRICE_PP           = $request->input('harga');
        $regional_price->DELETED_AT         = $request->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $regional_price->save();
        // dd($regional_price);
        return redirect('master/regionalprice')->with('succ_msg', 'Berhasil mengubah data harga regional!');
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'             => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);
        if ($validator->fails()) {
            return redirect('master/regionalprice')->withErrors($validator);
        }
        date_default_timezone_set("Asia/Bangkok");
        $regional_price = RegionalPrice::find($request->input('id'));
        $regional_price->delete();
        // dd($regional_price);
        return redirect('master/regionalprice')->with('succ_msg', 'Berhasil menghapus data harga regional!');
        //
    }
    public function download_template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $styleHeading1['font']['bold'] = true;
        $styleHeading1['font']['size'] = 20;

        $styleHeading2['font']['bold'] = true;
        $styleHeading2['font']['size'] = 14;

        $styleHeading3['font']['bold'] = true;
        $styleHeading3['font']['size'] = 12;

        $styleTitle['font']['bold']                         = true;
        $styleTitle['font']['size']                         = 11;
        $styleTitle['font']['color']['argb']                = \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE;
        $styleTitle['fill']['fillType']                     = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;
        $styleTitle['fill']['color']['argb']                = 'FF595959';
        $styleTitle['alignment']['horizontal']              = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
        $styleTitle['alignment']['vertical']                = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;
        $styleTitle['borders']['outline']['borderStyle']    = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;

        $styleTitle2['font']['bold']                         = true;
        $styleTitle2['font']['size']                         = 11;
        $styleTitle2['font']['color']['argb']                = \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE;
        $styleTitle2['fill']['fillType']                     = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;
        $styleTitle2['fill']['color']['argb']                = 'FF0070C0';
        $styleTitle2['alignment']['horizontal']              = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
        $styleTitle2['alignment']['vertical']                = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;
        $styleTitle2['borders']['outline']['borderStyle']    = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;

        $styleContent['font']['size']                         = 11;
        $styleContent['borders']['outline']['borderStyle']    = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
        $styleContent['alignment']['vertical']                = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;

        $styleContentCenter['font']['size']                         = 11;
        $styleContentCenter['borders']['outline']['borderStyle']    = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
        $styleContentCenter['alignment']['horizontal']              = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
        $styleContentCenter['alignment']['vertical']                = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;

        $styleContentCenterBold['font']['size']                         = 11;
        $styleContentCenterBold['font']['bold']                         = true;
        $styleContentCenterBold['borders']['outline']['borderStyle']    = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
        $styleContentCenterBold['alignment']['horizontal']              = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
        $styleContentCenterBold['alignment']['vertical']                = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;
        $sheet->getColumnDimension('A')->setWidth('3');
        $sheet->getColumnDimension('B')->setWidth('30');
        $sheet->getColumnDimension('C')->setWidth('20');
        $sheet->getColumnDimension('D')->setWidth('25');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo Finna');
        $drawing->setDescription('Logo Finna');
        $drawing->setPath(public_path('images/finna-logo.png')); /* put your path and image here */
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);

        $sheet->getRowDimension('7')->setRowHeight('30');
        $sheet->setCellValue('A7', "HARGA REGIONAL")->getStyle('A7')->applyFromArray($styleHeading1);

        $sheet->setCellValue('A8', 'NO')->getStyle('A8')->applyFromArray($styleTitle);
        $sheet->setCellValue('B8', 'NAMA PRODUK')->getStyle('B8')->applyFromArray($styleTitle);
        $sheet->setCellValue('C8', 'HARGA PRODUK')->getStyle('C8')->applyFromArray($styleTitle);
        $sheet->setCellValue('D8', 'REGION')->getStyle('D8')->applyFromArray($styleTitle);
        $products        = Product::all();
        $arrs_product = [];
        foreach ($products as $product) {
            array_push($arrs_product, $product->NAME_PRODUCT);
        }
        $regionals       = Regional::all();
        $arrs_regional = [];
        foreach ($regionals as $regional) {
            array_push($arrs_regional, $regional->NAME_REGIONAL);
        }
        $rowStart = 9;
        for ($i = 1; $i <= 20; $i++) {
            $sheet->setCellValue('A' . $rowStart, $i)->getStyle('A' . $rowStart)->applyFromArray($styleContentCenterBold)->getAlignment()->setWrapText(true);

            $sheet->getStyle('B' . $rowStart)->applyFromArray($styleContentCenterBold)->getAlignment()->setWrapText(true);
            $lstProduct = $sheet->getCell('B' . $rowStart)->getDataValidation();
            $lstProduct->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setShowDropDown(true);
            $lstProduct->setFormula1('"' . implode(',', $arrs_product) . '"');

            $sheet->getStyle('C' . $rowStart)->applyFromArray($styleContent)->getAlignment()->setWrapText(true);

            $sheet->getStyle('D' . $rowStart)->applyFromArray($styleContentCenterBold)->getAlignment()->setWrapText(true);
            $lstRegional = $sheet->getCell('D' . $rowStart)->getDataValidation();
            $lstRegional->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setShowDropDown(true);
            $lstRegional->setFormula1('"' . implode(',', $arrs_regional) . '"');
            $rowStart++;
        }
        $fileName = 'TEMPLATE_HARGA_REGIONAL_' . ((int)date('Y') + 1);
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
