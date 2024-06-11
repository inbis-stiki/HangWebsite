<?php

namespace App\Http\Controllers;

use App\Imports\RegionalPriceImport;
use App\Datefunc;
use App\Product;
use App\Regional;
use App\RegionalPrice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class TestController extends Controller
{
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
        $sheet->getColumnDimension('E')->setWidth('20');
        $sheet->getColumnDimension('F')->setWidth('20');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo Finna');
        $drawing->setDescription('Logo Finna');
        $drawing->setPath(public_path('images/new-logo.png')); /* put your path and image here */
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);

        $sheet->getRowDimension('7')->setRowHeight('30');
        $sheet->setCellValue('A7', "HARGA REGIONAL")->getStyle('A7')->applyFromArray($styleHeading1);

        $sheet->setCellValue('A8', 'NO')->getStyle('A8')->applyFromArray($styleTitle);
        $sheet->setCellValue('B8', 'REGIONAL')->getStyle('B8')->applyFromArray($styleTitle);
        $sheet->setCellValue('C8', 'KODE PRODUK')->getStyle('C8')->applyFromArray($styleTitle);
        $sheet->setCellValue('D8', 'HARGA PRODUK')->getStyle('D8')->applyFromArray($styleTitle);
        $sheet->setCellValue('E8', 'START DATE')->getStyle('E8')->applyFromArray($styleTitle);
        $sheet->setCellValue('F8', 'END DATE')->getStyle('F8')->applyFromArray($styleTitle);
        $products        = Product::all();
        $arrs_product = [];
        foreach ($products as $product) {
            array_push($arrs_product, $product->CODE_PRODUCT);
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
            $lstRegional = $sheet->getCell('B' . $rowStart)->getDataValidation();
            $lstRegional->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setShowDropDown(true);
            $lstRegional->setFormula1('"' . implode(',', $arrs_regional) . '"');

            $sheet->getStyle('C' . $rowStart)->applyFromArray($styleContentCenterBold)->getAlignment()->setWrapText(true);
            $lstProduct = $sheet->getCell('C' . $rowStart)->getDataValidation();
            $lstProduct->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setShowDropDown(true);
            $lstProduct->setFormula1('"' . implode(',', $arrs_product) . '"');

            $sheet->getStyle('D' . $rowStart)->applyFromArray($styleContent)->getAlignment()->setWrapText(true);

            $sheet->getStyle('E' . $rowStart)->applyFromArray($styleContent)->getAlignment()->setWrapText(true);
            $lstProduct = $sheet->getCell('E' . $rowStart)->getDataValidation();
            $lstProduct->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DATE);

            $sheet->getStyle('F' . $rowStart)->applyFromArray($styleContent)->getAlignment()->setWrapText(true);
            $lstProduct = $sheet->getCell('F' . $rowStart)->getDataValidation();
            $lstProduct->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DATE);

            
            $rowStart++;
        }
        $fileName = 'TEMPLATE_HARGA_REGIONAL_' . ((int)date('Y') + 1);
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function TestDate()
    {
        $dateFunc = new Datefunc();
        if (empty($dateFunc->currDate('112.6075501782834', '-7.965909011268979'))) {
            return response([
                "status_code"       => 403,
                "status_message"    => 'Data timezone tidak ditemukan di lokasi anda'
            ], 200);
        }

        $currDate = $dateFunc->currDate('112.6075501782834', '-7.965909011268979');

        echo $currDate;
    }

    public function TestPerformance()
    {
        for ($i = 0; $i < 20; $i++) {
            echo date("Y-m-d H:i:s");
            $this->TestDate();
        }
    }
}
